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
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR  . '/components/comp_users/models', 'UsersModel');

class CertilabControllerListar extends JControllerAdmin
{
    public function getTable()
    {

        // Check for request forgeries
        //JSession::checkToken('get') or die('Invalid Token');

        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', '', 'WORD');
        $usuario = JFactory::getUser();
        $draw = $jinput->get('draw', 1, 'INT');


        //  $helper = new ComponenteValidaciones();
        //  $autorizado = $helper->validateAutorizacion('listar', $menu, $usuario);
        //  if ($autorizado[0]) {
        $jinput = JFactory::getApplication()->input;
        $componente = $jinput->get('option', '', 'CMD');
        $parametros = JComponentHelper::getParams($componente);
        $prefijo = $parametros->get('componente', 'certilab');
        $modelo = $this->getModel('listar');
        $items = $modelo->getItemsMoreLinks();

        $resultado = array(
            "draw" => $draw,
            "recordsTotal" => $modelo->getTotalDb(),
            "recordsFiltered" => $modelo->getTotal(),
            "data" => $items,
       //     "error" => $modelo->getListQuery()->dump()
        "error"=>$jinput->post->getArray()
        );

        echo json_encode($resultado);
        jexit();
    }

    public function crear()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
           $modelo = $this->getModel('Listar');
            $tablas = $modelo->getTablas();
            $tabla = ucfirst(str_replace("#__certilab_", "", $tablas[0]['tabla']));
            $modelo = $this->getModel($tabla);
            $resultado = $modelo->guardarData();
            $resultado['input'] = $tabla;
        } catch (Exception $e) {
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function crearCotizacionLiberada()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->setLiberacion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function crearCotizacionAutorizada()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->setAutorizacion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function crearCotizacionAceptada()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->setAprobacion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function crearCotizacionAnulada()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->setAnulacion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function getCamposDeDatos()
    {

        // Check for request forgeries
        //JSession::checkToken('get') or die('Invalid Token');

        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', '', 'WORD');
        $usuario = JFactory::getUser();


        //  $helper = new ComponenteValidaciones();
        //  $autorizado = $helper->validateAutorizacion('listar', $menu, $usuario);
        //  if ($autorizado[0]) {
        $jinput = JFactory::getApplication()->input;
        $componente = $jinput->get('option', '', 'CMD');
        $parametros = JComponentHelper::getParams($componente);
        $prefijo = $parametros->get('componente', 'certilab');
        $modelo = $this->getModel('listar');
        $resultado = array(
            "resultado" => 'OK',
            "data" => $modelo->getCamposDeDatos(),
            "mensaje" => $modelo->getListQuery()->dump()
        );
        echo json_encode($resultado);
        jexit();
    }

    private function validarSesion()
    {
        $session = JFactory::getSession();
        return $session->isActive();
    }

    public function getModel($name = '', $prefix = '', $config = array())
    {
        $modelo = parent::getModel($name);
        if (!$modelo) {
            $modelo = parent::getModel('Crear');
        }
        return $modelo;
    }


    public function crearcotizacion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $jinput = JFactory::getApplication()->input;
            $modelo = $this->getModel('cotizaciones');
            $resultado = $modelo->crearCotizacion();
            $resultado = $resultado['OK'] == 'OK' ? ['OK' => 'OK','mensaje' => 'Cotizacion Creada']:['OK' => 'NOK','mensaje' => 'Cotizacion No Creada'];

        } catch (Exception $e) {

            $resultado['OK'] = 'NOK';
            $resultado['message'] = 'Error en lacreacion de cotizacion';
            //$resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }



    public function listarCotizacionOT()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->listarCotizacionOT();
            $resultado['input']= $jinput->post->getArray();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function listarDetallesDeCotizacion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->listarDetallesDeCotizacion();
            $resultado['input']= $jinput->post->getArray();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }


    public function crearmuestras()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Crearmuestras');
            $resultado = $modelo->crearMuestras();
            $resultado['input']= $jinput->post->getArray();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }


    public function  listarOrdenDeEjcucion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->listarOrdenDeEjcucion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function crearordendeejecucion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $jinput = JFactory::getApplication()->input;
            $modelo = $this->getModel('cotizacion');
            $resultado[] = $modelo->guardarData();
            $productos = $jinput->getArray(array());
            $cotizacion = $modelo->mtable->id;
            $jinput->set('d_cotizacion_id', $cotizacion);
            $jinput->set('menu', 120);
            $modelo = $this->getModel('crear');
            foreach ($productos['productos'] as $producto) {
                foreach ($producto as $key => $value) {
                    $jinput->set($key, $value);
                }
                $resultado[] = $modelo->guardarData();
            }
            $resultado[] = $jinput->getArray(array());
        } catch (Exception $e) {
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }


    public function crearordendetrabajo()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $jinput = JFactory::getApplication()->input;
            $modelo = $this->getModel('cotizaciones');
            $resultado[] = $modelo->crearordendetrabajo();
        } catch (Exception $e) {
            $resultado['error'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        echo json_encode($resultado);
        jexit();
    }

    public function cambiarEstadoOrden()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $jinput = JFactory::getApplication()->input;
            $modelo = $this->getModel('cotizaciones');
            $resultado = $modelo->cambiarEstadoOrden();
        } catch (Exception $e) {
            $resultado['error'] = $e->getMessage();
            $resultado['OK'] = 'NOK';
        }
        echo json_encode($resultado);
        jexit();
    }


    public function  listarDetallesRequisicion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->listarDetallesRequisicion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  listarDetallesOrdendeCompra()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->listarDetallesOrdenDeCompra();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  crearRequisicion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->crearRequisicion();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }



    public function crearCotizacionCerrada()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->setCerrada();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  autorizarrequisiciones()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->autorizarrequisiciones();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  cerrarrequisiciones()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->cerrarrequisiciones();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  listarHistoriaDeCotizacion()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo-> listarHistoriaDeCotizacion();
            $resultado['input']= $jinput->post->getArray();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function crearCliente()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = [];
        try {
            $modelo = $this->getModel('Crearclientes');
            $resultado = $modelo->crearCliente();
            if ($resultado['OK'] == 'OK') {
                $datauser = $modelo->getArrayData();
                $this->addModelPath(JPATH_ADMINISTRATOR . '\components\com_users\models\\', 'Users');
                $modelo = parent::getModel('User', 'UsersModel');
                $resultado['data'] =  $datauser;
                if ($modelo->save($datauser) == true) {
                    $usuarioid = $modelo->getState('user.id');
                    $jinput->set('usuarioid', $usuarioid);
                    $modelo = $this->getModel('Crearclientes');
                    $resultado['UP']= $modelo->updateUserJoomla();
                  } else {
                    $resultado['OK'] = 'NOK';
                    $resultado['message'] = 'Error creando el usuario';
                }
            }
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }


    public function listarDetallesDePedido()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->listarDetallesDePedido();
            $resultado['input']= $jinput->post->getArray();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  crearordendecompra()
    {
       // JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->crearOrdenDeCompra();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  autorizarordendecompra()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->autorizarordendecompra();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function  cerrarordendecompra()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Requisiciones');
            $resultado = $modelo->cerrarordendecompra();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }


    public function  autorizarpedidosweb()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $resultado = array();
        try {
            $modelo = $this->getModel('Cotizaciones');
            $resultado = $modelo->autorizarpedidosweb();
        } catch (Exception $e) {
            $resultado['OK'] = 'NOK';
            $resultado['error'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function duplicarlista()
    {
        $resultado = [];
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $listaid = $jinput->get('id', 0, 'INT');
        $listanombre = $jinput->get('l_nombre', '', 'STR');
        if ($listaid == 0 ||  $listanombre ='') {
            $resultado = ['OK' => 'NOK', $message = 'Lista no valida'];
        } else {
            try {
                $modelo = $this->getModel('Cotizaciones');
                $resultado = $modelo->duplicarLista();
            } catch (Exception $e) {
                $resultado['OK'] = 'NOK';
                $resultad['message'] = "error duplicando la lista";
                //$resultado['error'] = $e->getMessage();
            }
        }
        echo json_encode($resultado);
        jexit();
    }


}




