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
                                            <span class="text-success">Ouverte</span>
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
            @if($caisseOuverte)
                <input type="hidden" id="caisseOuverte_id" value="{{$caisseOuverte->id}}">
                <div class="row mt-5">
                    <div class="col-xl-4">
                        <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                            <div class="alert-text">
                                <h4 class="text-dark">
                                    Entr&eacute;e : <span class="text-success totalEntree">0</span> F CFA
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                            <div class="alert-text">
                                <h4 class="text-dark">
                                    Sortie : <span class="text-danger totalSortie">0</span> F CFA
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
                <div class="row mt-5">
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="centre">Rechercher par N° ref.</label>
                            <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="20235987" id="searchByRef">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="centre">Rechercher par type</label>
                            <select class="form-control" id="searchByType">
                                <option value="0"> Tous</option>
                                <option value="deposit"> Entr&eacute;e</option>
                                <option value="withdrawal"> Sortie</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="centrer">Rechercher par etat</label>
                            <select class="form-control" id="searchByState">
                                <option value="0"> Tous</option>
                                <option value="recorded"> Enregistr&eacute;e </option>
                                <option value="authorized"> Autoris&eacute;e </option>
                                <option value="unauthorized"> Annul&eacute;e</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="centrer">Rechercher par concern&eacute;</label>
                            <select class="form-control" id="searchByConcerne">
                                <option value="0"> Tous</option>
                                <option value="partenair_id"> Partenaire</option>
                                <option value="bank_id"> Banque </option>
                                <option value="other_caisse_id"> Encaiss - Decaiss</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-xl-12">
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                        data-pagination="true"
                        data-search="false"
                        data-toggle="table"
                        data-url="{{ url('operation', ['action' => 'list-operations']) }}"
                        data-unique-id="id"
                        data-show-toggle="false"
                        data-show-columns="false">
                        <thead>
                            <tr role="row">
                                <th data-field="dateOperation">Date</th>
                                <th data-field="reference">R&eacute;f&eacute;rence</th>
                                <th data-field="amount" data-formatter="amountFormatter">Montant</th>
                                <th data-field="operation_type" data-formatter="typeFormatter">Type</th>
                                <th data-formatter="concerneFormatter">Concerne</th>
                                <th data-field="receptionist">Mandataire</th>
                                <th data-formatter="stateFormatter">Etat</th>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="caisse_id">Caisse *</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" id="caisse_id" name="caisse_id" required>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Montant &agrave; l'ouverture *</label>
                                        <input type="number" min="0" class="form-control" name="montant_ouverture" id="montant_ouverture" placeholder="25000" required>
                                    </div>
                                </div>
                            </div>
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

    <!-- modal fermeture caisse -->
    <div class="modal fade bs-modal-close-caisse" data-backdrop="static" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="overlay overlay-block">
                    <form id="formCloseCaisse" action="#">
                        <div class="modal-header">
                            <h5 class="modal-title">Fermeture de caisse</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-custom alert-outline-warning fade show mb-2" role="alert">
                                        <div class="alert-text">
                                            <h4>
                                                Ouverture : 
                                                <span class="text-dark mr-2" id="ouverture">
                                                    0 
                                                </span> F CFA
                                            </h4><br/>
                                            <h4>
                                                Entr&eacute;e : 
                                                <span class="text-dark mr-2" id="entree">
                                                    0 
                                                </span> F CFA 
                                            </h4><br/>
                                            <h4>
                                                Sortie : 
                                                <span class="text-dark mr-2" id="sortie">
                                                    0 
                                                </span> F CFA
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-custom alert-outline-warning fade show mb-2" role="alert">
                                        <div class="text-danger  mb-4">
                                            <h2><br/><br/>
                                                Solde : <span class="mr-2" id="solde"> 0</span> F CFA 
                                                <input type="hidden" id="solde_fermeture" name="solde_fermeture">
                                                <input type="hidden" id="caisse_a_fermer" name="caisse_a_fermer">
                                            </h2><br/> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(Auth::user()->role=="Administrateur")
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Observation</label>
                                            <textarea class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);"  name="observation" id="observation" rows="3" placeholder="Votre observation"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
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

    <input type="hidden" id="role" value="{{Auth::user()->role}}">
    <input type="hidden" id="country_id" value="{{Auth::user()->country_id}}">
    <input type="hidden" id="city_id" value="{{Auth::user()->city_id}}">
    <script type="text/javascript">
        var $table = jQuery("#table"), rows = [];
        
        $(function () {

            $table.on('load-success.bs.table', function (e, data) {
                $(".totalEntree").html('' + Intl.NumberFormat().format(data.totalEntree)+ '');
                $(".totalSortie").html('' + Intl.NumberFormat().format(data.totalSortie)+ '');
                $(".totalSolde").html('' + Intl.NumberFormat().format(data.totalSolde)+ '');
                rows = data.rows;
            });

            $("#searchByRef").keyup(function (e) {
                var reference = $("#searchByRef").val();
                if(reference == ""){
                    $table.bootstrapTable('refreshOptions', {url: "{{url('operation', ['action' => 'list-operations'])}}"});
                }else{
                    $table.bootstrapTable('refreshOptions', {url: '../operation/list-operations-by-reference/' + reference});
                }
            });

            $("#searchByType").change(function (e) {
                var type = $("#searchByType").val();
                if(type == 0){
                    $table.bootstrapTable('refreshOptions', {url: "{{url('operation', ['action' => 'list-operations'])}}"});
                }else{
                    $table.bootstrapTable('refreshOptions', {url: '../operation/list-operations-by-type/' + type});
                }
            });

            $("#searchByState").change(function (e) {
                var state = $("#searchByState").val();
                if(state == 0){
                    $table.bootstrapTable('refreshOptions', {url: "{{url('operation', ['action' => 'list-operations'])}}"});
                }else{
                    $table.bootstrapTable('refreshOptions', {url: '../operation/list-operations-by-state/' + state});
                }
            });

            $("#searchByConcerne").change(function (e) {
                var concerne = $("#searchByConcerne").val();
                if(concerne == 0){
                    $table.bootstrapTable('refreshOptions', {url: "{{url('operation', ['action' => 'list-operations'])}}"});
                }else{
                    $table.bootstrapTable('refreshOptions', {url: '../operation/list-operations-by-concerne/' + concerne});
                }
            });

            $("#formOpenCaisse").submit(function (e) {
                    e.preventDefault();
                    var $overlayBlock = $(".overlay-block");
                    var $spinnerLg = $(".spinner-lg");

                    var methode = 'POST';
                    var url = "{{route('operation.open-caisse')}}";

                    ouvertureCaisseAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg);
            });

            $("#formCloseCaisse").submit(function (e) {
                e.preventDefault();
                var $overlayBlock = $(".overlay-block");
                var $spinnerLg = $(".spinner-lg");

                var methode = 'POST';
                var url = "{{route('operation.close-caisse')}}";
                closeCaisseAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg);
            });
        });

        function ouvertureCaisse(){
            var role = $("#role").val();
            if(role == 'Superviseur'){
                var country = $("#country_id").val();
                $.getJSON("../parametre/list-caisses-by-country/" + country, function (reponse) {
                    $('#caisse_id').html("<option value=''>Sélectionner une caisse</option>");
                    $.each(reponse.rows, function (index, caisse) { 
                        if(caisse.city_id == null && caisse.agency_id == null && caisse.ouverte == 0){
                            $("#caisse_id").append("<option value="+caisse.id+">"+caisse.libelle_caisse+"</option>")
                        }
                    });
                })
            }
            if(role == 'Comptable'){
                var city = $("#city_id").val();
                $.getJSON("../parametre/list-caisses-by-city/" + city, function (reponse) {
                    $('#caisse_id').html("<option value=''>Sélectionner une caisse</option>");
                    $.each(reponse.rows, function (index, caisse) { 
                        if(caisse.agency_id == null && caisse.ouverte == 0){
                            $("#caisse_id").append("<option value="+caisse.id+">"+caisse.libelle_caisse+"</option>")
                        }
                    });
                })
            }
            $(".bs-modal-open-caisse").modal("show");
        }
        function fermetureCaisse(){
            $("#ouverture").html("");
            $("#entree").html("");
            $("#sortie").html("");
            $("#solde").html("");
            var caisseOuverte = $("#caisseOuverte_id").val();

            $.getJSON("../operation/get-caisse-infos-cloture/" + caisseOuverte, function (reponse) {
                $.each(reponse.rows, function (index, caisse) { 
                    var montantOuverture = caisse.montant_ouverture;   
                    var montantOuverture = Intl.NumberFormat().format(montantOuverture);
                    var totalEntre = Intl.NumberFormat().format(reponse.totalEntre);
                    var totalSortie = Intl.NumberFormat().format(reponse.totalSortie);
                    var solde = parseInt(caisse.montant_ouverture) + parseInt(reponse.totalEntre) - parseInt(reponse.totalSortie);
                    var soldeTotal = Intl.NumberFormat().format(solde);
                    $("#ouverture").html(montantOuverture);
                    $("#entree").html(totalEntre);
                    $("#sortie").html(totalSortie);
                    $("#solde").html(soldeTotal);
                    $("#solde_fermeture").val(solde);
                });
            })
            $("#caisse_a_fermer").val(caisseOuverte);
            $(".bs-modal-close-caisse").modal("show");
        }

        function concerneFormatter(id, row){
            if(row.bank_id){
                return "<span>"+row.bank.libelle_bank+"<span>";
            }
            if(row.other_caisse_id){
                return row.operation_type == "deposit" ? "<span>Décaissement<span>" : "<span>Encaissement<span>";
            }
            if(row.partenair_id){
                return "<span>Partenaire " + row.partenair.name + "<span>";
            }
        }
        function typeFormatter(type){
            if(type == "deposit"){
                return "<span class='text-success'>Entrée<span>";
            }
            if(type == "withdrawal"){
                return "<span class='text-danger'>Sortie<span>";
            }
        }
        function amountFormatter(amount){
            return Intl.NumberFormat().format(amount);
        }
        function stateFormatter(id, row){
            if(row.state == "recorded"){
                return "<span>En attente de validation<span>";
            }
            if(row.state == "authorized"){
                return "<span class='text-success'>Autorisée le "+row.authorizationDate+" par " + row.authorized_by.name + "<span>";
            }
            if(row.state == "unauthorized"){
                return "<span class='text-danger'>Anulée<span>";
            }
        }
        function fileFormatter(file){
            return file ? "<a target='_blank' href='" + basePath + '/' + file + "'>Voir le document</a>" : "---";
        }

        //Open caisse
        function ouvertureCaisseAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg){
            jQuery.ajax({
                type: methode,
                url: url,
                cache: false,
                data: formData,
                success:function (response, textStatus, xhr){
                    if (response.code === 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 2500
                        });
                        //Si la caisse est ouverte on actualise la page
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
                    $overlayBlock.addClass('overlay');
                    $spinnerLg.addClass('spinner');
                },
                complete: function () {
                    $overlayBlock.removeClass('overlay');
                    $spinnerLg.removeClass('spinner');
                },
            });
        }

        //Close caisse
        function closeCaisseAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg){
            jQuery.ajax({
                type: methode,
                url: url,
                cache: false,
                data: formData,
                success:function (response, textStatus, xhr){
                    if (response.code === 1) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: response.msg,
                            showConfirmButton: false,
                            timer: 2500
                        });
                        //Si la caisse est fermé on actualise la page
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
                    $overlayBlock.addClass('overlay');
                    $spinnerLg.addClass('spinner');
                },
                complete: function () {
                    $overlayBlock.removeClass('overlay');
                    $spinnerLg.removeClass('spinner');
                },
            });
        }
    </script>
@endsection