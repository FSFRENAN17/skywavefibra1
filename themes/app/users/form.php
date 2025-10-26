<?php $this->layout("_theme"); ?>

<div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0"><?= $isEdit ? 'Editar Usuário' : 'Novo Usuário'; ?></h3>
            <span class="text-muted"><?= $isEdit ? 'Atualize as informações do usuário' : 'Cadastre um novo usuário'; ?></span>
        </div>
        <button type="submit" form="userForm" class="btn btn-primary">
            <i class="ki-outline ki-save fs-4 me-2"></i> Salvar
        </button>
    </div>

    <form id="userForm" method="post" action="<?= url('/app/users/save'); ?>" class="form" enctype="multipart/form-data">
        <div class="card-body p-9">
            <input type="hidden" name="id" value="<?= $user->id ?? ''; ?>">

            <!-- Nome completo -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Nome Completo</label>
                <div class="col-lg-9">
                    <input type="text" name="full_name" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person->full_name ?? ''; ?>" required>
                </div>
            </div>

            <!-- Documento -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Documento</label>
                <div class="col-lg-9">
                    <input type="text" id="document" name="document" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person->document ?? ''; ?>">
                </div>
            </div>

            <!-- Tipo de Pessoa -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Tipo de Pessoa</label>
                <div class="col-lg-9">
                    <select name="person_type" class="form-select form-select-solid">
                        <option value="individual" <?= ($user->person->person_type ?? 'individual') === 'individual' ? 'selected' : ''; ?>>Pessoa Física</option>
                        <option value="company" <?= ($user->person->person_type ?? '') === 'company' ? 'selected' : ''; ?>>Pessoa Jurídica</option>
                    </select>
                </div>
            </div>

            <!-- Data de Nascimento -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Data de Nascimento</label>
                <div class="col-lg-9">
                    <input type="date" name="birth_date" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->person->birth_date ?? ''; ?>">
                </div>
            </div>

            <!-- E-mail -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">E-mail</label>
                <div class="col-lg-9">
                    <input type="email" name="email" class="form-control form-control-lg form-control-solid"
                        value="<?= $user->email ?? ''; ?>" required>
                </div>
            </div>

            <!-- Senha -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Senha</label>
                <div class="col-lg-9">
                    <input type="password" name="password" class="form-control form-control-lg form-control-solid"
                        placeholder="<?= $isEdit ? 'Deixe em branco para manter a atual' : ''; ?>">
                </div>
            </div>
        </div>
    </form>
</div>

<?php $this->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(function() {
        // 🔹 Aplica máscara dinâmica CPF/CNPJ
        $('#document').mask('000.000.000-00', {
            reverse: true
        });
    });
</script>
<?php $this->end(); ?>