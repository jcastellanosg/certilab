<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelListadepreciosx extends JModelList
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
        $query->select($db->quoteName(array('listadeprecios_id', 'id', 'localidad_id', 'categoria_id', 'cliente_id', 'obra_id', 'producto_id', 'cantidad', 'valor', 'destino_id','weight')));
        $query->from($db->quoteName('#__certilab_listadeprecios_detalle'));
        $query->where($db->quoteName('listadeprecios_id') . ' = ' . $listadeprecios);
        $query->order('weight ASC');
$this->queryl = $query->dump();
         return $query;

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
            $getlistadeprecios= str_replace('#CLIENTE#',$cliente,$getlistadeprecios);
            $getlistadeprecios= str_replace('#LISTADEPRECIOS#',$listadeprecios,$getlistadeprecios);
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

}



