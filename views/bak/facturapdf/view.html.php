<?php


defined('_JEXEC') or die();
require_once JPATH_COMPONENT . '/helpers/tcpdf/myclass.php';

class InvoicesViewFacturapdf extends JViewLegacy
{
    protected $camposdedatos = array();

    function display($tpl = null)
    {
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('id', '0', 'int');
            $modelo = $this->getModel();
            $datosdefactura = $modelo->getFacturaEncabezado($id);
           if($datosdefactura != null && $id !=0){
             $this->camposdedatos['factura'] = $datosdefactura;
            $this->camposdedatos['detalles'] = $modelo->getFacturaDetalle($id);
            $this->camposdedatos['empresa'] = $modelo->getEmpresa($datosdefactura['idempresa']);
            $this->camposdedatos['cliente'] = $modelo->getCliente($datosdefactura['idcliente']);
            parent::display($tpl);
            exit;
        }
    }



}

?>


