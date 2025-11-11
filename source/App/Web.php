<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Core\Session;
use Source\Core\View;
use Source\Models\Account;
use Source\Models\Auth;
use Source\Models\Notification;
use Source\Models\Person;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Support\Email;

/**
 * WEB | Controller
 * @package Source\App
 */
class Web extends Controller
{
    /** WEB | Constructor */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");

        (new Access())->report();
        (new Online())->report();
    }

    /**
     * WEB | Home
     * @return void
     */
    public function home(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("home", [
            "head" => $head,
            "user" => $user ?? null,
        ]);
    }

    /*************************/
    /** *** AUTH (START) *** */

    /**
     * WEB - AUTH | Register
     * @param null|array $data
     * @return void
     */
    public function register(?array $data): void
    {
        if (Auth::account()) {
            redirect("/app");
        }

        $session = new Session();
        $personTemp = $session->has("userTemp") ? (object)$session->userTemp : null;

        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        $redirRaw = $data['redirect'] ?? ($_GET['redirect'] ?? ($session->post_auth_redirect ?? ''));
        $redir = safeRedirectPath($redirRaw);
        if ($redir) {
            $session->set('post_auth_redirect', $redir);
        }

        unset($data['redirect']);

        if (!empty($data['csrf'])) {
            if (in_array("", $data)) {
                $json['message'] = $this->message->warning("Informe seus dados para criar sua conta.")->render();
                echo json_encode($json);
                return;
            }

            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (!isset($data["agree"])) {
                $json['message'] = $this->message->error("Você deve aceitar os Termos de Uso e a Política de Privacidade para continuar o cadastro.")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();

            // monta a pessoa
            $person = (new Person())->bootstrap(
                "{$data["first_name"]} {$data["last_name"]}",
                $data["document"],
                "individual", // pode ser company se for CNPJ
                $data["birthdate"] ?? null
            );

            // monta a conta
            $account = (new Account())->bootstrap(
                0, // placeholder, será atribuído depois de salvar a person
                $data["email"],
                $data["password"]
            );

            if ($auth->register($person, $account)) {
                $session->unset("userTemp");
                $json['redirect'] = url("/confirma/" . $data["email"]) . ($redir ? ('?redirect=' . urlencode($redir)) : '');
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Criar Conta - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/cadastrar"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("auth/register", [
            "head" => $head,
            "person" => $personTemp
        ]);
    }


    /**
     * @param array|null $data
     * @return void
     */
    public function preRegister(?array $data): void
    {
        if (Auth::account()) { // helper que retorna a Account logada
            redirect("/app");
        }

        $data = filter_var_array($data, FILTER_UNSAFE_RAW);
        $session = new Session();

        $redirRaw = $data['redirect'] ?? ($_GET['redirect'] ?? '');
        if ($redir = safeRedirectPath($redirRaw)) {
            $session->set('post_auth_redirect', $redir);
        }

        unset($data['redirect']);

        if (!empty($data['csrf'])) {
            if (in_array("", $data)) {
                $json['message'] = $this->message->warning("Informe o CPF para criar sua conta.")->render();
                echo json_encode($json);
                return;
            }

            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (!empty($data["document"])) {
                $cpf = preg_replace('/[^0-9]/', '', $data["document"]);

                if (!isCpf($cpf)) {
                    $json['error'] = $this->message->warning("Você precisa informar um CPF válido.")->render();
                    echo json_encode($json);
                    return;
                }

                // agora buscamos na tabela person
                $person = (new Person())->find("document = :document", "document={$cpf}")->fetch();
                if ($person) {
                    $json['error'] = $this->message->warning("Pessoa já cadastrada")->render();
                    echo json_encode($json);
                    return;
                }

                $session->unset("userTemp");
                $session->set("userTemp", ["document" => $cpf]);

                $json['redirect'] = url("/cadastrar") . ($redir ? ('?redirect=' . urlencode($redir)) : '');
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Criar Conta - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/cadastrar"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("auth/pre-register", [
            "head" => $head
        ]);
    }


    /**
     * WEB - AUTH | Login
     * @param array|null $data
     * @return void
     */
    public function login(?array $data): void
    {
        if (Auth::account()) {
            redirect("/app");
        }

        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (request_limit("weblogin", 5, 60 * 5)) {
                $json['message'] = $this->message
                    ->error("Você já efetuou 5 tentativas. Por favor, aguarde 5 minutos para tentar novamente!")
                    ->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email'])) {
                $json['message'] = $this->message->warning("Informe seu email para entrar")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe sua senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = !empty($data['save']);
            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password'], $save);

            if ($login) {
                (new Session())->unset("weblogin");

                $session = new Session();

                // Coleta redirect de POST, GET ou sessão (fallback)
                $redirRaw = $data['redirect'] ?? ($_GET['redirect'] ?? ($session->post_auth_redirect ?? ''));
                $redir    = safeRedirectPath($redirRaw);
                if ($redir) {
                    $session->set('post_auth_redirect', $redir);
                }

                $account = $auth->account();

                // Verificação de e-mail confirmado
                if ($account->status != "confirmed") {
                    $auth->resendActivationCode($account); // novo método no Auth
                    Auth::logout();

                    $this->message->info("E-mail pendente de confirmação, reenviamos seu código de ativação.")->flash();

                    $json["redirect"] = url("/confirma/{$account->email}") . ($redir ? ('?redirect=' . urlencode($redir)) : '');
                    echo json_encode($json);
                    return;
                }

                // Atualizar estatísticas de login (se tiver os campos)
                $account->last_login = date("Y-m-d H:i:s");
                $account->save();

                // Ajusta destino (evita loops)
                $dest = $redir ?: '/app';
                if ($dest === '/entrar' || $dest === '/pre-cadastro') {
                    $dest = '/app';
                }

                $json['redirect'] = url($dest);
                $session->unset('post_auth_redirect'); // limpa redirect
            } else {
                $json['message'] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Entrar - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/entrar"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth/login", [
            "head" => $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail"),
        ]);
    }


    /**
     * WEB - AUTH | Forgot Password
     * @param null|array $data
     * @return void
     */
    /**
     * WEB - AUTH | Forgot Password
     */
    public function forget(?array $data): void
    {
        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        if (Auth::account()) {
            $this->message->warning("Você já está logado")->toast()->flash();
            redirect(url());
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["email"])) {
                $json['message'] = $this->message->info("Informe seu e-mail para continuar")->render();
                echo json_encode($json);
                return;
            }

            if (request_repeat("webforget", $data["email"])) {
                $json['message'] = $this->message->error("Oops! Você já tentou este e-mail antes")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            if ($auth->forget($data["email"])) {
                $json["message"] = $this->message->success("Acesse seu e-mail para recuperar a senha")->render();
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Recuperar Senha - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/recuperar"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("auth/forget", [
            "head" => $head
        ]);
    }


    /**
     * WEB - AUTH | Reset Password
     * @param array $data
     * @return void
     */
    /**
     * WEB - AUTH | Reset Password
     */
    public function reset(array $data): void
    {
        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        if (Auth::account()) {
            redirect("/app");
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["password"]) || empty($data["confirm-password"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();

            if ($auth->reset($data["email"], $data["code"], $data["password"], $data["confirm-password"])) {
                $this->message->success("Senha alterada com sucesso, você já pode usar a nova senha para entrar.")->flash();
                $json["redirect"] = url("entrar");
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Crie sua nova senha no " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/recuperar"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("auth/reset", [
            "head"  => $head,
            "email" => $data["email"],
            "code"  => $data["code"]
        ]);
    }


    /**
     * WEB - AUTH | OPT-IN Confirm
     * @param array $data
     * @return void
     */
    /**
     * WEB - AUTH | OPT-IN Confirm
     * @param array $data
     * @return void
     */
    public function confirm(array $data): void
    {
        $data = filter_var_array($data, FILTER_UNSAFE_RAW);
        $session = new Session();

        // Agora buscamos Account em vez de User
        $account = (new Account())->find("email = :email", "email={$data["email"]}")->fetch();
        if (!$account || $account->status == "confirmed") {
            $this->message->error("O e-mail que você está tentando confirmar não existe ou já foi confirmado.")->toast()->flash();

            if (isset($data["resendCode"])) {
                $json["redirect"] = url("/entrar");
                echo json_encode($json);
                return;
            }

            redirect(url("/entrar"));
            return;
        }

        // coleta/valida redirect de POST, GET ou sessão
        $redirRaw = $data['redirect'] ?? ($_GET['redirect'] ?? ($session->post_auth_redirect ?? ''));
        $redir = safeRedirectPath($redirRaw);
        if ($redir) {
            $session->set('post_auth_redirect', $redir);
        }

        // Se pediu reenvio do código
        if (isset($data["resendCode"])) {
            $person = (new Person())->findById($account->person_id);

            if (!$person) {
                $json['message'] = $this->message->error("Pessoa não encontrada para esta conta.")->render();
                echo json_encode($json);
                return;
            }

            $resendCode = (new Auth())->register($person, $account, true);

            if ($resendCode) {
                $json['message'] = $this->message->success(
                    "Reenviamos o código de ativação. Confira na Lixeira e na caixa de Spam."
                )->render();
                echo json_encode($json);
                return;
            }
        }


        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (request_limit("activation", 5, 60 * 5)) {
                $json['message'] = $this->message
                    ->error("Você já efetuou 5 tentativas. Por favor, aguarde 5 minutos para tentar novamente!")
                    ->render();
                echo json_encode($json);
                return;
            }

            $code = $data["code_1"] . $data["code_2"] . $data["code_3"] . $data["code_4"];
            if (strlen($code) != 4) {
                $json['message'] = $this->message->warning("Está faltando algum dígito.")->render();
                echo json_encode($json);
                return;
            }

            if ($code == $account->code) {
                // Confirm Account
                $account->status = "confirmed";
                $account->code   = null;
                $account->save();

                $person = (new Person())->findById($account->person_id);

                // Notification (Admin)
                $notification = (new Notification());
                $notification->image = "/shared/images/notifications/new-user.png";
                $notification->content = $person->full_name . " acabou de se cadastrar";
                $notification->uri = "/accounts/account/" . $account->id;
                $notification->save();

                // E-mail to Account owner
                $email = new \Source\Support\Email();
                $view = new \Source\Core\View(__DIR__ . "/../../shared/views/email");
                $subject = "Cadastro confirmado!";
                $body = $view->render("mail", [
                    "subject" => $subject,
                    "message" =>
                    "<h3>Tudo certo, {$person->full_name}</h3>
                    <p><strong>Você concluiu o seu cadastro!</strong></p>
                    <p>Estamos ansiosos para receber as suas indicações!</p>
                    <p>No decorrer do uso pode contar conosco para qualquer eventualidade, esperamos que você tenha a melhor experiência com o " . CONF_SITE_NAME . ". 
                    Ainda assim, caso prefira, pode entrar em contato conosco para sanar suas dúvidas!</p>
                    <p>Estaremos sempre disponíveis!</p>
                    <h3>Conte conosco!</h3>"
                ]);
                $email->bootstrap($subject, $body, $account->email, $person->full_name)->send();

                // vai para /obrigado carregando o redirect
                $json['redirect'] = url("/obrigado/{$account->email}") . ($redir ? ('?redirect=' . urlencode($redir)) : '');
                echo json_encode($json);
                return;
            } else {
                $json['message'] = $this->message->error("Código inválido.")->render();
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Confirme Seu Cadastro - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/confirma"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("auth/confirm", [
            "head"  => $head,
            "email" => $account->email
        ]);
    }


    /**
     * WEB - AUTH | OPT-IN Success
     * @param array $data
     * @return void
     */
    public function success(array $data): void
    {
        $head = $this->seo->render(
            "Bem-vindo ao " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/obrigado"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("auth/success", [
            "head" => $head,
            // "verse" => bibleVerse() ?? "Tudo posso naquele que me fortalece",
        ]);
    }

    /** *** AUTH (END) *** */
    /***********************/

    /**
     * WEB | Contact
     * @param array $data
     * @return void
     */
    public function contact(array $data): void
    {
        if (empty($data["message"])) {
            $json["message"] = $this->message->warning("Para enviar escreva sua mensagem.")->render();
            echo json_encode($json);
            return;
        }

        if (request_limit("webcontact", 3, 60 * 5)) {
            $json["message"] = $this->message->warning(
                "Por favor, aguarde 5 minutos para enviar novos contatos, sugestões ou reclamações"
            )->render();
            echo json_encode($json);
            return;
        }

        if (request_repeat("message", $data["message"])) {
            $json["message"] = $this->message->info(
                "Já recebemos sua mensagem. Agradecemos pelo contato e responderemos em breve."
            )->render();
            echo json_encode($json);
            return;
        }

        $subject = "Contato em: " . date_fmt("now");
        $message = filter_var($data["message"], FILTER_UNSAFE_RAW);

        $view = new View(__DIR__ . "/../../shared/views/email");
        $body = $view->render("mail", [
            "subject" => $subject,
            "message" => str_textarea($message)
        ]);

        (new Email())->bootstrap(
            $subject,
            $body,
            CONF_SITE_EMAIL,
            "Contato " . CONF_SITE_NAME
        )->send($data["email"], $data["name"]);

        $json["message"] = $this->message->success(
            "Recebemos sua mensagem, {$data["name"]}. Agradecemos pelo contato, responderemos em breve."
        )->render();
        echo json_encode($json);
    }

    /** WEB | Terms */
    public function terms(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " - Termos e condições de uso",
            CONF_SITE_DESC,
            url("/termos"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("terms", [
            "head" => $head
        ]);
    }

    /** WEB | Privacy */
    public function privacy(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " - Política de Privacidade",
            CONF_SITE_DESC,
            url("/termos"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("privacy", [
            "head" => $head
        ]);
    }

    /** WEB | Status */
    public function status(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " - Status do Serviço",
            CONF_SITE_DESC,
            url("/status"),
            url("/shared/assets/images/share.png")
        );

        echo $this->view->render("status", [
            "head" => $head
        ]);
    }

    /**
     * APP/WEB | LOGOUT
     * @return void
     */
    public function logout(): void
    {
        $this->message->info("Você saiu com sucesso", "Logout")->toast()->flash();

        Auth::logout();
        redirect(url());
    }

    /**
     * APP/WEB | Errors
     * @param array $data
     */
    public function error(array $data): void
    {
        $error = new \stdClass();
        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "Oops...";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está diponível no momento. Já estamos vendo isso mas caso precise, nos envie um e-mail.";
                $error->linkTitle = "ENVIAR E-MAIL";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = "Oops...";
                $error->title = "Desculpe. Estamos em manutenção!";
                $error->message = "Voltamos logo! Neste exato momento estamos trabalhando para melhorar nosso conteúdo para você controlar ainda melhor os seus investimentos.";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Conteúdo indisponível.";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido.";
                $error->linkTitle = "Continue navegando!";
                $error->link = url();
                break;
        }

        $head = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/ops/{$error->code}"),
            url("/shared/assets/images/share.png"),
            false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error
        ]);
    }
}
