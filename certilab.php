<?php

defined('_JEXEC') or die;


//require_once JPATH_COMPONENT.'/helpers/helperfacturacion.php';
require JPATH_COMPONENT . '/helpers/menu.php';
require JPATH_COMPONENT . '/helpers/templates.php';
require JPATH_COMPONENT . '/helpers/templatescomponent.php';



if (!JFactory::getUser()->authorise('core.manage', 'com_certilab')) {
    return;
}

$controller  = JControllerLegacy::getInstance('Certilab');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


