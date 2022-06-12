@extends('layouts.app')
@section('content')
    <script src="{{asset('js/crud.js')}}"></script>
    <script src="{{ asset('plugins/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js') }}"></script>
    <script src="{{asset('template/js/pages/features/forms/widgets/input-mask.js')}}"></script>
    <script src="{{asset('plugins/js/underscore-min.js')}}"></script>

    <link href="{{ asset('plugins/bootstrap-table/dist/bootstrap-table.min.css') }}" rel="stylesheet">
    <div class="card-body">
        <div class="bg-white rounded shadow-sm py-5 px-10 px-lg-20">
            <div class="row">
                <div class="col-xl-12">
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                        data-pagination="true"
                        data-search="true"
                        data-toggle="table"
                        data-url="{{ url('auth', ['action' => 'list-users']) }}"
                        data-unique-id="id"
                        data-show-toggle="false">
                        <thead>
                            <tr role="row">
                                <th data-field="name" data-searchable="true" data-sortable="true">Nom complet</th>
                                <th data-field="email">E-mail</th>
                                <th data-field="contact">Contact</th>
                                <th data-field="role">Role</th>
                                <th data-field="country.libelle_country">Pays</th>
                                <th data-field="city.libelle_city">Ville</th>
                                <th data-field="agency.libelle_agency">Agence</th>
                                <th data-field="statut_compte" data-formatter="statutFormatter">Etat</th>
                                <th data-field="last_login">Derni&egrave;re connex.</th>
                                <th data-field="id" data-formatter="optionFormatter" data-width="120px" data-align="center"><i class="ki ki-wrench"></i></th>
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
                            <h5 class="modal-title">Gestion des utilisateurs</h5>
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
                                        <label>Nom complet *</label>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="name" id="name" placeholder="Nom et prénom(s)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact *</label>
                                        <input type="text" class="form-control" id="contact" name="contact" placeholder="Contact téléphonique" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>E-mail *</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Adresse mail" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Role *</label>
                                        <select class="form-control" name="role" id="role" required>
                                            <option value="" ng-hide="true">Selectionner le role</option>
                                            <option value="Administrateur">Administrateur</option>
                                            <option value="Superviseur">Superviseur</option>
                                            <option value="Comptable">Comptable</option>
                                            <option value="Gerant">Gerant</option>
                                            <option value="Agent">Agent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 country_row">
                                    <div class="form-group">
                                        <label>Pays *</label>
                                        <select class="form-control" id="country_id" name="country_id">
                                            <option value="">Selectionner le pays</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}">{{$country->libelle_country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 city_row">
                                    <div class="form-group">
                                        <label>Ville ou zone *</label>
                                        <select class="form-control" id="city_id" name="city_id">
                                            <option value="">Selectionner la ville</option>
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 agency_row">
                                    <div class="form-group">
                                        <label>Agence *</label>
                                        <select class="form-control" id="agency_id" name="agency_id">
                                            <option value="">Selectionner l'agence</option>
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label>
                                                <input type="checkbox" name="statut_compte" id="statut_compte" ng-model="user.statut_compte" ng-checked="user.statut_compte==1"/><span></span>
                                            </label>
                                            <label for="statut_compte">&nbsp;Activer ou d&eacute;sactiver le compte</label>
                                        </span>
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
                            <input type="text" ng-hide="true" id="idUserDelete" value="@{{ user.id }}">
                            @csrf
                            <p class="text-center text-muted h5">Etes vous certain de vouloir supprimer cet utilisateur ?</p>
                            <p class="text-center h4">@{{ user.name }}</p>
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
    var $table = jQuery("#table"), rows = [];

    saoukiApp.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (user) {
            $scope.user = user;
        };
        $scope.initForm = function () {
            $scope.user = {};
        };
    });

    saoukiApp.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (user) {
            $scope.user = user;
        };
        $scope.initForm = function () {
            $scope.user = {};
        };
    });

    $(function () {

        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });
        
        $('#country_id, #city_id, #agency_id').select2({width: '100%'});

        $(".city_row, .agency_row, .country_row").hide();
        $("#city_id, #agency_id, #country_id").removeAttr('required');

        $("#role").change(function (e) {
            var role = $("#role").val();
            if(role == "Administrateur" || role == "Gerant" || role == "Superviseur"){
                $(".city_row, .agency_row, .country_row").hide();
                $("#city_id, #agency_id, #country_id").removeAttr('required');
            }
            if(role == "Comptable"){
                $(".agency_row").hide();
                $(".city_row, .country_row").show();
                $("#city_id, #country_id").attr('required', ''); 
            }
            if(role == "Agent"){
                $(".city_row, .country_row, .agency_row").show();
                $("#city_id, #country_id, #agency_id").attr('required', ''); 
            }
        });

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
            $(".city_row, .agency_row, .country_row").hide();
            $("#city_id, #agency_id, #country_id").removeAttr('required');
            $("#city_id").html("<option value=''> Sélectionner la ville</option>");
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
        });


        $("#formAjout").submit(function (e) {
            e.preventDefault();
            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var methode = 'POST';
            var url = "{{route('auth.users.store')}}";

            editerUserAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var id = $("#idUserDelete").val();

            deleteAction('users/' + id, $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });
    });

    function updateRow(idUser){
        var $scope = angular.element($("#formAjout")).scope();
        var user =_.findWhere(rows, {id: idUser});
        $scope.$apply(function () {
            $scope.populateForm(user);
        });
        $("#id").val(user.id);
        $("#name").val(user.name);
        $("#email").val(user.email);
        $("#contact").val(user.contact);
        $("#role").val(user.role);

        if(user.country_id!=null && user.city_id!=null && user.agency_id==null){
            $(".agency_row").hide();
            $(".city_row, .country_row").show();
            $("#city_id, #country_id").attr('required', ''); 
            $("#agency_id").removeAttr('required');
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
            $("#country_id").val(user.country_id).trigger('change');

            //Get cities and find city by country
            $.getJSON("../parametre/list-cities-by-country/" + user.country_id, function (reponse) {
                $("#city_id").html('<option value=""> Selectionner la ville </option>')
                $.each(reponse.rows, function (index, city) { 
                    $("#city_id").append("<option value="+city.id+">"+city.libelle_city+"</option>")
                });
                $("#city_id").val(user.city_id).trigger('change');
            });
            $("#agency_id").val("").trigger('change');
        }else if(user.country_id!=null && user.city_id!=null && user.agency_id!=null){
            $(".city_row, .country_row, .agency_row").show();
            $("#city_id, #country_id, #agency_id").attr('required', ''); 
            $("#country_id").val(user.country_id).trigger('change');

            //Get cities and find city by country
            $.getJSON("../parametre/list-cities-by-country/" + user.country_id, function (reponse) {
                $("#city_id").html('<option value=""> Selectionner la ville </option>')
                $.each(reponse.rows, function (index, city) { 
                    $("#city_id").append("<option value="+city.id+">"+city.libelle_city+"</option>")
                });
                $("#city_id").val(user.city_id);
            });

            //Get agencies and find agency by city
            $.getJSON("../parametre/list-agencies-by-city/" + user.city_id, function (reponse) {
                $("#city_id").html("<option value=''> Selectionner l'agence </option>")
                $.each(reponse.rows, function (index, agency) { 
                    $("#agency_id").append("<option value="+agency.id+">"+agency.libelle_agency+"</option>")
                });
                $("#agency_id").val(user.agency_id).trigger('change');
            });
        }else{
            $(".city_row, .agency_row, .country_row").hide();
            $("#city_id, #agency_id, #country_id").removeAttr('required');
            $("#city_id").html("<option value=''> Sélectionner la ville</option>");
            $("#agency_id").html("<option value=''> Sélectionner l'agence</option>");
        }
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idUser) {
        var $scope = angular.element($("#formSupprimer")).scope();
        var user =_.findWhere(rows, {id: idUser});
        $scope.$apply(function () {
            $scope.populateForm(user);
        });
        $(".bs-modal-supprimer").modal("show");
    }

    function statutFormatter(statut){
        return statut ? '<span class="label label-rounded label-success label-inline mr-2">Actif</span>' : '<span class="label label-rounded label-danger label-inline mr-2">Inactif</span>';
    }

    function optionFormatter(id, row) {
        return '<a class="flaticon2-pen text-primary cursor-pointer mr-4" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"></a>\n\<a class="flaticon-delete text-danger cursor-pointer" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"></a>';
    }

    function editerUserAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg, $table){
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
                        $(".city_row, .agency_row, .country_row").hide();
                        $("#city_id, #agency_id, #country_id").removeAttr('required');
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
