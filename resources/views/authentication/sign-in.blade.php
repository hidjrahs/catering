@extends('layouts.app-login')
@section('content')
<!--begin::Form-->
	<form class="form w-100" method="POST" novalidate="novalidate" id="kt_sign_in_form" action="{{$config['loginApi']}}">
		@csrf
        @method($data['method'])
		<!--begin::Body-->
		<div class="card-body">
			<!--begin::Heading-->
			<div class="text-start mb-10">
				<div class="logo-left text-center p-2 mb-6">
					<img alt="Logo-auth" src="{{ asset('logo_min.png')}}" class="h-125px" />
				</div>
				<!--begin::Title-->
				<h1 class="text-dark mb-3 fs-3x">Sign In</h1>
				<!--end::Title-->
				<!--begin::Text-->
				<div class="text-gray-400 fw-semibold fs-6">Use your credentials to access {{ config('app.name', 'Laravel') }}</div>
				<!--end::Link-->
			</div>
			<!--begin::Heading-->
			<!--begin::Input group=-->
			<div class="fv-row mb-8">
				<!--begin::Email-->
				<input type="text" placeholder="Email/Username" name="email" autocomplete="off" class="form-control form-control-solid" />
				<!--end::Email-->
			</div>
			<!--end::Input group=-->
			<div class="fv-row mb-7 row-relative">
				<!--begin::Password-->
				<input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control form-control-solid" />
				<!--end::Password-->
				<span class="pass-type" id="showHide">
					<i class="bi bi-eye text-primary icon-pass"></i>
				</span>
			</div>
			<!--end::Input group=-->
			<!--begin::Actions-->
			<div class="d-grid">
				<!--begin::Submit-->
				<button id="kt_sign_in_submit" class="btn btn-primary" type="submit">
					<!--begin::Indicator label-->
					<span class="indicator-label">Sign In</span>
					<!--end::Indicator label-->
					<!--begin::Indicator progress-->
					<span class="indicator-progress">
						<span>Please wait...</span>
						<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
					</span>
					<!--end::Indicator progress-->
				</button>
				<!--end::Submit-->
			</div>
			<!--end::Actions-->
		</div>
		<!--begin::Body-->
	</form>
	<!--end::Form-->
@endsection
@section('script')
<script src="{{ asset('js') }}/authentication.min.js"></script>
@endsection