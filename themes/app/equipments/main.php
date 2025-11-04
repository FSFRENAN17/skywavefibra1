<?php $this->layout("_theme"); ?>

<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <form action="<?= url("/app/equipments"); ?>" method="post" class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" name="search" class="form-control form-control-solid w-250px ps-13"
                    placeholder="Pesquisar equipamento..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </form>

            <?php if (!empty($search)): ?>
                <a href="<?= url('/app/equipamentos?clear=1'); ?>" class="btn btn-light ms-3">
                    <i class="ki-outline ki-cross fs-2"></i> Limpar
                </a>
            <?php endif; ?>
        </div>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <!--begin::Add-->
                <a href="<?= url("/app/equipamento/criar"); ?>" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i>Novo Equipamento
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
                <label for="equipmentLimit" class="me-2 fw-semibold">Mostrar:</label>
                <select id="equipmentLimit" class="form-select form-select-sm form-select-solid w-auto d-inline-block">
                    <option value="10" <?= $limit == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="50" <?= $limit == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?= $limit == 100 ? 'selected' : ''; ?>>100</option>
                </select>
                <span class="ms-2 text-muted">equipamentos por página</span>
            </div>

            <div class="text-muted">
                Exibindo <strong><?= count($equipments ?? []); ?></strong> de <strong><?= $total; ?></strong> registros
            </div>
        </div>
        <!--end::Controls-->

        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="equipmentsTable">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Fabricante</th>
                        <th>Modelo</th>
                        <th>Nº de Série</th>
                        <th>Status</th>
                        <th>Data de Cadastro</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (!empty($equipments)): ?>
                        <?php foreach ($equipments as $equipment): ?>
                            <tr>
                                <td><?= $equipment->id; ?></td>
                                <td class="text-capitalize"><?= $equipment->type; ?></td>
                                <td><?= $equipment->manufacturer ?? '<em>Não informado</em>'; ?></td>
                                <td><?= $equipment->model ?? '<em>Não informado</em>'; ?></td>
                                <td><?= $equipment->serial_number ?? '<em>Sem número</em>'; ?></td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        "available"   => ["Disponível", "success"],
                                        "allocated"   => ["Alocado", "primary"],
                                        "maintenance" => ["Manutenção", "warning"],
                                        "discarded"   => ["Descartado", "danger"]
                                    ];
                                    [$label, $color] = $statusLabels[$equipment->status] ?? ["Desconhecido", "secondary"];
                                    ?>
                                    <span class="badge badge-light-<?= $color; ?>"><?= $label; ?></span>
                                </td>
                                <td><?= date_fmt($equipment->created_at ?? "now"); ?></td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Ações
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="<?= url("/app/equipamento/{$equipment->id}"); ?>">
                                                    Ver / Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                    href="#"
                                                    onclick="confirmDelete(<?= $equipment->id; ?>)">
                                                    Excluir
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
                                Nenhum equipamento encontrado.
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
                            <a class="page-link"
                                href="<?= url("/app/equipamentos/{$i}/{$limit}"); ?>">
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
<script>
    /* 🔁 Alterar quantidade (limit) */
    document.getElementById('equipmentLimit').addEventListener('change', function() {
        const limit = this.value;
        window.location.href = "<?= url('/app/equipamentos'); ?>/1/" + limit;
    });

    /* 🗑️ Confirmação de exclusão */
    function confirmDelete(id) {
        if (confirm("Deseja realmente excluir este equipamento?")) {
            window.location.href = "<?= url('/app/equipamento/delete/'); ?>" + id;
        }
    }
</script>
<!--end::JS-->