<?php


defined('_JEXEC') or die();

class InvoicesViewFacturacioncrear extends JViewLegacy
{

    protected $camposdedatos = array();
    protected $camposparaforma= array();
    protected $user = null;
    protected $menu = '';


    function display($tpl = null)
    {
        //$prefix='Invoices';
        //Se requiere ejecutar al parecer es un error en Joomla ??
        //JHtml::_('grid.sort', '', '', "", "");
        // Inicio de creacion de pantalla


        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('id', '0', 'int');
        $helper = new HelperComponent;
        $this->camposparaforma = $helper->getproductosbyreference($id);

        $modelo = $this->getModel();
        $this->camposdedatos['campos'] = $modelo->getCamposDeDatos();
        $this->getCamposSelect();
        $this->camposdedatos['operadores'] = $modelo->getOperadores();
        $this->camposdedatos['links'] = $modelo->getLinks();
        $this->camposdedatos['menu'] = $modelo->menu;
        $this->camposdedatos['itemsdemenu'] = $modelo->getItemsMenu();
        $this->camposdedatos['urlactual'] = $modelo->getUrlActual();
        $this->camposdedatos['keyactual'] = $modelo->getKeyActual();
        $this->camposdedatos['datosmaster'] = $modelo->getDastosMaster();

        $this->addToolbar(null);

        parent::display($tpl);
    }


    protected function getCamposSelect()
    {
        if (count($this->camposdedatos['campos']) > 0) {
            $vector = "";
            $script = "";
            foreach (($this->camposdedatos['campos']) as $campo) {
                if (trim($campo['forma']) == 'select' && trim($campo['sql']) != '') {
                    $helper = new HelperComponent;
                    $select = $campo['id'];
                    $camposselect = $helper->getSelectData($campo['sql']);
                    if (!empty($camposselect)) {
                        if (count($camposselect[0]) > 2) {
                            $keys = array_keys($camposselect[0]);
                            $vector .= $this->crearvectordedatos($camposselect, $campo['id']);
                            $script .= $this->crearscriptdelselect($keys[2], $campo['id']);
                        } else {
                            $this->camposdedatos[$select] = $helper->getSelectData($campo['sql']);
                        }
                    }
                }
            }
            $this->camposdedatos['vector']=$vector;
            $this->camposdedatos['script']=$script;
        }
    }

    private function crearvectordedatos($campos, $id)
    {
       $vector = "";
        if (is_array($campos)) {
            $keys = array_keys($campos[0]);
            foreach ($campos as $campo) {
                $vector[] = "{id : {$campo[$keys[0]]} , valor : '{$campo[$keys[1]]}' , {$keys[2]} : {$campo[$keys[2]]} }\n\t";
            }
            $vector = "var {$id} = [" . implode(",", $vector) . "];\n\n\t";
        }
        return $vector;
    }


    private function crearscriptdelselect($idsource, $idtarget)
    {
         return "jQuery('#frm_{$idsource}').change(function() {
                        valor = jQuery(this).val();
                        reemplazaropciones(valor, '#frm_{$idtarget}', {$idtarget}, '{$idsource}');
        });\n\n\t";

    }

    /**
     * Add the page title and toolbar.
     */

    protected function addToolbar($links)
    {

/*     //   $canDo = VisitantesHelper::getActions();
        $user = JFactory::getUser();
*/
        JToolBarHelper::title(JText::_('Facturacion Fratris'), 'jea.png');
/*
        foreach ($links as $link) {
            if ($link['boton']) {
                if (trim($link['nombre'] == 'Add')) {
                    if ($canDo->get('core.create')) {
                        JToolBarHelper::addNew($link['task']);
                        //JToolBarHelper::custom('properties.copy', 'copy.png', 'copy_f2.png', 'Copiar');
                    }
                }
            }
        }

        if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {

            JToolBarHelper::editList('ingreso.edit');
      }

        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::publish('properties.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('properties.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::custom('properties.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
        }

        if ($canDo->get('core.delete')) {
            JToolBarHelper::divider();
            JToolBarHelper::deleteList(JText::_('COM_VISITANTES_MESSAGE_CONFIRM_DELETE'), 'properties.delete');
        }

        if ($canDo->get('core.admin')) {
           JToolBarHelper::divider();
            JToolBarHelper::preferences('com_visitantes');
        }
*/
    }


}

?>


