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
                            data-url="{{ url('parametre', ['action' => 'list-banks']) }}"
                            data-unique-id="id"
                            data-show-columns="true"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="libelle_bank" data-searchable="true" data-sortable="true">Banque</th>
                                    <th data-field="contact">Contact</th>
                                    <th data-field="email">E-mail</th>
                                    <th data-field="country.libelle_country">Pays</th>
                                    <th data-field="adress" data-visible="false">Adresse</th>
                                    @if(Auth::user()->role == "Administrateur")
                                    <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="ki ki-wrench"></i></th>
                                    @endif
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
                            <h5 class="modal-title">Gestion des banques</h5>
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
                                        <label>Nom de la banque *</label>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="libelle_bank" id="libelle_bank" placeholder="NSIA Banque" required>
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
                                        <label>Contact *</label>
                                        <input type="text" class="form-control" name="contact" id="contact" placeholder="Contact de la banque" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>E-mail </label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="E-mail de la banque">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Adresse de la banque </label>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="adress" id="adress" placeholder="Adresse de la banque">
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
                            <input type="text" ng-hide="true" id="idBankDelete" value="@{{ bank.id }}">
                            @csrf
                            <p class="text-center text-muted h5">Etes vous certain de vouloir supprimer cet enregistrement ?</p>
                            <p class="text-center h4">@{{ bank.libelle_bank }}</p>
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
        $scope.populateForm = function (bank) {
            $scope.bank = bank;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.bank = {};
        };
    });

    saoukiApp.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (bank) {
            $scope.bank = bank;
        };
        $scope.initForm = function () {
            $scope.bank = {};
        };
    });

    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });

        $('#country_id').select2({width: '100%'});

        $("#btnModalAjout").on("click", function () {
            $("#country_id").val('').trigger('change');
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();

            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var methode = 'POST';
            var url = "{{route('parametre.banks.store')}}";

            editerBankAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var id = $("#idBankDelete").val();

            deleteAction('banks/' + id, $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });
    });

    function updateRow(idBank) {
        var $scope = angular.element($("#formAjout")).scope();
        var bank =_.findWhere(rows, {id: idBank});
        $scope.$apply(function () {
            $scope.populateForm(bank);
        });
        $("#id").val(bank.id);
        $("#libelle_bank").val(bank.libelle_bank);
        $("#contact").val(bank.contact);
        $("#email").val(bank.email);
        $("#adress").val(bank.adress);
        $("#country_id").val(bank.country_id).trigger('change');
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idBank) {
        var $scope = angular.element($("#formSupprimer")).scope();
        var bank =_.findWhere(rows, {id: idBank});
        $scope.$apply(function () {
            $scope.populateForm(bank);
        });
       $(".bs-modal-supprimer").modal("show");
    }

    function optionFormatter(id, row) {
        return '<a class="flaticon2-pen text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"></a>\n\<a class="flaticon-delete text-danger cursor-pointer ml-2" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"></a>';
    }

    function editerBankAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg,$table) {
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
                        document.forms["formAjout"].reset();
                        $("#country_id").val("").trigger('change');
                        $table.bootstrapTable('refresh');
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
                        timer: 3000
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
