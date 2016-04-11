<?php

//require JPATH_COMPONENT . '/helpers/errorlog.php';

class HelperFacturacion
{

    public static function display_child_nodes($padre, $nivel, $rows)
    {
        $resultado = array();
        $data = array();
        $index = array();
        foreach ($rows as $row) {
            $id = $row["id"];
            $parent_id = $row["id"] == 0 ? "NULL" : $row["parent_id"];
            $data[$id] = $row;
            $index[$parent_id][] = $id;
        }
        $padre = $padre === NULL ? "NULL" : $padre;
        if (isset($index[$padre])) {
            foreach ($index[$padre] as $id) {
                array_push($resultado, array("nivel" => $nivel, "data" => $data[$id]));
                $items = self::display_child_nodes($id, $nivel + 1, $rows);
                foreach ($items as $item) {
                    array_push($resultado, $item);
                }
            }
        }
        return $resultado;
    }


    /*
     * Recuperar todos los padres de un nodo
     * Util por ejemplo para breadcrumpen el menu o los jefes de un empleado
     *
     */
    public static function display_parent_nodes($id, $rows)
    {
        $parents = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                if ((int)$row['id'] == $id) {
                    $parents[] = $row;
                    if ($row['id'] != 0) {
                        $parent = self::display_parent_nodes($row['parent_id'], $rows);
                        foreach ($parent as $item) {
                            array_push($parents, $item);
                        }
                    }
                    break;
                }
            }
        }
        return $parents;
    }

    /*
     * Recuperar todos los campos de la tabla menu select
     */
    public function getCamposDeDatos($menu)
    {
        $jinput = JFactory::getApplication()->input;
        $componente = $jinput->get('option', '', 'CMD');
        $parametros = JComponentHelper::getParams($componente);
        $prefijo = $parametros->get('componente', 'certilab');

        $tabla = "#__certilab_camposdedatos";
        $campos = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($tabla));
            $where = "  TRIM(MENU)  = '" . trim($menu) . "' ";;
            $query->where($where);
            $query->order('orden ASC');
            $db->setQuery($query);
            $campos = $db->loadAssocList();
        } catch (RuntimeException $e) {
            ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }

        return $campos;
    }


    public function getCamposWhere($menu)
    {
        $tabla = "#__certilab_vistas_where";
        $campos = [];
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('condicion');
            $query->from($db->quoteName($tabla));
            $query->where(" MENU  = " . $menu);
            $db->setQuery($query);
            $campos = $db->loadRowList();
        } catch (RuntimeException $e) {
            ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $campos;
    }

    /**
     * Retornar el arreglo detablas a usar en los query
     * @param $menu
     * @return bool|mixed
     */
    public function getTablas($menu)
    {
        $tablas = array();
        $jinput = JFactory::getApplication()->input;
        $componente = $jinput->get('option', '', 'CMD');
        $parametros = JComponentHelper::getParams($componente);
        $prefijo = $parametros->get('componente', 'certilab');
        $tabla = "#__certilab_vistas_tablas";
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($tabla));
            $query->where("MENU ='" . $menu . "'");
            $query->order('orden ASC');
            $db->setQuery($query);
            $tablas = $db->loadAssocList();
        } catch (RuntimeException $e) {
            ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "getHelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
            return false;
        }
        return $tablas;
    }

    /**
     * Recuperar todoslos campos que se usaran para los query posteriores
     * Armar el nombre de la tabla de formatos join categories (titleeselnombre de la vista)
     * Traer los campos con status 1 (Activos)
     * Retornar la tabla de cache
     * @param: $prefix  Nombre del componente
     * @return array  Array de campos
     *
     * @author: Jorge Castellanos.
     * @version       1.0
     * @copyright: Fratris S.A.S
     *
     **/
    public function getLinks($menu)
    {
        $tablas = array();
        $jinput = JFactory::getApplication()->input;
        $componente = $jinput->get('option', '', 'CMD');
        $parametros = JComponentHelper::getParams($componente);
        $prefijo = $parametros->get('componente', 'certilab');
        $tabla = "#__certilab_vistas_links";
        $links = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($tabla));
            $where = " MENU = '" . $menu . "'";
            $query->where($where);
            $query->order('orden ASC');
            $db->setQuery($query);
            $links = $db->loadAssocList();
        } catch (RuntimeException $e) {
            ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "getHelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
            return false;
        }
        return $links;
    }


    /**
     * Recuperar todos los operadores a usar en la busquedas
     *
     * Retornar el array de operadores dede el cache
     * @param: $prefix  Nombre del componente
     * @return array  Array de campos
     *
     * @author: Jorge Castellanos.
     * @version       1.0
     * @copyright: Fratris S.A.S
     *
     **/
    public function getOperadores()
    {
        // Get a storage key.
        $tabla = "#__" . $this->prefix . "_vistas_operadores";
        $operadores = false;

        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select(array('descripcion', 'valor', 'tipodecampo'));
            $query->from($db->quoteName($tabla));
            $query->where($db->quoteName('status') . ' = 1');
            $db->setQuery($query);
            $operadores = $db->loadAssocList();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'getOperadores', 'error');
            return false;
        }
        return $operadores;
    }


    public static function getSelectData($sql)
    {
        try {
            $db = JFactory::getDbo();
            $db->setQuery($sql);
            $resultado = $db->loadAssocList();
        } catch (RuntimeException $e) {
            ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "getHelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
            return false;
        }
        return $resultado;
    }


    /**
     * Recuperar el ID de la tabla
     * Armar el nombre de la tabla de formatos join categories (titleeselnombre de la vista)
     * Traer los campos con status 1 (Activos)
     * Retornar la tabla de cache
     * @param: $prefix  Nombre del componente
     * @return array  Array de campos
     *
     * @author: Jorge Castellanos.
     * @version       1.0
     * @copyright: Fratris S.A.S
     *
     **/
    public function getID($condicion, $tabla, $useprefix)
    {
        $id = false;
        $prefix = "";
        if ($useprefix != "")
            $prefix = $this->prefix . "_";
        $tabla = "#__" . $prefix . $tabla;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id');
            $query->from($db->quoteName($tabla));
            $query->where($condicion);
            $db->setQuery($query);
            $id = $db->loadResult();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'Helper:getID', 'error');
            return false;
        }
        return $id;
    }


    public function getCamposDeDatosMaster($menu)
    {
        $tablatablas = "#__" . $this->prefix . "_campos_select_master";
        $campos = false;
        $datos = array();
        $descripcion = array();
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('campo,descripcion');
            $query->from($db->quoteName($tablatablas));
            $where = "  TRIM(MENU)  = '" . trim($menu) . "' AND listar = 1 ";;
            $query->where($where);
            $query->order('orden ASC');
            $db->setQuery($query);
            $campos = $db->loadRowList();
            foreach ($campos as $campo) {
                $datos[] = $campo[0];
                $descripcion[] = $campo[1];
            }
            $campos = array($datos, $descripcion);
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'Helper:getCamposSelec', 'error');
        }
        return $campos;
    }

    public function getTablasMaster($menu)
    {
        $tablatablas = "#__" . $this->prefix . "_vistas_tablas_master";
        $tablas = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName($tablatablas));
            $query->where("MENU ='" . $menu . "'");
            $query->order('orden ASC');
            $db->setQuery($query);
            $tablas = $db->loadAssocList();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'getTables', 'error');
            return false;
        }
        return $tablas;
    }

    public function getproductosbyreference($id)
    {
        $tablas = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('v.id', 'v.valor', 'componente', 'producto', 'r.descripcion', 'r.referencia', 'r.id'), array('id', 'valor', 'componente', 'producto', 'descripcion', 'referencia', 'rid')));
            $query->from($db->quoteName('#__invoices_productosreferencias', 'r'));
            $query->join('INNER', $db->quoteName('#__invoices_productos', 'p') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('r.idproducto') . ')');
            $query->join('INNER', $db->quoteName('#__invoices_productoscomponentes', 'c') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('c.idproducto') . ')');
            $query->join('INNER', $db->quoteName('#__invoices_productoscomponentesvalores', 'v') . ' ON (' . $db->quoteName('v.idproductocomponente') . ' = ' . $db->quoteName('c.id') . ')');
            $query->where($db->quoteName('r.id') . '=' . $id);
            $query->order('r.id', 'producto', 'componente', 'valor');
            $db->setQuery($query);
            $tablas = $db->loadAssocList();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'getTables', 'error');
            return false;
        }
        return $tablas;
    }

    public function getproductosbyid($id)
    {
        $tablas = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('v.id', 'v.valor', 'v.imagen,c.componente')));
            $query->from($db->quoteName('#_productoscomponentesvalores', 'v'));
            $query->join('INNER', $db->quoteName('#__invoices_productoscomponentes', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('v.idproductocomponente') . ')');
            $query->join('INNER', $db->quoteName('#__fratris_invoices_productos', 'p') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('c.idproducto') . ')');
            $query->where($db->quoteName('p.id') . '=' . $id);
            $db->setQuery($query);
            $tablas = $db->loadAssocList();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'getTables', 'error');
            return false;
        }
        return $tablas;
    }


    public function deleteproductosreferenciasdetalles($id)
    {
        $resultado = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('idreferencia') . ' = ' . $id
            );
            $query->delete($db->quoteName('#__invoices_productosreferenciasdetalles'));
            $query->where($conditions);
            $db->setQuery($query);
            $resultado = $db->execute();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'getTables', 'error');
            return false;
        }
        return $resultado;

    }


    public function getcomponentesbyproductosreference($id)
    {
        $tablas = false;
        try {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('componente', 'producto', 'r.descripcion', 'r.referencia', 'r.id'), array('componente', 'producto', 'descripcion', 'referencia', 'rid')));
            $query->from($db->quoteName('#__invoices_productosreferencias', 'r'));
            $query->join('INNER', $db->quoteName('#__invoices_productos', 'p') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('r.idproducto') . ')');
            $query->join('INNER', $db->quoteName('#__invoices_productoscomponentes', 'c') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('c.idproducto') . ')');
            $query->where($db->quoteName('r.id') . '=' . $id);
            $query->order('r.id', 'producto', 'componente');
            $db->setQuery($query);
            $tablas = $db->loadAssocList();
        } catch (RuntimeException $e) {
            InvoicesErrorHelper::manejarerror($e->getMessage(), 'getTables', 'error');
            return false;
        }
        return $tablas;
    }

}

