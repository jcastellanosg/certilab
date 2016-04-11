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

class CertilabControllerUsuarios extends JControllerAdmin
{


    public function crearusuario()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $mensaje = 'Usuario Editado';
        if($jinput->get('u_id', 0, 'INT') == 0) {
            $mensaje = 'Usuario editado';
        }
        $resultado = array();
        try {
            $this->addModelPath(JPATH_ADMINISTRATOR . '\components\com_users\models\\', 'Users');
            $modelo = $this->getModel('User', 'UsersModel');
            if ($modelo->save($this->getArrayData()) == true) {
                $usuario = $modelo->getState('user.id');
                $jinput->set('nvouser', $usuario);
                $modelo = $this->getModel('Usuarios', 'CertilabModel');
                $resultado = $modelo->guardarPerfil();
            } else {
                $resultado['OK'] = "NOK";
                $resultado['mensaje'] = $modelo->getErrors();
            }
        }catch (Exception $e) {
            $resultado['OK'] = "Exception";
            $resultado['mensaje'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function getcategorias()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('u_id', 0, 'INT');
        $resultado = array();
        try {
            $this->addModelPath(JPATH_ADMINISTRATOR . '\components\com_users\models\\', 'Users');
            $modelo = $this->getModel('User', 'UsersModel');
            $resultado['OK'] = "OK";
            $resultado['grupos'] = $modelo->getAssignedGroups($id);
            $resultado['mensaje'] = $modelo-> getErrors();
        } catch (Exception $e) {
            $resultado['OK'] = "Exception";
            $resultado['mensaje'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }

    public function borrarusuario()
    {
        JSession::checkToken('get') or die('Invalid Token');
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get('u_id', 0, 'INT');
        $resultado = array();
        try {
            $this->addModelPath(JPATH_ADMINISTRATOR . '\components\com_users\models\\', 'Users');
            $modelo = $this->getModel('User', 'UsersModel');
            $resultado['OK'] = "OK";
            $resultado['grupos'] = $modelo->delete($id);
            $resultado['mensaje'] = 'Usuario Borrado';
            $resultado['errores'] = $modelo->getErrors();
        } catch (Exception $e) {
            $resultado['OK'] = "Exception";
            $resultado['mensaje'] = $e->getMessage();
        }
        echo json_encode($resultado);
        jexit();
    }


    private function validarSesion()
    {
        $session = JFactory::getSession();
        return $session->isActive();
    }


    private function  getArrayData()
    {
        $jinput = JFactory::getApplication()->input;
        $perfil =   $jinput->get('u_perfil', 0, 'INT');
        $data =array(
            'name' => $jinput->get('u_name', '', 'USERNAME'),
            'username' => $jinput->get('u_email', '', 'STRING'),
            'email' => $jinput->get('u_email', '', 'STRING'),
            'sendEmail' => $jinput->get('u_sendEmail', 0, 'INT'),
            'block' => $jinput->get('u_blockmenu', 0, 'INT'),
            'requireReset' => $jinput->get('u_requireReset', 0, 'INT'),
            'id' => $jinput->get('u_id', 0, 'INT'),
            'params' => array(
                'admin_style' => '',
                'admin_language' => '',
                'language' => '',
                'editor' => '',
                'helpsite' => '',
                'timezone' => ''
            ),
            'tags' => null
        );
        $password = $jinput->get('u_password', '', 'STRING');
        if($password != ''){
            $data['password'] = $password;
            $data['password2'] = $password;
        }
        //$grupos = $jinput->get('u_groups', '', 'ARRAY');
       if ($perfil==0) {
           $data['groups'] = [0=>2];
       } else {
           $data['groups'] = [0 => 7];
       }
return $data;
}

}


