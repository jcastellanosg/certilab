<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!empty($displayData['campos'])) {
    $camposdebusqueda = array();
    $tiposdecamposdebusqueda = array();
    foreach ($displayData['campos'] as $campo) {
        if ($campo['buscar'] == 1) {
            $texto = !empty($campo['textodeayudabusqueda']) ? $campo['textodeayudabusqueda'] : '';
            $id2 = !empty($campo['id2']) ? $campo['id2'] : '';
            $camposdebusqueda[] = HelperTemplates::getInstance()->gettemplate($campo['formabusqueda'], "buscar_{$campo['id']}", $campo['descripcion'], $texto, $campo['sql'], $id2);
            $tiposdecamposdebusqueda[] = "['{$campo['formabusqueda']}', 'buscar_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}']";
        }
    }

    /**
     *Generar la forma de creacion
     */
    if (!empty($camposdebusqueda)) {
        $camposdebusqueda = implode("\n", $camposdebusqueda);
        echo HelperTemplates::getInstance()->getformadebusqueda(true, $camposdebusqueda);
    }

     $tiposdecamposdebusqueda = implode(",\n\t\t", $tiposdecamposdebusqueda);
    $doc = JFactory::getDocument();
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecamposdebusqueda : $tiposdecamposdebusqueda;
}
