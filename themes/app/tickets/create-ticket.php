<?php $this->layout("_theme", ["activeMenu" => "cliente"]); ?>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold m-0">Abrir Chamado de Suporte</h3>
                </div>
                <div class="card-toolbar">
                    <a href="<?= url("app/meus-chamados") ?>" class="btn btn-sm btn-light">
                        <i class="ki-outline ki-left fs-3"></i>
                        Voltar
                    </a>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Form-->
                <form id="ticketForm" class="form" data-action="<?= url("app/criar-chamado") ?>" novalidate>
                    <?= csrf_input(); ?>

                    <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                        <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-info">Como podemos te ajudar?</h4>
                            <span>Preencha o formulário abaixo com detalhes sobre o problema ou solicitação. Nossa equipe responderá em breve!</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted required">Categoria</label>
                        <div class="col-lg-9">
                            <select name="category" class="form-select form-select-solid" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="installation">Instalação</option>
                                <option value="maintenance">Manutenção</option>
                                <option value="billing">Cobrança</option>
                                <option value="cancellation">Cancelamento</option>
                                <option value="technical">Suporte Técnico</option>
                            </select>
                            <div class="form-text">Selecione a categoria que melhor descreve seu problema</div>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted required">Assunto</label>
                        <div class="col-lg-9">
                            <input type="text" name="title" class="form-control form-control-solid" placeholder="Descreva brevemente o problema" maxlength="255" required />
                            <div class="form-text">Ex: "Internet lenta" ou "Problema com roteador"</div>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-3 fw-semibold text-muted">Prioridade</label>
                        <div class="col-lg-9">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="priority" value="low" id="priority_baixa" checked>
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary" for="priority_baixa">
                                    <i class="ki-outline ki-down fs-3"></i>
                                    Baixa
                                </label>

                                <input type="radio" class="btn-check" name="priority" value="medium" id="priority_media">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-warning" for="priority_media">
                                    <i class="ki-outline ki-right fs-3"></i>
                                    Média
                                </label>

                                <input type="radio" class="btn-check" name="priority" value="high" id="priority_alta">
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-danger" for="priority_alta">
                                    <i class="ki-outline ki-up fs-3"></i>
                                    Alta
                                </label>
                            </div>
                            <div class="form-text">Selecione a urgência do seu problema</div>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <label class="col-lg-3 fw-semibold text-muted required">Descrição</label>
                        <div class="col-lg-9">
                            <textarea name="description" class="form-control form-control-solid" rows="8" placeholder="Descreva detalhadamente o problema que você está enfrentando..." minlength="10" required></textarea>
                            <div class="form-text">Quanto mais detalhes você fornecer, mais rápido conseguiremos resolver</div>
                        </div>
                    </div>

                    <div class="separator mb-7"></div>

                    <div class="row">
                        <div class="col-lg-9 offset-lg-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-outline ki-check fs-3"></i>
                                Abrir Chamado
                            </button>
                            <a href="<?= url("app/meus-chamados") ?>" class="btn btn-light ms-2">Cancelar</a>
                        </div>
                    </div>

                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->

<?php $this->start("scripts"); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    
    const form = document.getElementById('ticketForm');
    let isSubmitting = false;
    let submissionTimeout;
    
    // Disable button immediately to prevent accidental clicks
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Previne dupla submissão - verifica flag E button state
        if (isSubmitting || submitBtn.disabled) {

            return false;
        }
        

        
        // Validação manual
        const category = form.querySelector('select[name="category"]').value;
        const title = form.querySelector('input[name="title"]').value;
        const description = form.querySelector('textarea[name="description"]').value;
        
        if (!category || !title || !description) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Por favor, preencha todos os campos obrigatórios.'
                });
            } else {
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
            return false;
        }
        
        // Marca como enviando ANTES de fazer o fetch
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        
        // Garante que o estado seja resetado após 60 segundos mesmo que a requisição falhe/demore
        submissionTimeout = setTimeout(() => {

            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }, 60000);
        
        const formData = new FormData(form);
        // include csrf token explicitly if needed server-side
        if (!formData.has('csrf')) {
            const csrfEl = form.querySelector("input[name='csrf']");
            if (csrfEl) formData.append('csrf', csrfEl.value);
        }
        

        
        fetch(form.dataset.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {

            clearTimeout(submissionTimeout);
            
            if (data.success) {
                // Imediatamente redireciona sem dar chance de novo clique
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Chamado Aberto!',
                        text: data.message || 'Seu chamado foi aberto com sucesso. Nossa equipe entrará em contato em breve.',
                        confirmButtonText: 'Ver Meus Chamados',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Usa timestamp para garantir que a URL mude
                        window.location.href = '<?= url("app/meus-chamados") ?>?t=' + Date.now();
                    });
                } else {
                    alert(data.message || 'Chamado aberto com sucesso!');
                    window.location.href = '<?= url("app/meus-chamados") ?>?t=' + Date.now();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Não foi possível abrir o chamado. Tente novamente.'
                    });
                } else {
                    alert(data.message || 'Erro ao abrir o chamado.');
                }
                // Reset state only on error
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {

            clearTimeout(submissionTimeout);
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao processar sua solicitação. Tente novamente.'
                });
            } else {
                alert('Erro ao processar solicitação.');
            }
            // Reset state only on error
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
        
        return false;
    });
});
</script>
<?php $this->stop(); ?>
