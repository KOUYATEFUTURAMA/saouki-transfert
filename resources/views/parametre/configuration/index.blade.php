@extends('layouts.app')
@section('content')

<script src="{{asset('js/crud.js')}}"></script>
<script src="{{asset('plugins/jQuery/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js')}}"></script>
<script src="{{asset('plugins/js/underscore-min.js')}}"></script>

<link href="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.css')}}" rel="stylesheet">
<div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
@if($infoConfig==null)
    <div class="col-md-1"></div>
    <div class="col-md-11">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    Configuration des param&egrave;tres
                </h3>
            </div>
            <form class="form" method="post" enctype="multipart/form-data" action="{{route('parametre.configurations.store')}}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom de l'entreprise *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="nom_compagnie" id="nom_compagnie"  placeholder="Abidjan transfert" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Adresse *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="adresse_compagnie" id="adresse_compagnie"  placeholder="Abobo rond point" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Responsable de l'entreprise *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="nom_responsable" id="nom_responsable"  placeholder="Ali BAMBA" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact du responsable *</label>
                                <input type="text" class="form-control" name="contact_responsable" id="contact_responsable"  placeholder="+225 0777865229" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact de l'entreprise</label>
                                <input type="text" class="form-control" name="cellulaire" id="cellulaire"  placeholder="+225 0777865229">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact fixe de l'entreprise</label>
                                <input type="text" class="form-control" name="telephone_fixe" id="telephone_fixe"  placeholder="+225 3088444444">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail de la compagnie</label>
                                <input type="email" class="form-control" name="email_compagnie" id="email_compagnie"  placeholder="abidjantransfert@gmail.com">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Site web de la compagnie</label>
                                <input type="text" class="form-control" name="site_web_compagnie" id="site_web_compagnie"  placeholder="abidjan-transfert.com">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Date d'xpiration de la licence</label>
                                <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" placeholder="22-01-2022" name="expiration_licence" data-target="#kt_datetimepicker_2">
                                    <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                        <span class="input-group-text">
                                            <i class="ki ki-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Logo de l'entreprise</label>
                                <input type="file" class="form-control" name="logo">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                </div>
            </form>
        </div>    
    </div>
    <div class="col-md-1"></div>
@else
    <div class="col-md-1"></div>
    <div class="col-md-11">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    Informations de la compagnie
                </h3>
            </div>
            <div class="col-md-12">
                <div class="card-body">
                    <div class="bg-white rounded shadow-sm py-20 px-10 px-lg-20">
                        <!--begin::Icon-->
                        <div class="d-flex flex-center position-relative">
                            <span class="svg svg-fill-primary opacity-5 position-absolute">
                                <svg width="175" height="200">
                                    <polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
                                </svg>
                            </span>
                            <img src="{{asset($infoConfig->logo)}}" class="h-120px" alt="user picture"/>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Content-->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="d-flex flex-column flex-center text-center">
                                    <div class="d-flex justify-content-center">
                                        <span class="font-weight-bolder display-4 text-dark-75 align-self-center">{{ $infoConfig->nom_compagnie }}</span>
                                    </div>
                                    <h4 class="font-size-h6 d-block d-block font-weight-bold text-dark-50 pb-5">{{ $infoConfig->nom_responsable }}
                                    </h4>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-map-marker"></i> {{ $infoConfig->adresse_compagnie }}</h4>
                                    <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-phone"></i> {{ $infoConfig->contact_responsable }}</h4>
                                    <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50"><i class="la la-envelope-open-text"></i> {{ $infoConfig->email_compagnie }}</h4>
                                    <h4 class="font-size-h5 d-block d-block font-weight-bold text-dark-50">Licence expire le : {{ isset($infoConfig->expiration_licence) ? date('d-m-Y',strtotime($infoConfig->expiration_licence)) : "illimitée"}}</h4>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-5"></div>
                        </div>
                        <div class="d-flex flex-center justify-content-between text-rigth"><br/>
                            <a href="{{route('parametre.configurations.show',$infoConfig->id)}}" class="btn btn-sm btn-primary">Modifier les infos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="col-md-1"></div>
@endif
</div>
<script type="text/javascript">

    $(function () {
        
        $('#kt_datetimepicker_2').datetimepicker({
            locale: 'fr',
            formatDate: 'DD-MM-yyyy',
            format: 'DD-MM-yyyy',
            minDate : new Date()
        });
    });
</script>
@endsection


