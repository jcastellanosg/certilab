<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$jinput = JFactory::getApplication()->input;
$idcampo = $jinput->get('campo', '', 'STRING');

if (!empty($displayData['campos'])) {
    $camposdecreacion = array();
    $tiposdecamposdecreacion = array();
    $camposdedicion = array();
//    $tabuladores = array();
    $contenidotabulador = array();
//    $contenidotabuladoredicion = array();
//    $contenidotabuladorcreacion = array();
//    $pantallaanterior = "";
//    $tabuladoredicion = array();
//    $tabuladorcreacion = array();
    //  $arrayselect = array();

    $i = 0;
    foreach ($displayData['campos'] as $campo) {
        if ($campo['editar'] == 1) {
            if (!empty($campo['formaedicion'])) {
                $camposareemplazar = array("#ID#" => "editar_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudaedicion'], "#DATA#" => "data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formaedicion']}' data-facturacion-requerido ='{$campo['requerido']}' data-facturacion-tipodecampo ='{$campo['tipo']}' data-facturacion-grabar = '{$campo['grabar_edicion']}' data-facturacion-len ='{$campo['longitud']}' data-facturacion-id ='{$campo['id']}' data-facturacion-validar ='{$campo['validar']}' data-facturacion-descripcion ='{$campo['descripcion']}' name = 'crear_{$campo['id']}' ");
                $camposdedicion[$campo['pantalla']][] = HelperTemplates::getInstance()->getformato($campo['formaedicion'], $camposareemplazar);
            }

            if (!empty($campo['formacreacion'])) {
                $camposareemplazar = array("#ID#" => "crear_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudacreacion'], "#DATA#" => "data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formacreacion']}' data-facturacion-requerido ='{$campo['requerido']}' data-facturacion-tipodecampo ='{$campo['tipo']}' data-facturacion-grabar = '{$campo['grabar_creacion']}' data-facturacion-len ='{$campo['longitud']}' data-facturacion-id ='{$campo['id']}' data-facturacion-validar ='{$campo['validar']}' data-facturacion-descripcion ='{$campo['descripcion']}' name = 'crear_{$campo['id']}' ");
                $camposdecreacion[$campo['pantalla']][] = HelperTemplates::getInstance()->getformato($campo['formacreacion'], $camposareemplazar);
            }
        }
    }



    //Format para poner dos campos por linea en la forma
    foreach ($camposdedicion as $k => &$arraycampos) {
        $arrlength = count($arraycampos);
        $row = "\n\t</div>\n\t<div class='row'>";
        $z = 0;
        for ($x = 0; $x < $arrlength; $x++) {
            if (!strpos($arraycampos[$x],'tab_images_uploader')) {
                $arraycampos[$x] = "<div class='col-md-6'>" . $arraycampos[$x] . "</div>";
            }
            if (!strpos($arraycampos[$x], "display:none")) {
                if ($z % 2 == 0 && $z > 0) {
                    $arraycampos[$x] = $row . $arraycampos[$x];
                    $z++;
                }
            }
        }
        $arraycampos[0] = "<div class='row'>\n\t" . $arraycampos[0];
        $arraycampos[$arrlength - 1] = $arraycampos[$arrlength - 1] . "\n\t</div>\n\t";

    }

    foreach ($camposdecreacion as $k => &$arraycampos) {
        $arrlength = count($arraycampos);
        $row = "\n\t</div>\n\t<div class='row'>";
        $z = 0;
        for ($x = 0; $x < $arrlength; $x++) {
            if (!strpos($arraycampos[$x],'tab_images_uploader')) {
                $arraycampos[$x] = "<div class='col-md-6'>" . $arraycampos[$x] . "</div>";
            }
            if (!strpos($arraycampos[$x], "display:none")) {
                if ($z % 2 == 0 && $z > 0) {
                    $arraycampos[$x] = $row . $arraycampos[$x];
                    $z++;
                }
            }
        }
        $arraycampos[0] = "<div class='row'>\n\t" . $arraycampos[0];
        $arraycampos[$arrlength - 1] = $arraycampos[$arrlength - 1] . "\n\t</div>\n\t";
    }


    $clase = "class='active'";
    $active = "active in ";
    $titulos = [];
    $i = 0;

    foreach ($camposdedicion as $tabulador => $campos) {
        $titulos[] = "<li " . $clase . "> \n\t\t\t <a href='#tab_editar_" . str_replace(" ", "_", $tabulador) . "' data-toggle='tab'>\n\t\t\t\t" . $tabulador . "\n\t\t\t</a>\n\t\t\t</li>";
        $encabezado = "<div class='tab-pane fade " . $active . "' id='tab_editar_" . str_replace(" ", "_", $tabulador) . "' >\n\t ";
        $contenido = implode("\n\t", $campos);
        $pie = "</div>";
        $contenidotabulador[] = $encabezado . $contenido . $pie;
        $clase = "";
        $active = "";
    }


    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='crearregistro_editar'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='cleardisplay_editar#secundaria'";
    $data = "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'editar_secundaria'";
    $forma ="name = 'editar_secundaria' data-facturacion-tipo = 'forma'";
    $camposareemplazar = array("FORMA" => $forma,"#TIPO#" => "Editar", "#TITULOS#" => implode("\n\t", $titulos), "#CONTENIDO#" => implode("\n\t", $contenidotabulador), "#BTCREAR#" => $btCrear, "#BTCANCELAR#" => $btCancelar, "#BTLIMPIAR#" => $btLimpiar, "#DATA#" => $data);
    echo HelperTemplates::getInstance()->getformato('tabulador', $camposareemplazar);


    $clase = "class='active'";
    $active = "active in ";
    $titulos = [];
    $i = 0;
    $contenidotabulador = [];
    foreach ($camposdecreacion as $tabulador => $campos) {
        $titulos[] = "<li " . $clase . "> \n\t\t\t <a href='#tab_crear_" . str_replace(" ", "_", $tabulador) . "' data-toggle='tab'>\n\t\t\t\t" . $tabulador . "\n\t\t\t</a>\n\t\t\t</li>";
        $encabezado = "<div class='tab-pane fade " . $active . "' id='tab_crear_" . str_replace(" ", "_", $tabulador) . "' >\n\t ";
        $contenido = implode("\n\t", $campos);
        $pie = "</div>";
        $contenidotabulador[] = $encabezado . $contenido . $pie;
        $clase = "";
        $active = "";
    }


    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='crearregistro_crear'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='cleardisplay_crear#secundaria'";
    $data = "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'crear_secundaria'";
    $forma ="name = 'crear_secundaria' data-facturacion-tipo = 'forma'";
    $camposareemplazar = array("FORMA" => $forma,"#TIPO#" => "Crear", "#TITULOS#" => implode("\n\t", $titulos), "#CONTENIDO#" => implode("\n\t", $contenidotabulador), "#BTCREAR#" => $btCrear, "#BTCANCELAR#" => $btCancelar, "#BTLIMPIAR#" => $btLimpiar, "#DATA#" => $data);
    echo HelperTemplates::getInstance()->getformato('tabulador', $camposareemplazar);


    $tiposdecamposdecreacion = implode(",\n\t\t", $tiposdecamposdecreacion);
    $doc = JFactory::getDocument();
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecamposdecreacion : $tiposdecamposdecreacion;
}





