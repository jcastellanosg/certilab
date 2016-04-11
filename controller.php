<?php

defined('_JEXEC') or die('Restricted access.');



class CertilabController extends JControllerLegacy
{


    protected $default_view = 'default';



    public function display($cachable = false, $urlparams = false)
    {
        //require_once JPATH_COMPONENT.'/helpers/visitantes.php';
        $view = $this->input->get('view', 'default');
        $layout = $this->input->get('layout', 'default');
        parent::display();
        return $this;
    }
	




}