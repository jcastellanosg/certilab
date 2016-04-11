<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */

require_once JPATH_COMPONENT . '/models/listar.php';

class InvoicesModelReferencias extends InvoicesModelListar
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }


}




