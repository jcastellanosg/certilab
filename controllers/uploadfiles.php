<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Default controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_invoices
 */

require_once JPATH_COMPONENT . '/helpers/UploadHandler/uploadfiles.php';


class CertilabControllerUploadfiles extends JControllerAdmin
{

    public function uploadFiles()
    {
        $modelo = parent::getModel('Crear');
        $upload_handler = new UploadHandler();
        jexit();
    }




}


