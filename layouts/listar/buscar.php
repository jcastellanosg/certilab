<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!empty($displayData['campos'])) {
    $camposdebusqueda = array();
    $tiposdecamposdebusqueda = array();
    foreach ($displayData['campos'] as $campo) {
        //if ($campo['buscar'] == 1) {
        //$texto = !empty($campo['textodeayudabusqueda']) ? $campo['textodeayudabusqueda'] : '';
        //$id2 = !empty($campo['id2']) ? $campo['id2'] : '';
        // $camposdebusqueda[] = HelperTemplates::getInstance()->gettemplate($campo['formabusqueda'], "buscar_{$campo['id']}", $campo['descripcion'], $texto, $campo['sql'], $id2);
        //$tiposdecamposdebusqueda[] = "['{$campo['formabusqueda']}', 'buscar_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}']";
        if ($campo['buscar'] == 1) {
            $tiposdecampos[] = "['{$campo['formabusqueda']}', 'buscar_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}','{$campo['pantalla']}']";
            $camposareemplazar = array("#ID#" => "buscar_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudabusqueda'], "#DATA#" => "data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formabusqueda']}' data-facturacion-tipodecampo ='{$campo['requerido']}_{$campo['tipo']}_{$campo['longitud']}' data-facturacion-id ='{$campo['id']}'  data-facturacion-label='{$campo['descripcion']}' ");
            $camposdebusqueda[] = HelperTemplates::getInstance()->getformato($campo['formabusqueda'], $camposareemplazar);
        }
    }


    /**
     *Generar la forma de creacion
     */
  //  if (!empty($camposdebusqueda)) {
  //      $camposdebusqueda = implode("\n", $camposdebusqueda);
  //      echo HelperTemplates::getInstance()->getformadebusqueda(true, $camposdebusqueda);
  //  }

    // Crear Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='buscar'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='resetbusqueda'";
    $data = "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'buscar'";
    $camposareemplazar = array("#TIPO#" => "Buscar", "#FORMACAMPOSMODAL#" => implode("\n\t", $camposdebusqueda), "#ACCION#" => "Buscar", "#DOCUMENTO#" => '', "#BTCREAR#" => $btCrear, "#BTCANCELAR#" => $btCancelar, "#BTLIMPIAR#" => $btLimpiar, "#DATA#" => $data);
    echo HelperTemplates::getInstance()->getformato('modal', $camposareemplazar);


    $tiposdecamposdebusqueda = implode(",\n\t\t", $tiposdecamposdebusqueda);
    $doc = JFactory::getDocument();
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecamposdebusqueda : $tiposdecamposdebusqueda;
}


