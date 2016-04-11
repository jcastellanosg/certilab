<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelRequisiciones extends JModelList
{

    public $listadeprecios;
    public $queryl;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getListQuery()
    {
        $jinput = JFactory::getApplication()->input;
        $cliente = $jinput->get('cliente', 0, 'int');
        $listadeprecios = $this->getListadePrecios($cliente);
        $this->listadeprecios = $listadeprecios;
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $dispatcher = JEventDispatcher::getInstance();
        $query->select($db->quoteName(array('listadeprecios_id', 'id', 'localidad_id', 'categoria_id', 'cliente_id', 'obra_id', 'producto_id', 'cantidad', 'valor', 'destino_id', 'weight')));
        $query->from($db->quoteName('#__certilab_listadeprecios_detalle'));
        $query->where($db->quoteName('listadeprecios_id') . ' = ' . $listadeprecios);
        $query->order('weight ASC');
        $this->queryl = $query->dump();
        return $query;

    }




    public function autorizarrequisiciones()
    {
        $resultado = [];
        $sqlOCCrear =<<<CREAR
                        INSERT INTO #__certilab_ordendecompraencabezado(descripcion,solicitadopor,requisicion_id,usuariocreo)
                        SELECT descripcion,solicitadopor,id,#USUARIO#
                        FROM #__certilab_requisicionencabezado
                        WHERE id=#REQUISICION#
CREAR;
        $sqlOCCrearDetalles =<<<CREARD
                        INSERT INTO #__certilab_ordendecompradetalle(encabezado_id,producto,cantidad)
                        SELECT #OCID# ,producto,cantidad
                        FROM #__certilab_requisiciondetalle
                        WHERE encabezado_id = #REQUISICION#
CREARD;

        $sqlOSEstados = <<<SELECT
                INSERT INTO #__certilab_requisicionesanuladasautorizadas (requisicion_id,notas,usuariocreo,estado)
                VALUES ( #REQUISICION#,#NOTAS#,#USUARIO#,#ESTADO#)
SELECT;
        $sqlOSUpdate = <<<SELECTU
                UPDATE #__certilab_requisicionencabezado
                SET estado = #ESTADO# WHERE id = #REQUISICION#
SELECTU;
        $jinput = JFactory::getApplication()->input;
        $requisicion = $jinput->get('c_id', 0, 'int');
        $estado = $jinput->get('c_estado', 0, 'int');
        $notas = substr($jinput->get('c_notas', '', 'STRING'), 0, 190);
        $creadopor = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $sqlOSEstados = str_replace('#REQUISICION#', $requisicion, $sqlOSEstados);
        $sqlOSEstados = str_replace('#NOTAS#', $db->quote($notas), $sqlOSEstados);
        $sqlOSEstados = str_replace('#USUARIO#', $creadopor, $sqlOSEstados);
        $sqlOSEstados = str_replace('#ESTADO#', $estado, $sqlOSEstados);

        $sqlOSUpdate = str_replace('#ESTADO#', $estado, $sqlOSUpdate);
        $sqlOSUpdate = str_replace('#REQUISICION#', $requisicion, $sqlOSUpdate);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlOSEstados);
            $db->execute();
            $ot_id = $db->insertid();
            if ($ot_id > 0) {
                $query = $db->getQuery(true);
                $db->setQuery($sqlOSUpdate);
                $db->execute();
                if($estado == 1) {  //Requisicion autorizada crear OC
                    $sqlOCCrear = str_replace("#REQUISICION", $requisicion, $sqlOCCrear);
                    $sqlOCCrear = str_replace("#USUARIO", $creadopor, $sqlOCCrear);

                    $db->setQuery($sqlOCCrear);
                    $db->execute();
                    $ot_id = $db->insertid();
                    if ($ot_id > 0) {
                        $sqlOCCrearDetalles = str_replace("#OCID#", $ot_id, $sqlOCCrearDetalles);
                        $sqlOCCrearDetalles = str_replace("#REQUISICION", $requisicion, $sqlOCCrearDetalles);
                        $db->setQuery($sqlOCCrearDetalles);
                        $db->execute();
                    }
                }
                $resultado['OK'] = 'OK';
            }
        } catch (RuntimeException $e) {
            $resultado['$sqlOSEstados'] = $sqlOSEstados;
            $resultado['$sqlOCCrear'] = $sqlOCCrear;
            $resultado['$sqlOCCrearDetalles'] = $sqlOCCrearDetalles;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function listarDetallesRequisicion()
    {
        $sqlAutorizacion = <<<SELECT
                       SELECT id  AS d_id ,producto AS d_producto ,cantidad AS d_cantidad , precio AS d_precio, total AS d_total
                       FROM #__certilab_requisiciondetalle
                       where encabezado_id  = #REQUISICION#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $requisicion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#REQUISICION#', $requisicion, $sqlAutorizacion);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $resultado['data'] = $db->loadAssocList();
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    public function listarDetallesOrdenDeCompra()
    {
        $sqlAutorizacion = <<<SELECT
                       SELECT id  AS d_id ,producto AS d_producto ,cantidad AS d_cantidad , precio AS d_precio, total AS d_total
                       FROM #__certilab_ordendecompradetalle
                       where encabezado_id  = #REQUISICION#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $requisicion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#REQUISICION#', $requisicion, $sqlAutorizacion);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $resultado['data'] = $db->loadAssocList();
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function crearRequisicion()
    {
        $resultado = [];
        $sqlOTEncabezadoU = <<<SELECT
                UPDATE #__certilab_requisicionencabezado SET
                        descripcion = #DESCRIPCION#,
                        solicitadopor = #SOLICITANTE#
                WHERE id = #ID#
SELECT;
        $sqlOTEncabezadoC = <<<SELECT
                INSERT INTO #__certilab_requisicionencabezado (descripcion,solicitadopor,usuariocreo)
                 VALUES (#DESCRIPCION#,#SOLICITANTE#,#CREADOPOR#)
SELECT;

        $sqlOTDetalleU = <<<SELECTDT
              UPDATE #__certilab_requisiciondetalle
              SET
                    producto = #PRODUCTO#,
                    cantidad = #CANTIDAD#,
                    creadopor = #CREADOPOR#
              WHERE id = #ID#

SELECTDT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('c_id', 0, 'int');
        $descripcion = substr($jinput->get('c_descripcion', 0, 'STRING'), 0, 190);
        $solicitante = $jinput->get('c_solicitadopor', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        if ($id === 0) {
            $sqlOTEncabezado = $sqlOTEncabezadoC;
        } else {
            $sqlOTEncabezado = $sqlOTEncabezadoU;
        }

        $sqlOTEncabezado = str_replace('#DESCRIPCION#', $db->quote($descripcion), $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#SOLICITANTE#', $solicitante, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CREADOPOR#', $creadopor, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#ID#', $id, $sqlOTEncabezado);
        try {
            //$query = $db->getQuery(true);
            $db->setQuery($sqlOTEncabezado);
            $db->execute();
            if ($id == 0) {
                $id = $db->insertid(); // Si es nuevo captura registro insertdo
            }
            if ($id > 0) {
                $productos = $jinput->getArray(array());
                $rowsinsertar = [];
                $rowsupdate = [];
                $rowsupdateid = [];
                foreach ($productos['productos'] as $producto) {
                    preg_match('/-?[0-9]+/', (string)$producto['d_id'], $matches);
                    $cotizaciondetalle_id = @ (int)$matches[0];

                    $decripcionproducto = substr(filter_var($producto['d_producto'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH), 0, 190);

                    preg_match('/-?[0-9]+/', (string)$producto['d_cantidad'], $matches);
                    $cantidad = @ (int)$matches[0];


                    if ($cotizaciondetalle_id > 0) {
                        $update = str_replace('#PRODUCTO#', $db->quote($decripcionproducto), $sqlOTDetalleU);
                        $update = str_replace('#CANTIDAD#', $cantidad, $update);
                        $update = str_replace('#CREADOPOR#', $creadopor, $update);
                        $update = str_replace('#ID#', $cotizaciondetalle_id,  $update);
                        $rowsupdate[] = $update;
                        $rowsupdateid[] = $cotizaciondetalle_id;
                    } else {
                        $rowsinsertar[] = "(" . $id . ",'" . $decripcionproducto . "'," . $cantidad ."," .  $creadopor . ")";
                    }
                }
                $resultado['delete'] = $this->deleteDetalleCotizaciones($rowsupdateid, $id);
                $resultado['update'] = $this->updateDetalleCotizaciones($rowsupdate);
                $resultado['insert'] = $this->insertDetalleCotizaciones($rowsinsertar);

            }
            $resultado['$sqlOTEncabezado']=$sqlOTEncabezado;
        } catch (RuntimeException $e) {
            $resultado['encabezado'] = $sqlOTEncabezado;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }


    public function crearOrdenDeCompra()
    {
        $resultado = [];
        $sqlOTEncabezadoU = <<<SELECT
                UPDATE #__certilab_ordendecompraencabezado SET
                        condicionesdenegociacion = #CONDICIONES#,
                        proveedor_id = #PROVEEDOR#,
                        precio = #PRECIO#,
                        diasentrega = #DIASENTREGA#
                WHERE id = #ID#
SELECT;
        $sqlOTEncabezadoC = <<<SELECT
                INSERT INTO #__certilab_ordendecompraencabezado (condicionesdenegociacion,proveedor_id,precio,diasentrega,usuariocreo)
                 VALUES (#CONDICIONES#,#PROVEEDOR#,#PRECIO#,#DIASENTREGA#,#CREADOPOR#)
SELECT;
        $sqlOTEncabezadoTotales = <<<SELECT
                                UPDATE  #__certilab_ordendecompraencabezado AS e  LEFT JOIN (SELECT encabezado_id,sum(total) as total FROM #__certilab_ordendecompradetalle group by encabezado_id ) as d
                                        ON e.id = d.encabezado_id
                                SET e.precio = d.total
                                WHERE e.id = #ID#
SELECT;
        $sqlOTDetalleU = <<<SELECTDT
              UPDATE #__certilab_ordendecompradetalle
              SET
                    producto = '#PRODUCTO#',
                    cantidad = #CANTIDAD#,
                    creadopor = #CREADOPOR#,
                    precio = #PRECIO#,
                    total = #TOTAL#
              WHERE id = #ID#

SELECTDT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('c_id', 0, 'int');
        $condiciones = substr($jinput->get('c_condicionesdenegociacion', 0, 'STRING'), 0, 190);
        $proveedor = $jinput->get('c_proveedor_id', 0, 'int');
        $precio = $jinput->get('c_precio', 0, 'int');
        $diasentrega = $jinput->get('c_diasentrega', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        if ($id === 0) {
            $sqlOTEncabezado = $sqlOTEncabezadoC;
        } else {
            $sqlOTEncabezado = $sqlOTEncabezadoU;
        }

        $sqlOTEncabezado = str_replace('#CONDICIONES#', $db->quote($condiciones), $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#PROVEEDOR#', $proveedor, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#PRECIO#', $precio, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#DIASENTREGA#', $diasentrega, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CREADOPOR#', $creadopor, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#ID#', $id, $sqlOTEncabezado);
        try {
            $query = $db->getQuery(true);
            $db->setQuery($sqlOTEncabezado);
            $db->execute();
            if ($id == 0) {
                $id = $db->insertid(); // Si es nuevo captura registro insertdo
            }
            if ($id > 0) {
                $productos = $jinput->getArray(array());
                $rowsinsertar = [];
                $rowsupdate = [];
                $rowsupdateid = [];
                foreach ($productos['productos'] as $producto) {
                    preg_match('/-?[0-9]+/', (string)$producto['d_id'], $matches);
                    $cotizaciondetalle_id = @ (int)$matches[0];

                    $decripcionproducto = substr(filter_var($producto['d_producto'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH), 0, 190);

                    preg_match('/-?[0-9]+/', (string)$producto['d_cantidad'], $matches);
                    $cantidad = @ (int)$matches[0];

                    preg_match('/-?[0-9]+/', (string)$producto['d_precio'], $matches);
                    $precio = @ (int)$matches[0];

                    if ($cotizaciondetalle_id > 0) {
                        $update = str_replace('#PRODUCTO#', $decripcionproducto, $sqlOTDetalleU);
                        $update = str_replace('#CANTIDAD#', $cantidad, $update);
                        $update = str_replace('#CREADOPOR#', $creadopor, $update);
                        $update = str_replace('#PRECIO#', $precio,  $update);
                        $update = str_replace('#TOTAL#', $cantidad * $precio,  $update);
                        $update = str_replace('#ID#', $cotizaciondetalle_id,  $update);
                        $rowsupdate[] = $update;
                        $rowsupdateid[] = $cotizaciondetalle_id;
                    } else {
                        $rowsinsertar[] = "(" . $id . ",'" . $decripcionproducto . "'," . $cantidad . "," . $precio . "," . $creadopor .",". $precio * $cantidad. ")";
                    }
                }
                $resultado['delete'] = $this->deleteDetalleOrdendeCompra($rowsupdateid, $id);
                $resultado['update'] = $this->updateDetalleOrdenDeCompra($rowsupdate);
                $resultado['insert'] = $this->insertDetalleOrdendeCompra($rowsinsertar);

                $sqlOTEncabezadoTotales = str_replace('#ID#', $id, $sqlOTEncabezadoTotales);
                $db->setQuery($sqlOTEncabezadoTotales);
                $db->execute();

            }
        } catch (RuntimeException $e) {
            $resultado['encabezado'] = $sqlOTEncabezado;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }

    public function deleteDetalleCotizaciones($rowsupdateid, $id)
    {
        if(!is_array($rowsupdateid) ||  count($rowsupdateid) <= 0 ) {
            return false;
        }
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                     DELETE FROM #__certilab_requisiciondetalle
                    WHERE id NOT IN (#ROWS#) AND encabezado_id = #ID#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $sqlAutorizacion = str_replace('#ROWS#', implode(",", $rowsupdateid), $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#ID#', $id, $sqlAutorizacion);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['OK'] = 'OK';
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }

    public function updateDetalleCotizaciones($rowsupdate)
    {
        $resultado = [];
        try {
            foreach ($rowsupdate as $sqlAutorizacion) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $db->setQuery($sqlAutorizacion);
                $db->execute();
                $resultado['OK'] = 'OK';
                $resultado['$sqlAutorizacion'] = $rowsupdate;
            }
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $rowsupdate;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }

    public function insertDetalleCotizaciones($rowsinsertar)
    {
        $resultado = [];
        $sqlOTDetalleC = <<<SELECTDT
                INSERT INTO #__certilab_requisiciondetalle (encabezado_id,producto,cantidad,creadopor)VALUES
SELECTDT;
        $jinput = JFactory::getApplication()->input;
        $sqlAutorizacion = $sqlOTDetalleC . implode(",", $rowsinsertar);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['OK'] = 'OK';
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }

    public function deleteDetalleOrdenDeCompra($rowsupdateid, $id)
    {
        if(!is_array($rowsupdateid) ||  count($rowsupdateid) <= 0 ) {
            return false;
        }
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                     DELETE FROM #__certilab_ordendecompradetalle
                    WHERE id NOT IN (#ROWS#) AND encabezado_id = #ID#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $sqlAutorizacion = str_replace('#ROWS#', implode(",", $rowsupdateid), $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#ID#', $id, $sqlAutorizacion);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['OK'] = 'OK';
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }

    public function updateDetalleOrdenDeCompra($rowsupdate)
    {
        $resultado = [];
        try {
            foreach ($rowsupdate as $sqlAutorizacion) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $db->setQuery($sqlAutorizacion);
                $db->execute();
                $resultado['OK'] = 'OK';
                $resultado['$sqlAutorizacion'] = $rowsupdate;
            }
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $rowsupdate;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }

    public function insertDetalleOrdenDeCompra($rowsinsertar)
    {
        $resultado = [];
        $sqlOTDetalleC = <<<SELECTDT
                INSERT INTO #__certilab_ordendecompradetalle (encabezado_id,producto,cantidad,creadopor)VALUES
SELECTDT;
        $jinput = JFactory::getApplication()->input;
        $sqlAutorizacion = $sqlOTDetalleC . implode(",", $rowsinsertar);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['OK'] = 'OK';
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }



    public function autorizarordendecompra()
    {
        $resultado = [];

        $sqlOSEstados = <<<SELECT
                INSERT INTO #__certilab_odenesdecompraanuladasautorizadas(ordendecompra_id,notas,usuariocreo,estado)
                VALUES ( #ORDEN#,#NOTAS#,#USUARIO#,#ESTADO#)
SELECT;
        $sqlOSUpdate = <<<SELECTU
                UPDATE #__certilab_ordendecompraencabezado
                SET estado = #ESTADO# WHERE id = #ORDEN#
SELECTU;
        $jinput = JFactory::getApplication()->input;
        $orden = $jinput->get('c_id', 0, 'int');
        $estado = $jinput->get('c_estado', 0, 'int');
        $notas = substr($jinput->get('c_notas', '', 'STRING'), 0, 190);
        $creadopor = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $sqlOSEstados = str_replace('#ORDEN#', $orden, $sqlOSEstados);
        $sqlOSEstados = str_replace('#NOTAS#', $db->quote($notas), $sqlOSEstados);
        $sqlOSEstados = str_replace('#USUARIO#', $creadopor, $sqlOSEstados);
        $sqlOSEstados = str_replace('#ESTADO#', $estado, $sqlOSEstados);

        $sqlOSUpdate = str_replace('#ESTADO#', $estado, $sqlOSUpdate);
        $sqlOSUpdate = str_replace('#ORDEN#', $orden, $sqlOSUpdate);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlOSEstados);
            $db->execute();
            $ot_id = $db->insertid();
            if ($ot_id > 0) {
                $query = $db->getQuery(true);
                $db->setQuery($sqlOSUpdate);
                $db->execute();
                $resultado['OK'] = 'OK';
            }
        } catch (RuntimeException $e) {
            $resultado['$sqlOSEstados'] = $sqlOSEstados;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    public function cerrarordendecompra()
    {
        $resultado = [];

        $sqlOSEstados = <<<SELECT
                INSERT INTO #__certilab_ordenesdecompracerradasanuladas(ordendecompra_id,notas,usuariocreo,estado)
                VALUES ( #ORDEN#,#NOTAS#,#USUARIO#,#ESTADO#)
SELECT;
        $sqlOSUpdate = <<<SELECTU
                UPDATE #__certilab_ordendecompraencabezado
                SET estado = #ESTADO# WHERE id = #ORDEN#
SELECTU;
        $jinput = JFactory::getApplication()->input;
        $orden = $jinput->get('c_id', 0, 'int');
        $estado = $jinput->get('c_estado', 0, 'int');
        $notas = substr($jinput->get('c_notas', '', 'STRING'), 0, 190);
        $creadopor = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $sqlOSEstados = str_replace('#ORDEN#', $orden, $sqlOSEstados);
        $sqlOSEstados = str_replace('#NOTAS#', $db->quote($notas), $sqlOSEstados);
        $sqlOSEstados = str_replace('#USUARIO#', $creadopor, $sqlOSEstados);
        $sqlOSEstados = str_replace('#ESTADO#', $estado, $sqlOSEstados);

        $sqlOSUpdate = str_replace('#ESTADO#', $estado, $sqlOSUpdate);
        $sqlOSUpdate = str_replace('#ORDEN#', $orden, $sqlOSUpdate);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlOSEstados);
            $db->execute();
            $ot_id = $db->insertid();
            if ($ot_id > 0) {
                $query = $db->getQuery(true);
                $db->setQuery($sqlOSUpdate);
                $db->execute();
                $resultado['OK'] = 'OK';
            }
        } catch (RuntimeException $e) {
            $resultado['$sqlOSEstados'] = $sqlOSEstados;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

}






