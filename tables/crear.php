<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:35 PM
 */

defined('_JEXEC') or die;
class CertilabTableCrear extends JTable
{
    public function __construct(&$db)
    {
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', 0, 'INT');
        $helper = new HelperFacturacion;
        $tablas = $helper->getTablas($menu);
        $tabla = $tablas[0]['tabla'];
        parent::__construct($tabla, 'id', $db);
    }

    public function bind($array, $ignore = '')
    {
        return parent::bind($array, $ignore);
    }
    public function store($updateNulls = false)
    {
        return parent::store($updateNulls);
    }
}