@extends('layouts.app')
@section('content')

<script src="{{asset('js/crud.js')}}"></script>
<script src="{{asset('plugins/jQuery/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js')}}"></script>
<script src="{{asset('plugins/js/underscore-min.js')}}"></script>

<link href="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.css')}}" rel="stylesheet">
<div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
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
                <input type="text" ng-hide="true" id="id" name="id" value="{{$infoConfig->id}}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom de l'entreprise *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="nom_compagnie" id="nom_compagnie"  placeholder="Smart lavage auto" value="{{$infoConfig->nom_compagnie}}" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Adresse *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="adresse_compagnie" value="{{$infoConfig->adresse_compagnie}}" id="adresse_compagnie"  placeholder="Abobo rond point" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Responsable de l'entreprise *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="nom_responsable" value="{{$infoConfig->nom_responsable}}" id="nom_responsable"  placeholder="Alfred KOFFI" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact du responsable *</label>
                                <input type="text" class="form-control" name="contact_responsable" id="contact_responsable" value="{{$infoConfig->contact_responsable}}"  placeholder="+225 0777865229" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact de l'entreprise</label>
                                <input type="text" class="form-control" name="cellulaire" id="cellulaire" value="{{$infoConfig->cellulaire}}"  placeholder="+225 0777865229">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact fixe de l'entreprise</label>
                                <input type="text" class="form-control" name="telephone_fixe" id="telephone_fixe" value="{{$infoConfig->telephone_fixe}}"  placeholder="+225 3088444444">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail de la compagnie</label>
                                <input type="email" class="form-control" name="email_compagnie" id="email_compagnie" value="{{$infoConfig->email_compagnie}}"  placeholder="smartlavage@gmail.com">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Site web de la compagnie</label>
                                <input type="text" class="form-control" name="site_web_compagnie" id="site_web_compagnie" value="{{$infoConfig->site_web_compagnie}}"  placeholder="smart-lavage.com">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Date d'xpiration de la licence</label>
                                <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" placeholder="22-01-2022" name="expiration_licence" data-target="#kt_datetimepicker_2" value="{{date('d-m-Y',strtotime($infoConfig->expiration_licence))}}">
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


