<?php $this->layout("_theme"); ?>

<div class="card">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3><?= $isEdit ? 'Editar Chamado #' . $ticket->id : 'Novo Chamado'; ?></h3>
        </div>
        <div class="card-toolbar">
            <a href="<?= $backUrl ?? url("/app/chamados"); ?>" class="btn btn-light btn-sm">
                <i class="ki-outline ki-arrow-left fs-3"></i> Voltar
            </a>
        </div>
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body">
        <form id="ticketForm" method="post" action="<?= url("/app/chamado/salvar"); ?>">
            <?= csrf_input(); ?>
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $ticket->id; ?>">
            <?php endif; ?>

            <div class="row g-5">
                <!--begin::Cliente-->
                <div class="col-md-6">
                    <label class="form-label required">Cliente</label>
                        <select name="customer_id" class="form-select" required <?= $isEdit ? 'disabled' : ''; ?>>
                        <option value="">Selecione um cliente...</option>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $customer): 
                                $customerPerson = $customer->person();
                            ?>
                                    <option value="<?= $customer->person_id; ?>" 
                                        <?= ($isEdit && $ticket->customer_id == $customer->person_id) ? 'selected' : ''; ?>>
                                    <?= $customerPerson->full_name ?? 'Cliente'; ?> - <?= $customerPerson->document ?? ''; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="customer_id" value="<?= $ticket->customer_id; ?>">
                        <small class="text-muted">O cliente não pode ser alterado após a criação do chamado</small>
                    <?php endif; ?>
                </div>
                <!--end::Cliente-->

                <!--begin::Categoria-->
                <div class="col-md-6">
                    <label class="form-label required">Categoria</label>
                    <select name="category" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="installation" <?= ($ticket->category ?? '') === 'installation' ? 'selected' : ''; ?>>Instalação</option>
                        <option value="maintenance" <?= ($ticket->category ?? '') === 'maintenance' ? 'selected' : ''; ?>>Manutenção</option>
                        <option value="billing" <?= ($ticket->category ?? '') === 'billing' ? 'selected' : ''; ?>>Cobrança</option>
                        <option value="cancellation" <?= ($ticket->category ?? '') === 'cancellation' ? 'selected' : ''; ?>>Cancelamento</option>
                        <option value="technical" <?= ($ticket->category ?? '') === 'technical' ? 'selected' : ''; ?>>Técnico</option>
                    </select>
                </div>
                <!--end::Categoria-->

                <!--begin::Prioridade-->
                <div class="col-md-6">
                    <label class="form-label required">Prioridade</label>
                    <select name="priority" class="form-select" required>
                        <option value="low" <?= ($ticket->priority ?? 'low') === 'low' ? 'selected' : ''; ?>>Baixa</option>
                        <option value="medium" <?= ($ticket->priority ?? '') === 'medium' ? 'selected' : ''; ?>>Média</option>
                        <option value="high" <?= ($ticket->priority ?? '') === 'high' ? 'selected' : ''; ?>>Alta</option>
                        <option value="critical" <?= ($ticket->priority ?? '') === 'critical' ? 'selected' : ''; ?>>Crítica</option>
                    </select>
                </div>
                <!--end::Prioridade-->

                <!--begin::Status-->
                <div class="col-md-6">
                    <label class="form-label required">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="open" <?= ($ticket->status ?? 'open') === 'open' ? 'selected' : ''; ?>>Em Aberto</option>
                        <option value="in-progress" <?= ($ticket->status ?? '') === 'in-progress' ? 'selected' : ''; ?>>Em Andamento</option>
                        <option value="resolved" <?= ($ticket->status ?? '') === 'resolved' ? 'selected' : ''; ?>>Resolvido</option>
                        <option value="canceled" <?= ($ticket->status ?? '') === 'canceled' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                <!--end::Status-->

                <!--begin::Funcionário Responsável-->
                <div class="col-md-12">
                    <label class="form-label required">Funcionário Responsável</label>
                        <select name="employee_id" class="form-select">
                        <option value="">Não atribuído</option>
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $employee): 
                                $employeePerson = $employee->person();
                            ?>
                                    <option value="<?= $employee->person_id; ?>" 
                                        <?= ($isEdit && (int)$ticket->employee_id === (int)$employee->person_id) ? 'selected' : ''; ?>>
                                    <?= $employeePerson->full_name ?? 'Funcionário'; ?> - <?= $employee->role_name ?? $employee->role; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">
                        <i class="ki-outline ki-information-2 fs-6"></i>
                        Obrigatório atribuir um funcionário antes de alterar o status do chamado
                    </small>
                </div>
                <!--end::Funcionário Responsável-->

                <!--begin::Título/Assunto-->
                <div class="col-md-12">
                    <label class="form-label required">Assunto do Chamado</label>
                    <input type="text" name="title" class="form-control" required 
                        value="<?= htmlspecialchars($ticket->title ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Ex: Problema com conexão, Solicitação de instalação, etc.">
                    <small class="text-muted">Título breve que descreve o problema ou solicitação</small>
                </div>
                <!--end::Título/Assunto-->

                <!--begin::Descrição-->
                <div class="col-md-12">
                    <label class="form-label required">Descrição do Problema</label>
                    <textarea name="description" class="form-control" rows="6" required 
                        placeholder="Descreva detalhadamente o problema ou solicitação do cliente..."><?= htmlspecialchars($ticket->description ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <small class="text-muted">Seja o mais detalhado possível para facilitar o atendimento</small>
                </div>
                <!--end::Descrição-->

                <!--begin::Informações de Data (somente edição)-->
                <?php if ($isEdit): ?>
                    <div class="col-md-12">
                        <div class="alert alert-light-info d-flex align-items-center p-5">
                            <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                            <div class="d-flex flex-column">
                                <h5 class="mb-1">Informações do Chamado</h5>
                                <span>Aberto em: <strong><?= date_fmt($ticket->opened_at, "d/m/Y às H:i"); ?></strong></span>
                                <?php if ($ticket->closed_at): ?>
                                    <span>Fechado em: <strong><?= date_fmt($ticket->closed_at, "d/m/Y às H:i"); ?></strong></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!--end::Informações de Data-->
            </div>

            <!--begin::Actions-->
            <div class="d-flex justify-content-end gap-3 mt-10">
                <a href="<?= url("/app/chamados"); ?>" class="btn btn-light">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary" data-form_submit>
                    <span class="indicator-label">
                        <?= $isEdit ? 'Atualizar Chamado' : 'Criar Chamado'; ?>
                    </span>
                    <span class="indicator-progress" style="display: none;">
                        Aguarde... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
            <!--end::Actions-->
        </form>

        <!--begin::Tabs para Comentários, Anexos e Histórico (somente em edição)-->
        <?php if ($isEdit): ?>
            <div class="separator separator-dashed my-10"></div>

            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_comments">
                        <i class="ki-outline ki-message-text-2 fs-2 me-2"></i>Comentários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_attachments">
                        <i class="ki-outline ki-file fs-2 me-2"></i>Anexos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_history">
                        <i class="ki-outline ki-time fs-2 me-2"></i>Histórico
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="ticketTabs">
                <!--begin::Tab Comentários-->
                <div class="tab-pane fade show active" id="tab_comments">
                    <div class="card card-flush mb-5">
                        <div class="card-body">
                            <form id="commentForm" class="ajax-off mb-10">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="ticket_id" value="<?= $ticket->id; ?>">
                                <input type="hidden" name="context" value="admin">
                                <label class="form-label">Adicionar Comentário</label>
                                <textarea name="comment" class="form-control mb-3" rows="3" 
                                    placeholder="Digite seu comentário..."></textarea>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_internal" id="isInternal">
                                        <label class="form-check-label" for="isInternal">
                                            Comentário interno (não visível ao cliente)
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Adicionar Comentário
                                    </button>
                                </div>
                            </form>

                            <div id="commentsList">
                                <div class="text-center py-10">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Carregando comentários...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Tab Comentários-->

                <!--begin::Tab Anexos-->
                <div class="tab-pane fade" id="tab_attachments">
                    <div class="card card-flush mb-5">
                        <div class="card-body">
                            <form id="attachmentForm" class="ajax-off mb-10">
                                <?= csrf_input(); ?>
                                <input type="hidden" name="ticket_id" value="<?= $ticket->id; ?>">
                                <input type="hidden" name="context" value="admin">
                                <label class="form-label">Upload de Anexo</label>
                                <div class="d-flex gap-3">
                                    <input type="file" name="file" class="form-control" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ki-outline ki-cloud-upload fs-3"></i> Enviar
                                    </button>
                                </div>
                                <small class="text-muted">Tamanho máximo: 10MB. Formatos: imagens, PDF, DOC, XLS, ZIP</small>
                            </form>

                            <div id="attachmentsList">
                                <div class="text-center py-10">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Carregando anexos...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Tab Anexos-->

                <!--begin::Tab Histórico-->
                <div class="tab-pane fade" id="tab_history">
                    <div class="card card-flush">
                        <div class="card-body">
                            <div id="historyList">
                                <div class="text-center py-10">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Carregando histórico...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Tab Histórico-->
            </div>
        <?php endif; ?>
        <!--end::Tabs-->
    </div>
    <!--end::Card body-->
</div>

<!--begin::JS-->
<script>
    // Aguarda jQuery estar disponível
    function initTicketForm() {
        
        // Inicializa Select2 para melhor UX
        $('select').select2({
            width: '100%',
            placeholder: function() {
                return $(this).data('placeholder') || 'Selecione...';
            }
        });

        // Submit do formulário via AJAX
        $("#ticketForm").submit(function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = form.find('[data-form_submit]');
            const btnText = btn.find('.indicator-label');
            const btnProgress = btn.find('.indicator-progress');

            // Validação básica
            const customerId = form.find('[name="customer_id"]').val();
            const description = form.find('[name="description"]').val().trim();

            if (!customerId) {
                alert('Por favor, selecione um cliente.');
                return;
            }

            if (!description) {
                alert('Por favor, descreva o problema.');
                return;
            }

            // Desabilita botão e mostra loading
            btn.prop('disabled', true);
            btnText.hide();
            btnProgress.show();

            // Envia formulário
            const csrfToken = $("input[name='csrf']").val();
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize() + '&csrf=' + encodeURIComponent(csrfToken),
                dataType: 'json',
                success: function(response) {
                    if (response.message) {
                        // Mostra mensagem (supondo que o backend retorne HTML da mensagem)
                        $('body').append(response.message);
                    }

                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    alert('Erro ao salvar chamado. Tente novamente.');

                },
                complete: function() {
                    btn.prop('disabled', false);
                    btnText.show();
                    btnProgress.hide();
                }
            });
        });

        <?php if ($isEdit): ?>
        // === COMENTÁRIOS ===
        function loadComments() {
            
            fetch("<?= url('/app/chamado/comentarios/' . $ticket->id); ?>", {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                return response.text();
            })
            .then(responseText => {
                
                let response;
                try {
                    response = JSON.parse(responseText);
                } catch (e) {
                    $('#commentsList').html('<div class="text-center text-danger py-10">Erro: Resposta inválida.</div>');
                    return;
                }
                
                if (!response.success && response.error) {
                    $('#commentsList').html('<div class="text-center text-danger py-10">Erro: ' + response.error + '</div>');
                    return;
                }
                
                const list = (response && response.comments) ? response.comments : (Array.isArray(response) ? response : []);
                
                let html = '';
                if (list.length > 0) {
                    list.forEach(function(comment) {
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
                                                    <span class="fw-bold text-gray-800 fs-5">${comment.user_name}</span>
                                                    <span class="${badgeColor} badge ms-2">${userType}</span>
                                                </div>
                                                <span class="text-muted fs-7">${comment.created_at}</span>
                                            </div>
                                            <div class="text-gray-700 fs-6 mb-2">${comment.comment}</div>
                                            ${comment.is_internal ? '<span class="badge badge-light-warning"><i class="ki-outline ki-lock fs-7 me-1"></i>Interno</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted py-10">Nenhum comentário ainda.</div>';
                }
                $('#commentsList').html(html);
            })
            .catch(error => {

                $('#commentsList').html('<div class="text-center text-danger py-10">Erro de conexão: ' + error.message + '</div>');
            });
        }

        $('#commentForm').submit(function(e) {
            e.preventDefault();
            
            const comment = $(this).find('[name="comment"]').val().trim();
            if (!comment) {
                alertRender("toast", "warning", "Digite um comentário antes de enviar", "Atenção!");
                return;
            }

            const csrfToken = $("input[name='csrf']").val();
            const formData = $(this).serialize() + '&csrf=' + encodeURIComponent(csrfToken);
            
            $.ajax({
                url: "<?= url('/app/chamado/comentario/adicionar'); ?>",
                type: 'POST',
                data: formData,
                dataType: 'text', // Changed from 'json' to 'text'
                xhrFields: {
                    withCredentials: true
                },
                success: function(responseText) {
                    
                    let response;
                    try {
                        response = JSON.parse(responseText);
                    } catch (e) {
                        alert('Erro: Resposta inválida do servidor');
                        return;
                    }
                    
                    if (response.success) {
                        alertRender("toast", "success", response.message || 'Comentário adicionado com sucesso!', "Sucesso!");
                        $('[name="comment"]').val('');
                        $('#isInternal').prop('checked', false);
                        loadComments();
                    } else {
                        alertRender("toast", "danger", response.message || 'Erro ao adicionar comentário', "Erro!");
                    }
                },
                error: function(xhr, status, error) {
                    alertRender("toast", "danger", 'Erro ao adicionar comentário', "Erro!");
                }
            });
        });
        
        // === ANEXOS ===
        function loadAttachments() {
            $.ajax({
                url: "<?= url('/app/chamado/anexos/' . $ticket->id); ?>",
                type: 'GET',
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {




                    
                    const list = (response && response.attachments) ? response.attachments : (Array.isArray(response) ? response : []);

                    let html = '';
                    if (list.length > 0) {
                        list.forEach(function(att) {
                            // Define cor baseada no tipo de usuário
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
                                                    <a href="${att.url}" target="_blank" class="fw-bold text-gray-800 text-hover-primary fs-6 d-block">
                                                        ${att.filename}
                                                    </a>
                                                    <div class="text-muted fs-7 mt-1">
                                                        <span class="fw-semibold">${att.formatted_size || att.size || ''}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>${att.uploaded_at}</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <span class="${badgeColor} badge">${userType}: ${att.user_name}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteAttachment(${att.id})">
                                                <i class="ki-outline ki-trash fs-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div class="text-center text-muted py-10">Nenhum anexo ainda.</div>';
                    }
                    $('#attachmentsList').html(html);
                },
                error: function(xhr, status, error) {

                    $('#attachmentsList').html('<div class="text-center text-danger py-10">Erro ao carregar anexos. Verifique o console.</div>');
                }
            });
        }

        $('#attachmentForm').submit(function(e) {
            e.preventDefault();

            
            const fileInput = $(this).find('[name="file"]')[0];
            if (!fileInput.files.length) {
                alertRender("toast", "warning", "Selecione um arquivo antes de enviar", "Atenção!");
                return;
            }

            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            
            // Desabilita botão durante upload
            submitBtn.prop('disabled', true).text('Enviando...');
            
            $.ajax({
                url: "<?= url('/app/chamado/anexo/upload'); ?>",
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {

                    if (response.success) {
                        alertRender("toast", "success", response.message || 'Arquivo anexado com sucesso!', "Sucesso!");
                        $('[name="file"]').val('');
                        loadAttachments();
                    } else {
                        alertRender("toast", "danger", response.message || 'Erro ao anexar arquivo', "Erro!");
                    }
                },
                error: function(xhr, status, error) {

                    alertRender("toast", "danger", 'Erro ao enviar arquivo. Tente novamente.', "Erro!");
                },
                complete: function() {
                    // Re-habilita botão
                    submitBtn.prop('disabled', false).text('Enviar');
                }
            });
        });

        window.deleteAttachment = function(id) {
            if (!confirm('Deseja realmente excluir este anexo?')) return;
            

            const csrfToken = $("input[name='csrf']").val();
            
            $.ajax({
                url: "<?= url('/app/chamado/anexo/delete'); ?>",
                type: 'POST',
                data: {id: id, csrf: csrfToken},
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {

                    if (response.success) {
                        alertRender("toast", "success", "Anexo excluído com sucesso!", "Sucesso!");
                        loadAttachments();
                    } else {
                        alertRender("toast", "danger", response.message || "Erro ao excluir anexo", "Erro!");
                    }
                },
                error: function(xhr, status, error) {

                    alertRender("toast", "danger", "Erro ao excluir anexo. Tente novamente.", "Erro!");
                }
            });
        };

        // === HISTÓRICO ===
        function loadHistory() {

            $.ajax({
                url: "<?= url('/app/chamado/historico/' . $ticket->id); ?>",
                type: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {

                    const history = Array.isArray(response) ? response : (response.history || []);
                    let html = '';
                    if (history.length > 0) {
                        history.forEach(function(item) {
                            html += `
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-${item.color || 'primary'}">
                                            <i class="ki-outline ${item.icon || 'ki-time'} fs-2 text-${item.color || 'primary'}"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-bold mb-2">${item.action}</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2 fs-7">${item.created_at}</div>
                                                <span class="text-gray-800 fw-semibold">por ${item.user_name}</span>
                                            </div>
                                        </div>
                                        ${item.description ? `<div class="text-gray-700">${item.description}</div>` : ''}
                                        ${item.old_value ? `<div class="text-muted fs-7 mt-2">De: ${item.old_value} → Para: ${item.new_value}</div>` : ''}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div class="text-center text-muted py-10">Nenhum histórico disponível.</div>';
                    }
                    $('#historyList').html('<div class="timeline">' + html + '</div>');
                },
                error: function(xhr, status, error) {

                    $('#historyList').html('<div class="text-center text-danger py-10">Erro ao carregar histórico. Verifique o console.</div>');
                }
            });
        }

        // Carrega dados quando as tabs são clicadas
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const target = $(e.target).attr('href');

            if (target === '#tab_comments' && $('#commentsList').html().includes('Carregando')) {

                loadComments();
            } else if (target === '#tab_attachments' && $('#attachmentsList').html().includes('Carregando')) {

                loadAttachments();
            } else if (target === '#tab_history' && $('#historyList').html().includes('Carregando')) {

                loadHistory();
            }
        });

        // Carrega comentários inicialmente (tab ativa) - COM DELAY

        setTimeout(function() {

            loadComments();
        }, 500);
        <?php endif; ?>
    }
    
    // Aguarda jQuery estar disponível antes de inicializar
    if (typeof $ !== 'undefined') {

        $(document).ready(initTicketForm);
    } else {

        window.addEventListener('load', function() {

            if (typeof $ !== 'undefined') {
                $(document).ready(initTicketForm);
            } else {

            }
        });
    }
</script>
<!--end::JS-->
