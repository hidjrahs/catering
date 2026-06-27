"use strict";
const KTSigninGeneral = (function () {
    let t, e, i, n;
    return {
        init: function () {
            (n = "Proses Sign In tidak berhasil"),
                (t = document.querySelector("#kt_sign_in_form")),
                (e = document.querySelector("#kt_sign_in_submit")),
                (i = FormValidation.formValidation(t, {
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: "Email/Username harus di isi.",
                                },
                            },
                        },
                        password: {
                            validators: {
                                notEmpty: { message: "Password harus di isi." },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })),
                e.addEventListener("click", function (o) {
                    let a;
                    o.preventDefault(),
                        i.validate().then(function (i) {
                            "Valid" == i &&
                                (e.setAttribute("data-kt-indicator", "on"),
                                (e.disabled = !0),
                                setTimeout(function () {
                                    function getCookie(name) {
                                        let value = "; " + document.cookie;
                                        let parts = value.split("; " + name + "=");
                                        if (parts.length === 2) return parts.pop().split(";").shift();
                                    };
                                    
                                        // beforeSubmit: function(arr, $form, options) {
                                        //     let done = this.async = true;
                                        //     $.get('/refresh-csrf').done(function(data) {
                                        //         $('input[name="_token"]').val(data.token);
                                        //         arr.push({name: '_token', value: data.token});
                                        //         done()
                                        //     });
                                        //     return false;
                                        // },
                                    $("#kt_sign_in_form").ajaxSubmit({
                                        type: "post",
                                        url: t.getAttribute("action"),
                                        dataType: "json",
                                        success: function (t) {
                                            e.removeAttribute(
                                                "data-kt-indicator"
                                            ),
                                                (e.disabled = !1),
                                                (location.href = t.direct
                                                    ? t.direct
                                                    : location.href);
                                        },
                                        error: function (t, i) {
                                            (a = t.responseJSON
                                                ? t.responseJSON
                                                : n),
                                                Swal.fire({
                                                    icon: "error",
                                                    text: a.message
                                                        ? a.message
                                                        : n,
                                                }),
                                                e.removeAttribute(
                                                    "data-kt-indicator"
                                                ),
                                                (e.disabled = !1);
                                        },
                                    });
                                }, 2e3));
                        });
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTSigninGeneral.init();
});
