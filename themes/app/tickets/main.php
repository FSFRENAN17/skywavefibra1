<?php $this->layout("_theme"); ?>

<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <form action="<?= url("/app/chamados"); ?>" method="post" class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" name="search" class="form-control form-control-solid w-250px ps-13"
                    placeholder="Pesquisar chamado..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </form>

            <?php if (!empty($search)): ?>
                <a href="<?= url('/app/chamados?clear=1'); ?>" class="btn btn-light ms-3">
                    <i class="ki-outline ki-cross fs-2"></i> Limpar
                </a>
            <?php endif; ?>
        </div>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">
                <!--begin::Filters-->
                <div class="btn-group">
                    <button type="button" class="btn btn-light-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ki-outline ki-filter fs-2"></i>Filtros
                    </button>
                    <div class="dropdown-menu p-5" style="min-width: 300px;">
                        <form action="<?= url("/app/chamados"); ?>" method="get">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="open" <?= ($filterStatus ?? '') === 'open' ? 'selected' : ''; ?>>Em Aberto</option>
                                    <option value="in-progress" <?= ($filterStatus ?? '') === 'in-progress' ? 'selected' : ''; ?>>Em Andamento</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categoria</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">Todas</option>
                                    <option value="installation" <?= ($filterCategory ?? '') === 'installation' ? 'selected' : ''; ?>>Instalação</option>
                                    <option value="maintenance" <?= ($filterCategory ?? '') === 'maintenance' ? 'selected' : ''; ?>>Manutenção</option>
                                    <option value="billing" <?= ($filterCategory ?? '') === 'billing' ? 'selected' : ''; ?>>Cobrança</option>
                                    <option value="cancellation" <?= ($filterCategory ?? '') === 'cancellation' ? 'selected' : ''; ?>>Cancelamento</option>
                                    <option value="technical" <?= ($filterCategory ?? '') === 'technical' ? 'selected' : ''; ?>>Técnico</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prioridade</label>
                                <select name="priority" class="form-select form-select-sm">
                                    <option value="">Todas</option>
                                    <option value="low" <?= ($filterPriority ?? '') === 'low' ? 'selected' : ''; ?>>Baixa</option>
                                    <option value="medium" <?= ($filterPriority ?? '') === 'medium' ? 'selected' : ''; ?>>Média</option>
                                    <option value="high" <?= ($filterPriority ?? '') === 'high' ? 'selected' : ''; ?>>Alta</option>
                                    <option value="critical" <?= ($filterPriority ?? '') === 'critical' ? 'selected' : ''; ?>>Crítica</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Aplicar Filtros</button>
                        </form>
                    </div>
                </div>
                <!--end::Filters-->
                
                <!--begin::Add-->
                <a href="<?= url("/app/chamado/criar"); ?>" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i>Novo Chamado
                </a>
                <!--end::Add-->
            </div>
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body py-4">
        <!--begin::Controls-->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <label for="ticketLimit" class="me-2 fw-semibold">Mostrar:</label>
                <select id="ticketLimit" class="form-select form-select-sm form-select-solid w-auto d-inline-block">
                    <option value="10" <?= $limit == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="50" <?= $limit == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?= $limit == 100 ? 'selected' : ''; ?>>100</option>
                </select>
                <span class="ms-2 text-muted">chamados por página</span>
            </div>

            <div class="text-muted">
                Exibindo <strong><?= count($tickets ?? []); ?></strong> de <strong><?= $total; ?></strong> registros
            </div>
        </div>
        <!--end::Controls-->

        <!-- CSRF token for actions -->
        <form style="display:none;">
            <?= csrf_input(); ?>
        </form>

        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="ticketsTable">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Assunto</th>
                        <th>Categoria</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Responsável</th>
                        <th>Aberto em</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (!empty($tickets)): ?>
                        <?php foreach ($tickets as $ticket):
                            $customer = $ticket->customer();
                            $customerPerson = $customer ? $customer->person() : null;
                            $employee = $ticket->employee();
                            $employeePerson = $employee ? $employee->person() : null;
                        ?>
                            <tr>
                                <td>
                                    <span class="text-gray-800 fw-bold">#<?= $ticket->id; ?></span>
                                </td>
                                <td>
                                    <?php if ($customerPerson): ?>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold"><?= $customerPerson->full_name; ?></span>
                                            <span class="text-muted fs-7"><?= $customerPerson->document; ?></span>
                                        </div>
                                    <?php else: ?>
                                        <em class="text-muted">Cliente não encontrado</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= url("/app/chamado/{$ticket->id}"); ?>" class="text-gray-800 text-hover-primary fw-bold">
                                        <?= htmlspecialchars($ticket->title ?? 'Sem título', ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </td>
                                <td><?= $ticket->categoryLabel(); ?></td>
                                <td><?= $ticket->priorityBadge(); ?></td>
                                <td><?= $ticket->statusBadge(); ?></td>
                                <td>
                                    <?php if ($employeePerson): ?>
                                        <span class="text-gray-800"><?= $employeePerson->full_name; ?></span>
                                    <?php else: ?>
                                        <em class="text-muted">Não atribuído</em>
                                    <?php endif; ?>
                                </td>
                                <td><?= date_fmt($ticket->opened_at, "d/m/Y H:i"); ?></td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            data-bs-auto-close="true"
                                            aria-expanded="false">
                                            Ações
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="<?= url("/app/chamado/{$ticket->id}"); ?>">
                                                    <i class="ki-outline ki-pencil fs-5 me-2"></i>Ver / Editar
                                                </a>
                                            </li>
                                            <?php if ($ticket->status !== 'resolved' && $ticket->status !== 'canceled'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus(<?= $ticket->id; ?>, 'in-progress')">
                                                        <i class="ki-outline ki-arrows-circle fs-5 me-2"></i>Marcar Em Andamento
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-success" href="javascript:void(0)" onclick="updateStatus(<?= $ticket->id; ?>, 'resolved')">
                                                        <i class="ki-outline ki-check-circle fs-5 me-2"></i>Marcar como Resolvido
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-warning" href="javascript:void(0)" onclick="updateStatus(<?= $ticket->id; ?>, 'canceled')">
                                                        <i class="ki-outline ki-cross-circle fs-5 me-2"></i>Cancelar
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmDelete(<?= $ticket->id; ?>)">
                                                    <i class="ki-outline ki-trash fs-5 me-2"></i>Excluir
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-10">
                                Nenhum chamado encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!--end::Table-->

        <!--begin::Pagination-->
        <?php if ($pages > 1): ?>
            <nav class="mt-5">
                <ul class="pagination justify-content-end">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="<?= url("/app/chamados/{$i}/{$limit}"); ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
        <!--end::Pagination-->
    </div>
    <!--end::Card body-->
</div>

<!--begin::JS-->
<style>
    /* Fix dropdown overflow in table-responsive */
    .table-responsive {
        overflow: visible !important;
    }
    
    /* Ensure dropdown appears above other elements */
    .dropdown-menu {
        position: absolute !important;
        z-index: 1050 !important;
    }
</style>

<script>
    /* 🔁 Alterar quantidade (limit) */
    document.getElementById('ticketLimit').addEventListener('change', function() {
        const limit = this.value;
        window.location.href = "<?= url('/app/chamados'); ?>/1/" + limit;
    });

    /* 🗑️ Confirmação de exclusão */
    function confirmDelete(id) {
        Swal.fire({
            title: 'Excluir Chamado',
            text: 'Deseja realmente excluir este chamado? Esta ação não pode ser desfeita.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-light'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= url('/app/chamado/delete/'); ?>" + id;
            }
        });
    }

    /* 🔄 Atualizar status */
    function updateStatus(id, status) {
        const messages = {
            'in-progress': 'Deseja marcar este chamado como "Em Andamento"?',
            'resolved': 'Deseja marcar este chamado como "Resolvido"?',
            'canceled': 'Deseja cancelar este chamado?'
        };

        const titles = {
            'in-progress': 'Marcar como Em Andamento',
            'resolved': 'Resolver Chamado',
            'canceled': 'Cancelar Chamado'
        };

        const icons = {
            'in-progress': 'info',
            'resolved': 'success',
            'canceled': 'warning'
        };

        Swal.fire({
            title: titles[status] || 'Confirmar alteração',
            text: messages[status] || 'Confirmar alteração de status?',
            icon: icons[status] || 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, confirmar',
            cancelButtonText: 'Cancelar',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-light'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar como form-urlencoded para o backend receber em $data
                const params = new URLSearchParams();
                params.append('id', id);
                params.append('status', status);
                const csrfEl = document.querySelector("input[name='csrf']");
                if (csrfEl) params.append('csrf', csrfEl.value);
                
                fetch("<?= url('/app/chamado/status'); ?>", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: params.toString()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Status atualizado com sucesso!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else if (data.message) {
                        // Parse a mensagem para detectar tipo (warning, error, success)
                        const msgStr = JSON.stringify(data.message);
                        let icon = 'success';
                        let title = 'Sucesso!';
                        
                        if (msgStr.includes('"type":"warning"') || msgStr.includes('atribuir um funcionário')) {
                            icon = 'warning';
                            title = 'Atenção!';
                        } else if (msgStr.includes('"type":"error"')) {
                            icon = 'error';
                            title = 'Erro!';
                        }
                        
                        // Extrair texto da mensagem
                        let text = 'Status atualizado com sucesso!';
                        const match = msgStr.match(/"text":"([^"]+)"/);
                        if (match && match[1]) {
                            text = match[1];
                        }
                        
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            confirmButtonText: 'OK',
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: icon === 'error' ? 'btn btn-danger' : 'btn btn-primary'
                            }
                        }).then(() => {
                            if (icon === 'success') {
                                window.location.reload();
                            }
                        });
                    }
                })
                .catch(error => {

                    Swal.fire({
                        title: 'Erro!',
                        text: 'Erro ao atualizar status',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                });
            }
        });
    }
</script>
<!--end::JS-->
