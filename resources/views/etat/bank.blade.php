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
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label for="theme_id">Recherche par nom de la banque</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="BIAO" id="searchByLibelle">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
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
                        <div class="col-xl-4">
                            <p class="text-right">
                                <a href="" class="btn btn-outline-success mr-4 text-right"><i class="la la-file-excel icon-lg"></i> Exporter</a>
                                <a class="btn btn-outline-warning text-right" onclick="imprimePdf()"><i class="la la-file-pdf icon-lg"></i> Imprimer</a>
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-4">
                            <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                                <div class="alert-text">
                                    <h4 class="text-dark">
                                        D&eacute;p&ocirc;t : <span class="text-success totalEntree">0</span> F CFA
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                                <div class="alert-text">
                                    <h4 class="text-dark">
                                        Retrait : <span class="text-danger totalSortie">0</span> F CFA
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                                <div class="alert-text">
                                    <h4 class="text-dark">
                                        Solde : <span class="text-dark totalSolde">0</span> F CFA
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            data-pagination="true"
                            data-search="false"
                            data-toggle="table"
                            data-url="{{ url('parametre', ['action' => 'list-solde-banks']) }}"
                            data-unique-id="id"
                            data-show-columns="false"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="libelle_bank">Banque</th>
                                    <th data-field="contact">Contact</th>
                                    <th data-field="email">E-mail</th>
                                    <th data-field="country.libelle_country">Pays</th>
                                    <th data-field="solde">Solde</th>
                                    <th data-formatter="ficheFormatter" data-width="70px" data-align="center">Fiche</th>
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

       $('#searchByCountry').select2({width: '100%'});

   });

   function ficheBank(idBank){
        alert('In progrsse !' + idBank);
   }

   function ficheFormatter(id, row) {
       return '<a class="flaticon2-list text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Fiche du client" onClick="javascript:ficheBank(' + row.id + ');"></a>';
   }
</script>
@endsection
