<div id="kt_app_footer" class="app-footer">
	<!--begin::Footer container-->
	<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
		<!--begin::Copyright-->
		<div class="text-dark order-2 order-md-1">
			@php 
				$time=date_default_timezone_get();
				$year=date("Y");
				$now=date(" H:i d-m-Y");
			@endphp
			<span class="text-muted fw-semibold me-1">{{$year}}&copy;</span>
			<a href="{{url('/')}}" target="_blank" class="text-gray-800 text-hover-primary">{{ config('app.name', 'Laravel') }}</a>
			| 
			Time Zone: {{$time}} | {{$now}}
		</div>
		<!--begin::Links-->
		<div class="d-flex align-items-center gap-4 order-1 order-md-2 mb-2 mb-md-0">
			<a href="{{ route('panduan.index') }}"
			   class="d-flex align-items-center gap-1 text-gray-600 text-hover-primary fw-semibold"
			   style="font-size: 0.82rem; text-decoration: none; transition: color .15s;">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-1">
					<path opacity="0.3" d="M20 3H4C2.9 3 2 3.9 2 5V16C2 17.1 2.9 18 4 18H7L10 21L13 18H20C21.1 18 22 17.1 22 16V5C22 3.9 21.1 3 20 3Z" fill="currentColor"/>
					<path d="M11 11H13V13H11V11ZM11 7H13V9.5H11V7Z" fill="currentColor"/>
				</svg>
				Panduan Pengguna
			</a>
		</div>
		<!--end::Links-->
		<div class="fw-semibold order-3">Delivered Theme By KeenThemes</div>
		<!--end::Copyright-->
	</div>
	<!--end::Footer container-->
</div>