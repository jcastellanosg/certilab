<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

$layoutmenu = new JLayoutFile('facturafmt1', null);
echo $layoutmenu->render($this->camposdedatos);


