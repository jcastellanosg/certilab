<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$jinput = JFactory::getApplication()->input;
$idcampo= $jinput->get('campo','','STRING');



if (!empty($displayData['campos'])) {
    $camposdecreacion = array();
    $camposdeedicion = array();
    $tiposdecampos = array();

    $arrayselect = array();

    /**********************************/
    foreach ($displayData['campos'] as $campo) {
        // Array's para creacion
        if ($campo['editar'] == 1 && trim($campo['formacreacion']) != '') {
            $tiposdecampos[] = "['{$campo['formacreacion']}', 'crear_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}','{$campo['pantalla']}']";
            $camposareemplazar = array("#ID#" => "crear_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudacreacion']."<p></p>" , "#DATA#" => "data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formacreacion']}' data-facturacion-requerido ='{$campo['requerido']}' data-facturacion-tipodecampo ='{$campo['tipo']}' data-facturacion-grabar = '{$campo['grabar_creacion']}' data-facturacion-len ='{$campo['longitud']}' data-facturacion-id ='{$campo['id']}' data-facturacion-validar ='{$campo['validar']}' data-facturacion-descripcion ='{$campo['descripcion']}' name = 'crear_{$campo['id']}' ");
            $camposdecreacion[] = HelperTemplates::getInstance()->getformato($campo['formacreacion'], $camposareemplazar);
        }


        // Array's para edicion
        if ($campo['editar'] == 1 && trim($campo['formaedicion']) != '') {
            $tiposdecampos[] = "['{$campo['formaedicion']}', 'editar_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}','{$campo['pantalla']}']";
            $camposareemplazar = array("#ID#" => "editar_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudaedicion']."<p></p>", "#DATA#" => "data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formaedicion']}' data-facturacion-requerido ='{$campo['requerido']}' data-facturacion-tipodecampo ='{$campo['tipo']}' data-facturacion-grabar = '{$campo['grabar_edicion']}' data-facturacion-len ='{$campo['longitud']}' data-facturacion-id ='{$campo['id']}' data-facturacion-validar ='{$campo['validar']}' data-facturacion-descripcion ='{$campo['descripcion']}' name = 'editar_{$campo['id']}'");
            $camposdeedicion[] = HelperTemplates::getInstance()->getformato($campo['formaedicion'], $camposareemplazar);
        }
    }


    // Crear Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='crearregistro_crear' ";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='cleardisplay_crear#secundaria'";
    $data =  "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'crear_secundaria'";
    $forma ="name = 'crear_secundaria' data-facturacion-tipo = 'forma'";
    $camposareemplazar = array("#FORMA#"=>$forma,"#TIPO#" => "Crear", "#FORMACAMPOSMODAL#" => implode("\n\t",$camposdecreacion), "#ACCION#" => "Crear", "#DOCUMENTO#" => '',"#BTCREAR#"=>$btCrear,"#BTCANCELAR#"=>$btCancelar,"#BTLIMPIAR#" =>$btLimpiar,"#DATA#"=>$data );
    echo  HelperTemplates::getInstance()->getformato('modal', $camposareemplazar);


    // Editar Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='crearregistro_editar' ";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='cleardisplay_editar#secundaria'";
    $data =  "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'editar_secundaria'";
    $forma = "name = 'editar_secundaria' data-facturacion-tipo = 'forma'";
    $camposareemplazar = array("#FORMA#"=>$forma,"#TIPO#" => "Editar", "#FORMACAMPOSMODAL#" => implode("\n\t",$camposdeedicion), "#ACCION#" => "Editar", "#DOCUMENTO#" => '',"#BTCREAR#"=>$btCrear,"#BTCANCELAR#"=>$btCancelar,"#BTLIMPIAR#" =>$btLimpiar,"#DATA#"=>$data );
    echo HelperTemplates::getInstance()->getformato('modal', $camposareemplazar);


    $tiposdecampos = implode(",\n\t\t", $tiposdecampos);
    $doc = JFactory::getDocument();
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecampos : $tiposdecampos;

    /*
    $tiposdecamposdecreacion = implode(",\n\t\t", $tiposdecamposdecreacion);
    $doc = JFactory::getDocument();
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecamposdecreacion : $tiposdecamposdecreacion;
    */
}

