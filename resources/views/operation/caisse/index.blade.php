@extends('layouts.app')
@section('content')
    <script src="{{asset('js/crud.js')}}"></script>
    <script src="{{ asset('plugins/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js') }}"></script>
    <script src="{{asset('template/js/pages/features/forms/widgets/input-mask.js')}}"></script>
    <script src="{{asset('plugins/js/underscore-min.js')}}"></script>
    <script src="{{asset('plugins/js/jquery.number.min.js')}}"></script>
    <link href="{{ asset('plugins/bootstrap-table/dist/bootstrap-table.min.css') }}" rel="stylesheet">

    <div class="card-body">
        <div class="bg-white rounded shadow-sm py-10 px-10 px-lg-20">
            <div class="row">
                <div class="col-md-4">
                    <div class="alert alert-custom alert-outline-warning fade show mb-2" role="alert">
                        <div class="alert-text">
                            <h4>
                                Etat de la caisse :  @if($caisseOuverte == null)
                                            <span class="text-danger">Ferm&eacute;e</span>
                                        @else
                                            <span class="text-success">Ouvert</span>
                                        @endif
                            </h4>
                            <h4>
                                Action : @if($caisseOuverte == null)
                                            <button type="button" onClick="ouvertureCaisse()" class="btn btn-xs btn-success btn-shadow font-weight-bold mr-2">Ouvrir la caisse</button>
                                        @else
                                            <button type="button" onClick="fermetureCaisse()" class="btn btn-xs btn-danger btn-shadow font-weight-bold mr-2">Fermer la caisse</button>
                                        @endif
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-custom alert-outline-warning fade show mb-2" role="alert">
                        <div class="alert-text">
                            <h4>
                                Pays : @if($country)
                                        <span class="text-dark">{{$country->libelle_country}}</span>
                                        @endif
                            </h4><br/>
                            <h4>
                                Zone : @if($city)
                                            <span class="text-dark">{{$city->libelle_city}}</span>
                                        @endif
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-custom alert-outline-warning fade show mb-2" role="alert">
                        <div class="alert-text">
                            <h4>
                                Caisse : @if($caisse)
                                            <span class="text-dark">{{$caisse->libelle_caisse}}</span>
                                        @endif
                            </h4><br/>
                            <h4>
                                Caissier : <span class="text-dark">{{Auth::user()->name}}</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if($caisseOuverte)
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="centre">Rechercher par N° facture</label>
                            <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="123456789" id="searchByFacture">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                        data-pagination="true"
                        data-search="false"
                        data-toggle="table"
                        data-url="{{ url('operation', ['action' => 'liste-operations']) }}"
                        data-unique-id="id"
                        data-show-toggle="false"
                        data-show-columns="false">
                        <thead>
                            <tr role="row">
                                <th data-field="id" data-formatter="factureFormatter" data-width="50px" data-align="center"><i class="ki ki-info"></i></th>
                                <th data-field="date_factures" data-sortable="true">Date</th>
                                <th data-field="numero_facture">N° facture</th>
                                <th data-field="client.full_name_client">Client</th>
                                <th data-field="vehicule.immatruculation">V&eacute;hicule</th>
                                <th data-field="montant_a_payer" data-formatter="montantFormatter">Montant</th>
                                <th data-formatter="payerFormatter">Pay&eacute;</th>
                                <th data-formatter="monnaieFormatter">Monnaie</th>
                                <th data-field="moyen_reglement.libelle_moyen_reglement">Moyen payement</th>
                                <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="ki ki-wrench"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal ouverture caisse -->
    <div class="modal fade bs-modal-open-caisse" data-backdrop="static" aria-modal="true" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="overlay overlay-block">
                    <form id="formOpenCaisse" action="#">
                        <div class="modal-header bg-green">
                            <h5 class="modal-title">Ouverture de la caisse</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                           
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary font-weight-bold">Valider</button>
                        </div>
                    </form>
                    <div class="overlay-layer">
                        <div class="spinner spinner-lg spinner-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection