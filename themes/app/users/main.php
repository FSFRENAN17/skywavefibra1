<?php $this->layout("_theme"); ?>

<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card title-->
        <div class="card-title">
            <!--begin::Search-->
            <form action="<?= url("/app/users"); ?>" method="post" class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" name="search" class="form-control form-control-solid w-250px ps-13"
                    placeholder="Pesquisar usuário..." value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </form>

            <!--end::Search-->
            <?php if (!empty($search)): ?>
                <a href="<?= url('/app/usuarios?clear=1'); ?>" class="btn btn-light ms-3">
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
                <!--begin::Add user-->
                <a href="<?= url("/app/usuario/criar"); ?>" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i>Novo Usuário
                </a>
                <!--end::Add user-->
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
                <label for="userLimit" class="me-2 fw-semibold">Mostrar:</label>
                <select id="userLimit" class="form-select form-select-sm form-select-solid w-auto d-inline-block">
                    <option value="10" <?= $limit == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="50" <?= $limit == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?= $limit == 100 ? 'selected' : ''; ?>>100</option>
                </select>
                <span class="ms-2 text-muted">usuários por página</span>
            </div>

            <div class="text-muted">
                Exibindo <strong><?= count($accounts ?? []); ?></strong> de <strong><?= $total; ?></strong> registros
            </div>
        </div>
        <!--end::Controls-->

        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="usersTable">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Pessoa</th>
                        <th>Tipo</th>
                        <th>Data de Cadastro</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 fw-semibold">
                    <?php if (!empty($accounts)): ?>
                        <?php foreach ($accounts as $account):
                            $person = $account->person();
                        ?>
                            <tr>
                                <td><?= $account->id; ?></td>
                                <td class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                        <div class="symbol-label">
                                            <img src="<?= $account->photo(); ?>"
                                                alt="<?= $person->full_name ?? 'Usuário'; ?>"
                                                class="w-100" />
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                            <?= $person->full_name ?? '<em>Sem nome</em>'; ?>
                                        </span>
                                        <span><?= $account->email; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?= $person->person_type === 'company' ? 'Empresa' : 'Pessoa Física'; ?>
                                </td>
                                <td>
                                    <?php [$label, $color] = $account->userType(); ?>
                                    <div class="badge badge-light-<?= $color; ?> fw-bold">
                                        <?= $label; ?>
                                    </div>
                                </td>
                                <td><?= date_fmt($account->created_at ?? "now"); ?></td>
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
                                                    href="<?= url("/app/usuario/{$account->id}"); ?>">
                                                    Ver
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="<?= url("/app/usuario/{$account->id}"); ?>">
                                                    Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                    href="#"
                                                    onclick="confirmDelete(<?= $account->id; ?>)">
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
                            <td colspan="6" class="text-center text-muted py-10">
                                Nenhum usuário encontrado.
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
                                href="<?= url("/app/usuarios/{$i}/{$limit}"); ?>">
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
    document.getElementById('userLimit').addEventListener('change', function() {
        const limit = this.value;
        const search = "<?= $search ?? 'all'; ?>";
        window.location.href = "<?= url('/app/usuarios'); ?>/1/" + limit;
    });

    /* 🗑️ Confirmação de exclusão */
    function confirmDelete(id) {
        if (confirm("Deseja realmente excluir este usuário?")) {
            window.location.href = "<?= url('/app/user/delete/'); ?>" + id;
        }
    }

    document.getElementById('clearSearch').addEventListener('click', function() {
        window.location.href = "<?= url('/app/usuarios'); ?>";
    });
</script>
<!--end::JS-->