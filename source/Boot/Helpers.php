<?php

/**
 * #######################
 * ###   BIBLE VERSE   ###
 * #######################
 */

function bibleVerse(): ?string
{
    $api = 'https://www.abibliadigital.com.br/api/verses/nvi/random';
    $response = false;
    $response = @file_get_contents($api);

    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        return "\"{$data["text"]}\" - {$data["book"]["name"]} {$data["chapter"]}:{$data["number"]}";
    }

    return null;
}

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
        return true;
    }

    return false;
}

/**
 * @param [type] $cpf
 * @return boolean
 */
function isCpf($cpf)
{
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais (CPF inválido)
    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    // Valida o primeiro dígito verificador
    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
        $sum += $cpf[$i] * (10 - $i);
    }
    $rest = $sum % 11;
    $digit1 = $rest < 2 ? 0 : 11 - $rest;

    // Valida o segundo dígito verificador
    $sum = 0;
    for ($i = 0; $i < 10; $i++) {
        $sum += $cpf[$i] * (11 - $i);
    }
    $rest = $sum % 11;
    $digit2 = $rest < 2 ? 0 : 11 - $rest;

    // Verifica se os dígitos verificadores estão corretos
    return ($cpf[9] == $digit1 && $cpf[10] == $digit2);
}

/**
 * Valida e formata um número de telefone brasileiro.
 * 
 * @param string $phone
 * @return string|null Retorna o número limpo se for válido, ou null se for inválido.
 */
function phoneValidator($phone)
{
    // Remove caracteres não numéricos
    $cleanPhone = preg_replace('/\D/', '', $phone);

    // Se o número começar com "55" e tiver mais que 11 dígitos, remover o "55"
    if (strlen($cleanPhone) > 11 && str_starts_with($cleanPhone, '55')) {
        $cleanPhone = substr($cleanPhone, 2); // Remove os dois primeiros dígitos
    }

    // Expressão regular para validar o formato (DDD + 9 dígitos)
    $pattern = '/^\d{2}9\d{8}$/'; // Exemplo: XX9XXXXYYYY (11 dígitos obrigatórios)

    // Verifica se o número é válido
    return preg_match($pattern, $cleanPhone) ? $cleanPhone : null;
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function toDecode(string $string): string
{
    return iconv('UTF-8', 'ISO-8859-1//IGNORE', $string);
}

function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_UNSAFE_RAW);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr                                 ';

    $slug = str_replace(
        ["-----", "----", "---", "--"],
        "-",
        str_replace(
            " ",
            "-",
            trim(strtr(toDecode($string), toDecode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(
        " ",
        "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $text
 * @return string
 */
function str_textarea(string $text): string
{
    $text = filter_var($text, FILTER_UNSAFE_RAW);
    $arrayReplace = ["&#10;", "&#10;&#10;", "&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;"];
    return "<p>" . str_replace($arrayReplace, "</p><p>", $text) . "</p>";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "...", bool $cutWord = false): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    if ($cutWord) {
        $chars = mb_substr($string, 0, $limit);
    } else {
        $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    }
    return "{$chars}{$pointer}";
}

/**
 * @param string $price
 * @return string
 */
function str_price(?string $price, bool $unround = false): string
{
    if ($unround) {
        $price = intval(($price * 100)) / 100;
    }

    return number_format(($price ?? 0), 2, ",", ".");
}

/**
 * @param string|null $search
 * @return string
 */
function str_search(?string $search): string
{
    if (!$search) {
        return "all";
    }

    $search = preg_replace("/[^a-z0-9A-Z\@\ ]/", "", $search);
    return (!empty($search) ? $search : "all");
}

/**
 * @param string|null $value
 * @return string
 */
function str_float(?string $value): string
{
    return trim(str_replace(["%", "R$", ".", ","], ["", "", "", "."], $value));
}

/**
 * @param string|null $value
 * @return float
 */
function round_float(?string $value): float
{
    return floatval(number_format(floatval($value), 2, ".", ""));
}

/**
 * @param string|null $value
 * @return string
 */
function str_percent(?string $value): string
{
    return str_replace(".", ",", $value);
}

/**
 * @param DateTime $start
 * @param DateTime $end
 * @return string
 */
function str_date_diff(DateTime $start, DateTime $end): string
{

    $dateDiff = $start->diff($end);

    if ($dateDiff->days == 0) {
        return "0 dias";
    }

    //Months
    $months = ($dateDiff->y > 0 ? $dateDiff->y * 12 : 0) + $dateDiff->m;
    $monthsStr = ($months == 1 ? "mês" : "meses");
    if ($months > 0) {
        $monthsReturn = $months . " " . $monthsStr;
    }

    //Days
    $days = $dateDiff->d;
    $daysStr = ($dateDiff->d == 1 ? "dia" : "dias");
    if ($days > 0) {
        $daysReturn = $days . " " . $daysStr;
    }

    //And condition
    if (isset($monthsReturn) && isset($daysReturn)) {
        $return = $monthsReturn . " e " . $daysReturn;
    } else {
        $return = ($monthsReturn ?? $daysReturn);
    }

    return $return;
}

function str_avarage_diff(array $dates): string
{
    $dates = array_map('strtotime', $dates);
    $average = array_sum($dates) / count($dates);
    return str_date_diff(new DateTime("now"), new DateTime(date("Y-m-d", $average)));
}

/**
 * @param string $message
 * @return string
 */
function str_whatsapp(string $message): string
{

    // Normalize as quebras de linha
    $message = str_replace(["\\n", "\r"], " <br> ", $message);

    // Converta \n em <br>
    $message = nl2br($message);

    // Substitua *negritos* por <strong>
    $message = preg_replace('/\*(.*?)\*/', '<strong>$1</strong>', $message);

    // Substitua _itálicos_ por <em>
    $message = preg_replace('/_(.*?)_/', '<em>$1</em>', $message);

    // Substitua ~tachado~ por <del>
    $message = preg_replace('/~(.*?)~/', '<del>$1</del>', $message);

    // Substitua ```blocos de código``` por <pre><code>
    $message = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $message);

    // Trate links automaticamente
    $message = preg_replace(
        '/(https?:\/\/[^\s]+)/',
        '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
        $message
    );

    return $message;
}

/**
 * Converte bytes para formato legível (KB, MB, GB).
 *
 * @param int $bytes Valor em bytes.
 * @param int $precision Número de casas decimais (padrão: 2).
 * @return string Valor convertido com unidade.
 */
function strBytes(int $bytes, int $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    if ($bytes <= 0) {
        return '0 B';
    }

    $pow = floor(log($bytes, 1024)); // Define a unidade
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function contact_value(int $personId, string $type): ?string
{
    $contact = (new \Source\Models\App\Contact())
        ->find("person_id = :pid AND contact_type = :type", "pid={$personId}&type={$type}")
        ->fetch();
    return $contact ? $contact->value : null;
}


/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost") !== false) {
        if ($path) {
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @return string
 */
function url_back(): string
{
    if (!empty($_SERVER["HTTP_REFERER"]) && str_contains($_SERVER["HTTP_REFERER"], CONF_SITE_DOMAIN)) {
        return $_SERVER["HTTP_REFERER"];
    }

    return url();
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

function safeRedirectPath(?string $p): ?string
{
    $p = trim((string)($p ?? ''));
    if ($p === '') return null;
    // aceita só caminhos internos
    if ($p[0] !== '/') return null;
    if (preg_match('#^/[a-zA-Z0-9/_\-\.\?\&\=\%\#]*$#', $p) !== 1) return null;
    // evita loops
    if ($p === '/entrar' || $p === '/pre-cadastro' || $p === '/cadastrar') return '/app';
    return $p;
}


/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */

// /**
//  * @return \Source\Models\User|null
//  */
// function user(): ?\Source\Models\User
// {
//     return \Source\Models\Auth::user();
// }

/**
 * @return \Source\Core\Session
 */
function session(): \Source\Core\Session
{
    return new \Source\Core\Session();
}

/**
 * @param string|null $path
 * @param string $theme
 * @return string
 */
function theme(?string $path = null, string $theme = CONF_VIEW_THEME): string
{

    if (str_contains($_SERVER['HTTP_HOST'], "localhost")) {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE . "/themes/{$theme}";
}

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(?string $image, int $width, ?int $height = null): ?string
{
    if ($image) {
        return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
    }

    return null;
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_fmt(?string $date, string $format = "d/m/Y H\hi"): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_br(?string $date): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string|null $date
 * @return string
 * @throws Exception
 */
function date_fmt_day(string $date = "now"): string
{
    $day = (new DateTime($date))->format("D");
    $dayBr = array(
        'Sun' => 'Dom',
        'Mon' => 'Seg',
        'Tue' => 'Ter',
        'Wed' => 'Qua',
        'Thu' => 'Qui',
        'Fri' => 'Sex',
        'Sat' => 'Sáb'
    );

    return $dayBr[$day];
}

/**
 * @param string|null $date
 * @return string
 * @throws Exception
 */
function date_fmt_app(?string $date = null): string
{
    $date = (empty($date) ? "now" : $date);
    return (new DateTime($date))->format(CONF_DATE_APP);
}

/**
 * @param string|null $date
 * @return string|null
 */
function date_fmt_back(?string $date = null): ?string
{
    if (!$date) {
        return null;
    }

    if (strpos($date, " ")) {
        $date = explode(" ", $date);
        return implode("-", array_reverse(explode("/", $date[0]))) . " " . $date[1];
    }

    return implode("-", array_reverse(explode("/", $date)));
}

/**
 * @param string $date
 * @param bool $future
 * @return bool
 */
function validateDate(string $date, bool $future = false): bool
{

    $formats = ["d/m/Y", "d/m/Y H:i", "d/m/Y H:i:s", "Y-m-d H:i:s"];

    foreach ($formats as $format) {
        $d = DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) === $date) {
            if (!$future && $d > new DateTime()) {
                return false;
            }
            return true;
        }
    }

    return false;
}

/**
 * @param integer $past
 * @param integer $future
 * @return array
 */
function scheduledInterval(int $past = 3, int $future = 3): array
{
    $actualDate = new DateTime();

    // Mapeia os dias da semana em português
    $days = [
        1 => 'Seg',
        2 => 'Ter',
        3 => 'Qua',
        4 => 'Qui',
        5 => 'Sex',
        6 => 'Sáb',
        7 => 'Dom'
    ];

    //Past
    for ($i = - ($past); $i < 0; $i++) {
        $date = (clone $actualDate)->modify("$i days");
        $dates["past"][$date->format('Y-m-d')] = [
            "dayName" => $days[$date->format('N')],
            "dayDate" => $date->format('d')
        ];
    }

    //Actual
    $dates["actual"][$actualDate->format("Y-m-d")] = [
        "dayName" => $days[$actualDate->format('N')],
        "dayDate" => $actualDate->format('d')
    ];

    //Future
    for ($i = 1; $i < $future + 1; $i++) {
        $date = (clone $actualDate)->modify("$i days");
        $dates["future"][$date->format('Y-m-d')] = [
            "dayName" => $days[$date->format('N')],
            "dayDate" => $date->format('d')
        ];
    }

    return $dates;
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string
 */
function csrf_input(): string
{
    $session = new \Source\Core\Session();
    $session->csrf();
    return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
}

/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    $session = new \Source\Core\Session();
    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return null|string
 */
function flash(): ?string
{
    $session = new \Source\Core\Session();
    if ($flash = $session->flash()) {
        return json_encode($flash);
    }
    return null;
}

/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 5, int $seconds = 60, bool $reset = false): bool
{
    $session = new \Source\Core\Session();

    if ($reset && $session->has($key)) {
        $session->unset($key);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
        $session->set($key, [
            "time" => time() + $seconds,
            "requests" => $session->$key->requests + 1
        ]);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
        return true;
    }

    $session->set($key, [
        "time" => time() + $seconds,
        "requests" => 1
    ]);

    return false;
}

/**
 * @param string $field
 * @param string $value
 * @return bool
 */
function request_repeat(string $field, string $value): bool
{
    $session = new \Source\Core\Session();
    if ($session->has($field) && $session->$field == $value) {
        return true;
    }

    $session->set($field, $value);
    return false;
}

/**
 * Retorna uma resposta JSON padronizada.
 *
 * @param array|object $response Dados a serem retornados no JSON.
 * @return void
 */
function jsonResponse(array|object $response): void
{
    echo json_encode($response);
    exit;
}

/**
 * Verifica se a página é originada de uma requisição é AJAX.
 *
 * @return bool Retorna true se for uma requisição AJAX, caso contrário, false.
 */
function isAjax(): bool
{
    return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
}


/**
 * ##################
 * ###   IMAGES   ###
 * ##################
 */

function random_images(string $page): string
{
    $imagesDir = "shared/assets/images/pages/" . $page . "/";
    $images = glob(__DIR__ . "/../../" . $imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    $randomImage = $images[array_rand($images)];
    return url() . "/" . $imagesDir . basename($randomImage);
}
