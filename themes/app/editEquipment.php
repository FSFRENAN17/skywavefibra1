<?php $this->layout("_theme"); ?>

<form action="<?= url("/app/equipamento/{$equipment->id}") ?>" method="POST" class="p-4 border rounded bg-light">
    <h3 class="mb-3">Edição de Equipamento</h3>

    <input type="hidden" name="_method" value="PUT" />

    <div class="mb-3">
        <label for="type" class="form-label">Tipo do Equipamento</label>
        <select name="type" id="type" class="form-select" required>
            <option value="">Selecione...</option>
            <option value="onu" <?= ($equipment->type == 'onu' ? 'selected' : '') ?>>ONU</option>
            <option value="router" <?= ($equipment->type == 'router' ? 'selected' : '') ?>>Router</option>
            <option value="radio" <?= ($equipment->type == 'radio' ? 'selected' : '') ?>>Rádio</option>
            <option value="switch" <?= ($equipment->type == 'switch' ? 'selected' : '') ?>>Switch</option>
            <option value="modem" <?= ($equipment->type == 'modem' ? 'selected' : '') ?>>Modem</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="manufacturer" class="form-label">Fabricante</label>
        <input type="text" name="manufacturer" id="manufacturer" class="form-control" maxlength="80" value="<?= $equipment->manufacturer ?>" required>
    </div>

    <div class="mb-3">
        <label for="model" class="form-label">Modelo</label>
        <input type="text" name="model" id="model" class="form-control" maxlength="80" value="<?= $equipment->model ?>" required>
    </div>

    <div class="mb-3">
        <label for="serial_number" class="form-label">Número de Série</label>
        <input type="text" name="serial_number" id="serial_number" class="form-control" maxlength="100" value="<?= $equipment->serial_number ?>" required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="available" <?= ($equipment->status == 'available' ? 'selected' : '') ?>>Disponível</option>
            <option value="allocated" <?= ($equipment->status == 'allocated' ? 'selected' : '') ?>>Alocado</option>
            <option value="maintenance" <?= ($equipment->status == 'maintenance' ? 'selected' : '') ?>>Manutenção</option>
            <option value="discarded" <?= ($equipment->status == 'discarded' ? 'selected' : '') ?>>Descartado</option>
        </select>
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEquipmentModal">Excluir</button>
    </div>
</form>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteEquipmentModal" tabindex="-1" aria-labelledby="deleteEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEquipmentModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja excluir este equipamento? Esta ação não pode ser desfeita.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="<?= url("/app/equipamento/{$equipment->id}") ?>" method="POST">
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>