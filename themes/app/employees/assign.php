<?php $this->layout("_theme"); ?>

<div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0">Associar Pessoa a Funcionário</h3>
            <span class="text-muted">Escolha uma pessoa existente e defina o cargo e função.</span>
        </div>
        <button type="submit" form="assignForm" class="btn btn-primary">
            <i class="ki-outline ki-save fs-4 me-2"></i> Salvar
        </button>
    </div>

    <form id="assignForm" method="post" action="<?= url('/app/funcionario/associar/salvar'); ?>" class="form">
        <div class="card-body p-9">
            <!-- Pessoa -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Pessoa</label>
                <div class="col-lg-9">
                    <select name="person_id" class="form-select form-select-solid" required>
                        <option value="">Selecione uma pessoa...</option>
                        <?php if (!empty($persons)): ?>
                            <?php foreach ($persons as $p): ?>
                                <option value="<?= $p->id; ?>">
                                    <?= $p->full_name; ?> — <?= $p->document; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled>Nenhuma pessoa disponível</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Cargo -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Cargo</label>
                <div class="col-lg-9">
                    <select name="role" class="form-select form-select-solid" required>
                        <option value="admin">Administrador</option>
                        <option value="support">Atendimento</option>
                        <option value="technician">Técnico</option>
                        <option value="finance">Financeiro</option>
                    </select>
                </div>
            </div>

            <!-- Função -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Função</label>
                <div class="col-lg-9">
                    <input type="text" name="role_name" class="form-control form-control-lg form-control-solid"
                        placeholder="Ex: Técnico de Rede, Analista Financeiro...">
                </div>
            </div>

            <!-- Data de Admissão -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Data de Admissão</label>
                <div class="col-lg-9">
                    <input type="date" name="hire_date" class="form-control form-control-lg form-control-solid"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>
            </div>

            <!-- Status -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Status</label>
                <div class="col-lg-9">
                    <select name="status" class="form-select form-select-solid">
                        <option value="active" selected>Ativo</option>
                        <option value="terminated">Desligado</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>