<?php
ini_set('display_errors', 1); // Força a exibição de erros
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$this->layout("_theme");

try {
    $pdo = \Source\Core\Connect::getInstance();
    if (!$pdo) {
        throw new PDOException("Falha ao obter instância PDO.");
    }
} catch (PDOException $e) {
    error_log("Erro de Conexão PDO em status.php: " . $e->getMessage());
    echo "<pre style='color:red;background:#111;padding:10px;'>Erro de Conexão PDO: " . $e->getMessage() . "</pre>";
    die();
}
?>

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading text-dark fw-bold fs-3">Gerenciamento de Status de Clientes</h1>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalStatus">
                            <i class="ki-outline ki-plus fs-2"></i> Novo Status
                        </button>
                    </div>
                </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <?php
                    // Exibe mensagens de erro ou sucesso da sessão
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        unset($_SESSION['error_message']);
                    }
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                        unset($_SESSION['success_message']);
                    }
                    ?>
                    <div class="card">
                        <div class="card-body py-4">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="tabela_status">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Status</th>
                                        <th>Motivo</th>
                                        <th>Última Alteração</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    <!-- Os dados serão carregados via AJAX pelo DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--begin::Modal-->
<div class="modal fade" id="modalStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form class="form" id="formStatus">
                <input type="hidden" name="id" id="status_id">
                <div class="modal-header">
                    <h2 class="fw-bold" id="tituloModal">Adicionar Novo Status</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Cliente</label>
                        <select class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#modalStatus" data-placeholder="Selecione o cliente" name="customer_id" id="customer_id" required>
                            <option></option> <!-- Opção vazia para placeholder do Select2 -->
                            <?php
                            try {
                                $clientes = $pdo->query("
                                    SELECT p.id, p.full_name
                                    FROM person p
                                    INNER JOIN customer c ON c.person_id = p.id
                                    ORDER BY p.full_name ASC
                                ");
                                foreach ($clientes->fetchAll(PDO::FETCH_ASSOC) as $cliente):
                                ?>
                                    <option value="<?= $cliente['id']; ?>"><?= htmlspecialchars($cliente['full_name']); ?></option>
                                <?php endforeach;
                            } catch (PDOException $e) {
                                error_log("Erro PDO ao carregar clientes em status.php: " . $e->getMessage());
                                echo "<option value=''>Erro ao carregar clientes: " . $e->getMessage() . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Status</label>
                        <select class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#modalStatus" data-placeholder="Selecione o status" name="status" id="status" required>
                            <option></option> <!-- Opção vazia para placeholder do Select2 -->
                            <option value="active">Ativo</option>
                            <option value="suspended">Suspenso</option>
                            <option value="blocked">Bloqueado</option>
                            <option value="canceled">Cancelado</option>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Motivo</label>
                        <textarea class="form-control form-control-solid" rows="3" name="reason" id="reason" placeholder="Descreva o motivo..."></textarea>
                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->

<!--begin::PHP CRUD-->
<?php
// Toda a lógica PHP de CRUD foi movida para o controlador App.php
// Este bloco agora está vazio ou pode ser removido.
?>
<!--end::PHP CRUD-->

<!--begin::Scripts-->
<script>
    $(document).ready(function () {
        console.log('jQuery document ready. Script status.php carregado.'); // Adicionado para depuração
        const tabelaStatus = $('#tabela_status').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/pt-BR.json' },
            pageLength: 5,
            ordering: true,
            responsive: true,
            processing: true,
            serverSide: true, // Habilita o processamento server-side
            ajax: {
                url: '<?= url("/app/status/data"); ?>', // URL da nova rota AJAX
                type: 'GET'
            },
            columns: [
                { "data": 0, "orderable": true },  // ID
                { "data": 1, "orderable": true },  // Cliente
                { "data": 2, "orderable": true },  // Status (já vem formatado em HTML)
                { "data": 3, "orderable": true },  // Motivo
                { "data": 4, "orderable": true },  // Última Alteração
                { "data": 5, "orderable": false }   // Ações (já vem formatado em HTML)
            ],
            createdRow: function (row, data, dataIndex) {
                // Anexa os eventos aos botões de ação e define os atributos de dados
                const editButton = $(row).find('.btn-editar');
                editButton.attr('data-id', data[0]); // ID
                editButton.attr('data-customer', data[6]); // customer_id (adicionado ao JSON no controlador)
                editButton.attr('data-status', data[2].match(/<span class="badge [^"]+">([^<]+)<\/span>/)[1].toLowerCase()); // Status (extrai do HTML)
                editButton.attr('data-reason', data[3]); // Motivo

                const deleteButton = $(row).find('.btn-excluir');
                deleteButton.attr('data-id', data[0]); // ID
            }
        });

        // Função para recarregar a tabela após operações de CRUD
        function reloadTable() {
            tabelaStatus.ajax.reload(null, false); // Recarrega os dados sem resetar a paginação
        }

        // Função para exibir mensagens (sucesso/erro)
        function showMessage(type, message) {
            const alertHtml = `<div class="alert alert-${type}">${message}</div>`;
            $('#kt_app_content_container').prepend(alertHtml);
            setTimeout(() => {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000); // Remove a mensagem após 5 segundos
        }

        // Lidar com o envio do formulário via AJAX
        $('#formStatus').submit(function (e) {
            e.preventDefault(); // Impede o envio padrão do formulário

            const formData = $(this).serialize(); // Coleta os dados do formulário
            const actionUrl = '<?= url("/app/status/save"); ?>';

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                dataType: 'json', // Espera JSON diretamente
                success: function (response) {
                    console.log("Resposta do servidor (success):", response); // Loga o JSON parseado

                    if (response.message) {
                        const messageType = response.type || 'info';
                        showMessage(messageType, response.message);
                    }

                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else if (response.existing_status) {
                        // Se houver um status existente, fechar o modal atual e abrir o de edição
                        $('#modalStatus').modal('hide'); // Fecha o modal de "Novo Status"
                        
                        // Preenche o formulário com os dados do status existente
                        $('#status_id').val(response.existing_status.id);
                        $('#customer_id').val(response.existing_status.customer_id).trigger('change');
                        $('#status').val(response.existing_status.status).trigger('change');
                        $('#reason').val(response.existing_status.reason);
                        $('#tituloModal').text('Editar Status'); // Muda o título para edição
                        
                        $('#modalStatus').modal('show'); // Abre o modal para edição
                        reloadTable(); // Recarrega a tabela para garantir que os dados estejam atualizados
                    } else {
                        $('#modalStatus').modal('hide');
                        reloadTable();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Erro na requisição AJAX (fail):", textStatus, errorThrown, jqXHR.responseText);
                    // A mensagem genérica de erro já é tratada por custom.js, não precisamos exibir outra aqui.
                    // showMessage('danger', 'Erro ao processar a requisição. Verifique o console para mais detalhes. Resposta do servidor: ' + jqXHR.responseText);
                }
            });
        });

        // Lidar com o clique no botão "Editar" (delegação de eventos)
        $(document).on('click', '.btn-editar', function () {
            const id = $(this).data('id');
            const customer = $(this).data('customer');
            const status = $(this).data('status');
            const reason = $(this).data('reason');

            console.log('Botão Editar clicado. Dados:', { id, customer, status, reason });

            $('#status_id').val(id);
            $('#customer_id').val(customer).trigger('change');
            $('#status').val(status).trigger('change');
            $('#reason').val(reason);
            $('#tituloModal').text('Editar Status');
            $('#modalStatus').modal('show');
        });

        // Lidar com o clique no botão "Excluir" (delegação de eventos)
        $(document).on('click', '.btn-excluir', function (e) {
            e.preventDefault();
            const statusId = $(this).data('id');
            const deleteUrl = '<?= url("/app/status/delete/"); ?>' + statusId;

            if (confirm('Deseja realmente excluir este registro?')) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE', // Usar método DELETE
                    success: function (response) {
                        console.log("Resposta da exclusão:", response); // Adicionado para depuração
                        if (response.message) {
                            const messageType = (typeof response.message === 'string' && response.message.includes('sucesso')) ? 'success' : 'danger';
                            showMessage(messageType, response.message);
                        }
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            reloadTable(); // Recarrega a tabela após o sucesso
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Erro na requisição AJAX DELETE:", textStatus, errorThrown, jqXHR.responseText); // Mantido para depuração
                        showMessage('danger', 'Erro ao excluir o status. Verifique o console para mais detalhes.');
                    }
                });
            }
        });

        // Reset modal on hide
        $('#modalStatus').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
            $('#status_id').val('');
            $('#tituloModal').text('Adicionar Novo Status');
            $('#customer_id').val('').trigger('change');
            $('#status').val('active').trigger('change');
        });
    });
</script>
<!--end::Scripts-->
