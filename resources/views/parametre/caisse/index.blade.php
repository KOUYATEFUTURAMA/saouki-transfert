@extends('layouts.app')
@section('content')

<script src="{{asset('js/crud.js')}}"></script>
<script src="{{asset('plugins/jQuery/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js')}}"></script>
<script src="{{asset('plugins/js/underscore-min.js')}}"></script>

<link href="{{asset('plugins/bootstrap-table/dist/bootstrap-table.min.css')}}" rel="stylesheet">

<div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="col-xl-12">
            <div class="card-body">
                <div class="bg-white rounded shadow-sm py-5 px-10 px-lg-20">
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            data-pagination="true"
                            data-search="true"
                            data-toggle="table"
                            data-url="{{ url('parametre', ['action' => 'list-caisses']) }}"
                            data-unique-id="id"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="libelle_caisse" data-searchable="true" data-sortable="true">Caisse</th>
                                    <th data-field="country.libelle_country">Pays</th>
                                    <th data-field="city.libelle_city">Ville</th>
                                    <th data-field="agency.libelle_agency">Agence</th>
                                    <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="ki ki-wrench"></i></th>
                                </tr>
                            </thead>
                    </table>
                </div>
            </div>
        </div>
</div>

<!-- modal ajout et modification -->
    <div class="modal fade bs-modal-ajout" data-backdrop="static" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="overlay overlay-block">
                    <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
                        <div class="modal-header">
                            <h5 class="modal-title">Gestion des caisses</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" ng-hide="true" name="id" id="id">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom de la caisse *</label>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="libelle_caisse" id="libelle_caisse" placeholder="Caisse 1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country_id">Pays *</label>
                                        <div class="input-group">
                                            <select class="form-control" id="country_id" name="country_id" required>
                                                <option value=""> Selectionner le pays </option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}"> {{$country->libelle_country}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city_id">Ville </label>
                                        <div class="input-group">
                                            <select class="form-control" id="city_id" name="city_id">
                                                <option value=""> Selectionner la ville </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="agency_id">Agence </label>
                                        <div class="input-group">
                                            <select class="form-control" id="agency_id" name="agency_id">
                                                <option value=""> Selectionner l'agence </option>
                                            </select>
                                        </div>
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

    <!-- Modal supprimer-->
    <div class="modal fade bs-modal-supprimer" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="overlay overlay-block">
                    <form id="formSupprimer" ng-controller="formSupprimerCtrl" action="#">
                        <div class="modal-body">
                            <input type="text" ng-hide="true" id="idCaisseDelete" value="@{{ caisse.id }}">
                            @csrf
                            <p class="text-center text-muted h5">Etes vous certain de vouloir supprimer cet enregistrement ?</p>
                            <p class="text-center h4">@{{ caisse.libelle_caisse }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default font-weight-bold" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger font-weight-bold">Confirmer</button>
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
     var add = true;
     var $table = jQuery("#table"), rows = [];

    saoukiApp.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (caisse) {
            $scope.caisse = caisse;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.caisse = {};
        };
    });

    saoukiApp.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (caisse) {
            $scope.caisse = caisse;
        };
        $scope.initForm = function () {
            $scope.caisse = {};
        };
    });

    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });

        $('#country_id, #city_id, #agency_id').select2({width: '100%'});

        $("#country_id").change(function (e) {
            var country = $("#country_id").val();
            $("#city_id").html('<option value=""> Sélectionner la ville </option>')
            $.getJSON("../parametre/list-cities-by-country/" + country, function (reponse) {
                $.each(reponse.rows, function (index, city) { 
                    $("#city_id").append("<option value="+city.id+">"+city.libelle_city+"</option>")
                });
            });
        });

        $("#city_id").change(function (e) {
            var city = $("#city_id").val();
            $("#agency_id").html("<option value=''> Sélectionner l'agence </option>")
            $.getJSON("../parametre/list-agencies-by-city/" + city, function (reponse) {
                $.each(reponse.rows, function (index, agency) { 
                    $("#agency_id").append("<option value="+agency.id+">"+agency.libelle_agency+"</option>")
                });
            });
        });

        $("#btnModalAjout").on("click", function () {
            $("#country_id").val("").trigger('change');
            $("#city_id").html("<option value=''> Sélectionner la ville</option>");
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();

            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var methode = 'POST';
            var url = "{{route('parametre.caisses.store')}}";

            editerCaisseAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var id = $("#idCaisseDelete").val();

            deleteAction('caisses/' + id, $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });
    });

    function updateRow(idCaisse) {
        var $scope = angular.element($("#formAjout")).scope();
        var caisse =_.findWhere(rows, {id: idCaisse});
        $scope.$apply(function () {
            $scope.populateForm(caisse);
        });
        $("#id").val(caisse.id);
        $("#libelle_caisse").val(caisse.libelle_caisse);

        if(caisse.country_id!=null && caisse.city_id!=null && caisse.agency_id==null){
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
            $("#country_id").val(caisse.country_id).trigger('change');

            //Get cities and find city by country
            $.getJSON("../parametre/list-cities-by-country/" + caisse.country_id, function (reponse) {
                $("#city_id").html('<option value=""> Selectionner la ville </option>')
                $.each(reponse.rows, function (index, city) { 
                    $("#city_id").append("<option value="+city.id+">"+city.libelle_city+"</option>")
                });
                $("#city_id").val(caisse.city_id).trigger('change');
            });
            $("#agency_id").val("").trigger('change');
        }else if(caisse.country_id!=null && caisse.city_id!=null && caisse.agency_id!=null){
            $("#country_id").val(caisse.country_id).trigger('change');

            //Get cities and find city by country
            $.getJSON("../parametre/list-cities-by-country/" + caisse.country_id, function (reponse) {
                $("#city_id").html('<option value=""> Selectionner la ville </option>')
                $.each(reponse.rows, function (index, city) { 
                    $("#city_id").append("<option value="+city.id+">"+city.libelle_city+"</option>")
                });
                $("#city_id").val(caisse.city_id);
            });

            //Get agencies and find agency by city
            $.getJSON("../parametre/list-agencies-by-city/" + caisse.city_id, function (reponse) {
                $("#city_id").html("<option value=''> Selectionner la ville </option>")
                $.each(reponse.rows, function (index, agency) { 
                    $("#agency_id").append("<option value="+agency.id+">"+agency.libelle_agency+"</option>")
                });
                $("#agency_id").val(caisse.agency_id).trigger('change');
            });
        }else if(caisse.country_id!=null && caisse.city_id==null && caisse.agency_id!=null){
            $("#country_id").val(caisse.country_id).trigger('change');
            $("#city_id").html("<option value=''> Selectionner la ville </option>")
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
        }else{
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
        }
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idCaisse) {
        var $scope = angular.element($("#formSupprimer")).scope();
        var caisse =_.findWhere(rows, {id: idCaisse});
        $scope.$apply(function () {
            $scope.populateForm(caisse);
        });
       $(".bs-modal-supprimer").modal("show");
    }

    function optionFormatter(id, row) {
        return '<a class="flaticon2-pen text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"></a>\n\<a class="flaticon-delete text-danger cursor-pointer ml-2" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"></a>';
    }

    function editerCaisseAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg, $table){
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
                        $("#country_id").val("").trigger('change');
                        $("#city_id").html("<option value=''> Sélectionner la ville</option>");
                        $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
                        $table.bootstrapTable('refresh');
                        document.forms["formAjout"].reset();
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
