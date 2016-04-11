<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$tiposdecamposdedatatable = array();
if (!empty($displayData['campos'])) {
    $camposdatatable = array();
    $encabezado = array();
    $botones = array();
    $acciones_id = array();
    $i = 0;

    /*
        foreach ($displayData['campos'] as $campo) {
            if ($campo['listar']) {
                $encabezado[] = "<th width='{$campo['width']}%'> {$campo['descripcion']} </th>";
                $ordenar = $campo['ordenar'] == 0 ? "false" : "true";
                $buscar = $campo['buscar'] == 0 ? "false" : "true";
                $formato = trim($campo['formato']) == "" ? "" : ",\"type\":\"{$campo['formato']}\"";
                $camposdatatable[] = "{ \"searchable\": " . $ordenar . ", \"orderable\": " . $ordenar . ", \"name\": \"" . $campo['descripcion'] . "\" ,\"data\": \"" . $campo['id'] . "\" " . $formato . "}\r\n\t\t";
                $tiposdecamposdedatatable[] = "['{$i}', '{$campo['id']}','{$campo['campo']}','buscar_{$campo['id']}','{$i}']";
                $i++;
            }
        }
    */
    foreach ($displayData['campos'] as $campo) {
        if ($campo['listar'] && $campo['pantalla'] == 'Encabezado') {
            $encabezado[] = "<th width='{$campo['width']}%'> {$campo['descripcion']} </th>";
            $ordenar = $campo['ordenar'] == 0 ? "false" : "true";
            $buscar = $campo['buscar'] == 0 ? "false" : "true";
            $formato = trim($campo['formato']) == "" ? "" : ",\"type\":\"{$campo['formato']}\"";
            $camposdatatable[] = "{ \"searchable\": " . $ordenar . ", \"orderable\": " . $ordenar . ", \"name\": \"" . $campo['descripcion'] . "\" ,\"data\": \"" . $campo['id'] . "\" " . $formato . "}\r\n\t\t";
            $tiposdecamposdedatatable[] = "['{$i}', '{$campo['id']}','{$campo['campo']}','buscar_{$campo['id']}','{$i}']";
            $i++;
        }
    }


    /*
        foreach ($displayData['links'] as $campo) {
            if (!$campo['boton']) {
                $encabezado[] = "<th width='5%'> {$campo['descripcion']} </th>";
                $camposdatatable[] = "{ \"orderable\": false, \"data\": \"{$campo['descripcion']}\" ,\"data\": \"{$campo['descripcion']}\" }\r\n\t\t";
            } else {
                $campodescripcion = str_replace("_", " ", $campo['descripcion']);
                $botones[] = "<a href='#' class='btn default red-stripe' id='lnk_{$campo['descripcion']}'>
                                <i class='{$campo['icono']}'></i>
                                <span class='hidden-480' >{$campodescripcion}</span>
                            </a>";
            }
        }
    */
    foreach ($displayData['links'] as $campo) {
        if (!$campo['boton']) {
            $encabezado[] = "<th width='5%'> {$campo['descripcion']} </th>";
            $camposdatatable[] = "{ \"orderable\": false, \"data\": \"{$campo['descripcion']}\" ,\"data\": \"{$campo['descripcion']}\" }\r\n\t\t";
        } else {
            $campodescripcion = str_replace("_", " ", $campo['descripcion']);
            $id = str_replace(' ', '_', $campo['descripcion']);
            $botones[] = "<a href='#' class='btn default red-stripe' data-facturacion-tipo ='accion' data-facturacion-accion ='{$campo['modelo']}' >
                            <i class='{$campo['icono']}' data-facturacion-tipo ='accion' data-facturacion-accion ='{$campo['modelo']}'  ></i>
                            <span class='hidden-480' data-facturacion-tipo ='accion' data-facturacion-accion ='{$campo['modelo']}'  >{$campodescripcion}</span>
                        </a>";
        }
    }


    $tiposdecamposdedatatable = implode(",\n\t\t", $tiposdecamposdedatatable);

    $acciones = implode("\n", $botones);
    $doc = JFactory::getDocument();
    $doc->camposdatatable = implode(",", $camposdatatable);
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecamposdedatatable : $tiposdecamposdedatatable;


}


?>

<div id='duplicar_modal' class='modal fade' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
                <h4 class='modal-title'>Seleccionar</h4>
            </div>
            <div class='modal-body form'>
                <form action='#' class='form-horizontal form-row-seperated' id='form_buscar'>
                    <div class='form-group' id='grupo_l_nombredelalista'>
                        <label class='control-label col-md-3' for='l_nombredelalista'><strong>Nombre de la lista</strong></label>
                        <div class='col-md-6'>
                            <div class='input-group input-medium'>
                                <input class='form-control' id='l_nombredelalista' placeholder='Escriba Nombre de la lista' >
                                <span class='input-group-addon'><i class='fa fa-user'></i></span>
                            </div>
                        </div>
                    </div>

                            <div class='form-group' id='grupo_l_incremento'>
                                <label class='control-label col-md-3' for='l_incremento'><strong>Incremento</strong></label>
                                <div class='col-md-6'>
                                    <div class='input-group input-medium'>
                                        <input class='form-control' id='l_incremento' name='crear_l_descripcion'>
                                        <span class='input-group-addon'><i class='fa fa-user'></i> </span>
                                    </div>
</div>
                                </div>

                    <div class='form-group' id='grupo_l_tipoincremento'  >
                        <label class="control-label col-md-3"><strong>Tipo Incremento</strong></label>
                        <div class="col-md-9">
                            <input type="checkbox" id='l_tipoincremento' class="make-switch" data-size="normal" checked data-off-text="Porcentaje" data-on-text="Valor" >

                        </div>
                    </div>

                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-primary' data-facturacion-tipo="accion"
                        data-facturacion-accion="cancelarduplicarmuestras"><i class='fa fa-check'></i>Cancelar
                </button>
                <button type='button' class='btn btn-primary' data-facturacion-tipo="accion"
                        data-facturacion-accion="duplicarlista" id='bt_form_duplicar_duplicar'><i
                        class='fa fa-check'></i>Duplicar
                </button>
            </div>
        </div>
    </div>
</div>




<div class="portlet" id="tabla" data-facturacion-tipo="pantalla" data-facturacion-pantalla="principal">

    <div class="portlet-title">
        <div class="caption">
            <ul class="list-inline" id="filtradopor">
                <li>
                    <i class="fa fa-list"></i>Listado
                </li>
            </ul>
        </div>

        <div class="actions">
            <!-- Botones de opciones -->
            <?php echo $acciones ?>
            <div class="btn-group">
                <a class="btn default yellow-stripe" href="#" data-toggle="dropdown">
                    <i class="fa fa-share"></i>
                    <span class="hidden-480">Utilidades</span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="#">
                            <i class="fa fa-file-excel-o"></i>
                            <span class="hidden-480">Exportar a Excel </span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-file-text-o"></i>
                            <span class="hidden-480">Exportar a CSV</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-file-code-o"></i>
                            <span class="hidden-480">Exportar a XML</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <div class="table-container">
            <div class="table-actions-wrapper">
                <span></span>
                <select class="table-group-action-input form-control input-inline input-small input-sm">
                    <option value="">Select...</option>
                    <option value="Cancel">Cancel</option>
                    <option value="Cancel">Hold</option>
                    <option value="Cancel">On Hold</option>
                    <option value="Close">Close</option>
                </select>
                <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i>Submit</button>
            </div>
            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                <thead>
                <tr role="row" class="heading" id="dtencabezado">
                    <?php echo implode("\n\t", $encabezado) ?>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div id=>