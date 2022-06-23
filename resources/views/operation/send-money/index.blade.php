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
                            data-url="{{ url('operation', ['action' => 'list-send-money']) }}"
                            data-unique-id="id"
                            data-show-columns="true"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    @if(Auth::user()->role == "Agent")
                                    <th data-field="id" data-formatter="recuFormatter">re&ccedil;u</th>
                                    @endif
                                    <th data-field="sendDate">Date</th>
                                    <th data-field="secret_code" data-search="true">Code secret</th>
                                    <th data-field="amount" data-formatter="amountFormatter">Montant</th>
                                    <th data-field="shipping_cost" data-formatter="amountFormatter" data-visible="false">Frais</th>
                                    <th data-field="discount_on_shipping_costs" data-formatter="amountFormatter" data-visible="false">Remise sur frais</th>
                                    <th data-formatter="shippingCostPaidFormatter">Frais pay&eacute;</th>
                                    <th data-formatter="totalFormatter">Total pay&eacute;</th>
                                    <th data-formatter="senderFormatter">Exp&eacute;diteur</th>
                                    <th data-formatter="recipientFormatter">Destinataire</th>
                                    <th data-field="destination_country.libelle_country">Pays de destinat.</th>
                                    @if(Auth::user()->role != "Agent")
                                    <th data-field="libelle_agency" data-visible="false">Agence</th>
                                    <th data-field="created_by.name" data-visible="false">Caissier</th>
                                    @endif
                                    @if(Auth::user()->role == "Agent")
                                    <th data-formatter="autrorizedFormatter" data-visible="false">Autorisation</th>
                                    @endif
                                    <th data-field="state" data-formatter="stateFormatter">Pay&eacute;</th>
                                    @if(Auth::user()->role == "Agent")
                                    <th data-formatter="optionAgentFormatter" data-width="60px" data-align="center"><i class="ki ki-wrench"></i></th>
                                    @endif
                                    @if(Auth::user()->role == "Superviseur" or Auth::user()->role == "Comptable")
                                    <th data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="ki ki-wrench"></i></th>
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
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="overlay overlay-block">
                    <form id="formAjout" ng-controller="formAjoutCtrl" action="#" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Gestion des envoies d'argent</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" ng-hide="true" name="id" id="id">
                            <input type="text" ng-hide="true" name="amountTotal" id="amountTotal">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date d'envoie *</label>
                                        <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" id="sendDate" name="sendDate" data-target="#kt_datetimepicker_2" value="{{date('d-m-Y H:i')}}" required>
                                            <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                <span class="input-group-text">
                                                    <i class="ki ki-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Montant &agrave; envoyer *</label>
                                        <input type="text" pattern="[0-9]+" min="0" class="form-control" name="amount" id="amount" placeholder="Montat" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group mt-6 costs_included">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label>
                                                <input type="checkbox" name="shipping_costs_included" id="shipping_costs_included" ng-checked="sendMoney.shipping_costs_included==1" onchange='handleChange(this);'/><span></span>
                                            </label>
                                            <label for="shipping_costs_included">&nbsp;Cochez si le frais d'envoie est inclus</label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Frais d'envoie *</label>
                                        <input type="number" class="form-control" name="shipping_cost" id="shipping_cost" placeholder="Montat frais d'envoie" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Remise sur frais </label>
                                        <input type="number" class="form-control" name="discount_on_shipping_costs" id="discount_on_shipping_costs" placeholder="Remise sur frais d'envoie">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="destination_country_id">Pays de destination * </label>
                                        <div class="input-group">
                                            <select class="form-control" id="destination_country_id" name="destination_country_id" required>
                                                <option value=""> Selectionner le pays </option>
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}"> {{$country->libelle_country}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Montant total à payer *</label>
                                        <input type="text" class="form-control" id="amountTotalAff" placeholder="Montat" readonly>
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
                                        <label for="sender_id">Slectionner l'exp&eacute;diteur s'il existe dans la liste </label>
                                        <div class="input-group">
                                            <select class="form-control" id="sender_id" name="sender_id">
                                                <option value=""> Selectionner l'exp&eacute;diteur </option>
                                                @foreach($customers as $sender)
                                                    <option value="{{$sender->id}}"> {{$sender->name.' '.$sender->surname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nom *</label>
                                        <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" name="sender_name" id="sender_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Pr&eacute;nom(s) *</label>
                                        <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" name="sender_surname" id="sender_surname" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact *</label>
                                        <input type="text" class="form-control" name="sender_contact" id="sender_contact" required>
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
                                        <label for="recipient_id">Slectionner le destinataire s'il existe dans la liste  </label>
                                        <div class="input-group">
                                            <select class="form-control" id="recipient_id" name="recipient_id">
                                                <option value=""> Selectionner le destinataire </option>
                                                @foreach($customers as $recipient)
                                                    <option value="{{$recipient->id}}"> {{$recipient->name.' '.$recipient->surname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nom *</label>
                                        <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" name="recipient_name" id="recipient_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Pr&eacute;nom(s) *</label>
                                        <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" name="recipient_surname" id="recipient_surname" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact *</label>
                                        <input type="text" class="form-control" name="recipient_contact" id="recipient_contact" required>
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
                            <input type="text" ng-hide="true" id="idSendMoneyDelete" value="@{{ sendMoney.id }}">
                            @csrf
                            @if(Auth::user()->role == "Agent")
                            <p class="text-center text-muted h5">Etes vous certain de vouloir supprimer cet enregistrement ?</p>
                            <p class="text-center h4">@{{ sendMoney.secret_code}}</p>
                            @endif
                            @if(Auth::user()->role != "Agent")
                            <p class="text-center text-muted h5">Etes vous certain de vouloir autoriser la suppression de cet enregistrement ?</p>
                            <p class="text-center h4">@{{ sendMoney.secret_code}}</p>
                            @endif
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
<input type="hidden" id="role" value="{{Auth::user()->role}}">
<script type="text/javascript">
     var add = true;
     var $table = jQuery("#table"), rows = [];

    saoukiApp.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (sendMoney) {
            $scope.sendMoney = sendMoney;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.sendMoney = {};
        };
    });

    saoukiApp.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (sendMoney) {
            $scope.sendMoney = sendMoney;
        };
        $scope.initForm = function () {
            $scope.sendMoney = {};
        };
    });

    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });

        $('#destination_country_id, #recipient_id, #sender_id').select2({width: '100%'});

        $('#kt_datetimepicker_2').datetimepicker({
            locale: 'fr',
            formatTime: 'H:mm',
            formatDate: 'DD-MM-yyyy',
            format: 'DD-MM-yyyy H:mm',
            maxDate : new Date()
        });

        $("#sender_id").change(function (e) {
            var sender = $("#sender_id").val();
            //get sender info
            if(sender != ""){
                $.getJSON("../parametre/find-customer/" + sender, function (reponse) {
                    $.each(reponse.rows, function (index, customer) { 
                        $("#sender_name").val(customer.name);
                        $("#sender_surname").val(customer.surname);
                        $("#sender_contact").val(customer.contact);
                    });
                });
            }else{
                $("#sender_name").val("");
                $("#sender_surname").val("");
                $("#sender_contact").val("");
            }
        });

        $("#recipient_id").change(function (e) {
            var recipient = $("#recipient_id").val();
            //get recipient info
            if(recipient != ""){
                $.getJSON("../parametre/find-customer/" + recipient, function (reponse) {
                    $.each(reponse.rows, function (index, customer) { 
                        $("#recipient_name").val(customer.name);
                        $("#recipient_surname").val(customer.surname);
                        $("#recipient_contact").val(customer.contact);
                    });
                });
            }else{
                $("#recipient_name").val("");
                $("#recipient_surname").val("");
                $("#recipient_contact").val("");
            }
        });

        $("#destination_country_id").change(function (e) {

            $("#amountTotalAff").val("");
            $("#amountTotal").val("");
            var amount = $("#amount").val();
            var shipping_cost = $("#shipping_cost").val();

            if(amount != "" && shipping_cost != ""){
                var discount_on_shipping_costs = $("#discount_on_shipping_costs").val() != "" ? $("#discount_on_shipping_costs").val() : 0;
                var amountTotal = parseInt(amount) + parseInt(shipping_cost) - parseInt(discount_on_shipping_costs);
                var totalPaye = Intl.NumberFormat().format(amountTotal); 
                $("#amountTotalAff").val(totalPaye);
                $("#amountTotal").val(amountTotal);
            }
        });

        $("#amount").keyup(function (e) {
            $("#shipping_cost").val("");
            $("#discount_on_shipping_costs").val("");
            $("#shipping_costs_included").prop("checked", false);
            $("#destination_country_id").val("").trigger('change');
            $("#amountTotal").val("");
            $("#amountTotalAff").val("");
        });

        $("#discount_on_shipping_costs").keyup(function (e) {
            $("#amountTotal").val("");
            $("#destination_country_id").val("").trigger('change');
            $("#amountTotalAff").val("");
        });
        
        $("#btnModalAjout").on("click", function () {
            $("#destination_country_id, #sender_id, #recipient_id").val('').trigger('change');
            $('#amount, #shipping_cost, #discount_on_shipping_costs').prop('readOnly', false);
            $(".costs_included").show();
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();

            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var methode = 'POST';
            var url = "{{route('operation.send-money.store')}}";
            editerSendMoneyAction(methode, url, $(this), $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var $overlayBlock = $(".overlay-block");
            var $spinnerLg = $(".spinner-lg");

            var id = $("#idSendMoneyDelete").val();

            deleteAction('send-money/' + id, $(this).serialize(), $overlayBlock, $spinnerLg, $table);
        });
    });

    function handleChange(checkbox) {
        var amount = $("#amount").val();
        $("#destination_country_id").val('').trigger('change');
        var frais = 0;
        var reste = 0;
        if(amount == ""){
            alert('Montant vide !');
            $("#shipping_costs_included").prop("checked", false);
            return false;
        }
        
        if(checkbox.checked == true){
            //get taux value
            let taux1 = 0; let taux2 = 0; let taux3 = 0;
            $.getJSON("../parametre/list-taux-transferts/", function (reponse) {
                $.each(reponse.rows, function (index, taux_transfert) { 
                    if(taux_transfert.interval_ligne == "Intervale 1"){
                        taux1 = taux_transfert.taux;
                    }
                    if(taux_transfert.interval_ligne == "Intervale 2"){
                        taux2 = taux_transfert.taux;
                    }
                    if(taux_transfert.interval_ligne == "Intervale 3"){
                        taux3 = parseInt(taux_transfert.montant_fixe);
                    }
                });
                
                //calcul auto du frais d'envoi
                if(amount > 300000){
                    if(amount > 500000){
                        var nbFois = 0;
                        do 
                            {
                                nbFois++;
                                amount -= 1000000;
                            }
                        while (amount > 500000);

                        if(amount > 300000 && amount <= 500000){
                            frais = parseInt(amount*taux2) + parseInt(taux3*nbFois);
                        }else if(amount <= 300000 && amount > 5000){
                            frais = parseInt(amount*taux1) + parseInt(taux3*nbFois);
                        }else{
                            frais = parseInt(taux3*nbFois);
                        }
                    }else{
                        frais = amount*taux2;  
                    }
                }else{
                    frais = amount*taux1;
                }
                $("#shipping_cost").val(frais);
            });
        }else{
            $("#shipping_cost").val("");
            $("#discount_on_shipping_costs").val("");
            $("#amountTotal").val("");
            $("#destination_country_id").val("").trigger('change');
        }
    }

    function updateRow(idSendMoney) {
        var role = $("#role").val();
        var $scope = angular.element($("#formAjout")).scope();
        var sendMoney =_.findWhere(rows, {id: idSendMoney});
        $scope.$apply(function () {
            $scope.populateForm(sendMoney);
        });
        $("#id").val(sendMoney.id);
        $("#sendDate").val(sendMoney.sendDate);
        $("#amount").val(sendMoney.amount);
        $("#shipping_cost").val(sendMoney.shipping_cost);
        $("#discount_on_shipping_costs").val(sendMoney.discount_on_shipping_costs);
        $("#destination_country_id").val(sendMoney.destination_country_id).trigger('change');
        $("#sender_id").val(sendMoney.sender_id).trigger('change');
        $("#recipient_id").val(sendMoney.recipient_id).trigger('change');
        var amountTotal = parseInt(sendMoney.amount) + parseInt(sendMoney.shipping_cost) - parseInt(sendMoney.discount_on_shipping_costs);
        var totalPaye = Intl.NumberFormat().format(amountTotal); 
        $("#amountTotalAff").val(totalPaye);
        $("#amountTotal").val(amountTotal);
        if(role == "Agent"){
            $('#amount, #shipping_cost, #discount_on_shipping_costs').prop('readOnly', true);
            $(".costs_included").hide();
        }
        //get sender info
        $.getJSON("../parametre/find-customer/" + sendMoney.sender_id, function (reponse) {
            $.each(reponse.rows, function (index, customer) { 
                $("#sender_name").val(customer.name);
                $("#sender_surname").val(customer.surname);
                $("#sender_contact").val(customer.contact);
            });
        });

        //get recipient info
        $.getJSON("../parametre/find-customer/" + sendMoney.recipient_id, function (reponse) {
            $.each(reponse.rows, function (index, customer) { 
                $("#recipient_name").val(customer.name);
                $("#recipient_surname").val(customer.surname);
                $("#recipient_contact").val(customer.contact);
            });
        });
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idSendMoney) {
        var $scope = angular.element($("#formSupprimer")).scope();
        var sendMoney =_.findWhere(rows, {id: idSendMoney});
        $scope.$apply(function () {
            $scope.populateForm(sendMoney);
        });
       $(".bs-modal-supprimer").modal("show");
    }

    function amountFormatter(amount){
        return Intl.NumberFormat().format(amount);
    }
    function stateFormatter(state){
        if(state == "sent"){
            return "<span class='text-danger'>NON<span>";
        }
        if(state == "withdrawn"){
            return "<span class='text-success'>OUI<span>";
        }
    }
    function senderFormatter(id, row){
        return row.sender.name + " " + row.sender.surname;
    }
    function recipientFormatter(id, row){
        return row.recipient.name + " " + row.recipient.surname;
    }
    function shippingCostPaidFormatter(id, row){
        let fraisPaye = parseInt(row.shipping_cost)-parseInt(row.discount_on_shipping_costs);
        return Intl.NumberFormat().format(fraisPaye);
    }  
    function totalFormatter(id, row){
        let totalPaye = parseInt(row.amount) + parseInt(row.shipping_cost)-parseInt(row.discount_on_shipping_costs);
        return Intl.NumberFormat().format(totalPaye);
    } 
    function autrorizedFormatter(id, row){
        if(row.to_delete == 1){
            return "<span class='text-success'>Suppression autorisé le "+row.authorizationDate+" par " + row.authorized_by.name + "<span>";
        }else{
            return "---";
        }
    }
    function printRow(idSendMoney){
       window.open("recu-money-send/" + idSendMoney ,'_blank')
    }
    function recuFormatter(id, row){
        if(row.state == "withdrawn"){
            return "---";
        }else{
            return '<a class="flaticon2-printer text-secondary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Imprimer" onClick="javascript:printRow(' + row.id + ');"></a>'; 
        }
    }
    function optionAgentFormatter(id, row){
        if(row.to_delete == 1){
            return '<a class="flaticon-delete text-danger cursor-pointer ml-2" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + row.id + ');"></a>';
        }
        if(row.state == "withdrawn"){
            return "---";
        }
        if(row.to_delete == 0 && row.state == "sent"){
            return '<a class="flaticon2-pen text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + row.id + ');"></a>';
        }     
    }
    function optionFormatter(id, row) {
        if(row.to_delete == 1){
            return "<span class='text-danger'>A supprimer<span>";
        }
        if(row.state == "withdrawn"){
            return "---";
        }
        if(row.to_delete == 0 && row.state == "sent"){
            return '<a class="flaticon2-pen text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + row.id + ');"></a>\n\<a class="flaticon-delete text-danger cursor-pointer ml-2" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + row.id + ');"></a>';
        }
    }

    function editerSendMoneyAction(methode, url, $formObject, formData, $overlayBlock, $spinnerLg,$table) {
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
