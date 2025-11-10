<?php $this->layout("_theme"); ?>

<h4 class="mt-5 bg-primary p-2 rounded">View do usuário sem contexto <small class="ms-2 text-muted">Quando o usuário se cadastra e não é nem cliente e nem funcionário</small></h4>
<hr>

<!--begin::Row-->
<div class="row gx-5 gx-xl-10">
    <!--begin::Col - Planos-->
    <div class="col-xl-4 mb-10">
        <div class="card card-flush h-100 shadow-sm border-0">
            <div class="card-header bg-primary text-white py-5">
                <h3 class="card-title fw-bold fs-2x mb-1">Nossos Planos de Internet</h3>
                <span class="fs-6 opacity-75">Escolha o plano ideal e navegue com a qualidade da Sky Wave Fibra.</span>
            </div>
            <div class="card-body p-6">
                <!-- Plano Básico -->
                <div class="mb-6 p-5 rounded border hover-elevate-up transition bg-light">
                    <h4 class="fw-bold text-primary mb-1">Plano Básico</h4>
                    <p class="text-gray-600 mb-2">Ideal para uso diário</p>
                    <div class="fs-2 fw-bold text-dark mb-2">R$ 89 <small class="fs-6 text-gray-600">/ mês</small></div>
                    <ul class="list-unstyled text-gray-700 mb-0">
                        <li>• 100 Mega de Velocidade</li>
                        <li>• Wi-Fi incluso</li>
                        <li>• Suporte 24h</li>
                    </ul>
                </div>

                <!-- Plano Família -->
                <div class="mb-6 p-5 rounded border hover-elevate-up transition bg-light">
                    <h4 class="fw-bold text-primary mb-1">Plano Família</h4>
                    <p class="text-gray-600 mb-2">Perfeito para vários dispositivos</p>
                    <div class="fs-2 fw-bold text-dark mb-2">R$ 129 <small class="fs-6 text-gray-600">/ mês</small></div>
                    <ul class="list-unstyled text-gray-700 mb-0">
                        <li>• 300 Mega de Velocidade</li>
                        <li>• Wi-Fi de alta performance</li>
                        <li>• Suporte técnico prioritário</li>
                    </ul>
                </div>

                <!-- Plano Turbo -->
                <div class="p-5 rounded border hover-elevate-up transition bg-light">
                    <h4 class="fw-bold text-primary mb-1">Plano Turbo</h4>
                    <p class="text-gray-600 mb-2">Máxima performance</p>
                    <div class="fs-2 fw-bold text-dark mb-2">R$ 199 <small class="fs-6 text-gray-600">/ mês</small></div>
                    <ul class="list-unstyled text-gray-700 mb-0">
                        <li>• 600 Mega de Velocidade</li>
                        <li>• Wi-Fi avançado incluso</li>
                        <li>• Atendimento VIP</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xl-8 mb-10">
        <!--begin::Engage widget 4-->
        <div class="card border-transparent w-100 shadow-sm sticky-top" data-bs-theme="light" style="background-color: #1C325E; top: 180px; z-index: 1;">
            <!-- <div class="card border-transparent w-100 shadow-sm" data-bs-theme="light" style="background-color: #1C325E;"> -->
            <!--begin::Body-->
            <div class="card-body d-flex ps-xl-15 position-relative">
                <!--begin::Wrapper-->
                <div class="m-0">
                    <!--begin::Title-->
                    <div class="position-relative fs-2x z-index-2 fw-bold text-white mb-7"
                        style="max-width: 480px; width: 100%;">
                        <span class="me-2">Navegue com a força da
                            <span class="position-relative d-inline-block text-warning">
                                <a href="javascript:void(0);" class="text-warning opacity-75-hover">Sky Wave Fibra</a>
                                <!--begin::Separator-->
                                <span class="position-absolute opacity-50 bottom-0 start-0 border-4 border-warning border-bottom w-100"></span>
                                <!--end::Separator-->
                            </span>
                        </span>
                        <br />Velocidade, estabilidade e suporte que te conectam a tudo!
                    </div>
                    <!--end::Title-->
                    <!--begin::Action-->
                    <div class="mb-3">
                        <a href="#contratar" class="btn btn-warning fw-semibold me-2 text-dark shadow">
                            💨 Contratar Agora
                        </a>
                    </div>
                    <!--end::Action-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Illustration-->
                <img src="assets/media/illustrations/sigma-1/17-dark.png"
                    class="position-absolute me-3 bottom-0 end-0 h-400px d-none d-md-block"
                    alt="Sky Wave Fibra - Internet de Alta Velocidade" />
                <!--end::Illustration-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Engage widget 4-->
    </div>
    <!--end::Col-->


</div>
<!--end::Row-->

<h5 class="mt-5 bg-primary p-2 rounded">View do cliente <small class="ms-2 text-muted">Só aparece para quem for cliente</small></h5>
<hr>

<?php if ($current_plan && $suggested_plan): ?>
<div class="row g-5 g-xxl-10">
    <div class="col-xxl-4 mb-xxl-10">
        <div class="card card-reset mb-5 mb-xl-10">
            <div class="card-body p-0">
                <div class="row g-5 g-lg-9">
                    <div class="col-6">
                        <div class="card card-shadow">
                            <div class="card-body p-0">
                                <a href='#' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                    <i class="ki-outline ki-element-11 fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                    <div class="fw-bold fs-5 pt-4">Meu Plano</div>
                                </a>
                                </div>
                            </div>
                        </div>
                    <div class="col-6">
                         </div>
                        <!--begin::Card-->
                        <div class="card card-shadow">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <!--begin::Items-->
                                <a href='<?= url("app/criar-chamado") ?>' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                    <!--begin::Icon-->
                                    <i class="ki-outline ki-rescue fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                    <!--end::Icon-->
                                    <!--begin::Desc-->
                                    <div class="fw-bold fs-5 pt-4">Suporte</div>
                                    <!--end::Desc-->
                                </a>
                                <!--end::Items-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-6">
                        <div class="card card-shadow">
                            <div class="card-body p-0">
                                <a href='#' data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan" class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                    <i class="ki-outline ki-rocket fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                    <div class="fw-bold fs-5 pt-4">Upgrade</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        </div>
                    </div>
            </div>
            </div>
        </div>
    <div class="col-xxl-8 mb-5 mb-xl-10">
        <div class="card border-0 mb-5 mb-xl-11" data-bs-theme="light" style="background-color: #844AFF">
            <div class="card-body py-0">
                <div class="row align-items-center lh-1 h-100">
                    <div class="col-7 ps-xl-10 pe-5">
                        <div class="fs-2qx fw-bold text-white mb-6">Faça agora o Upgrade</div>
                        <span class="fw-semibold text-white fs-6 mb-10 d-block opacity-75">Precisa de mais velocidade? Deixa isso com a gente!</span>
                        
                        <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-9">
                            <div class="d-flex align-items-center me-5 me-xl-13">
                                <div class="symbol symbol-30px symbol-circle me-3">
                                    <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                        <i class="ki-outline ki-abstract-41 fs-5 text-white"></i>
                                    </span>
                                </div>
                                <div class="text-white">
                                    <span class="fw-semibold d-block fs-8 opacity-75 mb-2">Seu plano atual</span>
                                    <span class="fw-bold fs-7"><?= $current_plan->name; ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-30px symbol-circle me-3">
                                    <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                        <i class="ki-outline ki-abstract-26 fs-5 text-white"></i>
                                    </span>
                                </div>
                                <div class="text-white">
                                    <span class="fw-semibold opacity-75 d-block fs-3 mb-2">Upgrade para</span>
                                    <span class="fw-bold fs-2"><?= $suggested_plan->name; ?></span>
                                </div>
                            </div>
                            </div>
                        <div class="d-flex d-grid gap-2">
                            <a href="#" class="btn btn-success me-lg-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Faça agora o Upgrade</a>
                        </div>
                        </div>
                    <div class="col-5 pt-5 pt-lg-15">
                        <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end bgi-position-y-bottom h-325px" style="background-image:url('assets/media/svg/illustrations/easy/8.svg"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<h5 class="mt-5 bg-primary p-2 rounded">View do funcionário <small class="ms-2 text-muted">Para funcionários</small></h5>
<hr>

<!--begin::Row-->
<div class="row gy-5 g-xl-10">
    <!--begin::Col-->
    <div class="col-xl-4 mb-xl-10">
        <!--begin::List widget 17-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Popular Products</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">8k social visitors</span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar">
                    <a href="apps/ecommerce/catalog/add-product.html" class="btn btn-sm btn-light">Add Product</a>
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-0">
                <!--begin::Content-->
                <div class="d-flex flex-stack my-5">
                    <span class="text-gray-500 fs-7 fw-bold">ITEM</span>
                    <span class="text-gray-500 fw-bold fs-7">ITEM PRICE</span>
                </div>
                <!--end::Content-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center me-3">
                        <!--begin::Icon-->
                        <img src="assets/media/stock/ecommerce/14.png" class="me-4 w-50px" alt="" />
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="flex-grow-1">
                            <a href="apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Fjallraven</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Item: #XDG-6437</span>
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Value-->
                    <span class="text-gray-800 fw-bold fs-6">$ 72.00</span>
                    <!--end::Value-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center me-3">
                        <!--begin::Icon-->
                        <img src="assets/media/stock/ecommerce/13.png" class="me-4 w-50px" alt="" />
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="flex-grow-1">
                            <a href="apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Nike AirMax</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Item: #XDG-1836</span>
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Value-->
                    <span class="text-gray-800 fw-bold fs-6">$ 45.00</span>
                    <!--end::Value-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center me-3">
                        <!--begin::Icon-->
                        <img src="assets/media/stock/ecommerce/41.png" class="me-4 w-50px" alt="" />
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="flex-grow-1">
                            <a href="apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Bose QC 35</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Item: #XDG-6254</span>
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Value-->
                    <span class="text-gray-800 fw-bold fs-6">$ 168.00</span>
                    <!--end::Value-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center me-3">
                        <!--begin::Icon-->
                        <img src="assets/media/stock/ecommerce/53.png" class="me-4 w-50px" alt="" />
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="flex-grow-1">
                            <a href="apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Greeny</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Item: #XDG-1746</span>
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Value-->
                    <span class="text-gray-800 fw-bold fs-6">$ 14.50</span>
                    <!--end::Value-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center me-3">
                        <!--begin::Icon-->
                        <img src="assets/media/stock/ecommerce/71.png" class="me-4 w-50px" alt="" />
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="flex-grow-1">
                            <a href="apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Apple Watches</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Item: #XDG-6245</span>
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Value-->
                    <span class="text-gray-800 fw-bold fs-6">$ 362.00</span>
                    <!--end::Value-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center me-3">
                        <!--begin::Icon-->
                        <img src="assets/media/stock/ecommerce/194.png" class="me-4 w-50px" alt="" />
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="flex-grow-1">
                            <a href="apps/ecommerce/sales/details.html" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">Friendly Robot</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Item: #XDG-2347</span>
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Value-->
                    <span class="text-gray-800 fw-bold fs-6">$ 48.00</span>
                    <!--end::Value-->
                </div>
                <!--end::Item-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::List widget 17-->
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="col-xl-8 mb-5 mb-xl-10">
        <!--begin::Table widget 6-->
        <div class="card card-flush h-xl-100">
            <!--begin::Header-->
            <div class="card-header pt-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Leading Agents by Category</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Total 424,567 deliveries</span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar">
                    <a href="apps/ecommerce/catalog/add-product.html" class="btn btn-sm btn-light">Add Product</a>
                </div>
                <!--end::Toolbar-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Nav-->
                <ul class="nav nav-pills nav-pills-custom mb-3">
                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6">
                        <!--begin::Link-->
                        <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2 active" data-bs-toggle="pill" href="#kt_stats_widget_6_tab_1">
                            <!--begin::Icon-->
                            <div class="nav-icon mb-3">
                                <i class="ki-outline ki-truck fs-1"></i>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Title-->
                            <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Van</span>
                            <!--end::Title-->
                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6">
                        <!--begin::Link-->
                        <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2" data-bs-toggle="pill" href="#kt_stats_widget_6_tab_2">
                            <!--begin::Icon-->
                            <div class="nav-icon mb-3">
                                <i class="ki-outline ki-bus fs-1"></i>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Title-->
                            <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Train</span>
                            <!--end::Title-->
                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="nav-item mb-3 me-3 me-lg-6">
                        <!--begin::Link-->
                        <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2" data-bs-toggle="pill" href="#kt_stats_widget_6_tab_3">
                            <!--begin::Icon-->
                            <div class="nav-icon mb-3">
                                <i class="ki-outline ki-logistic fs-1"></i>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Title-->
                            <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Drone</span>
                            <!--end::Title-->
                            <!--begin::Bullet-->
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Nav-->
                <!--begin::Tab Content-->
                <div class="tab-content">
                    <!--begin::Tap pane-->
                    <div class="tab-pane fade active show" id="kt_stats_widget_6_tab_1">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                        <th class="p-0 w-200px w-xxl-450px"></th>
                                        <th class="p-0 min-w-150px"></th>
                                        <th class="p-0 min-w-150px"></th>
                                        <th class="p-0 min-w-190px"></th>
                                        <th class="p-0 w-50px"></th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-1.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Brooklyn Simmons</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">1,240</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$5,400</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-2.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Annette Black</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">6,074</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$174,074</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-12.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Esther Howard</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">357</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$2,737</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-11.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Guy Hawkins</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">2,954</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$59,634</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-13.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Marvin McKinney</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">822</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$19,842</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Tap pane-->
                    <!--begin::Tap pane-->
                    <div class="tab-pane fade" id="kt_stats_widget_6_tab_2">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                        <th class="p-0 w-200px w-xxl-450px"></th>
                                        <th class="p-0 min-w-150px"></th>
                                        <th class="p-0 min-w-150px"></th>
                                        <th class="p-0 min-w-190px"></th>
                                        <th class="p-0 w-50px"></th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-11.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Guy Hawkins</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">2,954</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$59,634</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-13.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Marvin McKinney</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">822</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$19,842</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-1.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Brooklyn Simmons</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">1,240</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$5,400</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-2.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Annette Black</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">6,074</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$174,074</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-12.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Esther Howard</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">357</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$2,737</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Tap pane-->
                    <!--begin::Tap pane-->
                    <div class="tab-pane fade" id="kt_stats_widget_6_tab_3">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                        <th class="p-0 w-200px w-xxl-450px"></th>
                                        <th class="p-0 min-w-150px"></th>
                                        <th class="p-0 min-w-150px"></th>
                                        <th class="p-0 min-w-190px"></th>
                                        <th class="p-0 w-50px"></th>
                                    </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-1.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Brooklyn Simmons</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">1,240</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$5,400</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-11.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Guy Hawkins</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">2,954</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$59,634</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-13.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Marvin McKinney</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">822</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$19,842</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-12.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Esther Howard</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">357</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$2,737</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <img src="assets/media/avatars/300-2.jpg" class="" alt="" />
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">Annette Black</a>
                                                    <span class="text-muted fw-semibold d-block fs-7">Zuid Area</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold d-block mb-1 fs-6">6,074</span>
                                            <span class="fw-semibold text-gray-500 d-block">Deliveries</span>
                                        </td>
                                        <td>
                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$174,074</a>
                                            <span class="text-muted fw-semibold d-block fs-7">Earnings</span>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                                <div class="rating-label checked">
                                                    <i class="ki-solid ki-star fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-semibold d-block fs-7 mt-1">Rating</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Tap pane-->
                </div>
                <!--end::Tab Content-->
            </div>
            <!--end: Card Body-->
        </div>
        <!--end::Table widget 6-->
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->