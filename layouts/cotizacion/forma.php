<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


$jinput = JFactory::getApplication()->input;
$idcampo = $jinput->get('campo', '', 'STRING');

if (!empty($displayData['campos'])) {
    $tiposdecampos = []; // Array para crear vector en javascript de tipos de campos, utilpara validacion, inicializacion y recuperacion de valores
    $camposcreacionencabezado = []; // Campos para edicion/creacion de encabezado de documento
    $camposcreaciondetalle = []; // Campos para edicion/creacion de detalle de documento, se muestran en modal
    $camposedicionencabezado = []; // Campos para edicion/creacion de encabezado de documento
    $camposediciondetalle = []; // Campos para edicion/creacion de detalle de documento, se muestran en modal

    // Creacion de arrays
    foreach ($displayData['campos'] as $campo) {
        // Array's para creacion
        if ($campo['editar'] == 1 && trim($campo['formacreacion']) != '') {
            $tiposdecampos[] = "['{$campo['formacreacion']}', 'crear_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}','{$campo['pantalla']}']";
            $camposareemplazar = array("#ID#" => "crear_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudacreacion'],"#DATA#" => "data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formacreacion']}' data-facturacion-tipodecampo ='{$campo['requerido']}_{$campo['tipo']}_{$campo['grabar_creacion']}_{$campo['longitud']}' data-facturacion-id ='{$campo['id']}'");
            if ($campo['pantalla'] == 'Encabezado') {
                $camposcreacionencabezado[] = HelperTemplates::getInstance()->getformato($campo['formacreacion'], $camposareemplazar);
            } else {
                $camposcreaciondetalle[] = HelperTemplates::getInstance()->getformato($campo['formacreacion'], $camposareemplazar);
            }
        }



        // Array's para edicion
        if ($campo['editar'] == 1 && trim($campo['formaedicion']) != '') {
            $tiposdecampos[] = "['{$campo['formaedicion']}', 'editar_{$campo['id']}','{$campo['requerido']}','{$campo['tipo']}','{$campo['longitud']}','{$campo['pantalla']}']";
            $camposareemplazar = array("#ID#" => "editar_" . $campo['id'], "#DESCRIPCION#" => $campo['descripcion'], "#TEXTODEAYUDA#" => $campo['textodeayudaedicion'],"#DATA#"=>"data-facturacion-tipo ='input' data-facturacion-input ='{$campo['formaedicion']}' data-facturacion-tipodecampo ='{$campo['requerido']}_{$campo['tipo']}_{$campo['grabar_edicion']}_{$campo['longitud']}' data-facturacion-id ='{$campo['id']}'");
            if ($campo['pantalla'] == 'Encabezado') {
                $camposedicionencabezado[] = HelperTemplates::getInstance()->getformato($campo['formaedicion'], $camposareemplazar);
            } else {
                $camposediciondetalle[] = HelperTemplates::getInstance()->getformato($campo['formaedicion'], $camposareemplazar);
            }
        }
        // Campos de encabezado tabla detalles
        if ($campo['listar'] == 1 && $campo['pantalla'] == 'Detalle') {
            $headertabla[] = "<th>" . $campo['descripcion'] . "</th>";
        }

    }

    $headertabla[] = "<th class='hidden-print'>Editar</th><th class='hidden-print'>Borrar</th>";
    $headertabla = implode("\n", $headertabla);

    /* Generacion de pantallas*/

    /** Forma de Creacion  */
    $camposcreacionencabezado = implode("\n", $camposcreacionencabezado);
    $camposcreaciondetalle = implode("\n", $camposcreaciondetalle);

    // Crear Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='clearshow_crear#terciaria'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $guardar = "data-facturacion-tipo ='accion' data-facturacion-accion ='guardarcotizacion_crear'";
    $data =  "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'crear_secundaria'";
    $camposareemplazar = array("#TIPO#" => "Crear", "#FORMAENCABEZADOS#" => $camposcreacionencabezado, "#HEADERTABLA#" => $headertabla, "#ACCION#" => "Guardar", "#DOCUMENTO#" => "Cotizacion", "#DATA#" => $data,"#BTCREAR"=>$btCrear, "#BTCANCELAR#"=>$btCancelar, "#GUARDAR#"=>$guardar);
    $formaprincipal = HelperTemplates::getInstance()->getformato('factura', $camposareemplazar);

    // Crear Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='addrowdt_row'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_crear#secundaria'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='cleardisplay_crear#terciaria'";
    $data =  "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'crear_terciaria'";
    $camposareemplazar = array("#TIPO#" => "Crear", "#FORMACAMPOSMODAL#" => $camposcreaciondetalle, "#ACCION#" => "Crear", "#DOCUMENTO#" => 'Cotizacion',"#BTCREAR#"=>$btCrear,"#BTCANCELAR#"=>$btCancelar,"#BTLIMPIAR#" =>$btLimpiar,"#DATA#"=>$data );
    $formamodal = HelperTemplates::getInstance()->getformato('modal', $camposareemplazar);
    echo $formaprincipal;
    echo $formamodal;
    /** Fin Forma de Creacion  */



    /** Forma de Edicion */
    $camposedicionencabezado = implode("\n", $camposedicionencabezado);
    $camposediciondetalle = implode("\n", $camposediciondetalle);

    // Crear Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='clearshow_editar#terciaria'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_principal'";
    $guardar = "data-facturacion-tipo ='accion' data-facturacion-accion ='guardarcotizacion_editar'";
    $data =  "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'editar_secundaria'";
    $camposareemplazar = array("#TIPO#" => "Editar", "#FORMAENCABEZADOS#" => $camposedicionencabezado, "#HEADERTABLA#" => $headertabla, "#ACCION#" => "Guardar", "#DOCUMENTO#" => "Cotizacion", "#DATA#" => $data,"#BTCREAR"=>$btCrear, "#BTCANCELAR#"=>$btCancelar, "#GUARDAR#"=>$guardar);
    $formaprincipal = HelperTemplates::getInstance()->getformato('factura', $camposareemplazar);

    // Crear Atributos
    $btCrear = "data-facturacion-tipo ='accion' data-facturacion-accion ='addrowdt_row'";
    $btCancelar = "data-facturacion-tipo ='accion' data-facturacion-accion ='show_editar#secundaria'";
    $btLimpiar = "data-facturacion-tipo ='accion' data-facturacion-accion ='cleardisplay_editar#terciaria'";
    $data =  "data-facturacion-tipo = 'pantalla' data-facturacion-pantalla = 'editar_terciaria'";
    $camposareemplazar = array("#TIPO#" => "Editar", "#FORMACAMPOSMODAL#" => $camposediciondetalle, "#ACCION#" => "Editar", "#DOCUMENTO#" => 'Cotizacion',"#BTCREAR#"=>$btCrear,"#BTCANCELAR#"=>$btCancelar,"#BTLIMPIAR#" =>$btLimpiar,"#DATA#"=>$data );
    $formamodal = HelperTemplates::getInstance()->getformato('modal', $camposareemplazar);
    echo $formaprincipal;
    echo $formamodal;
    /** Fin Forma de Creacion  */

    /* Fin Forma de Edicion  */

    /** Forma de Impresion  */
    // Crear Atributos
  /** Fin Forma de Impresion  */


    $tiposdecampos = implode(",\n\t\t", $tiposdecampos);
    $doc = JFactory::getDocument();
    $doc->tiposdecampos = !empty($doc->tiposdecampos) ? $doc->tiposdecampos . ",\n\t\t" . $tiposdecampos : $tiposdecampos;


}
