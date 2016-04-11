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

class FacturacionModelPaises extends InvoicesModelCrear
{

    public function guardarData($camposdedatos, $tablas)
    {
        $resultado = array();
        try {
            $guardardata = parent::guardarData($camposdedatos, $tablas);
            if (empty($guardardata)) {
                if ($this->data['id'] == 0) {
                    $helper = new HelperComponent;
                    $id = $helper->getID('nit = ' . $this->data['nit'], "empresas", "invoices");
                    $creaempleado = $this->crearEmpleadoNoReporta($id);
                    $creacargo = $this->crearCargoNoReporta($id);
                    if (!$creaempleado) {
                        $resultado['creaempleado'] = $creaempleado;
                    }
                    if (!$creacargo) {
                        $resultado['creaempleado'] = $creaempleado;
                    }

                }
            }
        } catch (Exception $e) {
            $resultado['error'] = 'Message: ' . $e->getMessage();
        }
        return $resultado;
    }

    private function crearCargoNoReporta($id)
    {
        $resultado = false;
        $this->tabla = 'EmpresasCargos';
        if ($id) {
            $data = array('id' => 0, 'idempresa' => $id, 'cargo' => 'No Reporta', 'reportaa' => 0);
            $resultado = $this->save($data);
        }
        return $resultado;
    }

    private function crearEmpleadoNoReporta($id)
    {
        $resultado = false;
        $this->tabla = 'EmpresasEmpleados';
        if ($id) {
            $data = array('id' => 0, 'idempresa' => $id, 'nombre' => 'No Reporta');
            $resultado = $this->save($data);
        }
        return $resultado;

    }
}