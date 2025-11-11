<?php $this->layout("_theme"); ?>

<div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0"><?= $isEdit ? 'Editar Equipamento' : 'Novo Equipamento'; ?></h3>
            <span class="text-muted"><?= $isEdit ? 'Atualize as informações do equipamento' : 'Cadastre um novo equipamento'; ?></span>
        </div>
        <button type="submit" form="equipmentForm" class="btn btn-primary">
            <i class="ki-outline ki-save fs-4 me-2"></i> Salvar
        </button>
    </div>

    <form id="equipmentForm" method="post" action="<?= url('/app/equipamentos/salvar'); ?>" class="form">
        <div class="card-body p-9">
            <input type="hidden" name="id" value="<?= $equipment->id ?? ''; ?>">

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Tipo</label>
                <div class="col-lg-9">
                    <select name="type" class="form-select form-select-solid" required>
                        <?php
                        $types = ["onu" => "ONU", "router" => "Roteador", "radio" => "Rádio", "switch" => "Switch", "modem" => "Modem"];
                        foreach ($types as $key => $label): ?>
                            <option value="<?= $key; ?>" <?= ($equipment->type ?? '') === $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Fabricante</label>
                <div class="col-lg-9">
                    <input type="text" name="manufacturer" class="form-control form-control-lg form-control-solid"
                        value="<?= $equipment->manufacturer ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Modelo</label>
                <div class="col-lg-9">
                    <input type="text" name="model" class="form-control form-control-lg form-control-solid"
                        value="<?= $equipment->model ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Número de Série</label>
                <div class="col-lg-9">
                    <input type="text" name="serial_number" class="form-control form-control-lg form-control-solid"
                        value="<?= $equipment->serial_number ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Status</label>
                <div class="col-lg-9">
                    <select name="status" class="form-select form-select-solid">
                        <?php
                        $statuses = [
                            "available"   => "Disponível",
                            "allocated"   => "Alocado",
                            "maintenance" => "Em manutenção",
                            "discarded"   => "Descartado"
                        ];
                        foreach ($statuses as $key => $label): ?>
                            <option value="<?= $key; ?>" <?= ($equipment->status ?? 'available') === $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>