<?php


$header = "<th>" . implode("</th><th>", $this->datosgenerales['header']) . "</th>";
$datacolumns = "[" . implode(",", $this->datosgenerales['columns']) . "]";

?>



<form  class="form-inline">
    <div class="form-group">
        <label for="first_name" class="sr-only">Nombre</label>
        <input id='c_nit' type="text" class="form-control" data-fr-type='string'  data-fr-input='input' placeholder="Nombre">
    </div>
    <div class="form-group">
        <label for="last_name" class="sr-only">Direccion</label>
        <input id='c_direccion' type="text" class="form-control" data-fr-type='string' data-fr-input='input' placeholder="Direccion">
    </div>
    <div class="form-group">
        <label for="last_name" class="sr-only">Consecutivo</label>
        <input type="text" class="form-control" data-fr-type='input' data-fr-input='input' id="c_telefono" placeholder="Consecutivo">
    </div>
    <button type="button" class="btn btn-default" data-method="fr_searchDataTable" data-datatable="fr_mainTable">Buscar</button>
    <button type="button" class="btn btn-default" data-method="fr_clearsearchDataTable" data-datatable="fr_mainTable">Limpiar</button>
</form>


<table id="mainTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <?php echo $header ?>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <?php echo $header ?>
    </tr>
    </tfoot>
</TABLE>



<script>


    $(document).ready(function() {
        $('#mainTable').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                 "url": "http://localhost/centrek/administrator/index.php?option=com_certilab&task=generador.getList&menu=507",
                "type": "POST",
                "data" :  function ( d ) {
                    if (fr_datatables.data.length > 0) {
                        d.arraydecondiciones = fr_datatables.data
                    }
                }
            },
            "columns": <?php echo $datacolumns ?>,
            "language": {
            "processing": "<span><img src='<?php echo JURI::base( true ) ?>/templates/fratris/images/loading.gif'>Loading</span>"
        }
        } );
    } );

</script>
