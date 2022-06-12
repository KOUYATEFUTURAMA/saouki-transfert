@extends('layouts.app')
@section('content')
<div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card-body">
                    <div class="bg-white rounded shadow-sm py-20 px-10 px-lg-20">
                        <!--begin::Icon-->
                        <div class="d-flex flex-center position-relative">
                            <span class="svg svg-fill-primary opacity-5 position-absolute">
                                <svg width="175" height="200">
                                    <polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
                                </svg>
                            </span>
                            <img src="{{asset('images/user.jpg')}}" class="h-120px" alt="user picture" />
                        </div>
                        <!--end::Icon-->
                        <!--begin::Content-->
                        <div class="d-flex flex-column flex-center text-center">
                            <div class="d-flex justify-content-center">
                                <span class="font-weight-bolder display-4 text-dark-75 align-self-center">{{ $user->name}}</span>
                            </div>
                            <h4 class="font-size-h6 d-block d-block font-weight-bold text-dark-50 pb-5">{{ $user->role }}</h4>
                        </div>
                        <div class="text-left">
                            <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-phone"></i> {{ $user->contact }}</h4>
                            <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-envelope-open-text"></i> {{ $user->email }}</h4>
                            <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-history"></i> {{'Inscrit le '.$user->created }}</h4>
                            @if(Auth::user()->role == 'Superviseur' or Auth::user()->role == 'Comptable' or Auth::user()->role == 'Agent')
                            <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la fab la-font-awesome-flag"></i> {{$user->country->libelle_country}}</h4>
                            @endif
                            @if(Auth::user()->role == 'Comptable' or Auth::user()->role == 'Agent')
                            <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-dot-circle"></i> {{$user->city->libelle_city}}</h4>
                            @endif
                            @if(Auth::user()->role == 'Agent')
                            <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50 pb-5"><i class="la la-city"></i> {{$user->agency->libelle_agency}}</h4>
                            @endif
                        </div>
                        <div class="d-flex flex-center justify-content-between text-center">
                            <button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#modalProfilUpdate">Modifier mes infos</button>
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#passwordModal">Modifier le mot de passe</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <!--begin: profil Modal-->
    <div class="modal fade" id="modalProfilUpdate" data-backdrop="static" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="overlay overlay-block">
                    <form action="#" id="formProfil">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier les informations de mon profil</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="idUser" value="{{ $user->id }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Pr&eacute;nom(s) & Nom *</label>
                                        <input type="text" class="form-control" name="name" placeholder="Votre nom complet" value="{{ $user->name }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>E-mail *</label>
                                        <input type="email" class="form-control" name="email" placeholder="Votre e-mail" value="{{ $user->email }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact *</label>
                                        <input type="text" class="form-control" name="contact" placeholder="Votre contact" value="{{ $user->contact }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger font-weight-bold pull-left" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary font-weight-bold">Modifier</button>
                        </div>
                    </form>
                    <div class="overlay-layer">
                        <div class="spinner spinner-lg spinner-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end: profil Modal-->

<!--Password Modal-->
<div class="modal fade" id="passwordModal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier mon mot de passe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="overlay overlay-block">
                <form action="#" id="formPassword">
                    <div class="modal-body">
                        <input type="hidden" id="idUserPassword" value="{{ $user->id }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        @csrf
                        <div class="form-group">
                            <label>Mot de passe actuel *</label>
                            <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe actuel" required>
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe *</label>
                            <input type="password" minlength="8" name="new_password" class="form-control" placeholder="Entrez votre nouveau mot de passe" required>
                        </div>
                        <div class="form-group">
                            <label>Confirmer le mot de passe *</label>
                            <input type="password" minlength="8" name="repeat_password" class="form-control" placeholder="Confirmer votre nouveau mot de passe" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger font-weight-bold" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Modifier</button>
                    </div>
                </form>
                <div class="overlay-layer">
                    <div class="spinner spinner-lg spinner-danger"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.overlay-block').removeClass('overlay');
    $('.spinner-lg').removeClass('spinner');
    $(function () {

        $("#formProfil").submit(function (e) {
            e.preventDefault();
            jQuery.ajax({
                type: 'PUT',
                url: 'update-profil/' + $('#idUser').val(),
                cache: false,
                data: $(this).serialize(),
                success:function (response, textStatus, xhr){
                    if (response.code === 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 2500
                        });
                        location.reload();
                    }
                    if (response.code === 0) {
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                 },
                error: function (err) {
                    var res = eval('('+err.responseText+')');
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                beforeSend: function () {
                    $('.overlay-block').addClass('overlay');
                    $('.spinner-lg').addClass('spinner');
                },
                complete: function () {
                    $('.overlay-block').removeClass('overlay');
                    $('.spinner-lg').removeClass('spinner');
                },
            });
        });

        $("#formPassword").submit(function (e) {
            e.preventDefault();
            jQuery.ajax({
                type: 'PUT',
                url: 'update-password/' + $('#idUserPassword').val(),
                cache: false,
                data: $(this).serialize(),
                success:function (response, textStatus, xhr){
                    if (response.code === 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 2500
                        });
                        location.reload();
                    }
                    if (response.code === 0) {
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                 },
                error: function (err) {
                    var res = eval('('+err.responseText+')');
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                beforeSend: function () {
                    $('.overlay-block').addClass('overlay');
                    $('.spinner-lg').addClass('spinner');
                },
                complete: function () {
                    $('.overlay-block').removeClass('overlay');
                    $('.spinner-lg').removeClass('spinner');
                },
            });
        });
    });
</script>
@endsection

