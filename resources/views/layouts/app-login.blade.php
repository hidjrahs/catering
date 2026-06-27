<!DOCTYPE html>
<html lang="en">
	<head>
        <base href="{{ config('app.url') }}" />
        <title>{{ config('app.name', 'Laravel') }} {{ $config['title'] ? '[' . $config['title'] . ']' : '' }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta charset="utf-8" />
		<meta name="description" content="{{ config('app.name', 'Laravel') }} {{ $config['title'] ? '[' . $config['title'] . ']' : '' }}" />
		<meta name="keywords" content="{{ config('app.name', 'Laravel') }} , {{ $config['title'] ? '[' . $config['title'] . ']' : '' }}" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="id_ID" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="{{ config('app.name', 'Laravel') }} {{ $config['title'] ? '[' . $config['title'] . ']' : '' }}" />
		<meta property="og:url" content="{{ config('app.url') }}" />
		<meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />
		<link rel="canonical" href="{{ config('app.url') }}" />
		<link rel="shortcut icon" href="{{ asset('favicon.ico')}}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> -->
		<style id="crossOriginImport">
            @import url("https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700");
        </style>
        <!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		@yield('css')
		<style>
			#showHide{
                cursor: pointer;
            }
			.pass-type{
				position: absolute;
				right: 0px;
				top: 0px;
				width: 40px;
				text-align: center;
				height: 100%;
				font-size: 24px;
			}
			.icon-pass{
				font-size: 16px !important;
			}
			.row-relative{
				position: relative;
			}
			.logo-auth-wrapper{
				position: absolute;
				top: 15px;
				left: 15px;
			}
			.logo-auth-wrapper>img{
				border-radius: 20px;
  				border: 3px solid #c60101;
			}
			.position-relative{
				position: relative;
			}
			.logo-left{
				background: #ff6572;
				border-radius: 10px;
				border: 4px solid #c80201;
			}
			.logo-left>img{
				border: 4px solid #c80201;
  				border-radius: 50px;
			}
		</style>
        <!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>
            var defaultThemeMode = "light"; 
            var themeMode; 
            if ( document.documentElement ) { 
                if ( document.documentElement.hasAttribute("data-theme-mode")) { 
                    themeMode = document.documentElement.getAttribute("data-theme-mode"); 
                } else { 
                    if ( localStorage.getItem("data-theme") !== null ) { 
                        themeMode = localStorage.getItem("data-theme"); 
                    } else { themeMode = defaultThemeMode; } 
                } 
                if (themeMode === "system") { 
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; 
                } 
                document.documentElement.setAttribute("data-theme", themeMode); 
            }
        </script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Logo-->
				<a href="{{route('login')}}" class="d-block d-lg-none mx-auto py-20">
					<img alt="Logo" src="{{ asset('metronic/assets/media/logos/default.svg')}}" class="theme-light-show h-25px" />
					<img alt="Logo" src="{{ asset('metronic/assets/media/logos/default-dark.svg')}}" class="theme-dark-show h-25px" />
				</a>
				<!--end::Logo-->
				<!--begin::Aside-->
				<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
					<!--begin::Wrapper-->
					<div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
						<!--begin::Body-->
						<div class="py-20">
							@yield('content')
						</div>
						<!--end::Body-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Aside-->
				<!--begin::Body-->
				<div class="position-relative d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-start bgi-no-repeat" style="background-image: url({{ asset('metronic/assets/media/auth/bg11_catering.png')}})">
					{{--<div class="logo-auth-wrapper">
						<img alt="Logo-auth" src="{{ asset('logo_min.png')}}" class="h-75px" />
					</div>--}}
				</div>
				<!--begin::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "{{asset('metronic/assets/')}}";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset('metronic/assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset('metronic/assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset('plugins') }}/jquery.form.min.js"></script>
		<!--end::Custom Javascript-->
		@yield('script')
		<script>
			let table;
			$(function() {
				'use strict';
				let defaultError = "Proses tidak berhasil.";
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$(document).on('click', '#showHide', function() {
					let e=$(this),
						target=e.parent().find("[name='password']");
					if(target.attr('type')=='password'){
						e.html('<i class="bi bi-eye-slash text-primary icon-pass">');
						target.attr('type','text');
					}else{
						e.html('</i><i class="bi bi-eye text-primary icon-pass"></i>');
						target.attr('type','password');
					}
				});
			});
		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>