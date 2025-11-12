<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Account;
use Source\Models\App\Equipment;
use Source\Models\App\Plan;
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

    
    public function serviceStatus(): void
    {
        // ATENÇÃO: A VIEW CHAMA AGORA "status", esperando o arquivo 'status.php'
        $this->renderPage("status", [
            "active"      => "status",
            "title"       => "Status do Serviço",
            "subtitle"    => "Acompanhe a disponibilidade de nossos serviços",
            // Aqui você pode buscar dados de status de serviços e passá-los para a view
        ]);
    }

    /**
     * APP | Salva ou Atualiza Status de Serviço (via AJAX)
     * @param array $data
     * @return void
     */
    public function saveServiceStatusPost(?array $data): void
    {
        header('Content-Type: application/json'); // Garante que a resposta seja tratada como JSON
        $json = [];
        $pdo = \Source\Core\Connect::getInstance(); // Obter a instância PDO

        $id = $data['id'] ?? null;
        $customer_id = $data['customer_id'] ?? null;
        $status = $data['status'] ?? null;
        $reason = $data['reason'] ?? null;

        // Validação básica
        if (empty($customer_id) || empty($status)) {
            $json["message"] = "Cliente e Status são obrigatórios.";
            $json["type"] = "warning";
            echo json_encode($json);
            die(); // Garante que nada mais seja enviado
        }

        // Validação: Verificar se o customer_id existe na tabela customer
        try {
            $stmtCheckCustomer = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE person_id = ?");
            $stmtCheckCustomer->execute([$customer_id]);
            if ($stmtCheckCustomer->fetchColumn() == 0) {
                $json["message"] = "Erro: O cliente selecionado não é válido ou não está registrado como cliente.";
                $json["type"] = "danger";
                echo json_encode($json);
                die(); // Garante que nada mais seja enviado
            }
        } catch (PDOException $e) {
            error_log("Erro PDO na validação de cliente em App.php: " . $e->getMessage());
            $json["message"] = "Erro interno ao validar cliente.";
            $json["type"] = "danger";
            echo json_encode($json);
            die(); // Garante que nada mais seja enviado
        }

        try {
            if (empty($id)) {
                // Verificar se o cliente já possui um status
                $stmtCheckExisting = $pdo->prepare("SELECT id, customer_id, status, reason FROM service_status WHERE customer_id = ?");
                $stmtCheckExisting->execute([$customer_id]);
                $existingStatus = $stmtCheckExisting->fetch(\PDO::FETCH_ASSOC); // Buscar o registro completo

                if ($existingStatus) {
                    $json["message"] = "Este cliente já possui um status registrado. O formulário de edição foi carregado automaticamente.";
                    $json["type"] = "warning";
                    // Adicionar dados do status existente para o frontend abrir o modal de edição
                    $json["existing_status"] = [
                        "id" => $existingStatus['id'],
                        "customer_id" => $existingStatus['customer_id'],
                        "status" => $existingStatus['status'],
                        "reason" => $existingStatus['reason']
                    ];
                    echo json_encode($json);
                    die(); // Garante que nada mais seja enviado
                }

                // Inserir novo status
                $stmt = $pdo->prepare("INSERT INTO service_status (customer_id, status, reason, changed_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$customer_id, $status, $reason]);
                $json["message"] = "Status adicionado com sucesso!";
                $json["type"] = "success";
            } else {
                // Atualizar status existente
                // Ao atualizar, não precisamos verificar duplicidade de customer_id, pois estamos modificando um registro existente.
                $stmt = $pdo->prepare("UPDATE service_status SET customer_id=?, status=?, reason=?, changed_at=NOW() WHERE id=?");
                $stmt->execute([$customer_id, $status, $reason, $id]);
                $json["message"] = "Status atualizado com sucesso!";
                $json["type"] = "success";
            }
            $json["redirect"] = url("/app/status"); // Redireciona para recarregar a lista
        } catch (PDOException $e) {
            error_log("Erro PDO ao salvar status em App.php: " . $e->getMessage() . " (Código: " . $e->getCode() . ")");
            $json["message"] = "Erro PDO ao salvar status: " . $e->getMessage();
            $json["type"] = "danger";
        }

        echo json_encode($json);
        die(); // Adicionado para garantir que nada mais seja enviado
    }

    /**
     * APP | Exclui Status de Serviço (via AJAX)
     * @param array $data
     * @return void
     */
    public function deleteServiceStatus(?array $data): void
    {
        $json = [];
        $pdo = \Source\Core\Connect::getInstance();

        $id = $data['id'] ?? null;

        if (empty($id)) {
            $json["message"] = $this->message->warning("ID do status não fornecido para exclusão.")->toast()->render();
            echo json_encode($json);
            return;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM service_status WHERE id = ?");
            $stmt->execute([$id]);
            $json["message"] = $this->message->success("Status excluído com sucesso!")->toast()->render();
            $json["redirect"] = url("/app/status"); // Redireciona para recarregar a lista
        } catch (PDOException $e) {
            error_log("Erro PDO ao excluir status em App.php: " . $e->getMessage());
            $json["message"] = $this->message->error("Erro PDO ao excluir status: " . $e->getMessage())->toast()->render();
        }

        echo json_encode($json);
    }

    /**
     * APP | Retorna dados de Status de Serviço para DataTables (via AJAX)
     * @return void
     */
    public function getServiceStatusData(): void
    {
        header('Content-Type: application/json');
        $json = ["data" => []];
        $pdo = \Source\Core\Connect::getInstance();

        // Parâmetros do DataTables
        $draw = $_GET['draw'] ?? 1;
        $start = $_GET['start'] ?? 0;
        $length = $_GET['length'] ?? 10;
        $searchValue = $_GET['search']['value'] ?? '';

        $recordsTotal = 0;
        $recordsFiltered = 0;

        try {
            // Contar total de registros (sem filtro)
            $stmtTotal = $pdo->query("SELECT COUNT(*) FROM service_status");
            $recordsTotal = $stmtTotal->fetchColumn();

            $whereClause = "";
            $params = [];

            if (!empty($searchValue)) {
                $whereClause = " WHERE p.full_name LIKE :search OR ss.reason LIKE :search OR ss.status LIKE :search";
                $params[':search'] = '%' . $searchValue . '%';
            }

            // Contar registros filtrados
            $stmtFiltered = $pdo->prepare("
                SELECT COUNT(ss.id)
                FROM service_status ss
                JOIN customer c ON c.person_id = ss.customer_id
                JOIN person p ON p.id = c.person_id
                " . $whereClause
            );
            $stmtFiltered->execute($params);
            $recordsFiltered = $stmtFiltered->fetchColumn();

            // Obter dados com paginação e filtro
            $sqlQuery = "
                SELECT ss.id, p.full_name AS cliente, ss.status, ss.reason, ss.changed_at, ss.customer_id
                FROM service_status ss
                JOIN customer c ON c.person_id = ss.customer_id
                JOIN person p ON p.id = c.person_id
                " . $whereClause . "
                ORDER BY ss.id DESC
                LIMIT :start, :length
            ";
            $stmt = $pdo->prepare($sqlQuery);
            $stmt->bindValue(':start', (int)$start, \PDO::PARAM_INT);
            $stmt->bindValue(':length', (int)$length, \PDO::PARAM_INT);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $data = [];
            foreach ($rows as $row) {
                $statusClassMap = [
                    'active' => 'badge-light-success',
                    'suspended' => 'badge-light-warning',
                    'blocked' => 'badge-light-danger',
                    'canceled' => 'badge-light-secondary'
                ];
                $statusClass = $statusClassMap[$row['status']] ?? 'badge-light-info';
                $statusHtml = '<span class="badge ' . $statusClass . '">' . ucfirst($row['status']) . '</span>';

                $actionsHtml = '
                    <button class="btn btn-sm btn-light btn-active-light-primary me-2 btn-editar"
                        data-id="' . $row['id'] . '"
                        data-customer="' . $row['customer_id'] . '"
                        data-status="' . $row['status'] . '"
                        data-reason="' . htmlspecialchars($row['reason']) . '">
                        <i class="ki-outline ki-pencil fs-5"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-light-danger btn-excluir"
                        data-id="' . $row['id'] . '">
                        <i class="ki-outline ki-trash fs-5"></i> Excluir
                    </button>';

                $data[] = [
                    $row['id'],
                    htmlspecialchars($row['cliente']),
                    $statusHtml,
                    htmlspecialchars($row['reason']),
                    date("d/m/Y H:i", strtotime($row['changed_at'])),
                    $actionsHtml,
                    $row['customer_id'] // Adiciona customer_id para ser usado no createdRow
                ];
            }

            $json = [
                "draw" => (int)$draw,
                "recordsTotal" => (int)$recordsTotal,
                "recordsFiltered" => (int)$recordsFiltered,
                "data" => $data
            ];

        } catch (PDOException $e) {
            error_log("Erro PDO ao buscar dados de status para DataTables em App.php: " . $e->getMessage());
            $json["error"] = "Erro ao carregar dados: " . $e->getMessage();
            // Em caso de erro, ainda retornar um JSON válido para o DataTables
            $json = [
                "draw" => (int)$draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $json["error"]
            ];
        }

        echo json_encode($json);
        die(); // Garante que nenhum outro output PHP seja enviado
    }
    // =======================================================

    // Equipamentos
    public function equipments(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. Se for POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("equipment_search", $search);
            } else {
                $session->unset("equipment_search");
            }

            echo json_encode(["redirect" => url("/app/equipamentos")]);
            return;
        }

        // 🔹 2. Se vier GET com ?clear=1, limpa a busca
        if (!empty($_GET["clear"])) {
            $session->unset("equipment_search");
        }

        // 🔹 3. Busca persistente
        $search = $session->has("equipment_search") ? $session->equipment_search : "";

        // 🔹 4. Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query
        $equipmentModel = new \Source\Models\App\Equipment();

        if (!empty($search)) {
            $query = $equipmentModel->find(
                "(manufacturer LIKE CONCAT('%', :search, '%')
              OR model LIKE CONCAT('%', :search, '%')
              OR serial_number LIKE CONCAT('%', :search, '%'))",
                "search={$search}"
            );
        } else {
            $query = $equipmentModel->find();
        }

        $total = $query->count();
        $equipments = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("equipments/main", [
            "title"      => "Equipamentos",
            "equipments" => $equipments,
            "search"     => $search,
            "page"       => $page,
            "pages"      => $pages,
            "limit"      => $limit,
            "total"      => $total,
            "activeMenu" => "admin"
        ]);
    }

    public function equipment(?array $data): void
    {
        $isEdit = false;
        $equipment = new \Source\Models\App\Equipment();

        // 🔹 Edição
        if (!empty($data["id"])) {
            $equipment = (new \Source\Models\App\Equipment())->findById((int)$data["id"]);
            if (!$equipment) {
                (new \Source\Support\Message())->error("Equipamento não encontrado.")->flash();
                redirect("/app/equipamentos");
                return;
            }

            $isEdit = true;
        }

        $this->renderPage("equipments/form", [
            "title"       => $isEdit ? "Editar Equipamento" : "Novo Equipamento",
            "subtitle"    => $isEdit ? "Atualize as informações do equipamento" : "Cadastre um novo equipamento",
            "equipment"   => $equipment,
            "isEdit"      => $isEdit,
            "activeMenu"  => "admin"
        ]);
    }

    public function saveEquipmentPost(?array $data): void
    {
        $json = [];

        // 🔹 Edição ou novo
        if (!empty($data["id"])) {
            $equipment = (new \Source\Models\App\Equipment())->findById($data["id"]);
            if (!$equipment) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Equipamento não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
        } else {
            $equipment = new \Source\Models\App\Equipment();
        }

        // 🔹 Limpa e prepara dados
        $type          = $data["type"] ?? null;
        $manufacturer  = trim($data["manufacturer"] ?? "");
        $model         = trim($data["model"] ?? "");
        $serialNumber  = trim($data["serial_number"] ?? "");
        $status        = $data["status"] ?? "available";

        // 🔹 Verifica duplicidade de serial
        $serialExists = (new \Source\Models\App\Equipment())
            ->find("serial_number = :sn AND id != :id", "sn={$serialNumber}&id=" . ($equipment->id ?? 0))
            ->count();

        if ($serialExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O número de série informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Atualiza / cria
        $equipment->type          = $type;
        $equipment->manufacturer  = $manufacturer;
        $equipment->model         = $model;
        $equipment->serial_number = $serialNumber;
        $equipment->status        = $status;

        if (!$equipment->save()) {
            $json["message"] = $equipment->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Equipamento " . (!empty($data["id"]) ? "atualizado" : "criado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/equipamentos");
        echo json_encode($json);
    }

    public function deleteEquipment(?array $data): void
    {
        $id = (int)($data["id"] ?? 0);

        if (!$id) {
            (new \Source\Support\Message())->error("ID inválido.")->flash();
            redirect("/app/equipamentos");
            return;
        }

        $equipment = (new \Source\Models\App\Equipment())->findById($id);
        if (!$equipment) {
            (new \Source\Support\Message())->error("Equipamento não encontrado.")->flash();
            redirect("/app/equipamentos");
            return;
        }

        $equipment->destroy();

        (new \Source\Support\Message())
            ->success("Equipamento excluído com sucesso!")
            ->flash();

        redirect("/app/equipamentos");
    }

    //Funcionários
    // Funcionários
    public function employees(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. POST → salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("employee_search", $search);
            } else {
                $session->unset("employee_search");
            }

            echo json_encode(["redirect" => url("/app/funcionarios")]);
            return;
        }

        // 🔹 2. Limpa busca
        if (!empty($_GET["clear"])) {
            $session->unset("employee_search");
        }

        // 🔹 3. Busca persistente
        $search = $session->has("employee_search") ? $session->employee_search : "";

        // 🔹 4. Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query
        $employeeModel = new \Source\Models\App\Employee();

        if (!empty($search)) {
            $query = $employeeModel->find(
                "person_id IN (
                SELECT id FROM person 
                WHERE full_name LIKE CONCAT('%', :search, '%') 
                OR document LIKE CONCAT('%', :search, '%')
            )",
                "search={$search}"
            );
        } else {
            $query = $employeeModel->find();
        }

        $total = $query->count();
        $employees = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("employees/main", [
            "title"      => "Funcionários",
            "employees"  => $employees,
            "search"     => $search,
            "page"       => $page,
            "pages"      => $pages,
            "limit"      => $limit,
            "total"      => $total,
            "activeMenu" => "sistema"
        ]);
    }

    public function employee(?array $data): void
    {
        $isEdit = false;
        $employee = new \Source\Models\App\Employee();
        $person = new \Source\Models\Person();
        $employee->person = $person;

        if (!empty($data["id"])) {
            $employee = (new \Source\Models\App\Employee())->findById((int)$data["id"]);
            if (!$employee) {
                (new \Source\Support\Message())->error("Funcionário não encontrado.")->flash();
                redirect("/app/funcionarios");
                return;
            }

            $isEdit = true;
            $person = $employee->person();
            $employee->person = $person;
        }

        $this->renderPage("employees/form", [
            "title"       => $isEdit ? "Editar Funcionário" : "Novo Funcionário",
            "subtitle"    => $isEdit ? "Atualize as informações do funcionário" : "Cadastre um novo funcionário",
            "employee"    => $employee,
            "isEdit"      => $isEdit,
            "activeMenu"  => "sistema"
        ]);
    }

    public function saveEmployeePost(?array $data): void
    {
        $json = [];

        if (!empty($data["person_id"])) {
            $employee = (new \Source\Models\App\Employee())->findById($data["person_id"]);
            if (!$employee) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Funcionário não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
            $person = $employee->person();
        } else {
            $employee = new \Source\Models\App\Employee();
            $person = new \Source\Models\Person();
        }

        // 🔹 Dados da pessoa
        $fullName   = trim($data["full_name"] ?? "");
        $document   = preg_replace("/\D/", "", $data["document"] ?? "");
        $birthDate  = !empty($data["birth_date"]) ? $data["birth_date"] : null;

        // 🔹 Dados do funcionário
        $role       = $data["role"] ?? "support";
        $roleName   = trim($data["role_name"] ?? "");
        $hireDate   = $data["hire_date"] ?? date("Y-m-d");
        $status     = $data["status"] ?? "active";

        // 🔹 Verifica duplicidade de documento
        $docExists = (new \Source\Models\Person())
            ->find("document = :d AND id != :id", "d={$document}&id=" . ($person->id ?? 0))
            ->count();

        if ($docExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O documento informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Salva pessoa
        $person->full_name  = $fullName;
        $person->document   = $document;
        $person->birth_date = $birthDate;

        if (!$person->save()) {
            $json["message"] = $person->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Salva funcionário
        $employee->person_id = $person->id;
        $employee->role       = $role;
        $employee->role_name  = $roleName;
        $employee->hire_date  = $hireDate;
        $employee->status     = $status;

        if (!$employee->save()) {
            var_dump($employee);
            $json["message"] = $employee->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Funcionário " . (!empty($data["person_id"]) ? "atualizado" : "cadastrado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/funcionarios");
        echo json_encode($json);
    }

    public function deleteEmployee(?array $data): void
    {
        $id = (int)($data["id"] ?? 0);

        if (!$id) {
            (new \Source\Support\Message())->error("ID inválido.")->flash();
            redirect("/app/funcionarios");
            return;
        }

        $employee = (new \Source\Models\App\Employee())->findById($id);
        if (!$employee) {
            (new \Source\Support\Message())->error("Funcionário não encontrado.")->flash();
            redirect("/app/funcionarios");
            return;
        }

        $employee->destroy();

        (new \Source\Support\Message())
            ->success("Funcionário excluído com sucesso!")
            ->flash();

        redirect("/app/funcionarios");
    }

    /**
     * Página para associar pessoa existente a um funcionário
     */
    public function employeeAssign(?array $data): void
    {
        // Busca todas as pessoas que ainda não são funcionários
        $persons = (new \Source\Models\Person())
            ->find("id NOT IN (SELECT person_id FROM employee)", "", "id, full_name, document")
            ->fetch(true);

        $this->renderPage("employees/assign", [
            "title"      => "Associar Pessoa a Funcionário",
            "subtitle"   => "Selecione uma pessoa existente e defina as informações do vínculo como funcionário",
            "persons"    => $persons,
            "activeMenu" => "sistema"
        ]);
    }

    /**
     * POST para salvar associação pessoa → funcionário
     */
    public function saveEmployeeAssignPost(?array $data): void
    {
        $json = [];

        $personId  = (int)($data["person_id"] ?? 0);
        $role      = $data["role"] ?? "support";
        $roleName  = trim($data["role_name"] ?? "");
        $hireDate  = $data["hire_date"] ?? date("Y-m-d");
        $status    = $data["status"] ?? "active";

        // Verifica se a pessoa existe
        $person = (new \Source\Models\Person())->findById($personId);
        if (!$person) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Pessoa não encontrada.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // Verifica se já é funcionário
        $exists = (new \Source\Models\App\Employee())->findById($personId);
        if ($exists) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("Essa pessoa já está registrada como funcionário.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // Cria vínculo
        $employee = new \Source\Models\App\Employee();
        $employee->person_id = $personId;
        $employee->role      = $role;
        $employee->role_name = $roleName;
        $employee->hire_date = $hireDate;
        $employee->status    = $status;

        if (!$employee->save()) {
            var_dump($employee);
            $json["message"] = $employee->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Funcionário associado com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/funcionarios");
        echo json_encode($json);
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

    // Perfil
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

    // Clientes
    public function customers(): void
    {
        $this->renderPage("customers/main", [
            "active"      => "customers",
            "title"       => "Clientes",
            "subtitle"    => "Gerencie seus clientes",
            "activeMenu"  => "admin"
        ]);
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


    // Planos
    public function plans(): void
    {

        $page  = isset($data["page"]) ? (int)$data["page"] : 1;
        $limit = isset($data["limit"]) ? (int)$data["limit"] : 10;
        $search = $data["search"] ?? null;

        $plan = new Plan();

        if ($search) {
            $plans = $plan->find("name LIKE :s", "s=%{$search}%")->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
            $total = $plan->find("name LIKE :s", "s=%{$search}%")->count();
        } else {
            $plans = $plan->find()->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
            $total = $plan->find()->count();
        }

        $pages = ceil($total / $limit);

        $this->renderPage("plans/main", [
            "plans"  => $plans,
            "page"   => $page,
            "pages"  => $pages,
            "limit"  => $limit,
            "search" => $search,
            "total"  => $total,
            "active"      => "plans",
            "title"       => "Planos",
            "subtitle"    => "Gerencie seus planos",
            "activeMenu"  => "sistema"
        ]);
    }

    public function planForm(?array $data): void
    {
        $isEdit = false;
        $plan = null;

        // Edição
        if (!empty($data["id"])) {
            $plan = (new Plan())->findById((int)$data["id"]);
            if (!$plan) {
                $this->message->warning("Plano não encontrado.")->toast()->flash();
                redirect("/app/planos");
                return;
            }
            $isEdit = true;
        } else {
            // Criação
            $plan = new Plan();
        }

        $this->renderPage("plans/form", [
            "title"    => $isEdit ? "Editar Plano" : "Novo Plano",
            "subtitle" => $isEdit ? "Atualize as informações do plano" : "Cadastre um novo plano",
            "plan"     => $plan,
            "isEdit"   => $isEdit,
            "activeMenu" => "sistema"
        ]);
    }

    public function savePlan(array $data): void
    {
        if (!empty($data["id"])) {
            $plan = (new Plan())->findById((int)$data["id"]);
            if (!$plan) {
                $this->message->error("Plano não encontrado.")->toast()->flash();
                $json["redirect"] = url("/app/planos");
                echo json_encode($json);
                return;
            }
        } else {
            $plan = new Plan();
        }

        if (!empty($data)) {
            $plan->bootstrap(
                $data["name"],
                (int)$data["download_speed"],
                (int)$data["upload_speed"],
                (float)$data["price"],
                !empty($data["data_cap"]) ? (int)$data["data_cap"] : null,
                $data["description"] ?? null
            );

            if (!$plan->save()) {
                $this->message->error("Erro ao salvar o plano: " . $plan->message()->getText())->toast()->render();
                return;
            }

            $json["message"] = $this->message->success("Plano salvo com sucesso!")->toast()->flash();
            $json["redirect"] = url("/app/planos");
            echo json_encode($json);
        }
    }


    /** APP | Logout */
    public function logout(): void
    {
        Auth::logout();
        redirect("/entrar");
    }
}
