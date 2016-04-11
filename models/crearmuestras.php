<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelCrearmuestras extends JModelList
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






    public function crearMuestras()
    {
        $resultado = [];
        $sqlmuestrai = <<<SELECT
               INSERT INTO #__certilab_muestras(cliente_id,descripcion,estado_id,usuariocreo,recibidapor)

SELECT;

        $sqlmuestraivalues = <<<SELECT
               ( #CLIENTE#,'#DESCRIPCION#',#ESTADO#,#USUARIO#,#RECIBIO#)


SELECT;


        $sqlmuestrau = <<<SELECTU
               UPDATE #__certilab_muestras
                        SET
                        cliente_id = #CLIENTE#,
                        descripcion = '#DESCRIPCION#',
                        estado_id = #ESTADO#,
                        usuariocreo = #USUARIO#,
                        recibidapor=#RECIBIO#
                        WHERE id = #MUESTRA#
SELECTU;
        $sqlmuestraucodigo = <<<SELECTU
                 UPDATE #__certilab_muestras
                        SET
                        codigo = '#CODIGO#'
                        WHERE id = #MUESTRA#
SELECTU;
        $sqlcdocliente = <<<SELECTC
        SELECT codigointerno FROM #__certilab_clientes where id= #CLIENTE#
SELECTC;

        $jinput = JFactory::getApplication()->input;
        $muestra = $jinput->get('m_id', 0, 'INT');
        $cliente = $jinput->get('m_cliente_id', 0, 'INT');
        $descripcion = substr($jinput->get('m_descripcion', '', 'STRING'),0,95);
        $estado = $jinput->get('m_estado_id', 0, 'int');
        $recibio = $jinput->get('m_recibidapor', 0, 'int');
        $usuario = JFactory::getUser()->id;

        if ($muestra == 0) {
            $sqlstring =  $sqlmuestrai.$this->getSqlIValues();
        } else {
            $sqlstring =  $sqlmuestrau;
        }

        $sqlstring = str_replace('#MUESTRA#', $muestra, $sqlstring);
        $sqlstring = str_replace('#CLIENTE#', $cliente, $sqlstring);
        $sqlstring = str_replace('#DESCRIPCION#', $descripcion, $sqlstring);
        $sqlstring = str_replace('#ESTADO#', $estado, $sqlstring);
        $sqlstring = str_replace('#USUARIO#', $usuario, $sqlstring);
        $sqlstring = str_replace('#RECIBIO#', $recibio, $sqlstring);

        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlstring);
            $db->execute();
            $ot_id = $db->insertid();
            if ($ot_id > 0) {
                $sqlcdocliente  =  str_replace('#CLIENTE#', $cliente, $sqlcdocliente);
                $query = $db->getQuery(true);
                $db->setQuery($sqlcdocliente);
                $codigo = $db->loadResult();
                $arraycodigo = explode("-",$codigo);
                if (isset($codigo[0]) && isset($codigo[1])) {
                    $codigo = $arraycodigo[0] . "-" . $arraycodigo[1];
                }
                $codigo  = $codigo ."-". $ot_id ;
                $sqlmuestraucodigo  = str_replace('#CODIGO#', $codigo,  $sqlmuestraucodigo );
                $sqlmuestraucodigo  = str_replace('#MUESTRA#', $ot_id,  $sqlmuestraucodigo );
                $query = $db->getQuery(true);
                $db->setQuery($sqlmuestraucodigo);
                //$db->execute();
            }
            $resultado['OK'] = 'OK';

        } catch (RuntimeException $e) {
            $resultado['$sqlmuestraucodigo '] = $sqlmuestraucodigo ;
            $resultado['$sqlcdocliente'] = $sqlcdocliente;
            $resultado[' $sqlstring '] =  $sqlstring  ;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


private function getSqlIValues(){
    $jinput = JFactory::getApplication()->input;
    $cantidad = (int)$jinput->get('m_cantidad', 0, 'INT') ;
    $cantidad = $cantidad > 0 ? $cantidad :1 ;
    $descripcion = substr($jinput->get('m_descripcion', '', 'STRING'),0,95);
    $patrón = '/#[0-9]+#/';
    $muestrainicial = preg_match($patrón, $descripcion, $muestras) > 0 ? (int)str_replace("#","",$muestras[0]) : 1;
    $repeticiones = range($muestrainicial,$muestrainicial + $cantidad - 1);
    $resultado  =  array_map(array($this,'generateValuesForInsert'),$repeticiones);
    $resultado = str_replace("XX",implode("#",$repeticiones),$resultado);
    $resultado = str_replace("YY",$muestrainicial,$resultado);
    return "VALUES ". implode(",",$resultado);
  }


  private function generateValuesForInsert($i)
{
    $sqlmuestraivalues = "(#CLIENTE#,'#DESCRIPCION#',#ESTADO#,#USUARIO#,#RECIBIO#)";
    $jinput = JFactory::getApplication()->input;
    $descripcion = substr($jinput->get('m_descripcion', '', 'STRING'), 0, 95);
    $patron = '/#[0-9]+#/';
    $value = str_replace("#DESCRIPCION#", $descripcion, $sqlmuestraivalues);
    return preg_replace($patron, $i, $value);
}




}






