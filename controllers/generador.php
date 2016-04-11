<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Default controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_invoices
 */



class CertilabControllerGenerador extends JControllerAdmin
{
    public function getlist()
    {

        //JSession::checkToken('get') or die('Invalid Token');

        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', '', 'WORD');
        $draw = $jinput->get('draw', 1, 'INT');
        $modelo = $this->getModel('generador');
        $items = $modelo->getItems();

        $resultado = array(
            "draw" => $draw,
            //"recordsTotal" => 0$modelo->getTotalDb(),
            "recordsTotal" => 100,
            //"recordsFiltered" => $modelo->getTotal(),
            "recordsFiltered" => 50,
            "data" => $items,
           "error" => $modelo->getListQuery()->dump()
        //  "error"=>$jinput->post->getArray()
         //  "error"=>$modelo->cacheactivo
        );

        echo json_encode($resultado);
        jexit();
    }



}




