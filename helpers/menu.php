<?php

require JPATH_ADMINISTRATOR . '/components/com_certilab//helpers/manejareventos.php';
require JPATH_ADMINISTRATOR . '/components/com_certilab//helpers/helperfacturacion.php';
//require JPATH_COMPONENT . '/helpers/manejareventos.php';
//require JPATH_COMPONENT . '/helpers/helperfacturacion.php';
$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
require_once $basePath . '/models/category.php';


class ManejarMenu
{


    /**
     * @return array
     */
    public static function getMenu()
    {
        $itemsapublicar = array();
        $rows = self::getItemsDeMenu();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                if (self::isAutorizedItemdeMenu($row['id'])) {
                    $row['link'] = $row['link'] != '#' ? JUri::base() . $row['link'] : '#';
                    $itemsapublicar[] = $row;
                }
            }
            $itemsapublicar = HelperFacturacion::display_child_nodes(NULL, 0, $itemsapublicar);
        } else {
            ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "ManejarMenu", "id" => 1, "message" => "No hay items de menu mostrar", "type" => 'error'));
        }
      return $itemsapublicar;
    }

    private static function getItemsDeMenu()
    {
        $sqlGrupos = <<<SELECT
                  SELECT menu_id  FROM #__certilab_vistas_autorizaciones as a LEFT JOIN #__certilab_vistas_perfilesusuarios AS u ON a.perfil_id = u.perfil_id
                                WHERE a.estado = 1 AND u.estado = 1 AND usuario_id = #USER#
                                UNION
                  SELECT id FROM #__certilab_menu WHERE parent_id in (  SELECT menu_id  FROM #__certilab_vistas_autorizaciones as a LEFT JOIN #__certilab_vistas_perfilesusuarios AS u ON a.perfil_id = u.perfil_id
                                WHERE a.estado = 1 AND u.estado = 1 AND usuario_id = #USER# )
SELECT;

        $jinput = JFactory::getApplication()->input;
        //$componente = $jinput->get('option', '', 'CMD');
        //$parametros = JComponentHelper::getParams($componente);
        //$prefijo = 'certilab';
        $user = JFactory::getUser();
        $groups = $user->groups;
        if (in_array(8, $groups)) {
            $sqlGrupos = '';
        } else {
            $sqlGrupos = str_replace('#USER#', JFactory::getUser()->id,$sqlGrupos);
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $db->setQuery($sqlGrupos);
            $menuid = $db->loadColumn();
            $itemsmenu = [];
            $itemsmenu[] = 0;
            foreach ($menuid as $menu) {
                $itemsmenu[] = $menu;
                $itemsmenu[] = floor($menu / 100) * 100;
            }
            $menuid = array_unique($itemsmenu);
            $itemsmenu = implode(",", $menuid);
            if (trim($itemsmenu) != '') {
                $sqlGrupos = ' AND id in (' . $itemsmenu . ')';
            } else {
                $sqlGrupos = '';
            }
        }
        $resultado = array();
        $tabla = "#__certilab_menu";
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id', 'asset_id', 'parent_id', 'menu', 'link', 'publish','icono','descripcion')));
            $query->from($db->quoteName($tabla));
            $query->where('estado = 1 AND (id < 5000 OR  id >6000)  '. $sqlGrupos );
            $query->order('parent_id,orden ASC');
            $db->setQuery($query);
             $resultado = $db->loadAssocList();
        } catch (RuntimeException $e) {
            $resultado = $e->getMessage();
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "ManejarMenu", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }

        return $resultado;
    }

    private static function isAutorizedItemdeMenu($categoria_id)
    {
        /*    $user = JFactory::getUser();
            $valido = JAccess::check($user->id, "core.edit", $asset_id);
            $valido = $valido === true ? true : false;
                return $valido;
       */ return true;
    }

    public static function getParentsMenu($id)
    {
        $rows = self::getItemsDeMenu();
        return  HelperFacturacion::display_parent_nodes($id,$rows);
    }

}
