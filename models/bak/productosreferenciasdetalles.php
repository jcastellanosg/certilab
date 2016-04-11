<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:23 PM
 */


defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/models/crear.php';

JLoader::registerNamespace('Respect', JPATH_COMPONENT . '/helpers/validator');

//use Respect\Validation\Validator as v;

class InvoicesModelProductosreferenciasdetalles extends JModelAdmin
{
    protected $text_prefix = 'COM_INVOICES';
    protected $tabla;
    protected $data;

    public function guardarData($camposdedatos, $tablas)
    {
        $resultado = array();
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('referencia', '0', 'int');
        if ($id > 0) {
            $helper = new HelperComponent;
            $camposparaforma = $helper->getcomponentesbyproductosreference($id);
            $componente = "";
            // Borrar referencias existentes
            $helper->deleteproductosreferenciasdetalles($id);
            foreach ($camposparaforma as $campo) {
                $valorcomponente = $jinput->get($campo['componente'], '0', 'int');
                $descripcion = $jinput->get('descripcion_' . $campo['componente'], '', 'word');
                if ($valorcomponente > 0) {
                    $data = array('id' => 0, 'idreferencia' => $id, 'idcomponentevalor' => $valorcomponente, 'descripcion' => $descripcion);
                    $guardardata = $this->save($data);
                    if (!$guardardata) {
                        $resultado['errorguardarData'] = $guardardata;
                        $resultado['errorguardarData'] = $this->tabla;
                        $resultado['datos'] = $data;
                        break;
                    }
                }
            }
        }
        return $resultado;
    }



    public function __construct($config = array())
    {
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', '0', 'INT');
        $helper = new HelperComponent;
        $tablas = $helper->getTablas($menu);
        $tabla = $tablas[0]['TABLE_NAME'];
        $this->tabla = substr(strrchr($tabla, "_"), 1);
        parent::__construct($config);
    }


    public function getTable($type = '', $prefix = 'InvoicesTable', $config = array())
    {
        $type = $this->tabla;
        return JTable::getInstance($type, $prefix, $config);
    }


    public function getForm($data = array(), $loadData = true)
    {
    }

    protected function prepareTable($table)
    {
        //$table->pais = htmlspecialchars_decode($table->pais,ENT_QUOTES);
    }


    public function validateFormData($camposdedatos)
    {
        $msg = array();
        return $msg;
    }


}