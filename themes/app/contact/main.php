<?php $this->layout("_theme"); ?>

<div class="card">
    <div class="card-header border-0 pt-6">
        <!-- Mantendo a variável $title para o título da página -->
        <h2 class="card-title"><?= $title ?? "Canais de Atendimento Skywave Fibra"; ?></h2>
    </div>
    <div class="card-body pt-0">
        <p class="fs-4 fw-semibold text-muted">
            Para solicitar um plano personalizado, discutir suas necessidades empresariais ou entrar em contato direto com a equipe, utilize os canais abaixo.
        </p>

        <div class="d-flex flex-column gap-5 mt-8">
            
            <!-- WhatsApp -->
            <a href="https://wa.me/5575987092386" target="_blank" class="btn btn-lg btn-success w-lg-250px">
                <i class="ki-outline ki-whatsapp fs-2 me-3"></i> WhatsApp: (75) 8709-2386
            </a>
            
            
            <!-- Instagram -->
            <a href="https://instagram.com/skywave_fibra" target="_blank" class="btn btn-lg btn-light-danger w-lg-250px">
                <i class="ki-outline ki-instagram fs-2 me-3"></i> Instagram: @skywave_fibra
            </a>
            
            <!-- Facebook -->
            <a href="https://facebook.com/SkywaveFibra" target="_blank" class="btn btn-lg btn-light-primary w-lg-250px">
                <i class="ki-outline ki-facebook fs-2 me-3"></i> Facebook: Skywave Fibra
            </a>

  
            
        </div>
        
        <div class="mt-10">
            <a href="<?= url("app") ?>" class="btn btn-light">Voltar ao Dashboard</a>
        </div>
    </div>
</div>