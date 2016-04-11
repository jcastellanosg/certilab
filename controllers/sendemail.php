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

class CertilabControllerSendemail extends JControllerAdmin
{

    private $email = <<<EMAIL
    <table border="1">
       #HEAD#
       #BODY#
       #DETAIL#
    </table>

EMAIL;


    private $htmlencabezado = <<<ENCABEZADO
        <tr>
            <td>
              #LOGO#
            </td>

            <td colspan="2">
                #TITULO#
            </td>
            <td>
                <table border=0>
                    <tr><td>Codigo: #CODIGO#</td></tr>
                    <tr><td>Version: #VERSION#</td></tr>
                    <tr><td>Vigencia: #VIGENCIA#</td></tr>
                </table>
            </td>
        </tr>
ENCABEZADO;

    private $htmlcuerpo = <<<CUERPO
    <tr>
        <td colspan=4>
            <table border=0>
                 <tr>
                    <td style="padding-top: 20px">
                        &nbsp;
                     <td>
                </tr>
                <tr>
                    <td>Fecha Requisicion: #FECHA#</td>
                    <td>Numero Requisicion: #NUMERO#</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Nombre Solicitante: #NOMBRE#</td>
                </tr>
                <tr>
                    <td>Cargo Solicitante: #CARGO#</td>
                </tr>
                <tr>
                    <td style="padding-top: 20px">
                        &nbsp;
                     <td>
                </tr>

            </table>
        </td>
    </tr>
CUERPO;

    private $htmldetalle = <<<DETALLE
     <tr>
        <td colspan=3>Producto</td>
        <td>Cantidad</td>
     </tr>
        #DATA#
DETALLE;


    public function send()
    {
        $resultado['OK'] = 'NOK';
        $resultado['message'] = "NO enviado";

        // Check for request forgeries
        JSession::checkToken('get') or die('Invalid Token');
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);  // setSender = From
        $datamail = $this->getDataMail();
        $resultado['data'] = $datamail;
        if ($datamail['OK'] = 'OK') {
            $mailer->addRecipient($config->get('mailfrom'));
            $mailer->addBcc($datamail['destinatarios']);
            $mailer->setSubject($datamail['tema']);
            $mailer->isHTML(true);
            $mailer->Encoding = 'base64';
            $mailer->setBody($datamail['body']);
            $mailer->AddEmbeddedImage(JURI::root() . $this->pathlogo, 'logo_id', 'centrek.jpg', 'base64', 'image/jpg');
            // Optional file attached
            //$mailer->addAttachment(JPATH_COMPONENT.'/assets/document.pdf');   $mailer->isHTML();

            $send = $mailer->Send();
            //$resultado = [];
            if ($send !== true) {
                $resultado['OK'] = 'NOK';
                $resultado['message'] = $send->__toString();
            } else {
                $resultado['OK'] = 'OK';
                $resultado['message'] = "mailenviados satisfactoriamente";
            }
        }
        echo json_encode($resultado);
        jexit();
    }

    private function getDataMail()
    {
        $datosdelmail = [];
        $jinput = JFactory::getApplication()->input;
        $tipo = $jinput->get('TipoOperacion', '', 'WORD');

        if (method_exists($this, $tipo)) {
            $datosdelmail = $this->{$tipo}();
        } else {
            $datosdelmail['NOK'] = 'NOK';
            $datosdelmail['message'] = "No se puede enviar mail de {$tipo} !!";
        }
        return $datosdelmail;
    }

    private function cotizacion()
    {
        $resultado = [];
        $jinput = JFactory::getApplication()->input;
        $proveedores[] = $jinput->get('productos', 0, 'HTML');
        $destinatarios = $this->getEmailProveedores();
        if ($destinatarios['OK'] == 'NOK') {
            $resultado['OK'] = 'NOK';
            $resultado['message'] = $destinatarios['message'];
        } else {
            $resultado['destinatarios'] = $destinatarios['data'];
            $resultado['sql'] = $destinatarios['sql'];
            $resultado['tema'] = $this->getHtmlmail('getTemaCotizacion');
            $resultado['body'] = $this->getHtmlMail('getBodyCotizacion');
            $resultado['OK'] = 'OK';
        }
        return $resultado;
    }

    private function getHtmlMail($tipo)
    {
        if (method_exists($this, $tipo)) {
            $datosdelmail = $this->{$tipo}();
        } else {
            $datosdelmail['NOK'] = 'NOK';
            $datosdelmail['message'] = "No se puede enviar mail de {$tipo} !!";
        }
        return $datosdelmail;
    }

    private function getTemaCotizacion()
    {
        $jinput = JFactory::getApplication()->input;
        $numero = $jinput->get('c_id', 0, 'INT');
        $tema = "Solicitud de Requisicion: " . $numero;
        return $tema;
    }

    private function getBodyCotizacion()
    {
        $jinput = JFactory::getApplication()->input;
        $nombre = $jinput->get('e_nombre', '', 'WORD') . "" . $jinput->get('e_apellido', '', 'WORD');
        $fecha = $jinput->get('c_fechacreacion', '', 'STR');
        $numero = $jinput->get('c_id', 0, 'INT');
        $cargo = $jinput->get('g_cargo', '', 'STR');
        $logo = "<img src=" . JURI::root() . "media/com_certilab/assets/global/img/centrek.png " . "/>";

        $head = str_replace("#CODIGO#", "ADM-P01-F1", $this->htmlencabezado);
        $head = str_replace("#VERSION#", "2", $head);
        $head = str_replace("#VIGENCIA#", "01/02/2015", $head);
        $head = str_replace("#LOGO#", $logo, $head);
        $head = str_replace("#TITULO#", "Formato de Requisicion de Insumos y Servicios", $head);

        $body = str_replace("#NOMBRE#", $nombre, $this->htmlcuerpo);
        $body = str_replace("#NUMERO#", $numero, $body);
        $body = str_replace("#CARGO#", $cargo, $body);
        $body = str_replace("#FECHA#", $fecha, $body);



        $email = str_replace("#HEAD#",$head,$this->email);
        $email = str_replace("#BODY#",$body,$email);
        $detalles = $this->getDetallesCotizacion();
        if ($detalles['OK'] == 'OK') {
            $email = str_replace("#DETAIL#", $detalles['data'], $email);
        }
        return $email;
    }

    public function getEmailProveedores()
    {
        $resultado = [];
        try {
            $jinput = JFactory::getApplication()->input;
            $proveedores[] = $jinput->get('c_proveedor1', 0, 'INT');
            $proveedores[] = $jinput->get('c_proveedor2', 0, 'INT');
            $proveedores[] = $jinput->get('c_proveedor3', 0, 'INT');
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName(array('email')))
                ->from($db->quoteName('#__certilab_proveedores'))
                ->where('id IN (' . implode(",", $proveedores) . ')');
            $db->setQuery($query);
            $resultado['data'] = $db->loadColumn();
            $resultado['OK'] = 'OK';
            $resultado['sql'] = $query->dump();
        } catch (Exception $e) {
            $resultado['message'] = $e->getMessage();
            $resultado['NOK'] = 'OK';
        }
        return $resultado;
    }

    public function getDetallesCotizacion()
    {
        $htmldetalle = "";
        $resultado = [];
        try {
            $jinput = JFactory::getApplication()->input;
            $cotizacion = $jinput->get('c_id', 0, 'INT');
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query
                ->select($db->quoteName(array('producto','cantidad')))
                ->from($db->quoteName('#__certilab_requisiciondetalle'))
                ->where("encabezado_id = ". $cotizacion);
            $db->setQuery($query);
            $data = $db->loadRowList();
            foreach ($data as $linea) {
                $htmldetalle = $htmldetalle . "<tr><td colspan='3'>{$linea[0]}</td><td>{$linea[1]}</td></tr>";
            }
            $resultado['OK'] = 'OK';
            $resultado['data'] =  str_replace("#DATA#",$htmldetalle,$this->htmldetalle);
            $resultado['sql'] = $query->dump();
        } catch (Exception $e) {
            $resultado['message'] = $e->getMessage();
            $resultado['NOK'] = 'OK';
        }
        return $resultado;
    }

}




