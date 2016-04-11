<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelUsuarios extends JModelList
{


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


    public function guardarPerfil()
    {
        $resultado = [];
        $sqlValidacion = <<<SELECT
                       SELECT usuario_id FROM #__certilab_vistas_perfilesusuarios
                              WHERE usuario_id = #USUARIO#
SELECT;
        $sqlCreacion = <<<SELECT
                       INSERT INTO #__certilab_vistas_perfilesusuarios (perfil_id,usuario_id)
                              VALUES (#PERFIL#,#USUARIO#)
SELECT;
        $sqlActualizacion = <<<SELECT
                       UPDATE #__certilab_vistas_perfilesusuarios
                                SET perfil_id = #PERFIL# WHERE usuario_id = #USUARIO#
SELECT;
        $sqlAutorizacion = "";
        try {
            $jinput = JFactory::getApplication()->input;
            $usuario = $jinput->get('u_id', 0, 'int');
            $nvouser = $jinput->get('nvouser', 0, 'INT');
            $perfil = $jinput->get('u_perfil', 0, 'int');
            if ($usuario == 0) {
                $sqlAutorizacion = $sqlCreacion;
                $usuario = $nvouser;
            } else {
                $sqlValidacion = str_replace('#USUARIO#', $usuario, $sqlValidacion);
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $db->setQuery($sqlValidacion);
                $count = $db->loadResult();
                if ((int)$count > 0) {
                    $sqlAutorizacion = $sqlActualizacion;
                } else {
                    $sqlAutorizacion = $sqlCreacion;
                }
            }
            $sqlAutorizacion = str_replace('#PERFIL#', $perfil, $sqlAutorizacion);
            $sqlAutorizacion = str_replace('#USUARIO#', $usuario, $sqlAutorizacion);
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlAutorizacion);
            $db->execute();
            $resultado['mensaje'] = 'usuario ' . $usuario . ' actualizado';
            $resultado['OK'] = 'OK';
        } catch (RuntimeException $e) {
            $resultado['$sqlAutorizacion'] = $sqlAutorizacion;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


}



