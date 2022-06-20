<!doctype html>
<html lang="fr">
    <head>
        <title>{{config('app.name')}}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        <script src="{{asset('plugins/jQuery/query.min.js')}}"></script>
        <script src="{{asset('plugins/angular/angular.js')}}"></script>
        <script src="{{asset('assets/js/pages/features/miscellaneous/sweetalert2.js')}}"></script>        
        <script src="{{asset('assets/js/pages/features/forms/widgets/select2.js')}}"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

        <!-- Styles-->
        <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />

        <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
    </head>
    <body id="app" ng-app="app" class="quick-panel-right demo-panel-right offcanvas-right header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed aside-minimize-hoverable page-loading">
        <script type="text/javascript">
            var saoukiApp = angular.module('app', []);
            var basePath = "{{url('/')}}";
        </script>
        @php
            $home = '';
            if(Auth::user()->role == "Administrateur") {
                $home = 'home-admin';
            }
            if(Auth::user()->role == "Superviseur") {
                $home = 'home-superviseur';
            } 
            if(Auth::user()->role == "Comptable") {
                $home = 'home-comptable';
            } 
            if(Auth::user()->role == "Gerant") {
                $home = 'home-gerant';
            }
            if(Auth::user()->role == "Agent") {
                $home = 'home-agent';
            } 
        @endphp
        <!--begin::Header Mobile-->
		<div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
            <a href="{{route($home)}}" style="color:#ffffff;" class="font-size-h2">
				SAOUKI TRANSFERT
			</a>
            <div class="d-flex align-items-center">
                <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
					<span></span>
				</button>
				<button class="btn btn-hover-text-primary p-0 ml-3" id="kt_header_mobile_topbar_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>
            </div>
        </div>
        <!--end::Header Mobile-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">
                <!--begin::Aside-->
                <div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
                    <div class="brand flex-column-auto" id="kt_brand"> 
                        <a href="{{route($home)}}" style="color:#ffffff;" class="font-size-h2" class="brand-logo">
							S TRANSF.
						</a>
                        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
							<span class="svg-icon svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Text/Toggle-Right.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path fill-rule="evenodd" clip-rule="evenodd" d="M22 11.5C22 12.3284 21.3284 13 20.5 13H3.5C2.6716 13 2 12.3284 2 11.5C2 10.6716 2.6716 10 3.5 10H20.5C21.3284 10 22 10.6716 22 11.5Z" fill="black" />
										<path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M14.5 20C15.3284 20 16 19.3284 16 18.5C16 17.6716 15.3284 17 14.5 17H3.5C2.6716 17 2 17.6716 2 18.5C2 19.3284 2.6716 20 3.5 20H14.5ZM8.5 6C9.3284 6 10 5.32843 10 4.5C10 3.67157 9.3284 3 8.5 3H3.5C2.6716 3 2 3.67157 2 4.5C2 5.32843 2.6716 6 3.5 6H8.5Z" fill="black" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</button>
                    </div> 
                    <!--begin::Aside Menu-->
                    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
                        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
                            <ul class="menu-nav">
                                <li class="menu-item {{Route::currentRouteName() === $home ? 'menu-item-active' : ''}}" aria-haspopup="true">
									<a href="{{route($home)}}" class="menu-link">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
													<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-text">Tableau de bord</span>
									</a>
								</li>
                                <li class="h-20px"></li>
                                @if(Auth::user()->role == 'Agent')
                                @include('layouts.partials.menus.agent.index')
                                @include('layouts.partials.menus.agent.operation')
                                @endif
                                @if(Auth::user()->role == 'Superviseur')
                                    @include('layouts.partials.menus.superviseur.index')
                                    @include('layouts.partials.menus.superviseur.operation')
                                @endif
                                @if(Auth::user()->role == 'Comptable')
                                    @include('layouts.partials.menus.comptable.index')
                                    @include('layouts.partials.menus.comptable.operation')
                                @endif
                                @if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Gerant')
                                    @include('layouts.partials.menus.admin.parametre')
                                    @include('layouts.partials.menus.admin.operation')
                                    <li class="menu-item {{Route::currentRouteName() === 'auth.users.index' || Route::currentRouteName() === 'auth.user.profil' ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                        <a href="{{ route('auth.users.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                                        <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                        <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                            <span class="menu-text">Utilisateurs</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="menu-item" aria-haspopup="true">
									<a href="{{ route('logout') }}" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M7.62302337,5.30262097 C8.08508802,5.000107 8.70490146,5.12944838 9.00741543,5.59151303 C9.3099294,6.05357769 9.18058801,6.67339112 8.71852336,6.97590509 C7.03468892,8.07831239 6,9.95030239 6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,9.99549229 17.0108275,8.15969002 15.3875704,7.04698597 C14.9320347,6.73472706 14.8158858,6.11230651 15.1281448,5.65677076 C15.4404037,5.20123501 16.0628242,5.08508618 16.51836,5.39734508 C18.6800181,6.87911023 20,9.32886071 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 C4,9.26852332 5.38056879,6.77075716 7.62302337,5.30262097 Z" fill="#000000" fill-rule="nonzero"/>
                                                    <rect fill="#000000" opacity="0.3" x="11" y="3" width="2" height="10" rx="1"/>
                                                </g>
											</svg>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-text">Se D&eacute;connecter</span>
									</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
								</li>
                            </ul>
                        </div>
                    </div>
                </div> 
                <!--begin::Wrapper-->
                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                    <!--begin::Header-->
					<div id="kt_header" class="header header-fixed">
                        <!--begin::Container-->
                        <div class="container-fluid d-flex align-items-stretch justify-content-between">
                            <!--begin::Header Menu Wrapper-->
							<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                                <!--begin::Header Menu-->
								<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                                    <ul class="menu-nav">

                                    </ul>
                                </div>
                            </div>
                            <!--end::Header Menu Wrapper-->
                            <!--begin::Topbar-->
                            <div class="topbar">
                                <!--begin::User-->
								<div class="topbar-item ml-4">
                                    <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Bienvenue,</span>
                                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">
                                            &nbsp;{{Auth::user()->name}}
                                        </span>
                                        <span class="symbol symbol-35 symbol-light-success">
                                            <span class="symbol-label font-size-h5 font-weight-bold">{{Str::substr((Auth::user()->name),0,1)}}</span>
                                        </span>
                                    </div>
								</div>
								<!--end::User-->
                            </div>
                        </div>
                    </div>  
                    <!--end::Header-->  
                    <!--begin::Content-->
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        <!--begin::Subheader-->
                        <div class="subheader py-6 py-lg-8 subheader-transparent" id="kt_subheader">
                            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                                <!--begin::Info-->
								<div class="d-flex align-items-center flex-wrap mr-1">
									<!--begin::Page Heading-->
									<div class="d-flex align-items-baseline flex-wrap mr-5">
										<!--begin::Page Title-->
										<h5 class="text-dark font-weight-bold my-1 mr-5">
                                            {{$menuPrincipal}}
                                        </h5>
										<!--end::Page Title-->
                                        <!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
											<li class="breadcrumb-item text-muted">
												<a class="text-muted">
                                                    {{$titleControlleur}}
                                                </a>
											</li>
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page Heading-->
								</div>
								<!--end::Info-->
                                <!--begin::Toolbar-->
                                @if($btnModalAjout == "TRUE")
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Nouvel ajout" data-placement="top">
                                            <a id="btnModalAjout" class="btn btn-fixed-height btn-bg-primary btn-text-white btn-hover-text-white btn-icon-white font-weight-bolder font-size-sm px-5 my-1 mr-3">
                                                <span class="svg-icon svg-icon-md">
                                                    <i class="flaticon-add-circular-button"></i>
                                                    Ajout
                                            </span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                <!--end::Toolbar-->
                            </div>
                        </div>
                        <!--end::Subheader-->
                        <!--begin::Entry-->
                        <div class="d-flex flex-column-fluid">
                            <!--begin::Container-->
                            <div class="container">
                                @yield('content')
                            </div>
                            <!--end::Container-->
                        </div>
                    </div>
                    <!--begin::Footer-->
					<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
                        <!--begin::Container-->
						<div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
								<span class="text-muted font-weight-bold mr-2">{{date('Y')}}©</span>
								<a href="#" class="text-dark-75 text-hover-primary">{{config('app.name')}}</a>
							</div>
							<!--end::Copyright-->
						</div>
						<!--end::Container-->
                    </div>
                    <!--end::Footer-->
                </div>
            </div>    
        </div>
        <!--end::Main-->
        <!-- begin::User Panel-->
		<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
			<!--begin::Header-->
			<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
				<h3 class="font-weight-bold m-0">Mon profil</h3>
			</div>
			<!--end::Header-->
			<!--begin::Content-->
			<div class="offcanvas-content pr-5 mr-n5">
				<!--begin::Header-->
				<div class="d-flex align-items-center mt-5">
					<div class="symbol symbol-100 mr-5">
						<div class="symbol-label" style="background-image:url('{{url("images/user.jpg")}}')"></div>
					</div>
					<div class="d-flex flex-column">
						<a class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
                            {{Auth::user()->name}}
                        </a>
						<div class="text-muted mt-1">
                            {{Auth::user()->role}}
                        </div>
						<div class="navi mt-1">
							<a class="navi-item">
								<span class="navi-link p-0 pb-2">
									<span class="navi-text text-muted text-hover-primary">
                                        {{Auth::user()->email}}
                                    </span>
								</span>
							</a>
						</div>
                        @if(Auth::user()->role == 'Superviseur' or Auth::user()->role == 'Comptable' or Auth::user()->role == 'Agent')
                            <div class="text-muted mt-1">
                                Pays : {{Auth::user()->country->libelle_country}}
                            </div>
                        @endif
                        @if(Auth::user()->role == 'Comptable' or Auth::user()->role == 'Agent')
                            <div class="text-muted mt-1">
                                Zone : {{Auth::user()->city->libelle_city}}
                            </div>
                        @endif
                        @if(Auth::user()->role == 'Agent')
                            <div class="text-muted mt-1">
                                Agence : {{Auth::user()->agency->libelle_agency}}
                            </div>
                        @endif
					</div>
				</div>
				<!--end::Header-->
				<div class="separator separator-dashed mt-8 mb-5"></div>
				<div class="border-0 d-flex align-items-center justify-content-between pt-0">
                    <span class="navi-item mt-2">
                        <span class="navi-link">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-light-danger font-weight-bolder py-3 px-6"> <i class="flaticon-logout"></i> Se D&eacute;connecter</a>
                        </span>
                    </span>
                    <span class="navi-item mt-2">
                        <span class="navi-link">
                            <a href="{{ route('auth.user.profil') }}" class="btn btn-sm btn-light-primary font-weight-bolder py-3 px-6"> <i class="flaticon-user"></i> Voir profil</a>
                        </span>
                    </span>
                </div>
			</div>
			<!--end::Content-->
		</div>
		<!-- end::User Panel-->

        <script type="text/javascript">
            $(function () {
                $('.overlay-block').removeClass('overlay');
                $('.spinner-lg').removeClass('spinner');

                $(function () {
                $("#btnModalAjout").on("click", function () {
                    document.forms["formAjout"].reset();
                    $(".bs-modal-ajout").modal("show");
                });

                //Reactivation de fenetre modal (le cas ou 2 fenetres sont superposées)
                $(document).on('hidden.bs.modal', function (e) {
                    if ($('.modal:visible').length) {
                        $("body").addClass('modal-open');
                    }
                });
            });
               
            });
        </script>
        <script>var HOST_URL = "https://preview.keenthemes.com/keen/theme/tools/preview";</script>
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3E97FF", "secondary": "#E5EAEE", "success": "#08D1AD", "info": "#844AFF", "warning": "#F5CE01", "danger": "#FF3D60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#DEEDFF", "secondary": "#EBEDF3", "success": "#D6FBF4", "info": "#6125E1", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
        <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
        <script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
    </body>
</html>
