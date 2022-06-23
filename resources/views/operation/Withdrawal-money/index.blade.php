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
                            data-search="false"
                            data-toggle="table"
                            data-url="{{ url('operation', ['action' => 'list-withdrawal-money']) }}"
                            data-unique-id="id"
                            data-show-columns="false"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    @if(Auth::user()->role == "Agent")
                                    <th data-field="id" data-formatter="recuFormatter">re&ccedil;u</th>
                                    @endif
                                    <th data-field="withdrawalDate">Date</th>
                                    <th data-field="amount" data-formatter="amountFormatter">Montant</th>
                                    <th data-formatter="senderFormatter">Exp&eacute;diteur</th>
                                    <th data-formatter="recipientFormatter">Destinataire</th>
                                    <th data-field="libelle_country">Pays d'exp&eacute;dit.</th>
                                    @if(Auth::user()->role != "Agent")
                                    <th data-field="libelle_agency">Agence</th>
                                    <th data-field="created_by.name">Caissier</th>
                                    @endif
                                    <th data-field="state" data-formatter="stateFormatter">Pay&eacute;</th>
                                    <!--th data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="ki ki-wrench"></i></th-->
                                </tr>
                            </thead>
                    </table>
                </div>
            </div>
        </div>
</div>

<!-- modal ajout et modification -->
    <div class="modal fade bs-modal-ajout" data-backdrop="static" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="overlay overlay-block">
                    <form id="formAjout" ng-controller="formAjoutCtrl" action="#" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Gestion des retraits d'argent</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" ng-hide="true" name="id" id="id">
                            <input type="text" ng-hide="true" name="amount" id="amount">
                            <input type="text" ng-hide="true" name="id_recipient" id="id_recipient">
                            @csrf 
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date de retrait *</label>
                                        <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" id="withdrawalDate" name="withdrawalDate" data-target="#kt_datetimepicker_2" value="{{date('d-m-Y H:i')}}" required>
                                            <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                <span class="input-group-text">
                                                    <i class="ki ki-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>N° pi&egrave;ce d'identit&eacute; *</label>
                                        <input type="text" class="form-control" name="id_card_recipient" id="id_card_recipient" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="send_money_id">Code secret * </label>
                                        <div class="input-group">
                                            <select class="form-control" id="send_money_id" name="send_money_id" required>
                                                <option value=""> Selectionner le code secret </option>
                                                @foreach($moneySents as $moneySent)
                                                    <option value="{{$moneySent->id}}"> {{$moneySent->secret_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Montant *</label>
                                        <input type="text" class="form-control" id="amountAff" placeholder="Montat du retrait" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <h3 class="pb-1 text-dark-75 font-weight-bolder font-size-h5 text-center">Exp&eacute;diteur</h3>
                                </div>
                                <hr/>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nom *</label>
                                        <input type="text" class="form-control" id="sender_name" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Pr&eacute;nom(s) *</label>
                                        <input type="text" class="form-control"  id="sender_surname" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact *</label>
                                        <input type="text" class="form-control"  id="sender_contact" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Pays *</label>
                                        <input type="text" class="form-control" id="sender_country" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <h3 class="pb-1 text-dark-75 font-weight-bolder font-size-h5 text-center">Destinataire</h3>
                                </div>
                                <hr/>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nom *</label>
                                        <input type="text" class="form-control"  id="recipient_name" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Pr&eacute;nom(s) *</label>
                                        <input type="text" class="form-control"  id="recipient_surname" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact *</label>
                                        <input type="text" class="form-control" id="recipient_contact" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Pays *</label>
                                        <input type="text" class="form-control" id="recipient_country" readonly>
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

<input type="hidden" id="role" value="{{Auth::user()->role}}">
<script type="text/javascript">
     var add = true;
     var $table = jQuery("#table"), rows = [];

    saoukiApp.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (withdrawalMoney) {
            $scope.withdrawalMoney = withdrawalMoney;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.withdrawalMoney = {};
        };
    });

    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });

        $('#send_money_id').select2({width: '100%'});

        $('#kt_datetimepicker_2').datetimepicker({
            locale: 'fr',
            formatTime: 'H:mm',
            formatDate: 'DD-MM-yyyy',
            format: 'DD-MM-yyyy H:mm',
            maxDate : new Date()
        });

        $("#send_money_id").change(function (e) {
            var send_money = $("#send_money_id").val();

            $.getJSON("../operation/find-send-money/" + send_money, function (reponse) {
                $.each(reponse.rows, function (index, sendMoney) { 
                    var amount = Intl.NumberFormat().format(sendMoney.amount);
                    $("#amount").val(sendMoney.amount);
                    $("#amountAff").val(amount);
                    $("#sender_name").val(sendMoney.sender.name);
                    $("#sender_surname").val(sendMoney.sender.surname);
                    $("#sender_contact").val(sendMoney.sender.contact);
                    $("#sender_country").val(sendMoney.sending_country.libelle_country);

                    $("#recipient_name").val(sendMoney.recipient.name);
                    $("#recipient_surname").val(sendMoney.recipient.surname);
                    $("#recipient_contact").val(sendMoney.recipient.contact);
                    $("#recipient_country").val(sendMoney.destination_country.libelle_country);
                    $("#id_recipient").val(sendMoney.recipient.id);
                });
            });
        });
        
        $("#btnModalAjout").on("click", function () {
            $("#send_money_id").val('').trigger('change');
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();

            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var methode = 'POST';
            var url = "{{route('operation.withdrawal-money.store')}}";
            editerWithdrawalMoneyAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });
    });

    function updateRow(idWithdrawalMoney) {
        var $scope = angular.element($("#formAjout")).scope();
        var withdrawalMoney =_.findWhere(rows, {id: idWithdrawalMoney});
        $scope.$apply(function () {
            $scope.populateForm(withdrawalMoney);
        });
        $("#id").val(withdrawalMoney.id);
        $("#withdrawalDate").val(withdrawalMoney.withdrawalDate);
        $("#amount").val(withdrawalMoney.amount);
        $("#id_card_recipient").val(withdrawalMoney.id_card_recipient);
        $("#send_money_id").val(withdrawalMoney.send_money_id).trigger('change');
        
        $(".bs-modal-ajout").modal("show");
    }

    function stateFormatter(state){
        if(state == "sent"){
            return "<span class='text-danger'>NON<span>";
        }
        if(state == "withdrawn"){
            return "<span class='text-success'>OUI<span>";
        }
    }
    function amountFormatter(amount){
        return Intl.NumberFormat().format(amount);
    }
    function senderFormatter(id, row){
        return row.senderName + " " + row.senderSurname;
    }
    function recipientFormatter(id, row){
        return row.recipientName + " " + row.recipientSurname;
    }

    function printRow(idSendMoney){
       window.open("recu-withdrawal-money/" + idSendMoney ,'_blank')
    }
    function recuFormatter(id, row){
        return '<a class="flaticon2-printer text-secondary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Imprimer" onClick="javascript:printRow(' + row.id + ');"></a>'; 
    }
 
    function optionFormatter(id, row) {
        return '<a class="flaticon2-pen text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + row.id + ');"></a>';
    }

    function editerWithdrawalMoneyAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg,$table) {
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
                    $("#send_money_id").val('').trigger('change');
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
