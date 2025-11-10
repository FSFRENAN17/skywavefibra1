<?php $this->layout("_theme", ["activeMenu" => "cliente"]); ?>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <!--begin::Alert - Ticket Status-->
    <?php if ($ticket->status == 'resolved'): ?>
        <div class="alert alert-success d-flex align-items-center p-5 mb-5">
            <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Chamado Resolvido</h4>
                <span>Este chamado foi marcado como resolvido. Se o problema persistir, você pode reabri-lo.</span>
            </div>
        </div>
    <?php elseif ($ticket->status == 'in-progress'): ?>
        <div class="alert alert-info d-flex align-items-center p-5 mb-5">
            <i class="ki-outline ki-timer fs-2hx text-info me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-info">Em Andamento</h4>
                <span>Nossa equipe está trabalhando no seu chamado.</span>
            </div>
        </div>
        <?php endif; ?>

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title flex-column">
                    <h3 class="fw-bold mb-1">Chamado #<?= str_pad($ticket->id, 6, '0', STR_PAD_LEFT) ?></h3>
                    <div class="fs-6 text-muted"><?= htmlspecialchars($ticket->title, ENT_QUOTES, 'UTF-8') ?></div>
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
                
                <!--begin::Details-->
                <div class="row mb-7">
                    <div class="col-lg-3">
                        <div class="fs-6 text-muted fw-semibold">Categoria</div>
                        <div class="fs-5 fw-bold"><?= $ticket->categoryLabel() ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="fs-6 text-muted fw-semibold">Status</div>
                        <div class="mt-1"><?= $ticket->statusBadge() ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="fs-6 text-muted fw-semibold">Prioridade</div>
                        <div class="mt-1"><?= $ticket->priorityBadge() ?></div>
                    </div>
                    <div class="col-lg-3">
                        <div class="fs-6 text-muted fw-semibold">Data de Abertura</div>
                        <div class="fs-6 fw-bold"><?= date("d/m/Y H:i", strtotime($ticket->opened_at)) ?></div>
                    </div>
                </div>

                <?php if ($ticket->employee_id): ?>
                <div class="row mb-7">
                    <div class="col-lg-12">
                        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                            <i class="ki-outline ki-user-tick fs-2tx text-primary me-4"></i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <h4 class="text-gray-900 fw-bold">Responsável</h4>
                                    <div class="fs-6 text-gray-700"><?= $ticket->employee()->person()->first_name ?> está cuidando do seu chamado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="separator my-7"></div>

                <!--begin::Description-->
                <div class="mb-10">
                    <h4 class="fw-bold mb-4">Descrição do Problema</h4>
                    <div class="text-gray-800 fs-6">
                        <?= nl2br(htmlspecialchars($ticket->description)) ?>
                    </div>
                </div>
                <!--end::Description-->

                <!--begin::Tabs-->
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_comments">
                            <i class="ki-outline ki-message-text fs-3 me-1"></i>
                            Comentários (<span id="commentsCount">0</span>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_attachments">
                            <i class="ki-outline ki-file fs-3 me-1"></i>
                            Anexos (<span id="attachmentsCount">0</span>)
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!--begin::Tab Comments-->
                    <div class="tab-pane fade show active" id="tab_comments" role="tabpanel">
                        <div id="commentsContainer">
                            <div class="text-center py-10">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>
                        </div>

                        <!--begin::Comment Form-->
                        <div class="separator my-7"></div>
                        <h5 class="fw-bold mb-5">Adicionar Comentário</h5>
                        <form id="commentForm" class="ajax-off" action="#">
                            <?= csrf_input(); ?>
                            <input type="hidden" name="context" value="client">
                            <textarea name="comment" class="form-control form-control-solid mb-3" rows="4" placeholder="Digite seu comentário..."></textarea>
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-outline ki-message-add fs-3"></i>
                                Enviar Comentário
                            </button>
                        </form>
                        <!--end::Comment Form-->
                    </div>
                    <!--end::Tab Comments-->

                    <!--begin::Tab Attachments-->
                    <div class="tab-pane fade" id="tab_attachments" role="tabpanel">
                        <div id="attachmentsContainer">
                            <div class="text-center py-10">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>
                        </div>

                        <!--begin::Upload Form-->
                        <div class="separator my-7"></div>
                        <h5 class="fw-bold mb-5">Enviar Anexo</h5>
                        <form id="uploadForm" class="ajax-off" enctype="multipart/form-data" action="#">
                            <?= csrf_input(); ?>
                            <input type="hidden" name="context" value="client">
                            <input type="file" name="file" class="form-control form-control-solid mb-3" />
                            <div class="form-text mb-3">Arquivos permitidos: imagens, PDF, DOC, XLS. Tamanho máximo: 10MB</div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-outline ki-file-up fs-3"></i>
                                Enviar Arquivo
                            </button>
                        </form>
                        <!--end::Upload Form-->
                    </div>
                    <!--end::Tab Attachments-->
                </div>
                <!--end::Tabs-->

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
    const ticketId = <?= $ticket->id ?>;
    const toText = (msg) => {
        if (typeof msg === 'string') return msg;
        if (msg && typeof msg === 'object') {
            return msg.text || msg.message || JSON.stringify(msg);
        }
        return '';
    };
    
    // Load Comments
    function loadComments() {
        const url = '<?= url("app/chamado/comentarios") ?>/' + ticketId;

        fetch(url, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => {

                return response.json();
            })
            .then(data => {

                // Handle both formats: array directly or {success: true, comments: [...]}
                const comments = Array.isArray(data) ? data : (data.comments || []);
                
                // Filtrar comentários internos - clientes não podem ver
                const visibleComments = comments.filter(comment => comment.is_internal != 1);
                
                document.getElementById('commentsCount').textContent = visibleComments.length;
                
                if (visibleComments.length === 0) {
                    document.getElementById('commentsContainer').innerHTML = `
                        <div class="text-center py-10">
                            <i class="ki-outline ki-message-text fs-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum comentário ainda</p>
                        </div>
                    `;
                } else {
                    let html = '';
                    visibleComments.forEach(function(comment) {
                        // Define cor e ícone baseado no tipo de usuário
                        const borderColor = comment.is_employee ? 'border-primary' : 'border-success';
                        const badgeColor = comment.is_employee ? 'badge-light-primary' : 'badge-light-success';
                        const userType = comment.is_employee ? 'Funcionário' : 'Cliente';
                        const userIcon = comment.is_employee ? 'ki-shield-tick' : 'ki-profile-user';
                        
                        html += `
                            <div class="card ${borderColor} border border-2 mb-5 shadow-sm">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-start">
                                        <div class="symbol symbol-45px me-4">
                                            <div class="symbol-label bg-light-${comment.is_employee ? 'primary' : 'success'}">
                                                <i class="ki-outline ${userIcon} fs-2 text-${comment.is_employee ? 'primary' : 'success'}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="fw-bold text-gray-800 fs-5">${comment.user_name || 'Usuário'}</span>
                                                    <span class="${badgeColor} badge ms-2">${userType}</span>
                                                </div>
                                                <span class="text-muted fs-7">${comment.created_at || ''}</span>
                                            </div>
                                            <div class="text-gray-700 fs-6">${comment.comment || ''}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('commentsContainer').innerHTML = html;
                }
            })
            .catch(error => {

                document.getElementById('commentsContainer').innerHTML = `
                    <div class="text-center py-10">
                        <i class="ki-outline ki-message-text fs-3x text-danger mb-3"></i>
                        <p class="text-danger">Erro ao carregar comentários</p>
                    </div>
                `;
            });
    }
    
    // Load Attachments
    function loadAttachments() {
        const url = '<?= url("app/chamado/anexos") ?>/' + ticketId;

        fetch(url, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => {

                return response.json();
            })
            .then(data => {

                // Handle both formats: array directly or {success: true, attachments: [...]}
                const attachments = Array.isArray(data) ? data : (data.attachments || []);
                
                document.getElementById('attachmentsCount').textContent = attachments.length;
                
                if (attachments.length === 0) {
                    document.getElementById('attachmentsContainer').innerHTML = `
                        <div class="text-center py-10">
                            <i class="ki-outline ki-file fs-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum anexo</p>
                        </div>
                    `;
                } else {
                    let html = '';
                    attachments.forEach(function(att) {
                        // Define cor e ícone baseado no tipo de usuário
                        const bgColor = att.is_employee ? 'bg-light-primary' : 'bg-light-success';
                        const iconColor = att.is_employee ? 'text-primary' : 'text-success';
                        const badgeColor = att.is_employee ? 'badge-light-primary' : 'badge-light-success';
                        const userType = att.is_employee ? 'Funcionário' : 'Cliente';
                        
                        html += `
                            <div class="card mb-4 border border-2 ${att.is_employee ? 'border-primary' : 'border-success'} shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="symbol symbol-50px ${bgColor} me-4">
                                                <i class="ki-outline ${att.icon || 'ki-file'} fs-2x ${iconColor}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <a href="${att.url || '#'}" target="_blank" class="fw-bold text-gray-800 text-hover-primary fs-6 d-block">
                                                    ${att.filename || 'Arquivo'}
                                                </a>
                                                <div class="text-muted fs-7 mt-1">
                                                    <span class="fw-semibold">${att.formatted_size || att.size || ''}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>${att.uploaded_at || ''}</span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="${badgeColor} badge">${userType}${att.user_name ? ': ' + att.user_name : ''}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="${att.url || '#'}" target="_blank" class="btn btn-sm btn-icon btn-light-primary">
                                            <i class="ki-outline ki-down fs-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    document.getElementById('attachmentsContainer').innerHTML = html;
                }
            })
            .catch(error => {

                document.getElementById('attachmentsContainer').innerHTML = `
                    <div class="text-center py-10">
                        <i class="ki-outline ki-file fs-3x text-danger mb-3"></i>
                        <p class="text-danger">Erro ao carregar anexos</p>
                    </div>
                `;
            });
    }
    
    // Submit Comment
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const textarea = this.querySelector('textarea[name="comment"]');
            const comment = textarea.value;
            
            if (!comment.trim()) {
                Swal.fire('Atenção', 'Digite um comentário', 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('comment', comment);
            const csrfEl = commentForm.querySelector("input[name='csrf']");
            if (csrfEl) formData.append('csrf', csrfEl.value);
            const contextEl = commentForm.querySelector("input[name='context']");
            if (contextEl) formData.append('context', contextEl.value);
            
            fetch('<?= url("app/chamado") ?>/' + ticketId + '/comentario', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentForm.reset();
                    loadComments();
                } else {
                    Swal.fire('Erro', toText(data.message) || 'Erro ao adicionar comentário', 'error');
                }
            })
            .catch(error => {

                Swal.fire('Erro', 'Não foi possível adicionar o comentário', 'error');
            });
        });
    }
    
    // Submit Upload
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const fileInput = this.querySelector('input[name="file"]');
            if (!fileInput.files.length) {
                Swal.fire('Atenção', 'Selecione um arquivo', 'warning');
                return;
            }

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            // ensure backend can pick ticket id from POST when route format differs
            formData.append('ticket_id', ticketId);
            const csrfElU = uploadForm.querySelector("input[name='csrf']");
            if (csrfElU) formData.append('csrf', csrfElU.value);
            const contextElU = uploadForm.querySelector("input[name='context']");
            if (contextElU) formData.append('context', contextElU.value);

            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

            fetch('<?= url("app/chamado") ?>/' + ticketId + '/anexo', {
                method: 'POST',
                body: formData
            })
            .then(r => r.text())
            .then(text => {
                let data;
                try { data = JSON.parse(text); } catch (e) {

                    Swal.fire('Erro', 'Resposta inválida do servidor', 'error');
                    return;
                }
                if (data.success) {
                    uploadForm.reset();
                    fileInput.value = '';
                    loadAttachments();
                } else {
                    Swal.fire('Erro', toText(data.message) || 'Falha ao enviar arquivo', 'error');
                }
            })
            .catch(err => {

                Swal.fire('Erro', 'Não foi possível enviar o arquivo', 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }
    
    // Initial Load
    loadComments();
    loadAttachments();
});
</script>
<?php $this->end(); ?>
