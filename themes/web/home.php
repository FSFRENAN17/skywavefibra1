<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?= url(CONF_THEME) ?>/" />

    <?= $head ?>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" type="image/png" href="../../assets/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="../../assets/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/images/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="../../assets/images/favicon/site.webmanifest" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-body position-relative app-blank">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root" id="kt_app_root">

        <div class="mb-0" id="home">
            <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg" 
                style="background-image: url(assets/media/svg/illustrations/landing.svg)">
                
                <div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <div class="container">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center flex-equal">
                                <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                                    <i class="ki-outline ki-abstract-14 fs-2hx"></i>
                                </button>
                                <a href="<?= url() ?>">
                                    <img alt="Logo" src="../../assets/images/front/header.png" class="logo-default h-60px h-lg-60px rounded" />
                                    <img alt="Logo" src="../../assets/images/front/headerdark.png" class="logo-sticky h-60px h-lg-60px rounded" />
                                </a>
                            </div>
                            <div class="d-lg-block" id="kt_header_nav_wrapper">
                                <div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">
                                    <div class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold" id="kt_landing_menu">

                                        <div class="menu-item">
                                            <a class="menu-link nav-link active py-3 px-4 px-xxl-6" href="<?= url() ?>" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Início</a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6" href="#how-it-works" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Como Funciona</a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6" href="#pricing" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Planos</a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6" href="<?= url("status") ?>" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Status do Serviço</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-equal text-end ms-1">
                                <a href="<?= url("entrar") ?>" class="btn btn-success">Área do Cliente</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-center w-100 min-h-200px px-9">
                    <div class="text-center mb-5 mb-lg-10 py-10 py-lg-10">
                        <h1 class="text-white lh-base fw-bold fs-2x fs-lg-3x mb-5">
                            <?= $title ?? "Status do Serviço" ?>
                        </h1>
                        <p class="text-white opacity-75 fs-5">
                            <?= $subtitle ?? "Acompanhe em tempo real a disponibilidade dos nossos serviços." ?>
                        </p>
                    </div>
                </div>
                
            </div>
            <div class="landing-curve landing-dark-color mb-10 mb-lg-20">
                <svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
                </svg>
            </div>
            </div>
        <div class="mb-n10 mb-lg-n20 z-index-2">
            <div class="container">
                
                <div class="card shadow-sm mb-10">
                    <div class="card-header">
                        <h3 class="card-title">Monitoramento de Serviços</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-5 g-xl-8">
                            <div class="col-xl-4">
                                <div class="card bg-success hoverable">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <i class="ki-outline ki-wifi fs-2hx text-white"></i>
                                        <span class="fs-4 text-white fw-bold mt-2">Rede Fibra Óptica</span>
                                        <span class="fs-6 text-white opacity-75">100% Operacional</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card bg-warning hoverable">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <i class="ki-outline ki-chart-line-down fs-2hx text-gray-900"></i>
                                        <span class="fs-4 text-gray-900 fw-bold mt-2">Sistema de Boletos</span>
                                        <span class="fs-6 text-gray-900 opacity-75">Pode ter instabilidade</span>
                                    </div>
                                </div>
                            </div>
                             <div class="col-xl-4">
                                <div class="card bg-primary hoverable">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <i class="ki-outline ki-call fs-2hx text-white"></i>
                                        <span class="fs-4 text-white fw-bold mt-2">Central de Atendimento</span>
                                        <span class="fs-6 text-white opacity-75">Operacional</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-10">
                            <p class="text-muted">Última atualização: **(Aqui você insere a hora do PHP)**</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        <div class="mt-sm-n20">
            </div>

        <div class="mt-20 mb-n20 position-relative z-index-2" id="clientsgti">
            </div>

        <div class="mb-0">
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z" fill="currentColor"></path>
                </svg>
            </div>
            <div class="landing-dark-bg pt-20">
                <div class="container">
                    <div class="row py-10 py-lg-20">
                        <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
                            <div class="rounded landing-dark-border p-9 mb-10">
                                <h2 class="text-white" id="contactgti">Precisa de Suporte Técnico?</h2>
                                <span class="fw-normal fs-4 text-gray-700">
                                    Fale com nossa equipe pelo e-mail:
                                    <a href="mailto:suporte@skywavefibra.com.br" class="text-white opacity-50 text-hover-primary">suporte@skywavefibra.com.br</a>
                                </span>
                            </div>
                            <div class="rounded landing-dark-border p-9">
                                <h2 class="text-white">Atendimento Comercial</h2>
                                <span class="fw-normal fs-4 text-gray-700">
                                    Quer contratar um plano? Entre em contato:
                                    <a href="mailto:contato@skywavefibra.com.br" class="text-white opacity-50 text-hover-primary">contato@skywavefibra.com.br</a>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 ps-lg-16">
                            <div class="d-flex justify-content-center">
                                <div class="d-flex fw-semibold flex-column me-20">
                                    <h4 class="fw-bold text-gray-500 mb-6">Links Úteis</h4>
                                    <a href="#planos" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Nossos Planos</a>
                                    <a href="#clients" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Depoimentos</a>
                                    <a href="#contato" class="text-white opacity-50 text-hover-primary fs-5">Área do Cliente</a>
                                </div>
                                <div class="d-flex fw-semibold flex-column ms-lg-20">
                                    <h4 class="fw-bold text-gray-500 mb-6">Siga a Sky Wave</h4>
                                    <a href="#" class="mb-6"><img src="assets/media/svg/brand-logos/facebook-4.svg" class="h-20px me-2" alt="Facebook" /><span class="text-white opacity-50 text-hover-primary fs-5">Facebook</span></a>
                                    <a href="#" class="mb-6"><img src="assets/media/svg/brand-logos/instagram-2-1.svg" class="h-20px me-2" alt="Instagram" /><span class="text-white opacity-50 text-hover-primary fs-5">Instagram</span></a>
                                    <a href="#" class="mb-6"><img src="assets/media/svg/brand-logos/twitter.svg" class="h-20px me-2" alt="Twitter" /><span class="text-white opacity-50 text-hover-primary fs-5">Twitter</span></a>
                                    <a href="#" class="mb-6"><img src="../../assets/images/front/whatsapp.svg" class="h-20px me-2" alt="WhatsApp" /><span class="text-white opacity-50 text-hover-primary fs-5">WhatsApp</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="landing-dark-separator"></div>
                <div class="container">
                    <div class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
                        <div class="d-flex align-items-center order-2 order-md-1">
                            <a href="index.html"><img alt="Sky Wave Fibra" src="../../assets/images/front/header.png" class="h-60px h-md-60px rounded" /></a>
                            <span class="mx-5 fs-6 fw-semibold text-gray-600 pt-1"> &copy; 2025 Sky Wave Fibra - CNPJ 00.000.000/0001-00</span>
                        </div>
                        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
                            <li class="menu-item"><a href="#planos" class="menu-link px-2">Planos</a></li>
                            <li class="menu-item"><a href="#contato" class="menu-link px-2">Contato</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true"><i class="ki-outline ki-arrow-up"></i></div>
    </div>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true"><i class="ki-outline ki-arrow-up"></i></div>
    <script>var hostUrl = "assets/";</script>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>
    <script src="assets/plugins/custom/typedjs/typedjs.bundle.js"></script>
    <script src="assets/js/custom/landing.js"></script>
    <script src="assets/js/custom/pages/pricing/general.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Script de scroll suave (Copiado de home.php)
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute("href").substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80, 
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
