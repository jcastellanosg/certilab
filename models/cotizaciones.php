<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelCotizaciones extends JModelList
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

    public function setAprobacion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                       INSERT INTO #__certilab_cotizacionencabezado_aceptacion
                                    (cotizacion_id,creadopor,notas) VALUES(#COTIZACION#,#CREADOPOR#,#NOTAS# )
SELECT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $notas = $db->quote($jinput->get('c_notas', '', 'STR'));
        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#CREADOPOR#', $creadopor, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#NOTAS#', $notas, $sqlAutorizacion);
        try {
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['mensaje'] = 'cotizacion:' . $cotizacion;
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function setLiberacion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                       INSERT INTO #__certilab_cotizacionencabezado_liberacion
                                    (cotizacion_id,creadopor,notas) VALUES(#COTIZACION#,#CREADOPOR#,#NOTAS# )
SELECT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $notas = $db->quote($jinput->get('c_notas', '', 'STR'));
        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#CREADOPOR#', $creadopor, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#CREADOPOR#', $creadopor, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#NOTAS#', $notas, $sqlAutorizacion);
        try {
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['mensaje'] = 'cotizacion:' . $cotizacion;
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function setAutorizacion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                       INSERT INTO #__certilab_cotizacionencabezado_autorizacion
                                    (cotizacion_id,creadopor,notas) VALUES(#COTIZACION#,#CREADOPOR#,#NOTAS# )
SELECT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $notas = $db->quote($jinput->get('c_notas', '', 'STR'));

        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#CREADOPOR#', $creadopor, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#NOTAS#', $notas, $sqlAutorizacion);


        try {
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['mensaje'] = 'coizacion:' . $cotizacion;
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    public function setAnulacion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                       INSERT INTO #__certilab_cotizacionencabezado_anulacion
                                    (cotizacion_id,creadopor,notas) VALUES(#COTIZACION#,#CREADOPOR#,#NOTAS# )
SELECT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $notas = $db->quote($jinput->get('c_notas', '', 'STR'));

        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#CREADOPOR#', $creadopor, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#NOTAS#', $notas, $sqlAutorizacion);

        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['mensaje'] = 'coizacion:' . $cotizacion;
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function listarCotizacionOT()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                  SELECT d.id as d_id,p.nombre as p_nombre,p.descripcioncorta as p_descripcioncorta,
                        case when v.cantidad is null Then d.cantidad else d.cantidad - v.cantidad end as d_cantidad,
                        '' as d_muestra,'' as d_observaciones
                  FROM #__certilab_cotizaciondetalle as d
                            LEFT JOIN #__certilab_productos as p ON d.producto_id = p.id
                            LEFT JOIN #__view_certilab_totalenorden  as v ON  d.id = v.cotizaciondetalle_id
                  WHERE d.cotizacion_id = #COTIZACION#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('cotizacion_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
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

    public function crearOrdenDeTrabajo()
    {
        $resultado = [];
        $sqlOTEncabezado = <<<SELECT
                INSERT INTO #__certilab_ordendeservicio ( tipodeorden,cotizacion_id,proveedor_id,empleado_id,usuariocreo)
                VALUES ( #TIPODEORDEN#,#COTIZACION#,#PROVEEDOR#,#EMPLEADO#,#CREADOPOR#)
SELECT;
        $sqlOTDetalle = <<<SELECTDT
                INSERT INTO #__certilab_ordendeserviciodetalle (orden_id, cotizaciondetalle_id,muestra_id,cantidad,usuariocreo,observaciones)
                VALUES
SELECTDT;

        $jinput = JFactory::getApplication()->input;
        $tipodeorden = $jinput->get('l_tipodeorden', 0, 'int');
        $cotizacion = $jinput->get('c_id', 0, 'int');
        $proveedor = $jinput->get('l_proveedor', 0, 'int');
        $empleado = $jinput->get('l_empleado', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlOTEncabezado = str_replace('#TIPODEORDEN#', $tipodeorden, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#COTIZACION#', $cotizacion, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#PROVEEDOR#', $proveedor, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#EMPLEADO#', $empleado, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CREADOPOR#', $creadopor, $sqlOTEncabezado);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlOTEncabezado);
            $db->execute();
            $ot_id = $db->insertid();
            if ($ot_id > 0) {
                $productos = $jinput->getArray(array());
                $rows = [];
                foreach ($productos['productos'] as $producto) {
                    preg_match('/-?[0-9]+/', (string)$producto['d_id'], $matches);
                    $cotizaciondetalle_id = @ (int)$matches[0];

                    $muestra = (string)preg_replace('/[^A-Z0-9_\.-]/i', '', (string)$producto['d_muestra']);
                    $muestra_id = substr(ltrim($muestra, '.'), 0, 43);

                    preg_match('/-?[0-9]+/', (string)$producto['d_cantidad'], $matches);
                    $cantidad = @ (int)$matches[0];

                    preg_match('/-?[0-9]+/', (string)$producto['d_cantidad'], $matches);
                    $cantidad = @ (int)$matches[0];

                    $observaciones = (string)preg_replace('/[^A-Z0-9_\.-]/i', '', $producto['d_observaciones']);
                    $observaciones = substr(ltrim($observaciones, '.'), 0, 99);

                    $row = "(" . $ot_id . "," . $cotizaciondetalle_id . ",'" . $muestra_id . "'," . $cantidad . "," . $creadopor . ",'" . $observaciones . "')";
                    $rows[] = $row;
                }
                $sqlOTDetalle = $sqlOTDetalle . join(",", $rows) . ";";
                $query = $db->getQuery(true);
                $db->setQuery($sqlOTDetalle);
                $db->execute();
                $resultado['productos'] = $productos;
                $resultado['$sqlOTDetalle'] = $sqlOTDetalle;
                $resultado['insertados'] = $db->getAffectedRows();
                $resultado['OK'] = 'OK';

            } else {
                $resultado['$sqlenc'] = $sqlOTEncabezado;
                $resultado['OK'] = 'NOK';
            }
        } catch (RuntimeException $e) {
            $resultado['$sqlenc'] = $sqlOTEncabezado;
            $resultado['$sqldt'] = $sqlOTDetalle;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function listarOrdenDeEjcucion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                      SELECT s.id AS s_id,s.orden_id,cotizaciondetalle_id AS s_cotizacion,s.muestra_id AS s_muestra,s.cantidad as s_cantidad,s.fechacreacion,s.usuariocreo,s.observaciones as s_observaciones,
                      p.codigointerno as s_codigointerno,p.nombre AS s_norma ,p.descripcioncorta as s_descripcioncorta
                      FROM #__certilab_ordendeserviciodetalle s
                      LEFT JOIN #__certilab_cotizaciondetalle as c ON c.id = s.cotizaciondetalle_id
                      LEFT JOIN #__certilab_productos as p ON p.id= c.producto_id  WHERE  s.orden_id = #ORDENID#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $ordenid = $jinput->get('orden_id', 0, 'int');
        $sqlAutorizacion = str_replace('#ORDENID#', $ordenid, $sqlAutorizacion);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $resultado['data'] = $db->loadAssocList();
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['$sql'] = $sqlAutorizacion;
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    public function cambiarEstadoOrden()
    {
        $resultado = [];
        $sqlOSEstados = <<<SELECT
                INSERT INTO #__certilab_ordendeservicioanuladasterminadas (ordendeservicio_id,notas,usuariocreo,estado)
                VALUES ( #ORDEN#,'#NOTAS#',#USUARIO#,#ESTADO#)
SELECT;
        $sqlOSUpdate = <<<SELECTU
                UPDATE #__certilab_ordendeservicio
                SET estado = #ESTADO# WHERE id = #ORDEN#
SELECTU;

        $sqlMuestraUpdate = <<<MUESTRA
                UPDATE  #__certilab_muestras SET estado_id = 0
                WHERE codigo in (SELECT muestra_id  FROM centrek_one.wyztm_certilab_ordendeserviciodetalle WHERE orden_id = #ORDEN#)
MUESTRA;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $orden = $jinput->get('o_id', 0, 'int');
        $estado = $jinput->get('o_estado', 0, 'int');
        $notas = $db->quote(substr($jinput->get('s_notas', '', 'STRING'), 0, 199));
        $creadopor = JFactory::getUser()->id;

        $sqlOSEstados = str_replace('#ORDEN#', $orden, $sqlOSEstados);
        $sqlOSEstados = str_replace('#NOTAS#', $notas, $sqlOSEstados);
        $sqlOSEstados = str_replace('#USUARIO#', $creadopor, $sqlOSEstados);
        $sqlOSEstados = str_replace('#ESTADO#', $estado, $sqlOSEstados);

        $sqlOSUpdate = str_replace('#ESTADO#', $estado, $sqlOSUpdate);
        $sqlOSUpdate = str_replace('#ORDEN#', $orden, $sqlOSUpdate);

        $sqlMuestraUpdate = str_replace('#ORDEN#', $orden, $sqlMuestraUpdate);

        try {
            $query = $db->getQuery(true);
            $db->setQuery($sqlOSEstados);
            $db->execute();
            $ot_id = $db->insertid();
            if ($ot_id > 0) {
                $query = $db->getQuery(true);
                $db->setQuery($sqlOSUpdate);
                $db->execute();

                $query = $db->getQuery(true);
                $db->setQuery($sqlMuestraUpdate);
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


    public function setCerrada()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                       INSERT INTO #__certilab_cotizacionencabezado_cerradas
                                    (cotizacion_id,creadopor,notas) VALUES(#COTIZACION#,#CREADOPOR#,#NOTAS# )
SELECT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('c_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $notas = $db->quote($jinput->get('c_notas', '', 'STR'));
        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#CREADOPOR#', $creadopor, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#NOTAS#', $notas, $sqlAutorizacion);
        try {

            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['mensaje'] = 'coizacion:' . $cotizacion;
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    public function crearCotizacion()
    {
        $resultado = [];
        $sqlOTEncabezadoU = <<<SELECT
             UPDATE  #__certilab_cotizacionencabezado
             SET
                    cliente_id  = #CLIENTE#,
                    precio  = #PRECIO#,
                    usuariocreo  = #USUARIO#,
                    descripcion  = #DESCRIPCION#,
                    diasentrega  = #DIAS#,
                    condicionesdepago  = #CONDICIONES#
             WHERE  id  = #ID#
SELECT;

        $sqlOTEncabezadoC = <<<SELECT
                INSERT  INTO #__certilab_cotizacionencabezado (cliente_id , precio , usuariocreo , descripcion , diasentrega,condicionesdepago )
		            VALUES ( #CLIENTE#,#PRECIO#,#USUARIO#,#DESCRIPCION#,#DIAS#,#CONDICIONES#)
SELECT;
        $sqlOTEncabezadoTotales = <<<SELECT
                                UPDATE  #__certilab_cotizacionencabezado as e LEFT JOIN
                                        (SELECT cotizacion_id,sum(precio*cantidad) as total FROM #__certilab_cotizaciondetalle group by cotizacion_id ) as d
                                        ON e.id = d.cotizacion_id
                                SET e.precio = d.total
                                WHERE e.id = #ID#
SELECT;
        $sqlOTDetalleU = <<<SELECTDT
              UPDATE #__certilab_cotizaciondetalle
                    SET
                        categoria_id = #CATEGORIA#,
                        producto_id = #PRODUCTO#,
                        cantidad = #CANTIDAD#,
                        precio = #PRECIO#,
                        usuariocreo = #USUARIO#,
                        total = #TOTAL#,
                        descripcion = '#TOTAL#'
                    WHERE id = #ID#
SELECTDT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('c_id', 0, 'int');
        $descripcion = $db->quote(substr($jinput->get('c_descripcion', 0, 'STRING'), 0, 190));
        $condiciones = $db->quote(substr($jinput->get('c_condicionesdepago', 0, 'STRING'), 0, 98));
        $cliente = $jinput->get('c_cliente_id', 0, 'int');
        $precio = $jinput->get('c_precio', 0, 'int');
        $dias = $jinput->get('c_diasentrega', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        if ($id === 0) {
            $sqlOTEncabezado = $sqlOTEncabezadoC;
        } else {
            $sqlOTEncabezado = $sqlOTEncabezadoU;
        }

        $sqlOTEncabezado = str_replace('#DESCRIPCION#', $descripcion, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CLIENTE#', $cliente, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#PRECIO#', $precio, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#DIAS#', $dias, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#USUARIO#', $creadopor, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#ID#', $id, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CONDICIONES#', $condiciones, $sqlOTEncabezado);
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
                    $item = @ (int)$matches[0];

                    preg_match('/-?[0-9]+/', (string)$producto['d_categoria_id'], $matches);
                    $categoria = @ (int)$matches[0];

                    preg_match('/-?[0-9]+/', (string)$producto['d_producto_id'], $matches);
                    $idproducto = @ (int)$matches[0];

                    preg_match('/-?[0-9]+(\.[0-9]+)?/', (string)$producto['d_cantidad'], $matches);
                    $cantidad = @ (float)$matches[0];

                    preg_match('/-?[0-9]+(\.[0-9]+)?/', (string)$producto['d_precio'], $matches);
                    $precioitem = @ (float)$matches[0];


                    if ($item > 0) {
                        $update = str_replace('#CATEGORIA#', $categoria, $sqlOTDetalleU);
                        $update = str_replace('#PRODUCTO#', $idproducto, $update);
                        $update = str_replace('#CANTIDAD#', $cantidad, $update);
                        $update = str_replace('#PRECIO#', $precioitem, $update);
                        $update = str_replace('#USUARIO#', $creadopor, $update);
                        $update = str_replace('#TOTAL#', $cantidad * $precioitem, $update);
                        $update = str_replace('#ID#', $item, $update);
                        $rowsupdate[] = $update;
                        $rowsupdateid[] = $item;
                    } else {
                        $rowsinsertar[] = "(" . $id . "," . $categoria . "," . $idproducto . "," . $cantidad . "," . $precioitem . "," . $cantidad * $precioitem . "," . $creadopor . ")";
                    }
                }
                if (count($rowsupdateid) > 0) {
                    $resultado['delete'] = $this->deleteDetalleCotizaciones($rowsupdateid, $id);
                }
                if (count($rowsupdate) > 0) {
                    $resultado['update'] = $this->updateDetalleCotizaciones($rowsupdate);
                }
                if (count($rowsinsertar) > 0) {
                    $resultado['insert'] = $this->insertDetalleCotizaciones($rowsinsertar);
                }
                $sqlOTEncabezadoTotales = str_replace('#ID#', $id, $sqlOTEncabezadoTotales);
                $db->setQuery($sqlOTEncabezadoTotales);
                $db->execute();
                $resultado['OK'] = 'OK';
                $resultado['productos'] = $productos['productos'];
                $resultado['encabezado'] = $sqlOTEncabezadoTotales;
            }
        } catch (RuntimeException $e) {
            $resultado['encabezado'] = $sqlOTEncabezadoTotales;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }


    public function deleteDetalleCotizaciones($rowsupdateid, $id)
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                DELETE FROM #__certilab_cotizaciondetalle
                WHERE id NOT in(#ROWS#) AND cotizacion_id = #ID#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $sqlAutorizacion = str_replace('#ROWS#', implode(",", $rowsupdateid), $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#ID#', $id, $sqlAutorizacion);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['DetOK'] = 'OK';
            $resultado['deleteDetalleCotizaciones'] = $sqlAutorizacion;
        } catch (RuntimeException $e) {
            $resultado['deleteDetalleCotizaciones'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['DetOK'] = 'NOK';
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
                $resultado['DetOK'] = 'OK';
                $resultado['updateDetalleCotizaciones'] = $rowsupdate;
            }
        } catch (RuntimeException $e) {
            $resultado['updateDetalleCotizaciones'] = $rowsupdate;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['DetOK'] = 'NOK';
        }
        return $resultado;
    }

    public function insertDetalleCotizaciones($rowsinsertar)
    {
        $resultado = [];
        $sqlOTDetalleC = <<<SELECTDT
                INSERT INTO #__certilab_cotizaciondetalle(cotizacion_id,categoria_id,producto_id,cantidad,precio,total,usuariocreo)
                VALUES
SELECTDT;
        $jinput = JFactory::getApplication()->input;
        $sqlAutorizacion = $sqlOTDetalleC . implode(",", $rowsinsertar);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['DetOK'] = 'OK';
            $resultado['insertDetalleCotizaciones'] = $sqlAutorizacion;
        } catch (RuntimeException $e) {
            $resultado['insertDetalleCotizaciones'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['DetOK'] = 'NOK';
        }
        return $resultado;
    }

    public function listarDetallesDeCotizacion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
            SELECT     d.id as d_id,
                            CONCAT(p.nombre,'::',p.descripcioncorta,'::',p.codigointerno) AS p_nombre,
		                    d.cantidad AS  d_cantidad,
                            d.precio AS d_precio,
                            d.categoria_id as d_categoria_id,
                            d.producto_id as d_producto_id,
                            d.total AS d_total,
							c.categoria as a_categoria
			FROM #__certilab_cotizaciondetalle as d
			LEFT JOIN   #__certilab_productos as p ON d.producto_id  = p.id
            LEFT JOIN   #__certilab_categorias as c ON d.categoria_id  = c.id
            WHERE cotizacion_id = #COTIZACION#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('cotizacion_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
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


    public function  listarHistoriaDeCotizacion()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
            SELECT * FROM (
                SELECT c.id AS d_id, c.diasentrega as d_diasentrega , 'Creada' AS d_estado,c.fechacreacion AS d_fecha ,u.name AS  d_usuario
                FROM #__certilab_cotizacionencabezado as c
                LEFT JOIN #__users as u ON u.id = c.usuariocreo
                UNION
                SELECT cotizacion_id AS d_id, 0 as d_diasentrega,'Autorizada' AS d_estado,c.fechacreacion AS d_fecha ,u.name AS  d_usuario
                FROM  #__certilab_cotizacionencabezado_autorizacion as c
                LEFT JOIN #__users as u ON u.id = c.creadopor
                UNION
                SELECT  cotizacion_id AS d_id, 0 as d_diasentrega,'Aceptada' AS d_estado,c.fechacreacion AS d_fecha ,u.name AS  d_usuario
                FROM #__certilab_cotizacionencabezado_aceptacion as c
                LEFT JOIN #__users as u ON u.id = c.creadopor
                UNION
                SELECT  cotizacion_id AS d_id, 0 as d_diasentrega,'Cerrada' AS d_estado,c.fechacreacion AS d_fecha ,u.name AS  d_usuario
                FROM #__certilab_cotizacionencabezado_cerradas as c
                LEFT JOIN #__users as u ON u.id = c.creadopor
                ) as g
                WHERE g.d_id = #COTIZACION#
                order by g.d_id,g.d_fecha
SELECT;
        $jinput = JFactory::getApplication()->input;
        $cotizacion = $jinput->get('cotizacion_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#COTIZACION#', $cotizacion, $sqlAutorizacion);
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

    public function listarDetallesDePedido()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                SELECT categoria,p.codigointerno as codigo,
                       p.descripcioncorta as analisis,
                       d.muestra as muestra,
                       d.cantidad as cantidad,
                       d.pedido_id,
                       d.categoria_id,
                       d.analisis_id,
                       ensayos
                FROM #__certilab_pedidosdetalles as d
                LEFT JOIN #__certilab_productos as p ON p.id = d.analisis_id
                LEFT JOIN #__certilab_categorias as c ON c.id = d.categoria_id
                WHERE d.pedido_id = #PEDIDO#
SELECT;
        $jinput = JFactory::getApplication()->input;
        $pedido = $jinput->get('pedido_id', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#PEDIDO', $pedido, $sqlAutorizacion);
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
        }
        return $resultado;
    }


    public function  autorizarpedidosweb()
    {
        $resultado = [];
        $sqlAutorizacion = <<<SELECT
                       INSERT INTO #__certilab_pedidosanuladostramitados
                                    (pedido_id,notas,usuariocreo) VALUES(#PEDIDO#,#NOTAS#,#USUARIO# )
SELECT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $pedido = $jinput->get('c_id', 0, 'int');
        $notas = $jinput->get('c_observaciones', '', 'STRING');
        $estado = $jinput->get('c_estado', 0, 'INT');

        $creadopor = JFactory::getUser()->id;
        $sqlAutorizacion = str_replace('#PEDIDO#', $pedido, $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#NOTAS#', $db->quote($notas), $sqlAutorizacion);
        $sqlAutorizacion = str_replace('#USUARIO#', $creadopor, $sqlAutorizacion);
        if ($estado == 1) {
            $resultado = $this->crearCotizacionFromPedido($pedido);
            if ($resultado['OK'] != 'OK') {
                return $resultado;
            }
        }
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    public function crearCotizacionFromPedido($pedido)
    {
        $resultado = [];
        $sqlOTEncabezadoU = <<<SELECT
             UPDATE  #__certilab_cotizacionencabezado
             SET
                    cliente_id  = #CLIENTE#,
                    precio  = #PRECIO#,
                    usuariocreo  = #USUARIO#,
                    descripcion  = #DESCRIPCION#,
                    diasentrega  = #DIAS#,
                    condicionesdepago  = #CONDICIONES#
             WHERE  id  = #ID#
SELECT;

        $sqlOTEncabezadoC = <<<SELECT
                INSERT  INTO #__certilab_cotizacionencabezado (cliente_id , precio , usuariocreo , descripcion , diasentrega,condicionesdepago,pedido_id )
		            VALUES ( #CLIENTE#,#PRECIO#,#USUARIO#,#DESCRIPCION#,#DIAS#,#CONDICIONES#,#PEDIDO#)
SELECT;
        $sqlOTEncabezadoTotales = <<<SELECT
                                UPDATE  #__certilab_cotizacionencabezado as e LEFT JOIN
                                        (SELECT cotizacion_id,sum(precio*cantidad) as total FROM #__certilab_cotizaciondetalle group by cotizacion_id ) as d
                                        ON e.id = d.cotizacion_id
                                SET e.precio = d.total
                                WHERE e.id = #ID#
SELECT;
        $sqlOTDetalleU = <<<SELECTDT
              UPDATE #__certilab_cotizaciondetalle
                    SET
                        categoria_id = #CATEGORIA#,
                        producto_id = #PRODUCTO#,
                        cantidad = #CANTIDAD#,
                        precio = #PRECIO#,
                        usuariocreo = #USUARIO#,
                        total = #TOTAL#,
                        descripcion = '#TOTAL#'
                    WHERE id = #ID#
SELECTDT;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $id = 0;
        $descripcion = substr($jinput->get('c_descripcion', 0, 'STRING'), 0, 190);
        $condiciones = substr($jinput->get('c_condicionesdepago', 0, 'STRING'), 0, 98);
        $cliente = $jinput->get('c_cliente_id', 0, 'int');
        $precio = $jinput->get('c_precio', 0, 'int');
        $dias = $jinput->get('c_diasentrega', 0, 'int');
        $creadopor = JFactory::getUser()->id;
        if ($id === 0) {
            $sqlOTEncabezado = $sqlOTEncabezadoC;
        } else {
            $sqlOTEncabezado = $sqlOTEncabezadoU;
        }
        $sqlOTEncabezado = str_replace('#DESCRIPCION#', $db->quote($descripcion), $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CLIENTE#', $cliente, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#PRECIO#', $precio, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#DIAS#', $dias, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#USUARIO#', $creadopor, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#ID#', $id, $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#CONDICIONES#', $db->quote($condiciones), $sqlOTEncabezado);
        $sqlOTEncabezado = str_replace('#PEDIDO#', $pedido, $sqlOTEncabezado);
        try {

            $query = $db->getQuery(true);
            $db->setQuery($sqlOTEncabezado);
            $db->execute();
            if ($id == 0) {
                $id = $db->insertid(); // Si es nuevo captura registro insertdo
            }
            if ($id > 0) {
                $listadeprecios = $this->getListadePrecios($cliente);
                $listadeprecios = $listadeprecios['data'];
                $resultado['listaprecios']=$listadeprecios;

                $productos = $jinput->getArray(array());
                $rowsinsertar = [];
                $rowsupdate = [];
                $rowsupdateid = [];
                foreach ($productos['productos'] as $producto) {

                    //preg_match('/-?[0-9]+/', (string)$producto['d_id'], $matches);
                    $item = 0;

                    preg_match('/-?[0-9]+/', (string)$producto['categoria_id'], $matches);
                    $categoria = @ (int)$matches[0];

                    preg_match('/-?[0-9]+/', (string)$producto['analisis_id'], $matches);
                    $idproducto = @ (int)$matches[0];

                    preg_match('/-?[0-9]+(\.[0-9]+)?/', (string)$producto['ensayos'], $matches);
                    $cantidad = @ (float)$matches[0];

                    //preg_match('/-?[0-9]+(\.[0-9]+)?/', (string)$producto['precio'], $matches);
                    $precioitem = $this->getPrecio($listadeprecios, $idproducto);


                    if ($item > 0) {
                        $update = str_replace('#CATEGORIA#', $categoria, $sqlOTDetalleU);
                        $update = str_replace('#PRODUCTO#', $idproducto, $update);
                        $update = str_replace('#CANTIDAD#', $cantidad, $update);
                        $update = str_replace('#PRECIO#', $precioitem, $update);
                        $update = str_replace('#USUARIO#', $creadopor, $update);
                        $update = str_replace('#TOTAL#', $cantidad * $precioitem, $update);
                        $update = str_replace('#ID#', $item, $update);
                        $rowsupdate[] = $update;
                        $rowsupdateid[] = $item;
                    } else {
                        $rowsinsertar[] = "(" . $id . "," . $categoria . ",'" . $idproducto . "'," . $cantidad . "," . $precioitem . "," . $cantidad * $precioitem . "," . $creadopor . ")";
                    }
                }
                if (count($rowsupdateid) > 0) {
                    $resultado['delete'] = $this->deleteDetalleCotizaciones($rowsupdateid, $id);
                }
                if (count($rowsupdate) > 0) {
                    $resultado['update'] = $this->updateDetalleCotizaciones($rowsupdate);
                }
                if (count($rowsinsertar) > 0) {
                    $resultado['insert'] = $this->insertDetalleCotizaciones($rowsinsertar);
                }
                $sqlOTEncabezadoTotales = str_replace('#ID#', $id, $sqlOTEncabezadoTotales);
                $db->setQuery($sqlOTEncabezadoTotales);
                $db->execute();
                $resultado['OK'] = 'OK';
              //  $resultado['productos'] = $productos['productos'];
              //  $resultado['encabezado'] = $sqlOTEncabezadoTotales;
              //  $resultado['encabezado'] = $sqlOTEncabezadoTotales;

            }
        } catch (RuntimeException $e) {
            $resultado['encabezado'] = $sqlOTEncabezadoTotales;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        return $resultado;
    }


    public function getListadePrecios($cliente)
    {
        $resultado = [];
        $listadepreciocliente = "SELECT listadeprecios_id FROM #__certilab_clientes where id =" . $cliente;
        $listadepreciosempresa = "SELECT listadeprecios_id FROM #__certilab_empresas";
        $getlistadeprecios = <<<SELECT
                        SELECT  id,
                                listadeprecios_id,
                                categoria_id,
                                producto_id,
                                valor,
                                concat(categoria_id,'p',producto_id) as hash
                        FROM #__certilab_listadeprecios_detalle where listadeprecios_id = #LISTADEPRECIOS#
                        HAVING hash not in (SELECT concat(categoria_id,'p',producto_id) as hash
                                            FROM #__certilab_listadeprecios_excepciones
                                            WHERE cliente_id = #CLIENTE#)
                        UNION
                        SELECT  id,
                                0 as listadeprecios_id,
                                categoria_id,
                                producto_id,
                                valor,
                                concat(categoria_id,'p',producto_id) as hash
                        FROM #__certilab_listadeprecios_excepciones where cliente_id =  #CLIENTE#;
SELECT;

        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($listadepreciocliente);
            $listadeprecios = $db->loadResult();
            if ($listadeprecios <= 0) {
                $query = $db->getQuery(true);
                $db->setQuery($listadepreciosempresa);
                $listadeprecios = $db->loadResult();
                if ($listadeprecios <= 0) {
                    $resultado['mensaje'] = 'Lista de precios errada';
                    $resultado['OK'] = 'NOK';
                    return $resultado;
                }
            }
            $getlistadeprecios = str_replace('#CLIENTE#', $cliente, $getlistadeprecios);
            $getlistadeprecios = str_replace('#LISTADEPRECIOS#', $listadeprecios, $getlistadeprecios);
            $query = $db->getQuery(true);
            $db->setQuery($getlistadeprecios);
            $resultado['data'] = $db->loadAssocList();
            $resultado['mensaje'] = '';
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['mensaje1'] = $getlistadeprecios;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }

    public function getPrecio($listadeprecios, $idproducto)
    {
        $precio = 0;
        if (is_array($listadeprecios)) {
            foreach ($listadeprecios as $producto) {
                if ((int)$producto['producto_id'] == (int)$idproducto) {
                    $precio = $producto['valor'];
                    break;
                }
            }
            return $precio;
        }
    }


    public function duplicarlista($lista)
    {
        $listadeprecio = <<<INSERTLISTA
            INSERT INTO #__certilab_listadeprecios
                        (empresa_id,fechainicial,fechafinal,estado,autorizacion,usuariocreo,descripcion)
            SELECT empresa_id,fechainicial,fechafinal,estado,autorizacion,usuariocreo,#DESCRIPCION#
            FROM  #__certilab_listadeprecios WHERE id = #LISTAID#;

INSERTLISTA;

        $listadepreciodetalle = <<<INSERTLISTADETALLE

            INSERT INTO #__certilab_listadeprecios_detalle
                        (listadeprecios_id,localidad_id,categoria_id,cliente_id,obra_id,producto_id,tipo,cantidad,valor,usuariocreo,destino_id,weight)
            SELECT #NVALISTA#,localidad_id,categoria_id,cliente_id,obra_id,producto_id,tipo,cantidad,#VALOR#,usuariocreo,destino_id,weight
            FROM #__certilab_listadeprecios_detalle WHERE listadeprecios_id = #LISTAID#


INSERTLISTADETALLE;
        $db = JFactory::getDbo();

        $resultado = [ 'OK' => 'NOK'];
        $jinput = JFactory::getApplication()->input;
        $listaid = $jinput->get('id', 0, 'INT');
        $descripcion = $db->quote($jinput->get('l_nombre', '', 'STR'));
        $valor = $jinput->get('l_incremento', 0, 'INT');
        $tipo = $jinput->get('l_tipo', 0, 'INT');

        $valor =  $tipo == 1 ? "valor +" .$valor :  "valor * " .(1+$valor/100)  ;
        if ($listaid > 0) {
            $listadeprecio = str_replace("#LISTAID#", $listaid, $listadeprecio);
            $listadeprecio = str_replace("#DESCRIPCION#", $descripcion, $listadeprecio);

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($listadeprecio);
            try {
                $db->execute();
                $nvalista = $db->insertid(); // Si es nuevo captura registro insertdo
                if ($nvalista > 0) {

                    $listadepreciodetalle = str_replace("#LISTAID#", $listaid, $listadepreciodetalle);
                    $listadepreciodetalle = str_replace("#NVALISTA#", $nvalista, $listadepreciodetalle);
                    $listadepreciodetalle = str_replace("#VALOR#", $valor, $listadepreciodetalle);

                    $query = $db->getQuery(true);
                    $db->setQuery($listadepreciodetalle);
                    $db->execute();
                    $resultado['OK'] = 'OK';
                    $resultado['message'] = "Nueva lista creada!";
                }
            } catch (Exception $e) {
                $resultado['OK'] = 'NOK';
                $resultado['message'] = "Nueva lista creada!";
                //$resultado['error'] = $e->getMessage();
            }
        }
    return $resultado;
    }



}
