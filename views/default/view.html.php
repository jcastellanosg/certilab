<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();



/**
 * JEA default view.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabViewDefault extends JViewLegacy
{

    public function display($tpl = null)
    {
        // Set the toolbar
        $this->addToolBar();
        parent::display($tpl);
        $this->setDocument();
    }


    protected function addToolBar()
    {
       JToolBarHelper::preferences('com_facturacion');
    }

   public function setDocument()
    {
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', '0', 'INT');
        $document = JFactory::getDocument();
        $document->menu = ManejarMenu::getMenu();
        $document->breadcrumb = ManejarMenu::getParentsMenu($menu);
        $document->titulo = JText::_('COM_HELLOWORLD_ADMINISTRATION');
        $document->icono = JText::_('COM_HELLOWORLD_ADMINISTRATION');
        $document->tokendeseguridad = JSession::getFormToken() ."=1";
    }


    protected  function getCamposSelect($camposdedatos)
    {
        $data=[];
        $row=[];
        $valoresselect=[];
        foreach (($camposdedatos['campos']) as $campo) {
            $selectcreacion = strpos($campo['formacreacion'], 'select');
            $selectedicion = strpos($campo['formaedicion'], 'select');
            if (($selectcreacion >= 0   || $selectedicion >= true) && trim($campo['sql']) != '') {
                $camposselect = HelperFacturacion::getSelectData($campo['sql']);
                if (!empty($camposselect)) {
                    foreach ($camposselect as $vector) {
                        foreach ($vector as $key => $value) {
                            $data[] = "'$key':'{$value}'";
                        }
                        $row[] = ("{".implode(",",$data)."}\n\t\t\t");
                        $data =[];
                    }
                    $valoresselect[] = "{$campo['id']}:[".implode(",",$row)."]\n\t\t";
                    $row = [];
                }

            }
        }
        return "valoresselect = {".implode(',',$valoresselect)."}";
    }

}
