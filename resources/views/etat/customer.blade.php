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
                                <label for="theme_id">Recherche par nom ou p&eacute;nom(s)</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Ibrahim" id="searchByName">
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
                                <!--a href="" class="btn btn-outline-success mr-4 text-right"><i class="la la-file-excel icon-lg"></i> Exporter</a-->
                                <a class="btn btn-outline-warning text-right" onclick="imprimePdf()"><i class="la la-file-pdf icon-lg"></i> Imprimer</a>
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-6">
                            <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                                <div class="alert-text">
                                    <h4 class="text-dark">
                                        D&eacute;p&ocirc;t total : <span class="text-success totalEnvoi">0</span> F CFA
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="alert alert-custom alert-outline-primary  fade show mb-2" role="alert">
                                <div class="alert-text">
                                    <h4 class="text-dark">
                                        Retrait total : <span class="text-danger totalRetrait">0</span> F CFA
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
                            data-url="{{ url('parametre', ['action' => 'list-operation-customers']) }}"
                            data-unique-id="id"
                            data-show-columns="false"
                            data-show-toggle="false">
                            <thead>
                                <tr role="row">
                                    <th data-field="name">Nom</th>
                                    <th data-field="surname">Pr&eacute;nom(s)</th>
                                    <th data-field="contact">Contact</th>
                                    <th data-field="country.libelle_country">Pays</th>
                                    <th data-field="allDeposit" data-formatter="amountFormatter">Envoi Total</th>
                                    <th data-field="allWithdrawal" data-formatter="amountFormatter">Retrait Total</th>
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
           var totalEnvoi = Intl.NumberFormat().format(data.totalEnvoi);
           var totalRetrait = Intl.NumberFormat().format(data.totalRetrait);
           $(".totalEnvoi").html(totalEnvoi);
           $(".totalRetrait").html(totalRetrait);
       });

       $('#searchByCountry').select2({width: '100%'});

       
       $("#searchByName").keyup(function (e) {
            var name = $("#searchByName").val();
            if(name == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('parametre', ['action' => 'list-operation-customers'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../parametre/list-operation-customers/' + name});
            }
        });

        $("#searchByCountry").change(function (e) {
            var country = $("#searchByCountry").val();
            $("#searchByName").val("");
            if(country == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('parametre', ['action' => 'list-operation-customers'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../parametre/list-operation-customers-by-country/' + country});
            }
        });

    });

    function amountFormatter(amount){
        return Intl.NumberFormat().format(amount);
    }

    function imprimePdf(){
        var country = $("#searchByCountry").val();
        if(country == 0){
            window.open("list-operation-customers-pdf","_blank");
        }else{
            window.open("list-operation-customers-by-country-pdf/" + country,"_blank");
        }
    }
    
    function ficheCustomer(idCustomer){
        window.open("../parametre/fiche-customer/" + idCustomer,"_blank");
    }

   function ficheFormatter(id, row) {
       return '<a class="flaticon2-list text-primary cursor-pointer mr-4 ml-2" data-toggle="tooltip" title="Fiche du client" onClick="javascript:ficheCustomer(' + row.id + ');"></a>';
   }
</script>
@endsection
