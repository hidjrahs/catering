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
		<div class="fw-semibold order-1">Delivered Theme By KeenThemes</div>
		<!--end::Copyright-->
	</div>
	<!--end::Footer container-->
</div>