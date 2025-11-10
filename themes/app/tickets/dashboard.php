<?php $this->layout("_theme"); ?>

<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2"><?= $totalTickets; ?></span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total de Chamados</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Todos os registros</span>
                <div class="symbol-group symbol-hover flex-nowrap">
                    <i class="ki-outline ki-chart-simple-2 fs-3x text-primary"></i>
                </div>
            </div>
        </div>

        <div class="card card-flush h-md-50 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-primary me-2 lh-1 ls-n2"><?= $openTickets; ?></span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Em Aberto</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Aguardando atendimento</span>
                <div class="symbol-group symbol-hover flex-nowrap">
                    <i class="ki-outline ki-information-5 fs-3x text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-warning me-2 lh-1 ls-n2"><?= $inProgressTickets; ?></span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Em Andamento</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Sendo atendidos</span>
                <div class="symbol-group symbol-hover flex-nowrap">
                    <i class="ki-outline ki-arrows-circle fs-3x text-warning"></i>
                </div>
            </div>
        </div>

        <div class="card card-flush h-md-50 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-success me-2 lh-1 ls-n2"><?= $resolvedTickets; ?></span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Resolvidos</span>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Finalizados com sucesso</span>
                <div class="symbol-group symbol-hover flex-nowrap">
                    <i class="ki-outline ki-check-circle fs-3x text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Por Categoria</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Distribuição dos chamados</span>
                </h3>
            </div>
            <div class="card-body pt-6">
                <?php foreach ($byCategory as $cat => $count): 
                    $labels = [
                        'installation' => 'Instalação',
                        'maintenance' => 'Manutenção',
                        'billing' => 'Cobrança',
                        'cancellation' => 'Cancelamento',
                        'technical' => 'Técnico'
                    ];
                    $percentage = $totalTickets > 0 ? round(($count / $totalTickets) * 100, 1) : 0;
                ?>
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-outline ki-<?= $cat === 'technical' ? 'setting-2' : 'abstract-26'; ?> fs-2x text-primary"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $labels[$cat]; ?></span>
                            <span class="text-gray-500 fw-semibold"><?= $count; ?> chamados (<?= $percentage; ?>%)</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Por Prioridade</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Níveis de urgência</span>
                </h3>
            </div>
            <div class="card-body pt-6">
                <?php 
                $priorityData = [
                    'critical' => ['label' => 'Crítica', 'color' => 'danger', 'icon' => 'abstract-41'],
                    'high' => ['label' => 'Alta', 'color' => 'warning', 'icon' => 'arrow-up'],
                    'medium' => ['label' => 'Média', 'color' => 'info', 'icon' => 'minus'],
                    'low' => ['label' => 'Baixa', 'color' => 'success', 'icon' => 'arrow-down']
                ];
                
                foreach ($priorityData as $priority => $info): 
                    $count = $byPriority[$priority] ?? 0;
                    $percentage = $totalTickets > 0 ? round(($count / $totalTickets) * 100, 1) : 0;
                ?>
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-<?= $info['color']; ?>">
                                <i class="ki-outline ki-<?= $info['icon']; ?> fs-2x text-<?= $info['color']; ?>"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="text-gray-800 text-hover-primary fs-6 fw-bold"><?= $info['label']; ?></span>
                            <span class="text-gray-500 fw-semibold"><?= $count; ?> chamados (<?= $percentage; ?>%)</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-8">
        <div class="card card-flush">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Chamados Recentes</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Últimos 10 registros</span>
                </h3>
                <div class="card-toolbar">
                    <a href="<?= url('/app/chamados'); ?>" class="btn btn-sm btn-light-primary">
                        Ver Todos
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gs-0 gy-4">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Categoria</th>
                                <th>Prioridade</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentTickets)): ?>
                                <?php foreach ($recentTickets as $ticket):
                                    $customer = $ticket->customer();
                                    $customerPerson = $customer ? $customer->person() : null;
                                ?>
                                    <tr>
                                        <td><span class="fw-bold">#<?= $ticket->id; ?></span></td>
                                        <td>
                                            <?php if ($customerPerson): ?>
                                                <span class="text-gray-800"><?= $customerPerson->full_name; ?></span>
                                            <?php else: ?>
                                                <em class="text-muted">—</em>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="text-gray-700"><?= $ticket->categoryLabel(); ?></span></td>
                                        <td><?= $ticket->priorityBadge(); ?></td>
                                        <td><?= $ticket->statusBadge(); ?></td>
                                        <td><span class="text-gray-700"><?= date_fmt($ticket->opened_at, "d/m/Y"); ?></span></td>
                                        <td class="text-end">
                                            <a href="<?= url("/app/chamado/{$ticket->id}"); ?>" class="btn btn-sm btn-icon btn-light-primary">
                                                <i class="ki-outline ki-eye fs-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-10">
                                        Nenhum chamado encontrado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-xl-4">
        <div class="card card-flush mb-5">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Tempo Médio</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Resolução (30 dias)</span>
                </h3>
            </div>
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <span class="display-3 fw-bold text-primary mb-3"><?= $avgHours; ?>h</span>
                <span class="text-gray-700 fw-semibold fs-5">Tempo médio de resolução</span>
                <p class="text-gray-500 fs-7 mt-3 mb-0">
                    Baseado nos chamados resolvidos nos últimos 30 dias
                </p>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Status Geral</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Visão consolidada</span>
                </h3>
            </div>
            <div class="card-body pt-5">
                <div class="mb-5">
                    <div class="d-flex flex-stack mb-3">
                        <span class="fw-semibold text-gray-700 fs-6">Em Aberto</span>
                        <span class="badge badge-light-primary fs-7"><?= $openTickets; ?></span>
                    </div>
                    <div class="progress h-6px">
                        <div class="progress-bar bg-primary" role="progressbar" 
                            style="width: <?= $totalTickets > 0 ? ($openTickets / $totalTickets * 100) : 0; ?>%"></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="d-flex flex-stack mb-3">
                        <span class="fw-semibold text-gray-700 fs-6">Em Andamento</span>
                        <span class="badge badge-light-warning fs-7"><?= $inProgressTickets; ?></span>
                    </div>
                    <div class="progress h-6px">
                        <div class="progress-bar bg-warning" role="progressbar" 
                            style="width: <?= $totalTickets > 0 ? ($inProgressTickets / $totalTickets * 100) : 0; ?>%"></div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="d-flex flex-stack mb-3">
                        <span class="fw-semibold text-gray-700 fs-6">Resolvidos</span>
                        <span class="badge badge-light-success fs-7"><?= $resolvedTickets; ?></span>
                    </div>
                    <div class="progress h-6px">
                        <div class="progress-bar bg-success" role="progressbar" 
                            style="width: <?= $totalTickets > 0 ? ($resolvedTickets / $totalTickets * 100) : 0; ?>%"></div>
                    </div>
                </div>

                <div class="mb-0">
                    <div class="d-flex flex-stack mb-3">
                        <span class="fw-semibold text-gray-700 fs-6">Cancelados</span>
                        <span class="badge badge-light-secondary fs-7"><?= $canceledTickets; ?></span>
                    </div>
                    <div class="progress h-6px">
                        <div class="progress-bar bg-secondary" role="progressbar" 
                            style="width: <?= $totalTickets > 0 ? ($canceledTickets / $totalTickets * 100) : 0; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->
