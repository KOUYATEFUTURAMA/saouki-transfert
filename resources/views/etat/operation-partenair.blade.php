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
                                <label for="theme_id">Recherche par partenaire</label>
                                <div class="input-group input-group-sm">
                                    <select class="form-control" id="searchByPartenaire">
                                        <option value="0"> Tous les partenaires</option>
                                        @foreach($partenairs as $partenair)
                                        <option value="{{$partenair->id}}"> {{$partenair->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <p class="text-right">
                                <a href="" class="btn btn-outline-success mr-4 text-right"><i class="la la-file-excel icon-lg"></i> Exporter</a>
                                <a class="btn btn-outline-warning text-right" onclick="imprimePdf()"><i class="la la-file-pdf icon-lg"></i> Imprimer</a>
                            </p>
                        </div>
                    </div>
                    <table id="table" class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                            data-pagination="true"
                            data-search="false"
                            data-toggle="table"
                            data-url="{{ url('operation', ['action' => 'list-operations-partenairs']) }}"
                            data-unique-id="id"
                            data-show-columns="false"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="id" data-formatter="recuFormatter">re&ccedil;u</th>
                                    <th data-field="dateOperation">Date</th>
                                    <th data-field="reference" data-searchable="true">R&eacute;f&eacute;rence</th>
                                    <th data-field="amount" data-formatter="amountFormatter">Montant</th>
                                    <th data-field="operation_type" data-formatter="typeFormatter">Type</th>
                                    <th data-field="partenair.name">Partenaire</th>
                                    <th data-field="partenair.contact">Contact</th>
                                    <th data-field="receptionist">Mandataire</th>
                                    <th data-formatter="stateFormatter">Etat</th>
                                    <th data-field="user.name">Caissier</th>
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

       $('#searchByPartenaire').select2({width: '100%'});

   });

   function amountFormatter(amount){
        return Intl.NumberFormat().format(amount);
    }
    function stateFormatter(id, row){
        if(row.state == "recorded" && row.operation_type == "withdrawal"){
            return "<span>En attente d'autorisation<span>";
        }
        if(row.state == "authorized"){
            return "<span class='text-success'>Autorisée le "+row.authorizationDate+" par " + row.authorized_by.name + "<span>";
        }
        if(row.state == "unauthorized"){
            return "<span class='text-danger'>Anulée<span>";
        }
    }
    function typeFormatter(type){
        if(type == "deposit"){
            return "<span class='text-success'>Dépôt<span>";
        }
        if(type == "withdrawal"){
            return "<span class='text-danger'>Retrait<span>";
        }
    }
</script>
@endsection
