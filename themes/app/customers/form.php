<?php $this->layout("_theme"); ?>

<div class="card">
    <div class="card-header mt-5">
        <div>
            <h3>Associa Cliente</h3>
            <p class="text-muted mb-0">
                Busque o cliente pelo CPF ou CNPJ para associar um plano e equipamentos instalados.
                Caso o cliente ainda não esteja cadastrado, será possível criar um novo registro.
            </p>
        </div>
    </div>

    <div class="card-body">
        <form id="searchForm" action="<?= url("app/clientes/buscar"); ?>" method="post" class="ajax-off">
            <div class="input-group mb-3">
                <input type="text" id="searchDocument" name="document" class="form-control" placeholder="CPF/CNPJ">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        
        <div id="clientResult" class="mt-5" style="display:none;">
            <hr>
            <h5>Dados encontrados</h5>
            <div id="personInfo">
                <p><strong>Nome:</strong> <span id="p_name"></span></p>
                <p><strong>E-mail:</strong> <span id="p_email"></span></p>
                <p><strong>Documento:</strong> <span id="p_doc"></span></p>
            </div>

            <form id="clientForm" action="<?= url("/clientes/save"); ?>" method="post">
                <input type="hidden" name="person_id" id="person_id">

                <!-- Plano (se tiver tabela plans, carregue via server-side) -->
                <div class="mb-3">
                    <label>Plano</label>
                    <select name="plan_id" id="plan_id" class="form-select">
                        <option value="">-- selecione --</option>
                        <?php foreach ($plans ?? [] as $p): ?>
                            <option value="<?= $p->id; ?>"><?= $p->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Equipamento: exibir uma lista multi select ou rows para adicionar -->
                <div class="mb-3">
                    <label>Equipamento a alocar</label>
                    <select id="equipment_select" class="form-select">
                        <option value="">-- selecione equipamento --</option>
                        <?php foreach ($equipments ?? [] as $eq): ?>
                            <option value="<?= $eq->id; ?>"><?= $eq->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="addEquipment" class="btn btn-sm btn-outline-primary mt-2" type="button">Adicionar</button>
                </div>

                <div id="equipmentList"></div>

                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Salvar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->start("scripts"); ?>
<script>
    $(function() {
        // aplica máscara (use a mesma função que você já tem)
        // ... (mascara CPF/CNPJ)

        // Submit da busca usa seu ajax global: form sem .ajax-off será capturado
        // $('#searchForm').addClass('ajax-off');

        // handler global: seu ajaxPost chama success: se response.found true preenche
        // aqui assumimos que ajaxPost irá executar success com JSON -> window.location ou not
        // Para simplificar, você pode sobrescrever a função success localmente:
        window.ajaxPostLocal = function(data, url) {
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.found) {
                        $('#clientResult').show();
                        $('#person_id').val(res.person.id);
                        $('#p_name').text(res.person.full_name);
                        $('#p_email').text(res.account ? res.account.email : '');
                        $('#p_doc').text(res.person.document);

                        // popula equipamentos existentes
                        $('#equipmentList').html('');
                        (res.equipments || []).forEach(function(eq) {
                            $('#equipmentList').append(
                                `<div class="alert alert-light d-flex justify-content-between">
                  <div>${eq.equipment_name} — ${eq.start_date} ${eq.end_date ? 'até '+eq.end_date : ''}</div>
                  <div><button class="btn btn-sm btn-danger remove-existing" data-eid="${eq.equipment_id}">Remover</button></div>
               </div>`
                            );
                        });
                    } else {
                        // mensagem já mostrada via toast pelo servidor
                    }
                },
                error: function() {
                    alertRender("toast", "danger", "Erro na requisição");
                }
            });
        };

        // Ao submeter o form de busca, chame ajaxPostLocal em vez do global
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            const data = new FormData(this);
            ajaxPostLocal(data, this.action);
        });

        // Adicionar equipamento à lista (client-side)
        $('#addEquipment').on('click', function() {
            const eid = $('#equipment_select').val();
            const ename = $('#equipment_select option:selected').text();
            if (!eid) return alertRender("toast", "warning", "Selecione um equipamento");

            const row = `
      <div class="d-flex align-items-center mb-2" data-eid="${eid}">
        <input type="hidden" name="equipments[][equipment_id]" value="${eid}">
        <input type="date" name="equipments[][start_date]" value="<?= date('Y-m-d'); ?>" class="form-control form-control-sm me-2">
        <input type="date" name="equipments[][end_date]" class="form-control form-control-sm me-2">
        <div class="badge bg-light">${ename}</div>
        <button type="button" class="btn btn-sm btn-danger ms-2 remove-eq">Remover</button>
      </div>`;
            $('#equipmentList').append(row);
        });

        // remover item adicionado
        $(document).on('click', '.remove-eq', function() {
            $(this).closest('[data-eid]').remove();
        });

    });
</script>
<?php $this->end(); ?>