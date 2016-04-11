<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelCrearclientes extends JModelList
{

    public $listadeprecios;
    public $queryl;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getListQuery()
    {
        $jinput = JFactory::getApplication()->input;
        $cliente = $jinput->get('cliente', 0, 'int');
        $listadeprecios = $this->getListadePrecios($cliente);
        $this->listadeprecios = $listadeprecios;
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $dispatcher = JEventDispatcher::getInstance();
        $query->select($db->quoteName(array('listadeprecios_id', 'id', 'localidad_id', 'categoria_id', 'cliente_id', 'obra_id', 'producto_id', 'cantidad', 'valor', 'destino_id', 'weight')));
        $query->from($db->quoteName('#__certilab_listadeprecios_detalle'));
        $query->where($db->quoteName('listadeprecios_id') . ' = ' . $listadeprecios);
        $query->order('weight ASC');
        $this->queryl = $query->dump();
        return $query;

    }


    public function crearCliente()
    {
        $resultado = [];
        $sqlclientei = <<<SELECT
                INSERT INTO #__certilab_clientes (nit,nombre,direccion,telefono,email,usuariocreo,estado,listadeprecios_id,tipo)
                        VALUES (#NIT#,'#NOMBRE#','#DIRECCION#','#TELEFONO#','#EMAIL#',#USUARIO#,#ESTADO#,#LISTA#,#TIPO#)

SELECT;
        $sqlclienteu = <<<SELECTU
                UPDATE #__certilab_clientes
                        SET
                        nit = #NIT#,
                        nombre = '#NOMBRE#',
                        direccion = '#DIRECCION#',
                        telefono = '#TELEFONO#',
                        email = '#EMAIL#',
                        usuariocreo = #USUARIO#,
                        estado = #ESTADO#,
                        listadeprecios_id = #LISTA#,
                        tipo = #TIPO#
                        WHERE id = #CLIENTE#
SELECTU;

        $sqlclienteucodigo = <<<SELECTU
                UPDATE #__certilab_clientes
                        SET codigointerno = #CODIGO#
                        WHERE id = #CLIENTE#
SELECTU;
        $jinput = JFactory::getApplication()->input;
        $cliente = $jinput->get('c_id', 0, 'INT');
        $nit = $jinput->get('c_nit', 0, 'FLOAT');
        $nombre = substr($jinput->get('c_nombre', 0, 'STRING'), 0, 95);
        $direccion = substr($jinput->get('c_direccion', 0, 'STRING'), 0, 95);
        $telefono = substr($jinput->get('c_telefono', 0, 'STRING'), 0, 43);
        $email = substr($jinput->get('c_email', 0, 'STRING'), 0, 95);
        $estado = $jinput->get('c_estado', 0, 'int');
        $lista = $jinput->get('c_listadeprecios_id', 0, 'int');
        $tipo = $jinput->get('c_tipo', 0, 'int');
        $usuario = JFactory::getUser()->id;

        if ($cliente == 0) {
            $sqlstring = $sqlclientei;
        } else {
            $sqlstring = $sqlclienteu;
        }

        $sqlstring = str_replace('#NIT#', $nit, $sqlstring);
        $sqlstring = str_replace('#NOMBRE#', $nombre, $sqlstring);
        $sqlstring = str_replace('#DIRECCION#', $direccion, $sqlstring);
        $sqlstring = str_replace('#TELEFONO#', $telefono, $sqlstring);
        $sqlstring = str_replace('#EMAIL#', $email, $sqlstring);
        $sqlstring = str_replace('#USUARIO#', $usuario, $sqlstring);
        $sqlstring = str_replace('#ESTADO#', $estado, $sqlstring);
        $sqlstring = str_replace('#LISTA#', $lista, $sqlstring);
        $sqlstring = str_replace('#TIPO#', $tipo, $sqlstring);
        $sqlstring = str_replace('#CLIENTE#', $cliente, $sqlstring);
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlstring);
            $db->execute();
            $ot_id = $db->insertid();
            $clienteid = $cliente > 0 ? $cliente : $ot_id;
            $jinput->set('clienteid',$clienteid);  // ID del cliente
            if ($ot_id > 0) {
                if ($tipo == 1)
                    $tipo = 'CS';
                ELSE
                    $tipo = 'CE';
                $codigo = "CONCAT ('" . $tipo . "-" . $ot_id . "-',SUBSTR(YEAR(CURDATE()),3,2))";
                //$codigo  = "CONCAT ('". $tipo.   "-',SUBSTR(YEAR(CURDATE()),3,2),'-".$ot_id."')" ;

                $sqlclienteucodigo = str_replace('#CODIGO#', $codigo, $sqlclienteucodigo);
                $sqlclienteucodigo = str_replace('#CLIENTE#', $ot_id, $sqlclienteucodigo);
                $query = $db->getQuery(true);
                $db->setQuery($sqlclienteucodigo);


                //$db->execute();
                //$resultado['$sqlclienteucodigo'] = $sqlclienteucodigo;
            }
            $resultado['OK'] = 'OK';
            $resultado['mensaje'] = 'Cliente creado';
            $resultado['$sqlsring'] = $sqlstring;
        } catch (RuntimeException $e) {
            $resultado['$sqlclienteucodigo'] = $sqlclienteucodigo;
            $resultado['$sqlsring'] = $sqlstring;
            $resultado['mensaje'] = $e->getMessage();
            $resultado['mensaje'] = 'Error Creando Cliente';
            $resultado['OK'] = 'NOK';
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    function updateUserJoomla()
    {
        $sqlclienteu = <<<SELECTU
                UPDATE #__certilab_clientes
                        SET
                        user_estatus = #STATUS#
                        #USERID#
                        WHERE id = #CLIENTEID#
SELECTU;
        $db = JFactory::getDbo();
        $jinput = JFactory::getApplication()->input;
        $clienteid = $jinput->get('clienteid', 0, 'INT');
        $usuarioid = $jinput->get('usuarioid', 0, 'INT');
        if ($usuarioid != 0) {
            $sqlclienteu = str_replace('#USERID#', ',user_id = ' . $usuarioid, $sqlclienteu); // Usuario Nuevo
        } else {
            $sqlclienteu = str_replace('#USERID#', '', $sqlclienteu);
            $query = $db->getQuery(true);
            $db->setQuery("SELECT user_id FROM #__certilab_clientes where id =" . $clienteid);
            $usuarioid = $db->loadResult();
        }
        $user = JFactory::getUser($usuarioid);
        $status = $user->block == 1 ? 0 : 1;
        $sqlclienteu = str_replace('#STATUS#', $status, $sqlclienteu);
        $sqlclienteu = str_replace('#CLIENTEID#', $clienteid, $sqlclienteu);
        try {

            $query = $db->getQuery(true);
            $db->setQuery($sqlclienteu);
            $db->execute();
            return ['OK' => 'OK', "user" => 'Todo OK'];
        } catch (Exception $e) {
            return ['OK' => 'NOK', "user" => $e->getMessage(), 'sql' => $sqlclienteu];
        }
    }


    public function  getArrayData()
    {
        $jinput = JFactory::getApplication()->input;
        $clienteid = $jinput->get('clienteid', 0, 'INT');
        $email = trim(substr($jinput->get('c_email', 0, 'STRING'), 0, 95));
        $nombre = trim(substr($jinput->get('c_nombre', '', 'USERNAME'), 0, 95));
        $nit = $jinput->get('c_nit', 0, 'INT');
        $status = $jinput->get('c_user_estatus', 0, 'INT') == 0 ? 1 : 0;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $db->setQuery("SELECT user_id FROM #__certilab_clientes where id =" . $clienteid);
        $u_id = $db->loadResult();
        $u_id = JFactory::getUser($u_id)->id; // Validar que user exist

        $data = array(
            'name' => $nombre,
            'username' => $email,
            'email' => $email,
            'sendEmail' => 1,
            'block' => $status,
            'requireReset' => 0,
            'id' => $u_id,
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
        if ($u_id == 0) {
            $data['password'] = $nit;
            $data['password2'] = $nit;
            $data['groups'] = [0 => 12];
        }
        return $data;
    }

}






