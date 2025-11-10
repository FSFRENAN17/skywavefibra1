<!--begin::Menu wrapper-->
<div class="d-flex align-items-stretch" id="kt_app_header_menu_wrapper">
    <!--begin::Menu holder-->
    <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_menu_wrapper'}">
        <!--begin::Menu-->
        <div class="menu menu-rounded menu-column menu-lg-row menu-active-bg menu-title-gray-600 menu-state-gray-900 menu-arrow-gray-500 fw-semibold fw-semibold fs-6 align-items-stretch my-5 my-lg-0 px-2 px-lg-0" id="#kt_app_header_menu" data-kt-menu="true">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-220,0" class="<?= isset($activeMenu) && $activeMenu == "cliente" ? "here" : "" ?> menu-item show menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-outline ki-graph-3"></i>
                    </span>
                    <span class="menu-title">Cliente</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-850px">
                    <!--begin:Dashboards menu-->
                    <div class="menu-state-bg menu-extended overflow-hidden overflow-lg-visible" data-kt-menu-dismiss="true">
                        <!--begin:Row-->
                        <div class="row">
                            <!--begin:Col-->
                            <div class="col-lg-8 mb-3 mb-lg-0 py-3 px-3 py-lg-6 px-lg-6">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Menu item-->
                                        <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="index.html" class="menu-link active">
                                                <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                    <i class="ki-outline ki-element-11 text-primary fs-1"></i>
                                                </span>
                                                <span class="d-flex flex-column">
                                                    <span class="fs-6 fw-bold text-gray-800">Meu plano</span>
                                                    <span class="fs-7 fw-semibold text-muted">Detalhes do seu plano</span>
                                                </span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Menu item-->
                                        <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="dashboards/ecommerce.html" class="menu-link">
                                                <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                    <i class="ki-outline ki-basket text-danger fs-1"></i>
                                                </span>
                                                <span class="d-flex flex-column">
                                                    <span class="fs-6 fw-bold text-gray-800">Upgrade</span>
                                                    <span class="fs-7 fw-semibold text-muted">Mais velocidade pra você</span>
                                                </span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Menu item-->
                                        <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="dashboards/projects.html" class="menu-link">
                                                <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                    <i class="ki-outline ki-abstract-44 text-info fs-1"></i>
                                                </span>
                                                <span class="d-flex flex-column">
                                                    <span class="fs-6 fw-bold text-gray-800">Suporte</span>
                                                    <span class="fs-7 fw-semibold text-muted">Precisa de ajuda?</span>
                                                </span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Menu item-->
                                        <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="dashboards/marketing.html" class="menu-link">
                                                <span class="menu-custom-icon d-flex flex-center flex-shrink-0 rounded w-40px h-40px me-3">
                                                    <i class="ki-outline ki-chart-simple text-gray-900 fs-1"></i>
                                                </span>
                                                <span class="d-flex flex-column">
                                                    <span class="fs-6 fw-bold text-gray-800">Status de Serviço</span>
                                                    <span class="fs-7 fw-semibold text-muted">Como está o provedor?</span>
                                                </span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                                <div class="separator separator-dashed mx-5 my-5"></div>
                                <!--begin:Landing-->
                                <div class="d-flex flex-stack flex-wrap flex-lg-nowrap gap-2 mx-5">
                                    <div class="d-flex flex-column me-5">
                                        <div class="fs-6 fw-bold text-gray-800">Site Oficial</div>
                                        <div class="fs-7 fw-semibold text-muted">Você conhece o nosso site oficial?</div>
                                    </div>
                                    <a href="<?= url() ?>" target="_blank" class="btn btn-sm btn-primary fw-bold">skywavefibra.com.br/</a>
                                </div>
                                <!--end:Landing-->
                            </div>
                            <!--end:Col-->
                            <!--begin:Col-->
                            <div class="menu-more bg-light col-lg-4 py-3 px-3 py-lg-6 px-lg-6 rounded-end">
                                <!--begin:Heading-->
                                <h4 class="fs-6 fs-lg-4 text-gray-800 fw-bold mt-3 mb-3 ms-4">Links Úteis</h4>
                                <!--end:Heading-->
                                <!--begin:Menu item-->
                                <div class="menu-item p-0 m-0">
                                    <!--begin:Menu link-->
                                    <a href="https://www.speedtest.net/" target="_blank" class="menu-link py-2">
                                        <span class="menu-title">Teste sua velocidade</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                            </div>
                            <!--end:Col-->
                        </div>
                        <!--end:Row-->
                    </div>
                    <!--end:Dashboards menu-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" data-kt-menu-offset="22,0" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-outline ki-wrench"></i>
                    </span>
                    <span class="menu-title">Suporte</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="<?= url("app/meus-chamados") ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-message-text-2 fs-2"></i>
                            </span>
                            <span class="menu-title">Meus Chamados</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="<?= url("app/criar-chamado") ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-message-add fs-2"></i>
                            </span>
                            <span class="menu-title">Abrir Chamado</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" data-kt-menu-offset="-400,0" class="<?= isset($activeMenu) && $activeMenu == "admin" ? "here" : "" ?> menu-item menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-outline ki-category"></i>
                    </span>
                    <span class="menu-title">Adm</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0">
                    <!--begin:Pages menu-->
                    <div class="menu-active-bg px-4 px-lg-0">
                        <!--begin:Tabs nav-->
                        <div class="d-flex w-100 overflow-auto">
                            <ul class="nav nav-stretch nav-line-tabs fw-bold fs-6 p-0 p-lg-10 flex-nowrap flex-grow-1">
                                <!--begin:Nav item-->
                                <li class="nav-item mx-lg-1">
                                    <a class="nav-link py-3 py-lg-6 active text-active-primary" href="#" data-bs-toggle="tab" data-bs-target="#menu_clientes">Clientes</a>
                                </li>
                                <!--end:Nav item-->
                                <!--begin:Nav item-->
                                <li class="nav-item mx-lg-1">
                                    <a class="nav-link py-3 py-lg-6 text-active-primary" href="#" data-bs-toggle="tab" data-bs-target="#menu_equipamentos">Equipamentos</a>
                                </li>
                                <!--end:Nav item-->
                                <!--begin:Nav item-->
                                <li class="nav-item mx-lg-1">
                                    <a class="nav-link py-3 py-lg-6 text-active-primary" href="#" data-bs-toggle="tab" data-bs-target="#menu_funcionarios">Funcionários</a>
                                </li>
                                <!--end:Nav item-->
                                <!--begin:Nav item-->
                                <li class="nav-item mx-lg-1">
                                    <a class="nav-link py-3 py-lg-6 text-active-primary" href="#" data-bs-toggle="tab" data-bs-target="#menu_chamados">Chamados</a>
                                </li>
                                <!--end:Nav item-->
                            </ul>
                        </div>
                        <!--end:Tabs nav-->
                        <!--begin:Tab content-->
                        <div class="tab-content py-4 py-lg-8 px-lg-7">
                            <!--begin:Tab pane-->
                            <div class="tab-pane active w-lg-600px" id="menu_clientes">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-8 mb-6 mb-lg-0">
                                        <!--begin:Row-->
                                        <div class="row">
                                            <!--begin:Col-->
                                            <div class="col-lg-6">
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/clientes") ?>" class="menu-link">
                                                        <span class="menu-title fw-bold text-info">
                                                            <i class="fa fa-search me-2"></i>
                                                            Listar Cliente
                                                        </span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("/app/cliente/novo") ?>" class="menu-link">
                                                        <span class="menu-title">Cadastrar Novo Cliente</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                            </div>
                                            <!--end:Col-->
                                        </div>
                                        <!--end:Row-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-4">
                                        <img src="assets/media/stock/900x600/46.jpg" class="rounded mw-100" alt="" />
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                            </div>
                            <!--end:Tab pane-->
                            <!--begin:Tab pane-->
                            <div class="tab-pane w-lg-600px" id="menu_equipamentos">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-8 mb-6 mb-lg-0">
                                        <!--begin:Row-->
                                        <div class="row">
                                            <!--begin:Col-->
                                            <div class="col-lg-6">
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/equipamentos") ?>" class="menu-link">
                                                        <span class="menu-title fw-bold text-info text-nowrap">
                                                            <i class="fa fa-list me-2"></i>
                                                            Listar Equipamentos
                                                        </span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/equipamento/novo") ?>" class="menu-link">
                                                        <span class="menu-title">Cadastrar Equipamento</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                            </div>
                                            <!--end:Col-->
                                        </div>
                                        <!--end:Row-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-4">
                                        <img src="assets/media/stock/900x600/46.jpg" class="rounded mw-100" alt="" />
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                            </div>
                            <!--end:Tab pane-->
                            <!--begin:Tab pane-->
                            <div class="tab-pane w-lg-600px" id="menu_funcionarios">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-8 mb-6 mb-lg-0">
                                        <!--begin:Row-->
                                        <div class="row">
                                            <!--begin:Col-->
                                            <div class="col-lg-6">
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/funcionarios") ?>" class="menu-link">
                                                        <span class="menu-title fw-bold text-info text-nowrap">
                                                            <i class="fa fa-list me-2"></i>
                                                            Listar Funcionários
                                                        </span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                            </div>
                                            <!--end:Col-->
                                        </div>
                                        <!--end:Row-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-4">
                                        <img src="assets/media/stock/900x600/46.jpg" class="rounded mw-100" alt="" />
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                            </div>
                            <!--end:Tab pane-->
                            <!--begin:Tab pane-->
                            <div class="tab-pane w-lg-600px" id="menu_chamados">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-8 mb-6 mb-lg-0">
                                        <!--begin:Row-->
                                        <div class="row">
                                            <!--begin:Col-->
                                            <div class="col-lg-12">
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/chamados/dashboard") ?>" class="menu-link">
                                                        <span class="menu-title fw-bold text-info text-nowrap">
                                                            <i class="ki-outline ki-graph-up me-2"></i>
                                                            Dashboard de Chamados
                                                        </span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/chamados") ?>" class="menu-link">
                                                        <span class="menu-title">Chamados Em Aberto</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                                <!--begin:Menu item-->
                                                <div class="menu-item p-0 m-0">
                                                    <!--begin:Menu link-->
                                                    <a href="<?= url("app/chamados/historico") ?>" class="menu-link">
                                                        <span class="menu-title">Histórico de Chamados</span>
                                                    </a>
                                                    <!--end:Menu link-->
                                                </div>
                                                <!--end:Menu item-->
                                            </div>
                                            <!--end:Col-->
                                        </div>
                                        <!--end:Row-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-4">
                                        <img src="assets/media/stock/900x600/46.jpg" class="rounded mw-100" alt="" />
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                            </div>
                            <!--end:Tab pane-->
                        </div>
                        <!--end:Tab content-->
                    </div>
                    <!--end:Pages menu-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" data-kt-menu-offset="22,0" class="<?= isset($activeMenu) && $activeMenu == "sistema" ? "here" : "" ?> menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-outline ki-setting-2"></i>
                    </span>
                    <span class="menu-title">Sistema</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="<?= url("app/usuarios?clear=1") ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-people fs-2"></i>
                            </span>
                            <span class="menu-title">Usuários</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="<?= url("app/planos") ?>">
                            <span class="menu-icon">
                                <i class="ki-outline ki-tablet-text-down fs-2"></i>
                            </span>
                            <span class="menu-title">Planos</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <div class="menu-separator border my-3"></div>
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="https://github.com/SkyWave-Fibra/site" target="_blank" title="Documentação técnica presente no GitHub" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                            <span class="menu-icon">
                                <i class="ki-outline ki-rocket fs-2"></i>
                            </span>
                            <span class="menu-title">Documentação</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu holder-->
</div>
<!--end::Menu wrapper-->