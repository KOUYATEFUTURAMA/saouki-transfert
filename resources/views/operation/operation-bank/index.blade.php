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
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label for="theme_id">Recherche par banque</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control" id="searchByBank">
                                        <option value="0"> Toutes les banques</option>
                                        @foreach($banks as $bank)
                                        <option value="{{$bank->id}}"> {{$bank->libelle_bank}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            data-pagination="true"
                            data-search="true"
                            data-toggle="table"
                            data-url="{{ url('operation', ['action' => 'list-operations-banks']) }}"
                            data-unique-id="id"
                            data-show-columns="true"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="dateOperation">Date</th>
                                    <th data-field="reference" data-searchable="true">R&eacute;f&eacute;rence</th>
                                    <th data-field="amount" data-formatter="amountFormatter">Montant</th>
                                    <th data-field="operation_type" data-formatter="typeFormatter">Type</th>
                                    <th data-field="bank.libelle_bank">Banque</th>
                                    <th data-field="bank.contact" data-visible="false">Contact</th>
                                    <th data-field="libelle_country">Zone</th>
                                    <th data-field="receptionist">Mandataire</th>
                                    <th data-field="receptionist" data-visible="false">Carte d'identit&eacute; Mandataire</th>
                                    <th data-field="user.name" data-visible="false">Caissier</th>
                                    <th data-field="observation" data-visible="false">Observation</th>
                                    <th data-field="file_to_upload" data-formatter="fileFormatter">Document</th>
                                    @if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable")
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
                    <form id="formAjout" ng-controller="formAjoutCtrl" action="#" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Gestion des op&eacute;rations bancaires</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" ng-hide="true" name="id" id="id">
                            <input type="text" ng-hide="true" name="state" id="state" value="authorized">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="bank_id">Banque * </label>
                                        <div class="input-group">
                                            <select class="form-control" id="bank_id" name="bank_id" required>
                                                <option value=""> Selectionner la banque </option>
                                                @foreach($banks as $bank)
                                                    <option value="{{$bank->id}}"> {{$bank->libelle_bank}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" class="form-control" id="contact_bank" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date d'op&eacute;ration *</label>
                                        <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" id="dateOperation" name="dateOperation" data-target="#kt_datetimepicker_2" value="{{date('d-m-Y H:i')}}" required>
                                            <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                <span class="input-group-text">
                                                    <i class="ki ki-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-10">
                                        <label for="operation_type">
                                        <input type="radio" name="operation_type" id="deposit" value="deposit" ng-model="operation.operation_type" ng-checked="operation.operation_type!='withdrawal'"/>
                                        &nbsp;Retrait banque</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label for="operation_type">
                                        <input type="radio" name="operation_type" id="operation_type" value="withdrawal" ng-model="operation.operation_type" ng-checked="operation.operation_type=='withdrawal'"/>
                                        &nbsp;D&eacute;p&ocirc;t banque</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Montant *</label>
                                        <input type="number" class="form-control" name="amount" id="amount" placeholder="Montat" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Mandataire </label>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="receptionist" id="receptionist" placeholder="Nom complet">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>R&eacute;f&eacute;rence *</label>
                                        <input type="text" class="form-control" name="reference" id="reference" placeholder="N° de chèque ou de versement" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Document scann&eacute;</label>
                                        <input type="file" class="form-control" name="file_to_upload">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observation</label>
                                        <textarea class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);"  name="observation" id="observation" rows="3" placeholder="Votre observation"></textarea>
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
                            <input type="text" ng-hide="true" id="idOperationDelete" value="@{{ operation.id }}">
                            @csrf
                            <p class="text-center text-muted h5">Etes vous certain de vouloir supprimer cet enregistrement ?</p>
                            <p class="text-center h4">@{{ operation.dateOperation + ' de ' + operation.bank.libelle_bank}}</p>
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
        $scope.populateForm = function (operation) {
            $scope.operation = operation;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.operation = {};
        };
    });

    saoukiApp.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (operation) {
            $scope.operation = operation;
        };
        $scope.initForm = function () {
            $scope.operation = {};
        };
    });

    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });

        $('#bank_id, #searchByBank').select2({width: '100%'});

        $('#kt_datetimepicker_2').datetimepicker({
            locale: 'fr',
            formatTime: 'H:mm',
            formatDate: 'DD-MM-yyyy',
            format: 'DD-MM-yyyy H:mm',
            maxDate : new Date()
        });

        $("#bank_id").change(function (e) {
            var bank = $("#bank_id").val();
            $.getJSON("../parametre/find-bank/" + bank, function (reponse) {
                $.each(reponse.rows, function (index, bank) { 
                    $("#contact_bank").val(bank.contact);
                });
           });
        });

        $("#searchByBank").change(function (e) {
            var bank = $("#searchByBank").val();
            if(bank == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('operation', ['action' => 'list-operations-banks'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../operation/list-operations-by-banks/' + bank});
            }
        });
        
        $("#btnModalAjout").on("click", function () {
            $("#bank_id").val('').trigger('change');
            $("#contact_bank").val('');
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();

            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var methode = 'POST';
            var url = "{{route('operation.operations.store')}}";
            var formData = new FormData($(this)[0]);
            editerOperationAction(methode, url, $(this), formData, $overlayBlock, $spinnerLg, $table);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var id = $("#idOperationDelete").val();

            deleteAction('operations/' + id, $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });
    });

    function updateRow(idOperation) {
        var $scope = angular.element($("#formAjout")).scope();
        var operation =_.findWhere(rows, {id: idOperation});
        $scope.$apply(function () {
            $scope.populateForm(operation);
        });
        $("#id").val(operation.id);
        $("#amount").val(operation.amount);
        $("#state").val(operation.state);
        $("#dateOperation").val(operation.dateOperation);
        $("#receptionist").val(operation.receptionist);
        $("#reference").val(operation.reference);
        $("#observation").val(operation.observation);
        $("#bank_id").val(operation.bank_id).trigger('change');
    
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idOperation) {
        var $scope = angular.element($("#formSupprimer")).scope();
        var operation =_.findWhere(rows, {id: idOperation});
        $scope.$apply(function () {
            $scope.populateForm(operation);
        });
       $(".bs-modal-supprimer").modal("show");
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
    function typeFormatter(type){
        if(type == "withdrawal"){
            return "<span class='text-danger'>Dépôt banque<span>";
        }
        if(type == "deposit"){
            return "<span class='text-success'>Retrait banque<span>";
        }
    }
 
    function fileFormatter(file){
        return file ? "<a target='_blank' href='" + basePath + '/' + file + "'>Voir le document</a>" : "---";
    }
    function optionFormatter(id, row) {
        return '<a class="flaticon2-pen text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"></a>\n\<a class="flaticon-delete text-danger cursor-pointer ml-2" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"></a>';
    }

    function editerOperationAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg,$table) {
        jQuery.ajax({
            type: methode,
            url: url,
            cache: false,
            data: formData,
            contentType: false,
            processData: false,
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
                    $("#bank_id").val('').trigger('change');
                    $("#contact_bank").val('');
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
