<?php $this->layout("_theme", ["activeMenu" => "cliente"]); ?>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold m-0">Meus Chamados</h3>
                </div>
                <div class="card-toolbar">
                    <a href="<?= url("app/criar-chamado") ?>" class="btn btn-sm btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i>
                        Abrir Novo Chamado
                    </a>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                
                <?php if (empty($tickets)): ?>
                    <!--begin::Empty state-->
                    <div class="text-center py-20">
                        <i class="ki-outline ki-message-text-2 fs-5x text-gray-400 mb-5"></i>
                        <h3 class="fs-2 fw-bold text-gray-800 mb-3">Nenhum chamado encontrado</h3>
                        <p class="text-gray-600 fs-5 mb-7">Você ainda não abriu nenhum chamado de suporte.</p>
                        <a href="<?= url("app/criar-chamado") ?>" class="btn btn-primary">
                            <i class="ki-outline ki-message-add fs-3"></i>
                            Abrir Meu Primeiro Chamado
                        </a>
                    </div>
                    <!--end::Empty state-->
                <?php else: ?>
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">Protocolo</th>
                                    <th class="min-w-125px">Categoria</th>
                                    <th class="min-w-125px">Assunto</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-100px">Prioridade</th>
                                    <th class="min-w-125px">Data Abertura</th>
                                    <th class="text-end min-w-100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td>
                                        <span class="text-gray-800 fw-bold">#<?= str_pad($ticket->id, 6, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td>
                                        <?= $ticket->categoryLabel() ?>
                                    </td>
                                    <td>
                                        <a href="<?= url("app/meu-chamado/{$ticket->id}") ?>" class="text-gray-800 text-hover-primary fw-bold">
                                            <?= htmlspecialchars($ticket->title, ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $ticket->statusBadge() ?>
                                    </td>
                                    <td>
                                        <?= $ticket->priorityBadge() ?>
                                    </td>
                                    <td>
                                        <?= date("d/m/Y H:i", strtotime($ticket->opened_at)) ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= url("app/meu-chamado/{$ticket->id}") ?>" class="btn btn-sm btn-light btn-active-light-primary">
                                            <i class="ki-outline ki-eye fs-5"></i>
                                            Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->

                    <?php if (!empty($paginator)): ?>
                    <!--begin::Pagination-->
                    <div class="d-flex flex-stack flex-wrap pt-10">
                        <?= $paginator->render() ?>
                    </div>
                    <!--end::Pagination-->
                    <?php endif; ?>
                <?php endif; ?>

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->
