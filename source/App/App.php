<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Account;
use Source\Models\App\Equipment;
use Source\Models\Auth;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * APP | Controller
 * @package Source\App
 */
class App extends Controller
{
    /** @var Account */
    private $user;

    /** APP | Constructor */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");

        if (!$this->user = Auth::account()) {
            $this->message->warning("Efetue login para acessar o APP.")->toast()->flash();
            redirect("/entrar");
        }

        (new Access())->report();
        (new Online())->report();
    }

    /**
     * Renderiza a página com os dados fornecidos.
     *
     * Esta função configura os metadados da página (SEO) e renderiza o template especificado.
     *
     * @param string $templateName Nome do template a ser renderizado.
     * @param array|null $data Dados a serem passados para a view (opcional).
     * @param string|null $headTitle Título da página (opcional).
     * @param string|null $headDescription Descrição da página (opcional).
     * @param string|null $headUrl URL da página (opcional).
     * @param string|null $headImage Imagem de compartilhamento da página (opcional).
     * @param bool $headFollow Indica se os motores de busca devem seguir os links da página (padrão: true).
     * @return void
     */
    private function renderPage(
        string $templateName,
        ?array $data = [],
        ?string $headTitle = null,
        ?string $headDescription = null,
        ?string $headUrl = null,
        ?string $headImage = null,
        bool $headFollow = true
    ): void {
        // Gera os metadados para SEO
        $head = $this->seo->render(
            $headTitle ?? CONF_SITE_NAME,
            $headDescription ?? CONF_SITE_DESC,
            $headUrl ?? url("/app"),
            $headImage ?? url("/shared/assets/images/share.png"),
            $headFollow
        );

        // Garante que $data seja um array antes de modificar
        $data = array_merge(["head" => $head], $data ?? []);

        // Renderiza a página
        echo $this->view->render($templateName, $data);
    }

    /** APP | Home */
    public function home(): void
    {
        $this->renderPage("home", [
            "active"      => "home",
            "title"       => "Início",
            "subtitle"    => "Bem-vindo(a)!",
        ]);
    }

    /** APP | Equipamentos */

    public function equipments(): void
    {
        $this->renderPage("equipments", [
            "active"      => "equipments",
            "title"       => "Equipamentos",
            "subtitle"    => "Gerencie seus equipamentos",
            "equipments" => (new Equipment())->find()->fetch(true) ?? [],
        ]);
    }


    public function equipment(): void
    {
        $this->renderPage("equipment", [
            "active"      => "equipment",
            "title"       => "Equipamentos",
            "subtitle"    => "Gerencie seus equipamentos",
        ]);
    }

    /**
     * APP | Edita Equipamento
     * @param array $data
     * @return void
     */
    public function editEquipment(array $data): void
    {
        $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
        $equipment = (new Equipment())->findById($equipmentId);

        if (!$equipment) {
            $this->message->error("Equipamento não encontrado!")->toast()->flash();
            redirect("/app/equipamentos");
        }

        $this->renderPage("editEquipment", [
            "active"      => "equipments",
            "title"       => "Editar Equipamento",
            "subtitle"    => "Edite os dados do equipamento",
            "equipment"   => $equipment
        ]);
    }

    /**
     * APP | Salva ou Atualiza Equipamento
     * @param array $data
     * @return void
     */
    public function saveEquipment(array $data): void
    {
        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        $equipmentId = null;
        if (!empty($data['id']) && $data['_method'] === 'PUT') {
            $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
        }

        $equipment = ($equipmentId ? (new Equipment())->findById($equipmentId) : new Equipment());

        if (!$equipment) {
            jsonResponse([
                "success" => false,
                "message" => $this->message->error("Equipamento não encontrado para atualização.")->toast()->render()
            ]);
            return;
        }

        $equipment->type = $data['type'] ?? '';
        $equipment->manufacturer = $data['manufacturer'] ?? '';
        $equipment->model = $data['model'] ?? '';
        $equipment->serial_number = $data['serial_number'] ?? '';
        $equipment->status = $data['status'] ?? '';

        if (!$equipment->save()) {
            jsonResponse([
                "success" => false,
                "message" => ($equipment->message() ?: $this->message)
                    ->error("Erro ao salvar o equipamento.")->toast()->render()
            ]);
            return;
        }

        $message = $equipmentId ? "Equipamento atualizado com sucesso!" : "Equipamento cadastrado com sucesso!";
        $this->message->success($message)->toast()->flash();

        jsonResponse([
            "success"  => true,
            "message"  => $this->message->success($message)->toast()->render(),
            "redirect" => url("/app/equipamentos")
        ]);
    }

    // Usuários

    /**
     * APP | Exclui Equipamento
     * @param array $data
     * @return void
     */
    public function deleteEquipment(array $data): void
    {
        $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
        $equipment = (new Equipment())->findById($equipmentId);

        if (!$equipment) {
            jsonResponse([
                "success" => false,
                "message" => $this->message->error("Equipamento não encontrado para exclusão.")->toast()->render()
            ]);
            return;
        }

        if (!$equipment->destroy()) {
            jsonResponse([
                "success" => false,
                "message" => ($equipment->message() ?: $this->message)
                    ->error("Erro ao excluir o equipamento.")->toast()->render()
            ]);
            return;
        }

        $this->message->success("Equipamento excluído com sucesso!")->toast()->flash();

        jsonResponse([
            "success"  => true,
            "message"  => $this->message->success("Equipamento excluído com sucesso!")->toast()->render(),
            "redirect" => url("/app/equipamentos")
        ]);
    }

    // Usuários

    public function users(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. Se for POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("user_search", $search);
            } else {
                $session->unset("user_search");
            }

            echo json_encode(["redirect" => url("/app/usuarios")]);
            return;
        }

        // 🔹 2. Se vier GET com ?clear=1, limpa a busca
        if (!empty($_GET["clear"])) {
            $session->unset("user_search");
        }

        // 🔹 3. Busca persistente (mantida na sessão)
        $search = $session->has("user_search") ? $session->user_search : "";

        // 🔹 4. Paginação e limite
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query
        $accountModel = new \Source\Models\Account();

        if (!empty($search)) {
            $query = $accountModel->find(
                "(email LIKE CONCAT('%', :search, '%')
              OR person_id IN (
                  SELECT id FROM person
                  WHERE full_name LIKE CONCAT('%', :search, '%') /*!999999 NO_INDEX_MERGE */
              ))",
                "search={$search}"
            );
        } else {
            $query = $accountModel->find();
        }

        $total = $query->count();
        $accounts = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("users/main", [
            "title"    => "Usuários",
            "accounts" => $accounts,
            "search"   => $search,
            "page"     => $page,
            "pages"    => $pages,
            "limit"    => $limit,
            "total"    => $total,
            "activeMenu" => "sistema"
        ]);
    }

    public function user(?array $data): void
    {
        $isEdit = false;

        $user = new \Source\Models\Account();
        $person = new \Source\Models\Person();
        $user->person = $person;

        // 🔹 Edição
        if (!empty($data["id"])) {
            $user = (new \Source\Models\Account())->findById((int)$data["id"]);
            if (!$user) {
                (new \Source\Support\Message())->error("Usuário não encontrado.")->flash();
                redirect("/app/usuarios");
                return;
            }

            $isEdit = true;
            $person = $user->person();
            $user->person = $person;
        }

        $this->renderPage("users/form", [
            "title"    => $isEdit ? "Editar Usuário" : "Novo Usuário",
            "subtitle" => $isEdit ? "Atualize as informações do usuário" : "Cadastre um novo usuário",
            "user"     => $user,
            "isEdit"   => $isEdit,
            "activeMenu" => "sistema"
        ]);
    }


    public function saveUserPost(?array $data): void
    {
        $json = [];

        $account = null;
        $person = null;

        // 🔹 Edição
        if (!empty($data["id"])) {
            $account = (new \Source\Models\Account())->findById($data["id"]);
            if (!$account) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Usuário não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
            $person = $account->person();
        } else {
            // 🔹 Criação
            $person = new \Source\Models\Person();
            $account = new \Source\Models\Account();
        }

        // 🔹 Limpa e prepara dados
        $fullName   = trim($data["full_name"]);
        $document   = preg_replace("/\D/", "", $data["document"]);
        $personType = $data["person_type"] ?? "individual";
        $birthDate  = !empty($data["birth_date"]) ? $data["birth_date"] : null;
        $email      = trim($data["email"]);

        // 🔹 Verifica duplicidade de CPF
        $cpfExists = (new \Source\Models\Person())
            ->find("document = :d AND id != :id", "d={$document}&id=" . ($person->id ?? 0))
            ->count();

        if ($cpfExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O CPF informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Verifica duplicidade de e-mail
        $emailExists = (new \Source\Models\Account())
            ->find("email = :e AND id != :id", "e={$email}&id=" . ($account->id ?? 0))
            ->count();

        if ($emailExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O e-mail informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Atualiza / cria Person
        $person->full_name   = $fullName;
        $person->document    = $document;
        $person->person_type = $personType;
        $person->birth_date  = $birthDate;

        if (!$person->save()) {
            $json["message"] = $person->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Atualiza / cria Account
        $account->person_id = $person->id;
        $account->email     = $email;
        $account->status    = "confirmed";

        if (!empty($data["password"])) {
            $account->password = $data["password"];
        }

        if (!$account->save()) {
            $json["message"] = $account->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Retorno final
        $json["message"] = (new \Source\Support\Message())
            ->success("Usuário " . (!empty($data["id"]) ? "atualizado" : "criado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/usuarios");
        echo json_encode($json);
    }


    /** APP | Perfil */
    public function profile(): void
    {
        $this->renderPage("profile/main", [
            "active"      => "profile",
            "title"       => "Perfil",
            "subtitle"    => "Gerencie seu perfil",
            "user"        => $this->user,
        ]);
    }

    public function profileSave(array $data): void
    {
        $user = $this->user; // Account
        $person = $user->person();

        // === Upload da foto de perfil ===
        if (!empty($_FILES["photo"]) and $_FILES["photo"]["size"] > 0) {
            $file = $_FILES["photo"];
            $upload = new Upload();

            // Remove imagem anterior
            if (!empty($user->avatar)) {
                (new Thumb())->flush("storage/{$user->avatar}");
                $upload->remove("storage/{$user->avatar}");
            }

            // Faz upload da nova
            if (!$avatarPath = $upload->image($file, "{$person->full_name}-" . time(), 360)) {
                $json["message"] = $upload->message()
                    ->before("Ooops {$person->shortName()}! ")
                    ->after(".")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }

            $user->avatar = $avatarPath;
        }

        // === Atualiza dados da pessoa ===
        $person->full_name   = $data["full_name"] ?? $person->full_name;
        $person->document    = $data["document"] ?? $person->document;
        $person->person_type = $data["person_type"] ?? $person->person_type;
        $person->birth_date  = !empty($data["birth_date"]) ? $data["birth_date"] : $person->birth_date;
        $person->save();

        // === Atualiza e-mail ===
        if (!empty($data["email"])) {
            $user->email = $data["email"];
        }
        $user->save();

        // === Atualiza contatos ===
        foreach (["phone", "whatsapp"] as $type) {
            $value = trim($data[$type] ?? "");
            if (empty($value)) {
                continue;
            }

            $contact = (new \Source\Models\App\Contact())
                ->find("person_id = :pid AND contact_type = :t", "pid={$person->id}&t={$type}")
                ->fetch();

            if (!$contact) {
                $contact = new \Source\Models\App\Contact();
                $contact->person_id = $person->id;
                $contact->contact_type = $type;
            }

            $contact->value = $value;
            $contact->save();
        }

        // === Atualiza endereço ===
        $address = $person->address() ?? new \Source\Models\App\Address();

        $address->street     = $data["street"]     ?? $address->street;
        $address->number     = $data["number"]     ?? $address->number;
        $address->district   = $data["district"]   ?? $address->district;
        $address->city       = $data["city"]       ?? $address->city;
        $address->state      = !empty($data["state"]) ? strtoupper($data["state"]) : $address->state;
        $address->zipcode    = $data["zipcode"]    ?? $address->zipcode;
        $address->complement = $data["complement"] ?? $address->complement;
        $address->save();

        // Vincula endereço à pessoa (caso ainda não exista)
        if (!$person->address()) {
            $pa = new \Source\Models\App\PersonAddress();
            $pa->person_id    = $person->id;
            $pa->address_id   = $address->id;
            $pa->address_type = "billing";
            $pa->save();
        }

        // === Resposta ===
        $json["success"] = true;
        $json["message"] = $this->message->success("Perfil atualizado com sucesso!")->toast()->render();
        echo json_encode($json);
    }

    public function searchClientByCpf(?array $data): void
    {
        $json = [];

        // 🔹 1. Validação básica
        $document = $data["document"] ?? null;
        if (empty($document)) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("Informe o CPF ou CNPJ para busca.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 2. Normaliza documento (mantém apenas números)
        $document = preg_replace("/\D/", "", $document);

        // 🔹 3. Busca a pessoa (Person)
        $person = (new \Source\Models\Person())
            ->find("document = :d", "d={$document}")
            ->fetch();

        if (!$person) {
            $json["found"] = false;
            $json["message"] = (new \Source\Support\Message())
                ->info("Pessoa não encontrada. Você pode criar um novo usuário.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 4. Busca a conta (Account)
        $account = (new \Source\Models\Account())
            ->find("person_id = :pid", "pid={$person->id}")
            ->fetch();

        // 🔹 5. Busca o cliente (Customer)
        $customer = (new \Source\Models\App\Customer())
            ->find("person_id = :pid", "pid={$person->id}")
            ->fetch();

        // 🔹 6. Busca o plano (usando Model Plan, se existir)
        $plan = null;
        if ($customer && !empty($customer->plan_id)) {
            $plan = (new \Source\Models\App\Plan())
                ->findById($customer->plan_id);
        }

        // 🔹 7. Busca os equipamentos alocados (via Model CustomerEquipment + relation manual)
        $equipments = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid", "cid={$person->id}")
            ->fetch(true);

        // 🔹 8. Adiciona o nome do equipamento a cada item (JOIN via PHP, não SQL)
        if ($equipments) {
            foreach ($equipments as $equipment) {
                $eq = (new \Source\Models\App\Equipment())
                    ->findById($equipment->equipment_id);
                $equipment->equipment_name = $eq ? $eq->name : "Equipamento desconhecido";
            }
        }

        // 🔹 9. Monta resposta JSON
        $json["found"] = true;
        $json["person"] = [
            "id"          => $person->id,
            "full_name"   => $person->full_name,
            "document"    => $person->document,
            "person_type" => $person->person_type,
            "birth_date"  => $person->birth_date
        ];

        $json["account"] = $account ? [
            "id"     => $account->id,
            "email"  => $account->email,
            "status" => $account->status ?? null
        ] : null;

        $json["customer"] = $customer ? [
            "id"      => $customer->id ?? null,
            "status"  => $customer->status ?? null,
            "plan_id" => $customer->plan_id ?? null,
            "plan"    => $plan ? $plan->name : null
        ] : null;

        $json["equipments"] = $equipments ?: [];

        echo json_encode($json);
    }

    /**
     * Página de criação/edição de cliente
     */
    public function clientForm(?array $data): void
    {
        // 🔹 1. Dados iniciais
        $customer = null;
        $person = null;
        $account = null;

        // 🔹 2. Se vier ID na rota, estamos editando
        if (!empty($data["id"])) {
            $customer = (new \Source\Models\App\Customer())
                ->find("person_id = :pid", "pid={$data["id"]}")
                ->fetch();

            if ($customer) {
                $person = (new \Source\Models\Person())->findById($customer->person_id);
                $account = (new \Source\Models\Account())->find("person_id = :pid", "pid={$customer->person_id}")->fetch();
            } else {
                $person = (new \Source\Models\Person())->findById($data["id"]);
                $account = (new \Source\Models\Account())->find("person_id = :pid", "pid={$data["id"]}")->fetch();
            }
        }

        // 🔹 3. Carrega planos disponíveis
        $plans = (new \Source\Models\App\Plan())
            ->find()
            ->order("price ASC")
            ->fetch(true);

        // 🔹 4. Carrega equipamentos disponíveis
        $equipments = (new \Source\Models\App\Equipment())
            ->find()
            ->order("name ASC")
            ->fetch(true);

        // 🔹 5. Equipamentos já alocados (se cliente existente)
        $customerEquipments = [];
        if ($person) {
            $customerEquipments = (new \Source\Models\App\CustomerEquipment())
                ->find("customer_id = :cid", "cid={$person->id}")
                ->fetch(true) ?? [];
        }

        // 🔹 6. Renderiza a página
        $this->renderPage("customers/form", [
            "active"             => "customers",
            "title"              => !empty($data["id"]) ? "Editar Cliente" : "Novo Cliente",
            "subtitle"           => !empty($data["id"]) ? "Atualize os dados do cliente" : "Cadastrar novo cliente",
            "customer"           => $customer,
            "person"             => $person,
            "account"            => $account,
            "plans"              => $plans,
            "equipments"         => $equipments,
            "customerEquipments" => $customerEquipments,
            "activeMenu"         => "admin"
        ]);
    }


    public function saveCustomer(?array $data): void
    {
        $json = [];

        // Esperamos: document, person_id (opcional), plan_id (opcional), equipments => array of equipment_id, start_date, end_date
        $document = preg_replace("/\D/", "", $data["document"] ?? "");
        $personId = !empty($data["person_id"]) ? (int)$data["person_id"] : null;
        $planId   = !empty($data["plan_id"]) ? (int)$data["plan_id"] : null;
        $equipments = $data["equipments"] ?? []; // esperar array [[equipment_id, start_date, end_date], ...]

        // Verifica pessoa
        if ($personId) {
            $person = (new \Source\Models\Person())->findById($personId);
            if (!$person) {
                $json["message"] = (new \Source\Support\Message())->error("Pessoa não encontrada.")->toast()->render();
                echo json_encode($json);
                return;
            }
        } else {
            // tenta achar por document
            $person = (new \Source\Models\Person())->find("document = :d", "d={$document}")->fetch();
            if (!$person) {
                $json["message"] = (new \Source\Support\Message())->warning("Pessoa não encontrada. Crie a pessoa antes.")->toast()->render();
                echo json_encode($json);
                return;
            }
        }

        // Se já existe customer?
        $customerModel = new \Source\Models\App\Customer();
        $customer = $customerModel->find("person_id = :pid", "pid={$person->id}")->fetch();

        if (!$customer) {
            // cria novo customer
            $customer = new \Source\Models\App\Customer();
            $customer->person_id = $person->id;
        }

        // atualiza campos do customer (por ex. plan_id, status)
        if (!is_null($planId)) {
            $customer->plan_id = $planId;
        }
        $customer->status = $data["customer_status"] ?? ($customer->status ?? 'active');

        if (!$customer->save()) {
            $json["message"] = $customer->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // Agora alocar equipamentos: para simplicidade, removo/insiro
        // Você pode optar por inserir novos sem deletar. Exemplo abaixo apaga todas as alocações e recria.
        $pdo = \Source\Core\Connect::getInstance();
        $pdo->beginTransaction();
        try {
            // opcional: remover alocações antigas (se quiser sobrescrever)
            $stmtDel = $pdo->prepare("DELETE FROM customer_equipment WHERE customer_id = :cid");
            $stmtDel->execute(["cid" => $person->id]); // cuidado: a constraint customer_equipment_ibfk_1 usa customer_id referencing customer.person_id
            // Inserir novas alocações
            $stmtIns = $pdo->prepare("INSERT INTO customer_equipment (customer_id, equipment_id, start_date, end_date) VALUES (:cid, :eid, :s, :e)");
            foreach ($equipments as $eq) {
                $eid = (int)$eq["equipment_id"];
                $s = !empty($eq["start_date"]) ? $eq["start_date"] : date("Y-m-d");
                $e = !empty($eq["end_date"]) ? $eq["end_date"] : null;
                $stmtIns->execute([
                    "cid" => $person->id,
                    "eid" => $eid,
                    "s" => $s,
                    "e" => $e
                ]);
            }
            $pdo->commit();
        } catch (\Throwable $th) {
            $pdo->rollBack();
            $json["message"] = (new \Source\Support\Message())->error("Falha ao alocar equipamentos: " . $th->getMessage())->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())->success("Cliente atualizado com sucesso")->toast()->render();
        $json["redirect"] = url("/clientes"); // ou a rota que quiser
        echo json_encode($json);
    }



    /** APP | Logout */
    public function logout(): void
    {
        Auth::logout();
        redirect("/entrar");
    }
}
