<?php $this->layout("_theme"); ?>

<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <form action="<?= url("/app/funcionarios"); ?>" method="post" class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" name="search" class="form-control form-control-solid w-250px ps-13"
                    placeholder="Pesquisar funcionário..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </form>

            <?php if (!empty($search)): ?>
                <a href="<?= url('/app/funcionarios?clear=1'); ?>" class="btn btn-light ms-3">
                    <i class="ki-outline ki-cross fs-2"></i> Limpar
                </a>
            <?php endif; ?>
        </div>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                <!--begin::Export-->
                <button type="button" class="btn btn-light-primary me-3">
                    <i class="ki-outline ki-exit-up fs-2"></i>Exportar
                </button>
                <!--end::Export-->
                <!--begin::Add-->
                <a href="<?= url("/app/funcionario/criar"); ?>" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i>Novo Funcionário
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
                <label for="employeeLimit" class="me-2 fw-semibold">Mostrar:</label>
                <select id="employeeLimit" class="form-select form-select-sm form-select-solid w-auto d-inline-block">
                    <option value="10" <?= $limit == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="50" <?= $limit == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?= $limit == 100 ? 'selected' : ''; ?>>100</option>
                </select>
                <span class="ms-2 text-muted">funcionários por página</span>
            </div>

            <div class="text-muted">
                Exibindo <strong><?= count($employees ?? []); ?></strong> de <strong><?= $total; ?></strong> registros
            </div>
        </div>
        <!--end::Controls-->

        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="employeesTable">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Funcionário</th>
                        <th>Cargo</th>
                        <th>Função</th>
                        <th>Data de Admissão</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $employee):
                            $person = $employee->person();
                            [$roleLabel, $roleColor] = $employee->roleLabel();
                            [$statusLabel, $statusColor] = $employee->statusLabel();
                        ?>
                            <tr>
                                <td><?= $employee->person_id; ?></td>
                                <td class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                        <div class="symbol-label">
                                            <img src="<?= $employee->photo(); ?>"
                                                alt="<?= $person->full_name ?? 'Funcionário'; ?>"
                                                class="w-100" />
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                            <?= $person->full_name ?? '<em>Sem nome</em>'; ?>
                                        </span>
                                        <span class="text-muted fs-7"><?= $person->document ?? ''; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge badge-light-<?= $roleColor; ?> fw-bold">
                                        <?= $roleLabel; ?>
                                    </div>
                                </td>
                                <td><?= $employee->role_name ?? '<em>—</em>'; ?></td>
                                <td><?= date_fmt($employee->hire_date); ?></td>
                                <td>
                                    <div class="badge badge-light-<?= $statusColor; ?> fw-bold">
                                        <?= $statusLabel; ?>
                                    </div>
                                </td>
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
                                                    href="<?= url("/app/funcionario/{$employee->person_id}"); ?>">
                                                    Ver / Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                    href="#"
                                                    onclick="confirmDelete(<?= $employee->person_id; ?>)">
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
                            <td colspan="7" class="text-center text-muted py-10">
                                Nenhum funcionário encontrado.
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
                                href="<?= url("/app/funcionarios/{$i}/{$limit}"); ?>">
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
    document.getElementById('employeeLimit').addEventListener('change', function() {
        const limit = this.value;
        window.location.href = "<?= url('/app/funcionarios'); ?>/1/" + limit;
    });

    /* 🗑️ Confirmação de exclusão */
    function confirmDelete(id) {
        if (confirm("Deseja realmente excluir este funcionário?")) {
            window.location.href = "<?= url('/app/funcionario/delete/'); ?>" + id;
        }
    }
</script>
<!--end::JS-->