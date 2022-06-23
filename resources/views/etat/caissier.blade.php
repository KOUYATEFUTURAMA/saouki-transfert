@extends('layouts.app')
@section('content')
<script src="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js')}}"></script>
<link href="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.css')}}" rel="stylesheet">
<div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
    <div class="col-xl-12">
            <div class="card-body">
                <div class="bg-white rounded shadow-sm py-5 px-10 px-lg-20">
                    <div class="row">
                        <div class="col-xl-12">
                            <p class="text-right">
                                <a href="" class="btn btn-outline-success mr-4 text-right"><i class="la la-file-excel icon-lg"></i> Exporter</a>
                                <a class="btn btn-outline-warning text-right" onclick="imprimePdf()"><i class="la la-file-pdf icon-lg"></i> Imprimer</a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label for="theme_id">Recherche par nom</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Issouf" id="searchByName">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label for="theme_id">Recherche par pays</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control" id="searchByCountry">
                                        <option value="0"> Tous les pays</option>
                                        @foreach ($countries as $country)
                                        <option value="{{$country->id}}">{{$country->libelle_country}}</option>   
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label for="theme_id">Recherche par zone</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control" id="searchByCity">
                                        <option value="0"> Toutes les zones</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label for="theme_id">Recherche par agence</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control" id="searchByAgency">
                                        <option value="0"> Toutes les agences</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            data-pagination="true"
                            data-search="false"
                            data-toggle="table"
                            data-url="{{ url('auth', ['action' => 'list-agents']) }}"
                            data-unique-id="id"
                            data-show-columns="false"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="name">Agent</th>
                                    <th data-field="contact">Contact</th>
                                    <th data-field="contact">E-mail</th>
                                    <th data-field="country.libelle_country">Pays</th>
                                    <th data-field="city.libelle_city">Zone</th>
                                    <th data-field="agency.libelle_agency">Agence</th>
                                </tr>
                            </thead>
                    </table>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];

    $(function () {
       $table.on('load-success.bs.table', function (e, data) {
           rows = data.rows;
       });

       $('#searchByCountry, #searchByCity, #searchByAgency').select2({width: '100%'});
   });
</script>
@endsection
