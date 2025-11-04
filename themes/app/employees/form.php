<?php $this->layout("_theme"); ?>

<div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0"><?= $isEdit ? 'Editar Funcionário' : 'Novo Funcionário'; ?></h3>
            <span class="text-muted"><?= $isEdit ? 'Atualize as informações do funcionário' : 'Cadastre um novo funcionário'; ?></span>
        </div>
        <button type="submit" form="employeeForm" class="btn btn-primary">
            <i class="ki-outline ki-save fs-4 me-2"></i> Salvar
        </button>
    </div>

    <form id="employeeForm" method="post" action="<?= url('/app/funcionarios/salvar'); ?>" class="form">
        <div class="card-body p-9">
            <input type="hidden" name="person_id" value="<?= $employee->person_id ?? ''; ?>">

            <!-- Nome completo -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Nome Completo</label>
                <div class="col-lg-9">
                    <input type="text" name="full_name" class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->person->full_name ?? ''; ?>" required>
                </div>
            </div>

            <!-- Documento -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Documento</label>
                <div class="col-lg-9">
                    <input type="text" id="document" name="document" class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->person->document ?? ''; ?>">
                </div>
            </div>

            <!-- Data de Nascimento -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Data de Nascimento</label>
                <div class="col-lg-9">
                    <input type="date" name="birth_date" class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->person->birth_date ?? ''; ?>">
                </div>
            </div>

            <hr class="my-10">

            <!-- Cargo -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Cargo</label>
                <div class="col-lg-9">
                    <select name="role" class="form-select form-select-solid" required>
                        <?php
                        $roles = [
                            "admin"      => "Administrador",
                            "support"    => "Atendimento",
                            "technician" => "Técnico",
                            "finance"    => "Financeiro"
                        ];
                        foreach ($roles as $key => $label): ?>
                            <option value="<?= $key; ?>" <?= ($employee->role ?? '') === $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Função -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Função</label>
                <div class="col-lg-9">
                    <input type="text" name="role_name" class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->role_name ?? ''; ?>"
                        placeholder="Ex: Técnico de rede, Analista financeiro...">
                </div>
            </div>

            <!-- Data de Admissão -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Data de Admissão</label>
                <div class="col-lg-9">
                    <input type="date" name="hire_date" class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->hire_date ?? date('Y-m-d'); ?>" required>
                </div>
            </div>

            <!-- Status -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Status</label>
                <div class="col-lg-9">
                    <select name="status" class="form-select form-select-solid">
                        <option value="active" <?= ($employee->status ?? '') === 'active' ? 'selected' : ''; ?>>Ativo</option>
                        <option value="terminated" <?= ($employee->status ?? '') === 'terminated' ? 'selected' : ''; ?>>Desligado</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $this->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(function() {
        // Máscara para CPF/CNPJ
        $('#document').mask('000.000.000-00', {
            reverse: true
        });
    });
</script>
<?php $this->end(); ?>