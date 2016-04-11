<?php


defined('_JEXEC') or die();

include_once JPATH_COMPONENT_ADMINISTRATOR . "/views/default/view.html.php";

class CertilabViewFacturas extends  FacturacionViewDefault
{

    protected $camposdedatos = array();


    function display($tpl = null)
    {
        parent::display($tpl);
    }




    protected function getCamposSelect()
    {
        $data = array();
        $datosselect = array();
        foreach (($this->camposdedatos['campos']) as $campo) {
            if ((trim($campo['formacreacion']) == 'select' || trim($campo['formaedicion']) == 'select') && trim($campo['sql']) != '') {
                $camposselect = HelperFacturacion::getSelectData($campo['sql']);
                if (!empty($camposselect)) {
                    foreach ($camposselect as $vector) {
                        $data[] = "'crear_{$campo['id']}','editar_{$campo['id']}'";
                        foreach ($vector as $key => $value) {
                            $data[] = "'$key', '{$value}'";
                        }
                        $datosselect[] = "[" . implode(',', $data) . "]\n\t\t";
                        $data = array();
                    }
                }
            }
        }
        return  implode(',', $datosselect) ;
    }




}

