<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
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
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="{{ asset('metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		@yield('css')
		<style>
			.dataTables_filter,
            .dataTables_length {
                display: none;
            }
			.not-allowed{
                cursor: not-allowed;
            }
            .skeleton,
            .hidden{
                display: none;
            }
            .skeleton.load {
                display: block;
                /* animation: skeleton-loading 1s linear infinite alternate; */
            }
			.app-sidebar-logo,
			.app-sidebar-menu{
				border-right: 1px solid #e6e8f0;
			}
		</style>
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" 
		data-kt-app-layout="light-sidebar" 
		data-kt-app-sidebar-enabled="true" 
		data-kt-app-sidebar-fixed="true" 
		data-kt-app-sidebar-hoverable="true" 
		data-kt-app-sidebar-push-header="true" 
		data-kt-app-sidebar-push-toolbar="true" 
		data-kt-app-sidebar-push-footer="true" 
		data-kt-app-toolbar-enabled="true" 
		@if(in_array(url()->current(),[route('customer_service'),route('customer_service.order')]))
		data-kt-app-sidebar-minimize="on"
		@endif
		class="app-default">
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
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				@include('layouts.header')
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					@include('layouts.sidebar')
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							{{--@ include('layouts.toolbar')--}}
							<!--end::Toolbar-->
							<!--begin::Content-->
							@yield('content')
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						@include('layouts.footer')
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
		
		<!--begin::Javascript-->
		<script>var hostUrl = "{{asset('metronic/assets/')}}";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset('metronic/assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset('metronic/assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="{{ asset('metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
		<script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
		<script src="{{ asset('plugins') }}/jquery.form.min.js"></script>
		<!--end::Vendors Javascript-->
		<script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {token}',
                }
            });
            async function asyncData(uri, value,method="get") {
                let getData,listdata;
                try {
					listdata = {
						'_token': "{{ csrf_token() }}"
					};
					if (value) {
						listdata['data'] = value; 
					}
                    getData = await $.ajax({
                        type: method,
                        url: uri,
                        data: listdata,
                        dataType: "json",
                    });
                    return getData;
                } catch (error) {
                    return error;
                }
            };

            async function asyncPost(uri, value) {
                let getData,listdata;
                try {
					listdata={};
					if(value) {
						listdata=[];
						listdata.push({"_token": "{{ csrf_token() }}"})
						listdata.push(value)
					}
                    getData = await $.ajax({
                        type: "post",
                        url: uri,
                        data: listdata,
                        dataType: "json",
                    });
                    return getData;
                } catch (error) {
                    return error;
                }
            };
        </script>
		<!--end::Javascript-->
		@yield('script')
	</body>
	<!--end::Body-->
</html>