<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:23 PM
 */


defined('_JEXEC') or die;

JLoader::registerNamespace('Respect', JPATH_COMPONENT . '/helpers/validator');
use Respect\Validation\Validator as v;

class CertilabModelCrear extends JModelAdmin
{
    protected $text_prefix = 'COM_CERTILB';
    protected $tabla;
    protected $data;
    protected $helper;

    public function __construct($config = array())
    {

        $this->helper = new HelperFacturacion;
        //  $tablas = $helper->getTablas($menu);
        //  $tabla = $tablas[0]['TABLE_NAME'];
        //  $this->tabla = substr(strrchr($tabla, "_"), 1);
        parent::__construct($config);
    }


    public function getTable($type = '', $prefix = 'CertilabTable', $config = array())
    {
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', 0, 'INT');
        $tablas = $this->helper->getTablas($menu);
        $tabla = ucfirst(str_replace("#__certilab_", "", $tablas[0]['tabla']));
        $tabla = JTable::getInstance($tabla, $prefix, $config);
        if (!is_object($tabla)) {
            $tabla = JTable::getInstance('Crear', $prefix, $config);
        }
        return $tabla;
    }


    public function getForm($data = array(), $loadData = true)
    {
    }

    protected function prepareTable($table)
    {
        //$table->pais = htmlspecialchars_decode($table->pais,ENT_QUOTES);
    }

    /*
        public function validateFormData($camposdedatos)
        {
            $msg = array();
            $app = JFactory::getApplication();
            $postdata = $app->input->post->getArray();;
            foreach ($camposdedatos as $campo) {
                if ($campo['editar']) {
                    foreach ($postdata as $input => $valor) {
                        if (trim($campo['id']) == $input) {
                            if ($campo['requerido']) {
                                $valido = $this->validateCampo($campo, trim($valor));
                                if (!$valido) {
                                    $msg[$campo['id']] = "Por favor revise el valor de {$campo['descripcion']} valor {$valor} ";
                                }
                            }
                        }
                    }
                }
            }
            return $msg;
        }


        private function validateCampo($campo, $valor)
        {
            $resultado = false;
            $id = $campo['tipo'];
            switch ($id) {
                case 'int' :
                    $resultado = v::int()->assert($valor);
                    break;
                case 'varchar' :
                    $additionalChars = "áéíóúüÁÉÍÓÚÜñÑ_@.";
                    $resultado = v::alnum($additionalChars)->length(0, $campo['longitud'])->validate($valor);
                    if ($resultado && $campo['requerido']) {
                        $resultado = v::notEmpty()->validate($valor);
                    }
                    break;
                case 'decimal' :
                    $resultado = v::numeric()->validate($valor);
                    break;
                case 'datetime' :
                    $resultado = v::date()->validate($valor);
                    break;
            }
            return $resultado;
        }
    */

    public function guardarData()
    {
        $resultado = array();
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', 0, 'INT');
        $tipo = $jinput->get('TipoOperacion', 'editar', 'STRING');
        $data = array();
        if ($menu > 0) {
            $camposdedatos = $this->helper->getCamposDeDatos($menu);
            $tablas = $this->helper->getTablas($menu);
            // traer el prefijo de edicion de la tabla principal
            $prefijo = "{$tablas[0]['alias']}_";
            foreach ($camposdedatos as $campo) {
                //Buscar todos los campos que sean de edicion y cuyo prefijo coincida con la tabla principal
                if (($tipo == 'editar' && $campo['grabar_edicion'] == 1) || ($tipo == 'crear' && $campo['grabar_creacion'] == 1)) {
                    if ($campo['editar'] && substr($campo['id'], 0, 2) == $prefijo) {
                        //Nombre del campo
                        $key = substr($campo['campo'], 2);
                        //Armar el valor del id en la forma de creacion "crear_" + prefijo de la tabla + nombre del campo
                        //de acuerdo al tipo recuperar el valor

                        $data[$key] = $this->getDataFromInput(trim($campo['tipo']), trim($campo['id']));
                    }
                }
            }
            $user = JFactory::getUser();
            $data['usuariocreo'] = $user->id;
            $this->data = $data;
            try {
                $guardardata = $this->save($data);
                if (!$guardardata) {
                    $mensaje = $this->getError();
                    if (!empty($mensaje)) {
                        $mensaje = substr($mensaje, 0, strpos($mensaje, 'SQL'));
                    }
                    $resultado['Mensaje'] = $mensaje;
                    $resultado['OK'] = 'NOK';
                    $resultado['Datos'] = $data;
                } else {
                    $resultado['Mensaje'] = "Se creo el registr de manera exitosa!";
                    $resultado['OK'] = 'OK';
                    $resultado['Datos'] = $data;
                }

            } catch (Exception $e) {
                $resultado['OK'] = 'NOK';
                $resultado['Mensaje'] = $e->getMessage();
                $resultado['Datos'] = $data;
            }
        }
        return $resultado;
    }

    public function guardarDataWithPrefijo($prefijo)
    {
        $resultado = array();
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', 0, 'INT');
        $tipo = $jinput->get('TipoOperacion', 'editar', 'STRING');
        $data = array();
        if ($menu > 0) {
            $camposdedatos = $this->helper->getCamposDeDatos($menu);
            $tablas = $this->helper->getTablas($menu);
            // traer el prefijo de edicion de la tabla principal
            //$prefijo = "{$tablas[0]['alias']}_";
            foreach ($camposdedatos as $campo) {
                //Buscar todos los campos que sean de edicion y cuyo prefijo coincida con la tabla principal
                if (($tipo == 'editar' && $campo['grabar_edicion'] == 1) || ($tipo == 'crear' && $campo['grabar_creacion'] == 1)) {
                    if ($campo['editar'] && substr($campo['id'], 0, 2) == $prefijo) {
                        //Nombre del campo
                        $key = substr($campo['campo'], 2);
                        //Armar el valor del id en la forma de creacion "crear_" + prefijo de la tabla + nombre del campo
                        //de acuerdo al tipo recuperar el valor

                        $data[$key] = $this->getDataFromInput(trim($campo['tipo']), trim($campo['id']));
                    }
                }
            }
            $user = JFactory::getUser();
            $data['usuariocreo'] = $user->id;
            $this->data = $data;
            try {
                $guardardata = $this->save($data);
                if (!$guardardata) {
                    $mensaje = $this->getError();
                    if (!empty($mensaje)) {
                        $mensaje = substr($mensaje, 0, strpos($mensaje, 'SQL'));
                    }
                    $resultado['Mensaje'] = $mensaje;
                    $resultado['OK'] = 'NOK';
                    $resultado['Datos'] = $data;
                } else {
                    $resultado['Mensaje'] = "Se creo el registr de manera exitosa!";
                    $resultado['OK'] = 'OK';
                    $resultado['Datos'] = $data;
                }

            } catch (Exception $e) {
                $resultado['OK'] = 'NOK';
                $resultado['Mensaje'] = $e->getMessage();
                $resultado['Datos'] = $data;
            }
        }
        return $resultado;
    }

    protected function getDataFromInput($tipo, $campo)
    {
        $resultado = false;
        $jinput = JFactory::getApplication()->input;
        switch ($tipo) {
            case 'int' :
                $resultado = $jinput->get($campo, 0, 'INT');
                break;
            case 'varchar' :
                $resultado = trim($jinput->get($campo, ' ', 'STRING'));
                break;
            case 'decimal' :
                $resultado = $jinput->get($campo, 0, 'FLOAT');
                break;
            case 'datetime' :
                $resultado = str_replace('/', '-', $jinput->get($campo, '', 'STRING'));
                //$resultado = JFactory::getDate($resultado);
                break;
        }
        return $resultado;
    }
}
