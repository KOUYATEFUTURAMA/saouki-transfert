<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<meta charset="utf-8" />
		<title>{{config('app.name')}}</title>
		<meta name="description" content="Login page example" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
        <script src="{{asset('plugins/jQuery/query.min.js')}}"></script>
        <script src="{{asset('assets/js/pages/features/miscellaneous/sweetalert2.js')}}"></script>
		<!--begin::Page Custom Styles(used by this page)-->
		<link href="{{asset('assets/css/pages/login/login-2.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<link href="{{asset('assets/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body>
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root" >
			<!--begin::Login-->
			<div style="background-image:url('{{asset('images/loginbg1.jpeg')}}');" class="login login-2 login-signin-on d-flex flex-column flex-column-fluid bg-white position-relative overflow-hidden"  id="kt_login">
				<!--begin::Body-->
				<div class="login-body d-flex flex-column-fluid align-items-stretch justify-content-center">
					<div class="container row">
						<div class="col-lg-12 d-flex align-items-center">
							<!--begin::Signin-->
							<div class="login-form login-signin">
								<!--begin::Form-->
								<form class="form w-xxl-550px rounded-lg p-20" method="post" action="{{ route('confirme-compte') }}" novalidate="novalidate" id="kt_login_signin_form" style="background-color: #fff;">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ Session::get('email'); }}">
									<!--begin::Title-->
									<div class="pb-13 pt-lg-0 pt-5">
										<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg text-center">
                                            SAOUKI TRANSFERT
                                        </h3>
                                        <p class="font-weight-bolder text-dark font-size-h5" style="text-align: center;">Confirmation de votre compte</p>
									</div>
                                    @if(\Session::has('msg'))
										<h4 style="color: red;">{!! \Session::get('msg') !!}</h4>
                                    @endif
									<div class="form-group">
                                        <label class="font-size-h6 font-weight-bolder text-dark pt-5">Mot de passe *</label>
                                        <div class="input-icon input-icon-right">
                                            <input type="password" minlength="8" name="password" id="password-field" class="form-control form-control-solid h-auto p-4 rounded-lg" placeholder="Mot de passe" required>
                                            <span>
                                                <i class="far fa-eye toggle-password" toggle="#password-field"  style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                                <h4 style="color: red;">{{ $message }}</h4>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-size-h6 font-weight-bolder text-dark pt-5">Confirmer le mot de passe *</label>
                                        <div class="input-icon input-icon-right">
                                            <input type="password" minlength="8" name="password_confirmation" id="password-confirm-field"  class="form-control form-control-solid h-auto p-4 rounded-lg" placeholder="Confirmer le mot de passe" required>
                                            <span>
                                                <i class="far fa-eye toggle-password-confirm" toggle="#password-confirm-field"  style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        @error('password_confirm')
                                                <h4 style="color: red;">{{ $message }}</h4>
                                        @enderror
										<p class="text-muted font-weight-bold font-size-h4">V&eacute;rifier le r&eacute;sultat de ce calcul svp.</p>
										<p class="text-muted font-weight-bold font-size-h4">
											<span style="cursor: pointer;" id="btnRefresh">
												<i class="ki ki-refresh"></i>&nbsp;&nbsp;
											</span>
											<span id="val1">0</span>
											<input type="hidden" name="inputVal1" id="inputVal1">
											+
											<span id="val2">0</span> 
											<input type="hidden" name="inputVal2" id="inputVal2">
											= 
											<input type="text" id="sommeval" name="sommeval">
										</p>
                                    </div>
									<!--end::Form group-->
									<!--begin::Action-->
									<div class="pb-lg-0 pb-5">
                                        <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Confirmer</button>
									</div>
									<!--end::Action-->
								</form>
								<!--end::Form-->
							</div>
							<!--end::Signin-->
						</div>
					</div>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
		<script>var HOST_URL = "https://preview.keenthemes.com/keen/theme/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3E97FF", "secondary": "#E5EAEE", "success": "#08D1AD", "info": "#844AFF", "warning": "#F5CE01", "danger": "#FF3D60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#DEEDFF", "secondary": "#EBEDF3", "success": "#D6FBF4", "info": "#6125E1", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
		<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="{{asset('assets/js/pages/custom/login/login.js')}}"></script>
		<!--end::Page Scripts-->
        <script>
            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if(input.attr("type") == "password") {
                    input.attr("type", "text");
                }else{
                  input.attr("type", "password");
                }
            });
            $(".toggle-password-confirm").click(function() {
                $(this).toggleClass("fa-eye-slash");
                var input = $($(this).attr("toggle"));
                console.log(input.attr("type"));
                if(input.attr("type") == "password") {
                    input.attr("type", "text");
                }else{
                  input.attr("type", "password");
                }
            });
			$(function () {
				var val1 = Math.floor(Math.random() * 5) + 1;
				var val2 = Math.floor(Math.random() * 10) + 1;
				var somme = parseInt(val1) + parseInt(val2)

				$("#sommeval").val("");
				$("#val1").html(val1);
				$("#val2").html(val2);
				$("#inputVal1").val(val1);
				$("#inputVal2").val(val2);

				$("#btnRefresh").on("click", function () {
					var val1 = Math.floor(Math.random() * 5) + 1;
					var val2 = Math.floor(Math.random() * 10) + 1;
					var somme = parseInt(val1) + parseInt(val2)

					$("#sommeval").val("");
					$("#val1").html(val1);
					$("#val2").html(val2);
					$("#inputVal1").val(val1);
					$("#inputVal2").val(val2);
				});
			});
        </script>
	</body>
	<!--end::Body-->
</html>
