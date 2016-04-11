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

            $layoutforma = new JLayoutFile('formasreferencia', null);
            echo $layoutforma->render($this->camposparaforma);

            $layouttablacontenido = new JLayoutFile('tablacontenido', null);
            echo $layouttablacontenido->render($this->camposdedatos);

echo '
        </div>
    </div>

';
