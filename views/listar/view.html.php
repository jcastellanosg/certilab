<?php


defined('_JEXEC') or die();

include_once JPATH_COMPONENT_ADMINISTRATOR . "/views/default/view.html.php";

class CertilabViewListar extends  CertilabViewDefault
{

    protected $camposdedatos = array();


    function display($tpl = null)
    {
        $modelo = $this->getModel();
        $this->camposdedatos['campos'] = $modelo->getCamposDeDatos();
        $this->camposdedatos['links'] = $modelo->getLinks();
        parent::display($tpl);
    }

    public function setDocument(){

        parent::setDocument();
        $document = JFactory::getDocument();
        $jinput = JFactory::getApplication()->input;
        $valoresactuales = "var valores_actuales = [['{$jinput->get('campo','','STRING')}','{$jinput->get('id',0,'INT')}']];";
        $document->urldatatable = JURI::getInstance()->toString() . "&task=listar.getTable&" . JSession::getFormToken() . "=1";
        $parametros = array('option'=>'com_certilab','menu'=>$jinput->get('menu',0,'INT'),'task'=>'listar.crear',JSession::getFormToken() =>'1');
        $urlcrear = "'".JUri::current()."?".JUri::buildQuery($parametros)."'";
        $document->urlcrear = $urlcrear;
        $document->valoresactuales = $valoresactuales;
        $document->valoresselect = parent::getCamposSelect($this->camposdedatos);
    }

}

