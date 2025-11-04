<?php $this->layout("_theme"); ?>

<div class="container py-4">
    <h2 class="mb-4">Cadastrar Novo Plano</h2>

    <form action="<?= url("/app/plans/save"); ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <input type="hidden" name="id" value="<?= $plan->id ?? "" ?>">

        <!-- Nome do plano -->
        <div class="col-md-6">
            <label for="name" class="form-label">Nome do plano</label>
            <input type="text" name="name" id="name" value="<?= $plan->name ?? "" ?>" class="form-control" placeholder="Ex: Fibra 300 Mega" required>
            <div class="invalid-feedback">Informe o nome do plano.</div>
        </div>

        <!-- Velocidade de download -->
        <div class="col-md-3">
            <label for="download_speed" class="form-label">Download (Mbps)</label>
            <input type="number" name="download_speed" id="download_speed" value="<?= $plan->download_speed ?? "" ?>" class="form-control" placeholder="Ex: 300" required>
            <div class="invalid-feedback">Informe a velocidade de download.</div>
        </div>

        <!-- Velocidade de upload -->
        <div class="col-md-3">
            <label for="upload_speed" class="form-label">Upload (Mbps)</label>
            <input type="number" name="upload_speed" id="upload_speed" class="form-control" value="<?= $plan->upload_speed ?? "" ?>" placeholder="Ex: 150" required>
            <div class="invalid-feedback">Informe a velocidade de upload.</div>
        </div>

        <!-- Franquia de dados -->
        <div class="col-md-4">
            <label for="data_cap" class="form-label">Franquia de dados (GB)</label>
            <input type="number" name="data_cap" id="data_cap" class="form-control" value="<?= $plan->data_cap ?? "" ?>" placeholder="Ex: 500 (deixe vazio para ilimitado)">
        </div>

        <!-- Preço -->
        <div class="col-md-4">
            <label for="price" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= $plan->price ?? "" ?>" class="form-control" placeholder="Ex: 149.90" required>
            <div class="invalid-feedback">Informe o preço do plano.</div>
        </div>

        <!-- Descrição -->
        <div class="col-12">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Ex: Internet fibra óptica com Wi-Fi 6 e suporte 24h."><?= $plan->description ?? "" ?></textarea>
        </div>

        <!-- Botão -->
        <div class="col-12">
            <button type="submit" class="btn btn-primary px-4">Salvar Plano</button>
        </div>
    </form>
</div>

<?php $this->start("scripts"); ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    // Validação Bootstrap
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
<script>
    // $(document).ready(function() {
    //     $('#price').mask('#.##0,00', {
    //         reverse: true
    //     });
    // });
</script>

<?php $this->end(); ?>