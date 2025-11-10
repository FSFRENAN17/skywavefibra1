//URL Config
let url = document.getElementById("url-global").getAttribute("data-url")

//Alert Flash
let alertFlash = document.getElementById("alert-flash").innerHTML
if (alertFlash) {
    let alert = JSON.parse(alertFlash)
    alertRender(alert.type, alert.class, alert.text, alert.title);
}

//Alert Render
function alertRender(type, style, text, title) {

    let textEncoded = (function () {
        let t = document.createElement('textarea');
        t.innerHTML = text;
        return t.value;
    })();

    function icon(style) {
        switch (style) {
            case "danger":
                return "shield-cross"
            case "info":
                return "information-4"
            case "warning":
                return "information"
            case "primary":
                return "shield-tick"
            default:
                return "information"
        }
    }

    if (type === "toast") {
        let id = new Date().getTime();
        let alert = `
                <div id="alertToast${id}" class="bs-toast toast animate__animated animate__tada hide animate__tada" data-bs-delay="15000" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 0.475rem;">
                    <div class="toast-header bg-${style}" style="border-radius: 0.475rem 0.475rem 0 0;">
                        <img src="${url}/shared/assets/images/favicon/favicon.ico" class="rounded me-2" height="16">
                        <strong class="me-auto fs-6">${(title ?? "Alerta")}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body fs-6">
                        ${textEncoded}
                    </div>
                </div>
            `;

        $("#alert-container-toast").append(alert);

        new bootstrap.Toast(
            document.getElementById("alertToast" + id)
        ).show();
        return;
    }

    let alert = `
            <div class="notice d-flex bg-light-${style} rounded border-${style} border border-dashed mb-9 p-6">
                <!--begin::Icon-->
                <i class="ki-duotone ki-${icon(style)} fs-2tx text-${style} me-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <!--end::Icon-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1">
                    <!--begin::Content-->
                    <div class="fw-semibold">
                        <div class="fs-6 text-gray-700 fs-1">${textEncoded}</div>
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
        `;

    $("#alert-container-fixed").html(alert);
    // new bootstrap.Toast(
    //     document.getElementById("alertFixed")
    // ).show();

}

//AJAX data-post
$("[data-post]").on("click", function (e) {
    e.preventDefault()
    if ($(this).hasClass("ajax-off")) {
        return
    }

    let url = $(this).data("post")
    let data = new FormData();
    $.each($(this).data(), function (key, value) {
        data.append(key, value);
    });

    if (data.confirm) {
        swal({
            title: "Atenção!",
            text: data.confirm,
            icon: "warning",
            buttons: ["Cancelar", "Sim"],
            dangerMode: true,
        }).then((confirm) => {
            if (confirm) {
                ajaxPost(data, url)
            }
        });
    } else {
        ajaxPost(data, url)
    }
})

// AJAX Form (exclude forms with class .ajax-off)
$("form:not(.ajax-off)").on("submit", function (e) {
    e.preventDefault()
    // let data = $(this).serialize();
    let data = new FormData(this);
    let url = $(this).attr("action");
    ajaxPost(data, url)
});

//Ajax Post
function ajaxPost(data, url) {

    // let loader = $(".loader");

    $.ajax({
        url: url,
        data: data,
        type: "POST",
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            // loader.fadeIn();
            KTApp.showPageLoading();
        },
        success: function (response) {

            //redirect
            if (response.redirect) {
                window.location.href = response.redirect;
                return;
            }

            //reload
            if (response.reload) {
                window.location.reload();
                return;
            }

            //message
            if (response.message) {
                (response.message.type === "toast" ?
                    alertRender("toast", response.message.class, response.message.text, response.message.title) :
                    alertRender("fixed", response.message.class, response.message.text)
                )
            }

            KTApp.hidePageLoading();
        },
        complete: function () {
            KTApp.hidePageLoading();
        },
        error: function (res) {
            let text = "Desculpe, não foi possível processar a requisição. Favor tente novamente!";
            let title = "Oops, nós estamos com problemas!"

            alertRender("toast", "danger", text, title)

            KTApp.hidePageLoading();
        },
    });
}

//Slug Creator
function str_slug(str) {
    str = str.trim();
    str = str.toLowerCase();

    const from = "áàãâäéèêëíìîïóòõôöúùûüçñ";
    const to = "aaaaaeeeeiiiiooooouuuucn";

    for (let i = 0; i < from.length; i++) {
        str = str.replace(new RegExp(from[i], 'g'), to[i]);
    }

    str = str.replace(/[^a-z0-9\s-]/g, '');
    str = str.replace(/[\s-]+/g, '-');
    str = str.replace(/^-+|-+$/g, '');

    return str;
}