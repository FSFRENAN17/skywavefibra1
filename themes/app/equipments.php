<?php $this->layout("_theme"); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title">Lista de Equipamentos</h4>

                <div class="d-flex">

                    <form action="<?= url("/app/equipamentos") ?>" method="get" class="d-flex" style="width: 300px;">
                        <input
                            type="text"
                            name="s"
                            class="form-control"
                            placeholder="Buscar por modelo, série..."
                            value="<?= ($search ?? '') ?>">
                        <button class="btn btn-primary ms-2" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
            <!-- Tipo -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Fabricante</th>
                            <th>Modelo</th>
                            <th>Número de Série</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th style="width: 100px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($equipments) && is_array($equipments)): ?>
                            <?php foreach ($equipments as $equipment): ?>
                                <tr>
                                    <td><?= $equipment->type ?></td>
                                    <td><?= $equipment->manufacturer ?></td>
                                    <td><?= $equipment->model ?></td>
                                    <td><?= $equipment->serial_number ?></td>
                                    <td>
                                        <?php
                                        $status = $equipment->status;

                                        // Mapeia os status em inglês para português
                                        $statusTranslations = [
                                            'available' => 'Disponível',
                                            'allocated' => 'Alocado',
                                            'maintenance' => 'Em manutenção',
                                            'discarded' => 'Descartado'
                                        ];
                                        $statusPt = $statusTranslations[$status] ?? ucfirst($status);

                                        // Define a classe da cor com base no status original
                                        $badgeClass = 'bg-secondary'; // Padrão
                                        if ($status == 'available') $badgeClass = 'bg-success';
                                        if ($status == 'allocated') $badgeClass = 'bg-info';
                                        if ($status == 'maintenance') $badgeClass = 'bg-warning text-dark';
                                        if ($status == 'discarded') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $statusPt ?></span>
                                    </td>
                                    <td><?= date_fmt($equipment->created_at, "d/m/Y") ?></td>
                                    <td>
                                        <a href="<?= url("/app/equipamento/{$equipment->id}") ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum equipamento encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>