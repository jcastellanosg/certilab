<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:23 PM
 */


defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/models/crear.php';

class InvoicesModelFacturasdetalles extends InvoicesModelCrear
{


    public function guardarData($camposdedatos, $tablas)
    {

        $resultado = array();

        try {
            $jinput = JFactory::getApplication()->input;
            $resultado['entrada'] = $jinput;
        } catch (Exception $e) {
            $resultado['error'] = 'Message: ' . $e->getMessage();
        }
        return $resultado;
    }



    public function crearUsuario($mail, $name, $apellido)
    {
        $data = array(
            'username' => $mail,
            'email' => $mail,
            'password' => $mail,
            'password2' => $mail,
            'name' => $name . " " . $apellido,
            'block' => 1
        );

        $user = new JUser;
        $user->bind($data);
        $user->save();
        $helper = new HelperComponent;
        $id = $helper->getID("username = '" . trim($mail) . "'", 'users', '');
        $this->data['userid'] = $id;
        //return $this->save[$this->data];
        // set the user in group ID = x
        //JUserHelper::setUserGroup($user->get('id'), array(x) );

        /*login dentro de Joomla
        $app = &JFactory::getApplication();
        if($app->isSite()) {
            $credentials = array(
                "username" => $mail,
                "password" => $code
            );
            $app->login($credentials);*/
    }


}
