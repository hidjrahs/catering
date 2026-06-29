<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
	<!--begin::Logo-->
	<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
		<!--begin::Logo image-->
		<a href="../../demo1/dist/index.html">
			<img alt="Logo" src="{{ asset('logo_min.png')}}" class="h-50px app-sidebar-logo-default theme-light-show" />
			<img alt="Logo" src="{{ asset('logo_dark.jpeg')}}" class="h-50px app-sidebar-logo-default theme-dark-show" />
			<img alt="Logo" src="{{ asset('logo_min.png')}}" class="h-25px app-sidebar-logo-minimize" />
		</a>
		<!--end::Logo image-->
		<!--begin::Sidebar toggle-->
		<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
			<span class="svg-icon svg-icon-2 rotate-180">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
					<path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Sidebar toggle-->
	</div>
	<!--end::Logo-->
	<!--begin::sidebar menu-->
	<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
		<!--begin::Menu wrapper-->
		<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
			<!--begin::Menu-->
<div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
 				@if(in_array('menu-sidebar',array_keys($config)))
                    @if($config['menu-sidebar']&&is_array($config['menu-sidebar']))
                        @foreach($config['menu-sidebar'] as $itemsMenu)
                            @if($itemsMenu['type']=="label")
                                <!--begin:Menu item-->
                                <div class="menu-item pt-5">
                                    <!--begin:Menu content-->
                                    <div class="menu-content">
                                        <span class="menu-heading fw-bold text-uppercase fs-7">{{$itemsMenu['name']}}</span>
                                    </div>
                                    <!--end:Menu content-->
                                </div>
                                <!--end:Menu item-->
                            @endif
                            @if($itemsMenu['type']=="menu")
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
@if($itemsMenu['url']=='run.queue.import')
                                        <a class="menu-link {{$itemsMenu['class']}}" href="javascript:;" data-run-queue id="kt_run_queue_sidebar">
                                            {!!$itemsMenu['icon']!!}
                                            <span class="menu-title">{{$itemsMenu['name']}}</span>
                                        </a>
                                        @else
                                        <a class="menu-link {{$itemsMenu['class']}}" href="{{$itemsMenu['url']}}">
                                            {!!$itemsMenu['icon']!!}
                                            <span class="menu-title">{{$itemsMenu['name']}}</span>
                                        </a>
                                    @endif
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                            @endif
                            @if($itemsMenu['type']=="submenu")
                                @php
                                    $urlSub=Collect($itemsMenu['data_sub'])->pluck('url')->toArray();
                                    $current=url()->current();
                                    if($current==route('menus_catering.import')){
                                        $current=route('menus_catering');
                                    }
                                @endphp
                                <!--begin:Menu item-->
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{in_array($current,$urlSub)?'show':''}}">
                                    <!--begin:Menu link-->
                                    <span class="menu-link">
                                        <span class="menu-icon">
                                            {!!$itemsMenu['icon']!!}
                                        </span>
                                        <span class="menu-title">{{$itemsMenu['name']}}</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <!--end:Menu link-->
                                    <!--begin:Menu sub-->
                                    <div class="menu-sub menu-sub-accordion">
                                        @foreach($itemsMenu['data_sub'] as $itemsMenuSub)
                                            <!--begin:Menu item-->
                                            <div class="menu-item">
                                                <!--begin:Menu link-->
@if($itemsMenuSub['url']=='run.queue.import')
                                                <a class="menu-link {{$itemsMenuSub['class']}}" href="javascript:;" data-run-queue>
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    {!!$itemsMenuSub['icon']!!}
                                                    <span class="menu-title">{{$itemsMenuSub['name']}}</span>
                                                </a>
@else
                                                <a class="menu-link {{$itemsMenuSub['class']}}" href="{{$itemsMenuSub['url']}}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{$itemsMenuSub['name']}}</span>
                                                </a>
@endif
                                                <!--end:Menu link-->
                                            </div>
                                            <!--end:Menu item-->
                                        @endforeach
                                    </div>
                                    <!--end:Menu sub-->
                                </div>
                                <!--end:Menu item-->
                            @endif

                        @endforeach
                    
                    @endif
                @endif
			</div>
			<!--end::Menu-->
		</div>
		<!--end::Menu wrapper-->
	</div>
	<!--end::sidebar menu-->
</div>