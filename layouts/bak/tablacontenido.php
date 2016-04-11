<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


// componentes de datatables
//$document = JFactory::getDocument();
//$document->addScript('media/com_invoices/jquery.serializejson.min',true);
//$document->addScript('//cdn.datatables.net/responsive/1.0.4/js/dataTables.responsive.js',true);


$tabla = <<<'TABLA'

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading text-center">
        <div>Listar #TITULOPANEL# </div>
        <div>#MASTERDETAIL#</div>
  </div>
  <div class="panel-body">

          <table id="maintb" class=" display table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
                <tr>
                 #ENCABEZADO#
                </tr>
            </thead>
            <tfoot>
                <tr>
                 #PIE#
                </tr>
            </tfoot>

        </table>
        <div>

            <?php echo JHtml::_('form.token') ?>
        </div>

</div>
TABLA;


$encabezado = "";
$validadores = array();
$campos = "";
$titulomenu = "";
// Crear los campos de encabezados de Datatable, por cada campo del query getCamposDeDatatable()
// se crea un th en la tabla que luego llenara Datatables. ($encabezado)
// De igual forma se creara una definicion de campo, para que la use Datatables ($campos)
foreach ($displayData['campos'] as $campo) {
    $titulomenu = str_replace("-", " ", $campo['titulomenu']);
    if ($campo['listar']) {
        $encabezado .= "<th>" . $campo['descripcion'] . "</th>\r\n\t\t\t";
        $ordenar = $campo['ordenar'] == 0 ? "false" : "true";
        $buscar = $campo['buscar'] == 0 ? "false" : "true";
        $formato = trim($campo['formato']) == "" ? "" : ",\"type\":\"{$campo['formato']}\"";
        $campos[] = "{ \"searchable\": " . $ordenar . ", \"orderable\": " . $ordenar . ", \"name\": \"" . $campo['descripcion'] . "\" ,\"data\": \"" . $campo['id'] . "\" " . $formato . "}\r\n\t\t";
    }
    $validador = array();
    $camposdefecha = array();
    if ($campo['editar'] > 0) {
        if ($campo['requerido'] > 0) {
            $validador[] = "  notEmpty:{ message: '{$campo['descripcion']} es requerido'}\n\t";
            $tipodecampo = $campo['tipo'];
            switch ($tipodecampo) {
                case 'int':
                    $validador[] = "\t\t\t integer: { message: '{$campo['descripcion']} debe ser numerico' }\r ";
                    break;
                case 'decimal':
                    $validador[] = "\t\t\t numeric: { message: '{$campo['descripcion']} debe ser numerico' }\r ";
                    break;
                case 'datetime':
                    $camposdefecha = $campo['id'];
                    $validador[] = "  \t\t\t date: {format: 'YYYY-MM-DD h:m:s',message: 'La fecha no es valida' }\r ";
                    break;
                case 'varchar':
                    $validador[] = "\t\t\t stringLength: {
                                max: {$campo['longitud']},
                            message: '{$campo['descripcion']} debe tener menos de {$campo['longitud']} letras'}\r";

                    break;
            }
        } else {
            $campoavalidar = '$field';
            $validador[] = "callback: {callback: function(value, validator, {$campoavalidar}) {return true;}}\n\t";
        }
    }

    if (!empty($validador)) {
        $validadores[] = "  \n\t{$campo['id']}: {
                               validators:{" . implode(",", $validador)
            . "}
                                 }\r";
    }

}

$validadores = implode(',', $validadores);

/**
 * Crear los campos de encabezados de cadalink en el Datatable, por cada campo del query getLinks() se crea un th en la tabla que luego llenara Datatables. ($encabezado)
 * De igual forma se creara una definicion de campo, para que la use Datatables ($campos)
 **/
foreach ($displayData['links'] as $campo) {
    if (!$campo['boton']) {
        $encabezado .= "<th>" . $campo['descripcion'] . "</th>\r\n\t\t\t";
        $campos[] = "{ \"orderable\": false, \"data\": \"{$campo['descripcion']}\" ,\"data\": \"{$campo['descripcion']}\" }\r\n\t\t";
    }
}


$campos = implode(",", $campos);

// definicion de encabezadoy pie de pagina Datatatble
$tabla = str_replace('#ENCABEZADO#', $encabezado, $tabla);
$tabla = str_replace('#PIE#', $encabezado, $tabla);


$masterdetail = "";
if ($displayData['datosmaster']) {
    $datosmaster = $displayData['datosmaster'];
    $encabezados = array_keys($displayData['datosmaster']);
    $glyphicon = '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>';
    foreach ($encabezados as $encabezado) {
        $masterdetail .= " {$glyphicon} {$encabezado}: {$datosmaster[$encabezado]}\n";
        $glyphicon = '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
    }
}
$tabla = str_replace('#MASTERDETAIL#', $masterdetail, $tabla);
$tabla = str_replace('#TITULOPANEL#', ucfirst($titulomenu), $tabla);


echo $tabla;


// Crear la url para obtener los datos de json
$urlgettable = "'" . $displayData['urlactual'] . "&task=listar.getTable&json=1&" . JSession::getFormToken() . "=1 '";

// Crear la url para boton crear
$urlcrear = "'" . $displayData['urlactual'] . "&task=listar.crear&" . JSession::getFormToken() . "=1 '";



?>

<script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/responsive/1.0.4/js/dataTables.responsive.js"></script>
<script src="../media/com_invoices/js/jquery.serializejson.min.js"></script>;
<script src="../media/com_invoices/js/formValidation.min.js"></script>;
<script src="../media/com_invoices/js/bootstrap.min.js"></script>;
<script>

var tabla;

<?php echo $displayData['vector'] ?>

function cambiarEstado(jsonData) {
    for (i in jsonData) {
        console.log(i.substring(2) + " : "+ jsonData[i]);
        if(i.substring(2) == 'estado')
        if (jsonData[i]== 'on' ){
             jsonData[i]= 1;
        } else {
             jsonData[i]= 0;
        }
    }
}

function EnviarData($forma) {
    jQuery("#btGuardarForma").attr("disabled", true);
    var data = jQuery('#Send_Data').serializeJSON();
    console.log(data);
    cambiarEstado(data);
    console.log(data);
    var saveData = jQuery.ajax({
        type: 'POST',
        url: <?php echo $urlcrear ?>,
        timeout: 10000,
        data: data,
        dataType: 'json',
        success: function (resultData) {
            console.log('resultado de actualizacion');
            console.log(resultData);
            if (resultData.hasOwnProperty('resultado')) {
                jQuery('#btCerrarFormaUp').trigger('click');
                jQuery('#maintb').DataTable().draw();
            }
        },
        error: function (x, t, m) {
            if (t === "timeout") {
                alert("Esta demorando demasiado");
            } else {
                alert('error');
                alert(t);
            }
        }
    });

    saveData.always(function () {
        jQuery("#btGuardarForma").attr("disabled", false);
    });
}

function Editar(item) {
    showForm('Editar', '1');
    jQuery("#Send_Data")[0].reset();
    jQuery("[id*=_id]").val(0);
    id = jQuery(item).closest('tr');
    datos = tabla.row(id).data();
    console.log(datos);
    Object.keys(datos).forEach(function (key) {
        var id = '#frm_' + key;
        if (isCheckBox(id)) {
            if (datos[key] > 0) {
                jQuery(id).attr("checked", true);
            } else {
                jQuery(id).attr("checked", false);
            }
        } else {
            jQuery(id).val(datos[key]);
        }
        jQuery(id).change();
    });
}

function isCheckBox(id) {
    var $result = jQuery(id);
    return $result[0] && $result[0].type === 'checkbox';
}

function showForm(accion, id) {
    jQuery('#myModal').modal('show');
    jQuery('#tituloforma').text(accion + ' <?php echo ucfirst($titulomenu) ?>');
    if (id)
        jQuery('#tarea').val('Editar');
    else
        jQuery('#tarea').val('Crear');
}

function reemplazaropciones(valor, idtarget, vector, condicion) {
    jQuery(idtarget).html(""); //reset child options
    opciones = jQuery.map(vector, function (val, key) {
        if (val[condicion] == valor)
            return val;
    });
    jQuery(opciones).each(function (i) { //populate child options
        jQuery(idtarget).append("<option value=\"" + opciones[i].id + "\">" + opciones[i].valor + "</option>");
    });
    jQuery(idtarget).change();
}

/*
 Limpiar en Datatables las columnas de busqueda y hacer su valor vacio
 Buscar si los input de busqeda tienen datos y actualizar en Datatables
 columna de busqueda correspondiente
 */

function filterColumn() {
    jQuery("input[id^='cols_']").each(function (i, el) {
        var id = jQuery(el).attr('id');
        var indice = id.split("_");
        var i = indice[1];
        var selector = '#' + id.replace("cols", "sel");
        var operador = jQuery(selector).val();
        var valor = jQuery(el).val();
        valor = valor.trim();
        if (valor != "") {
            var campo = indice[2] + "_" + indice[3] + "#" + operador + "#" + valor;
            jQuery('#maintb').DataTable().column(i).search(campo, false, true);
        } else
            jQuery('#maintb').DataTable().column(i).search('', false, true);
    });
    jQuery('#maintb').DataTable().draw();
}

/*
 Limpiar los inut de busqueda
 */
function resetinput() {
    jQuery("input[id^='cols_']").each(function (i, el) {
        jQuery(el).val('');
        var id = jQuery(el).attr('id');
        var datos = id.split('_');
        var i = datos[1];
        jQuery('#maintb').DataTable().column(i).search('', false, true);
    });
    jQuery('#maintb').DataTable().draw();
}


jQuery(document).ready(function () {
    jQuery('head').append('<link rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.css" type="text/css" />');
    jQuery('head').append('<link rel="stylesheet" href="//cdn.datatables.net/responsive/1.0.4/css/dataTables.responsive.css" type="text/css" />');
    jQuery('head').append('<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" type="text/css" />');
    jQuery('head').append('<link rel="stylesheet" href="../media/com_invoices/css/formValidation.min.css" type="text/css" />');

    tabla = jQuery('#maintb').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "columns": [<?php echo $campos ?>],
        "ajax": {
            "url": <?php echo $urlgettable ?>,
            "data": {vistalo: "vista", desvistalo: "desvistalo"}
        },
        "fnCreatedRow": function (nRow, aData, iDataIndex) {
            //nRow.cells[0].innerHTML = '<div class="checkbox"><label><input type="checkbox" class="checkbox" value=""><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></label></div>';
        }
    });

    jQuery('#btSearch').click(function (e) {
        filterColumn();
        jQuery('#collapseAyuda').collapse('hide');
        return false;
    });

    jQuery('#btReset').click(function (e) {
        resetinput();
        jQuery('#collapseAyuda').collapse('hide');
        return false;
    });

    jQuery('#btCrear').click(function (e) {
        showForm('Crear', 0);
        jQuery("#Send_Data")[0].reset();
        jQuery("[id*=_id]").val(0);
        jQuery('#frm_<?php echo $displayData['keyactual'][0] ?>').val(0).val(<?php echo $displayData['keyactual'][1] ?>).change();
        return true;
    });

    jQuery('#btReferencia').click(function (e) {
        jQuery('#myModal').modal('show');
        var table = jQuery('#maintb').DataTable();
        datos = table.rows().data();
        for (i = 0; i < datos.length; i++) {
            var row1 = table.row(i).data();
            jQuery("#frm_" + row1.c_componente).val(row1.d_idcomponentevalor);
            jQuery("#descripcion_" + row1.c_componente).val(row1.d_descripcion);
        }
    });


    jQuery('#btGuardarForma').click(function (e) {
        jQuery("#Send_Data").submit();
        return true;
    });

    jQuery('#myModal').on('shown.bs.modal', function () {
        jQuery('#Send_Data').formValidation('resetForm', false);

    });

    <?php echo $displayData['script'] ?>

    jQuery('#Send_Data')
        // IMPORTANT: You must declare .on('init.field.fv')
        // before calling .formValidation(options)
        .on('init.field.fv', function (e, data) {
            // data.fv      --> The FormValidation instance
            // data.field   --> The field name
            // data.element --> The field element

            var $parent = data.element.parents('.form-group'),
                $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');

            // You can retrieve the icon element by
            // $icon = data.element.data('fv.icon');

            $icon.on('click.clearing', function () {
                // Check if the field is valid or not via the icon class
                if ($icon.hasClass('glyphicon-remove')) {
                    // Clear the field
                    data.fv.resetField(data.element);
                }
            });
        })

        .formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                <?php echo $validadores ?>
            }
        })

        .on('success.form.fv', function (e) {
            // Prevent form submission
            e.preventDefault();

            var $forma = jQuery(e.target),
                fv = $forma.data('formValidation');

            EnviarData($forma);


        });


});



</script>
