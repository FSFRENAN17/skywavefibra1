<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script>
                © IsslerWeb.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Desenvolvido pela liderança do curso de GTI
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- removeNotificationModal -->
<div id="removeNotificationModal">
    <!-- Recurso removido | div mantida para atender o app.js do template -->
</div>

<!-- Add New Event MODAL -->
<!-- Recurso removido | div mantida para atender às funcionalidades do template -->
<div class="modal fade" id="event-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-info">
                <h5 class="modal-title" id="modal-title">Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form class="needs-validation" name="event-form" id="form-event" novalidate>
                    <div class="text-end">
                        <!--                        <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary" id="edit-event-btn" data-id="edit-event" onclick="editEvent(this)" role="button">Edit</a>-->
                        <a href="javascript:void(0);" id="idEvent" data-url="<?= url("/presence"); ?>" data-id="" class="btn btn-sm btn-soft-secondary" onclick="presence(this)">
                            Marcar presença
                        </a>
                    </div>
                    <div class="event-details">
                        <div class="d-flex mb-2">
                            <div class="flex-grow-1 d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ri-calendar-event-line text-muted fs-16"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="d-block fw-semibold mb-0"
                                        id="event-start-date-tag"></h6>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-time-line text-muted fs-16"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="d-block fw-semibold mb-0"><span
                                        id="event-timepicker1-tag"></span> - <span
                                        id="event-timepicker2-tag"></span></h6>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-map-pin-line text-muted fs-16"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="d-block fw-semibold mb-0"><span
                                        id="event-location-tag"></span></h6>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-discuss-line text-muted fs-16"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="d-block text-muted mb-0"
                                    id="event-description-tag"></p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                <i class=" ri-map-pin-user-fill text-muted fs-16"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6>Interessados:</h6>
                                <p class="d-block text-muted mb-0"
                                    id="event-players-tag"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row event-form">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Responsável</label>
                                <select class="form-select d-none" name="category"
                                    id="event-category" required>
                                    <option value="bg-soft-danger">Internato</option>
                                    <option value="bg-soft-success">GTI</option>
                                    <option value="bg-soft-primary">Direito</option>
                                    <option value="bg-soft-info">Odontologia</option>
                                    <option value="bg-soft-dark">Psicologia</option>
                                    <option value="bg-soft-warning">Pedagogia</option>
                                </select>
                                <div class="invalid-feedback">Selecione um responsável válido
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input class="form-control d-none" placeholder="********"
                                    type="password" name="password" id="event-password"
                                    required value="" />
                                <div class="invalid-feedback">Informe a senha</div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Quadras (fazer select com as qaudras
                                    disponíveis)</label>
                                <input class="form-control d-none"
                                    placeholder="Informe o nome do evento" type="text"
                                    name="title" id="event-title" required value="" />
                                <div class="invalid-feedback">Informe o nome do evento</div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-12">
                            <div class="mb-3">
                                <label>Data</label>
                                <div class="input-group d-none">
                                    <input type="text" id="event-start-date"
                                        class="form-control flatpickr flatpickr-input"
                                        placeholder="Selecione a data" readonly required>
                                    <span class="input-group-text"><i
                                            class="ri-calendar-event-line"></i></span>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-12" id="event-time">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Horário de início</label>
                                        <div class="input-group d-none">
                                            <input id="timepicker1" type="text"
                                                class="form-control flatpickr flatpickr-input"
                                                placeholder="Início" readonly>
                                            <span class="input-group-text"><i
                                                    class="ri-time-line"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Horário de finalização</label>
                                        <div class="input-group d-none">
                                            <input id="timepicker2" type="text"
                                                class="form-control flatpickr flatpickr-input"
                                                placeholder="Fim" readonly>
                                            <span class="input-group-text"><i
                                                    class="ri-time-line"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="event-location">Local</label>
                                <div>
                                    <input type="text" class="form-control d-none"
                                        name="event-location" id="event-location"
                                        placeholder="Quadra 1, Quadra 2...">
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <input type="hidden" id="eventid" name="eventid" value="" />
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Detalhes</label>
                                <textarea class="form-control d-none" id="event-description"
                                    placeholder="Digite os detalhes do evento" rows="3"
                                    spellcheck="false"></textarea>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                    <div class="hstack gap-2 justify-content-end">
                        <button type="submit" class="btn btn-success" id="btn-save-event">
                            Adicionar evento
                        </button>
                    </div>
                </form>
            </div>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div>