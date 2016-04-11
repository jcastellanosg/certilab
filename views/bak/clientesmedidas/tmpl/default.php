<?php

// no direct access
defined('_JEXEC') or die('Restricted access');




echo '
    <div class="row">
        <div class="col-md-3">';

            $layoutmenu = new JLayoutFile('menu', null);
            echo $layoutmenu->render($this->camposdedatos);

echo '
        </div>
        <div class="col-md-9">
 ';

$layouttablabusqueda = new JLayoutFile('tablabusqueda', null);
echo $layouttablabusqueda->render($this->camposdedatos);

            $layoutformaclientesmedidas = new JLayoutFile('formas', null);
            echo $layoutformaclientesmedidas->render($this->camposdedatos);

            $layouttablacontenido = new JLayoutFile('tablacontenido', null);
            echo $layouttablacontenido->render($this->camposdedatos);

echo '
        </div>
    </div>

';
