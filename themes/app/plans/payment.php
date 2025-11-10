<?php 
$this->layout("_theme"); 
$newPlan = $newPlan ?? null;
if (!$newPlan) { return; } 

// Dados fictícios de pagamento (Maquiagem)
$fakePixKey = "(99) 99999-9999"; 
$fakeBoletoNumber = "34191.09008 10000.000000 00000.123456 9 644500000" . ($newPlan->price * 100);
?>

<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <h3 class="card-title fw-bold fs-2">Concluir Upgrade: Pagamento</h3>
    </div>

    <div class="card-body pt-0">
        <div class="alert alert-info d-flex align-items-center p-5 mb-10">
            <i class="ki-outline ki-information-4 fs-2hx text-info me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-info">Simulação Acadêmica</h4>
                <span>Este pagamento é fictício e simula a tela de checkout. O valor será de R$ <?= number_format($newPlan->price, 2, ',', '.') ?>.</span>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-5">
                <div class="bg-light-primary rounded p-5">
                    <h4 class="fw-bold mb-3">Detalhes da Transação</h4>
                    <p class="fs-5">
                        Você está contratando o plano: <strong class="text-primary"><?= $newPlan->name; ?></strong>
                    </p>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-dashed py-2">
                        <span class="text-muted fw-semibold">Próxima Mensalidade:</span>
                        <span class="fs-4 fw-bold text-gray-800"><?= $newPlan->priceFormatted(); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3">
                        <span class="fs-3 fw-bolder text-gray-900">Total Imediato a Pagar:</span>
                        <span class="fs-2x fw-bolder text-danger"><?= $newPlan->priceFormatted(); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h3 class="fw-bold mb-5">Opções de Pagamento (Simulação)</h3>

                <form action="<?= url("app/upgrade/process") ?>" method="post">
                    <input type="hidden" name="planId" value="<?= $newPlan->id; ?>">

                    <div class="card mb-5">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 text-success">Pagamento por PIX</h5>
                            <p class="fw-semibold text-muted">Use a chave Telefone: <strong class="text-success"><?= $fakePixKey; ?></strong></p>
                            <img src="https://via.placeholder.com/150?text=QR+CODE+PIX" alt="QR Code Fictício" class="w-150px h-150px rounded border p-2">
                            <p class="fs-6 mt-3">Valor: **<?= $newPlan->priceFormatted(); ?>**</p>
                        </div>
                    </div>

                    <div class="card mb-8">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 text-warning">Boleto Bancário</h5>
                            <p class="fw-semibold text-muted">Número (fictício):</p>
                            <code class="d-block bg-light p-2 rounded fw-bold text-warning fs-7"><?= $fakeBoletoNumber; ?></code>
                            <p class="fs-6 mt-3">Valor: **<?= $newPlan->priceFormatted(); ?>**</p>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg btn-primary">
                            <i class="ki-outline ki-send fs-3 me-2"></i> Simular Pagamento e Ativar Upgrade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>