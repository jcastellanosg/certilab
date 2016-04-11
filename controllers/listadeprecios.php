<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Default controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_invoices
 */

require_once JPATH_COMPONENT . '/helpers/manejareventos.php';
require_once JPATH_COMPONENT . '/helpers/helperfacturacion.php';

class CertilabControllerListadeprecios extends JControllerAdmin
{
    public function getLista()
    {

        // Check for request forgeries
        //JSession::checkToken('get') or die('Invalid Token');
        $resultado = [];
        $usuario = JFactory::getUser();
        $jinput = JFactory::getApplication()->input;
        $cliente = $jinput->get('cliente', 0, 'int');
        if ($cliente <= 0) {
            $resultado['mensaje'] = 'Codigo de cliente errado';
            $resultado['OK'] = 'NOK';
        }


        //  $helper = new ComponenteValidaciones();
        //  $autorizado = $helper->validateAutorizacion('listar', $menu, $usuario);
        //  if ($autorizado[0]) {
        $modelo = parent::getModel('listadepreciosx');
        $resultado = $modelo->getListadePrecios($cliente);
        echo json_encode($resultado);
        jexit();
    }

  /*
public function getCotizacionDetalle(){
    $usuario = JFactory::getUser();


    //  $helper = new ComponenteValidaciones();
    //  $autorizado = $helper->validateAutorizacion('listar', $menu, $usuario);
    //  if ($autorizado[0]) {
    $modelo = parent::getModel('listadeprecios');
    $items = $modelo->getItems();
    $error = "";
    $resultado = array(
        "data" => $items,
        "listadeprecios" => $modelo->listadeprecios,
        "error" => $error
//"error"=>$jinput->post->getArray()
    );

    echo json_encode($resultado);
    jexit();
}

*/
}

