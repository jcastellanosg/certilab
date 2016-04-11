<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelGenerador extends JModelList
{
    private $parametros;
    private $querytotal;
    private $operators = [
        '=' => '='
    ];


    public function __construct($config = array())
    {
        $jinput = JFactory::getApplication()->input;
        $this->parametros['componente'] = $jinput->get('option', '', 'WORD');
        $this->parametros['menu'] = $jinput->get('menu', 0, 'INT');
        $this->parametros['arraydecondiciones'] = $jinput->get('arraydecondiciones', [], 'ARRAY');;
        $this->parametros['arrayorder'] = $jinput->get('order', null, 'ARRAY');
        $this->parametros['prefix'] = JFactory::getApplication()->get('dbprefix');
        $this->parametros['vista'] = 'Test';
        parent::__construct($config);


    }


    public function getTotalDb()
    {
        try {
            $total = (int)$this->_getListCount($this->querytotal);
        } catch (RuntimeException $e) {
            //ManejarEventos::manejarEvento(array("clase" => "InvoicesModelListar", "evento" => 1, "id" => 1, "message" => $this->querytotal, "type" => 1));
            $total = 0;
            return $e->getMessage();
        }
        return $total;
    }


    /**
     * Construir el Query para el listado requerido
     * @return string: Un query listo ara ejecutar
     */
    public function getListQuery()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $paginacion = $this->setPaginacion();
        $querysring = $this->getQueryComands() . " LIMIT {$paginacion['start']} ,{$paginacion['limit']}";
        $query->setQuery($querysring);
        return $query;
    }


    /*
     * Inicializa las variables de estado list.start y y list.limit, que se encargan de paginar los resultados del query
     * list, estas toman los valores del input (variables start y length)
     * "start":"0","length":"100"
     *
     */
    public function setPaginacion()
    {
        $jinput = JFactory::getApplication()->input;
        $start = $jinput->get('start', '0', 'INT');
        $limit = $jinput->get('length', '0', 'INT');
        return ["start" => $start, "limit" => $limit];
    }


    /**
     * Generar nuevo arreglo [False..True] por cada tabla validada , si existe n false retorna con error
     * Genera un nuevo array de tipos de relaciones entre tablas
     * los campos de datos
     * @return object
     */
    public function getQueryComands()
    {
        $queryselect = [];

        $queryselect[] = "SELECT " . implode(",\n", $this->getQuerySelect());

        $queryselect[] = implode(" ", $this->getQueryTables());

        $condiciones = $this->getQueryWhere();
        if (!empty($condiciones)) {
            $queryselect[] = "WHERE " . implode(" AND ", $condiciones);
        }

        $orden = $this->getQueryOrder();
        if (!empty($orden)) {
            $queryselect[] = "ORDER BY {$orden}";
        }
        return implode(' ', $queryselect);
    }


    /**
     * Recuperar datos de la Base de Datos
     * @return array con dos indices OK (False|True) y data de la Base de Datos
     */
    public function getDataFromBD($parametros)
    {
        $resultado = ['OK' => false];
        try {
            if (isset($parametros['tabla']) && isset($parametros['select'])) {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select($parametros['select']);
                if (is_array($parametros['tabla'])) {
                    $query->from($db->quoteName($parametros['tabla']['tabla'], $parametros['tabla']['prefijo']));
                } else {
                    $query->from($db->quoteName($parametros['tabla']));
                }
                if (isset($parametros['join'])) {
                    $query->join($parametros['join']['tipo'], $parametros['join']['join']);
                }
                if (isset($parametros['where'])) {
                    $query->where($parametros['where']);
                }
                if (isset($parametros['order'])) {
                    $query->order($parametros['order']);
                }
                $db->setQuery($query);
                $campos = $db->loadAssocList();
                $resultado = ['OK' => true, 'data' => $campos];
            }
        } catch (RuntimeException $e) {
            $resultado = [$e->getMessage()];
            //ManejarEventos::manejarEvento(array('evento' => 1, "clase" => "HelperFacturacion", "id" => 1, "message" => $e->getMessage(), "type" => 'error'));
        }
        return $resultado;
    }


    function autorizeEdition()
    {

    }

    function autorizeCreate()
    {
        return true;
    }

    function autorizeEdit()
    {
        return true;
    }

    function autorizeDelete()
    {
        return true;
    }


    public function addActions($campos)
    {
        $edit = $this->autorizeEdit();
        $create = $this->autorizeCreate();
        $delete = $this->autorizeDelete();
        if ($edit) {
            $campos[] = '1 AS Editar';
        }
        if ($create) {
            $campos[] = '1 AS Crear';
        }
        if ($delete) {
            $campos[] = '1 AS Editar';
        }
        return $campos;
    }


    private function satinizarCampo($campo, $alias = '')
    {
        $db = JFactory::getDbo();
        return !empty($alias) ? $db->quoteName($campo, $alias) : $db->quoteName($campo);
    }

    private function satinizarValue($value)
    {
        $db = JFactory::getDbo();
        return $db->quote($value);
    }


    /**
     * Recupera los datos de los campos dependiendo del menu, se recupera de la BD o del cache
     * @return array un arreglo asociativo con los campos
     */
    public function getCamposDeDatos()
    {
        $resultado = ['OK' => false];
        $componente = $this->parametros['componente'];
        $menu = $this->parametros['menu'];
        $tabla = "#__{$componente}_cnf_campos";
        $id = $tabla . $menu;
        $store = $this->getStoreId($id);
        if (!isset($this->cache[$store])) {
            $parametros['select'] = '*';
            $parametros['tabla'] = $tabla;
            $parametros['where'] = 'menu = ' . (int)$menu;
            $parametros['order'] = 'menu ASC';
            $resultado = $this->getDataFromBD($parametros);
            $resultado['data'] = array_map([$this, 'getTableDotColumn'], $resultado['data']);
            if ($resultado['OK']) {
                $this->cache[$store] = $resultado['data'];
                $resultado = $this->cache[$store];
            }
        } else {
            $resultado = $this->cache[$store];
        }
        return $resultado;
    }

    private function getTableDotColumn($row)
    {
        $table = trim($row['table_alias']);
        $columna = $row['column_name'];
        $descripcion = $row['column_alias'];
        $formula = $columna . " AS " . $this->satinizarValue($descripcion);
        $row['column_select'] = $table != '' ? $this->satinizarCampo("{$table}.{$columna}", $descripcion) : $formula;
        return $row;
    }

    /**
     *  Recupera los datos (de la BD o del cache) de la tabla cnf_tablas para el menu correspondiente.
     * @return: Arreglo asociativo con los nombres y relaciones de las tablas
     */
    private function getTables()
    {
        $resultado = [];
        $componente = $this->parametros['componente'];;
        $menu = $this->parametros['menu'];
        $tabla = "#__{$componente}_cnf_tablas";
        $id = $tabla . $menu;
        $store = $this->getStoreId($id);
        if (!isset($this->cache[$store]) || empty($this->cache[$store])) {
            $parametros['select'] = '*';
            $parametros['tabla'] = $tabla;
            $parametros['where'] = 'menu = ' . (int)$menu;
            $parametros['order'] = 'orden ASC';
            $resultado = $this->getDataFromBD($parametros);
            $resultado['data'] = array_map([$this, 'getTableCondition'], $resultado['data']);
            if ($resultado['OK']) {
                $this->cache[$store] = $resultado['data'];
                $resultado = $this->cache[$store];
            }
        } else {
            $resultado = $this->cache[$store];
        }
        return $resultado;
    }


    private function getTableCondition($row)
    {
        $row['table_select'] = trim($row['tipo']) == 'UNION' ? $this->getFromTable($row) : $this->getFromJOIN($row);
        return $row;
    }

    private function getFromTable($row)
    {
        $table = $this->satinizarCampo($row['tabla'], $row['alias']);
        return " FROM {$table} ";
    }

    private function getFromJOIN($row)
    {
        $table = $this->satinizarCampo($row['tabla'], $row['alias']);
        $condition = !empty($row['condicion']) ? $this->createJoinRelation($row['condicion']) : '';
        return "{$row['tipo']} JOIN $table $condition";
    }

    /**
     * Validar que las condiciones del JOIN TYPE cumplan el patron table.field = table.field
     * Si las condiciones son validas genera un string de condicion valido
     * @param $row []  por cada registro de la tabla Tablas
     * return string de condicion
     */
    private function createJoinRelation($row)
    {
        preg_match_all("/\s*(\w+\.\w*)\s*=\s*(\w+\.\w+)\s*(AND)*/i", $row, $resultado);
        $condiciones = array_map([$this, 'createJoinCondition'], $resultado[1], $resultado[2]);
        $condiciones = implode(" AND ", $condiciones);
        return "ON {$condiciones}";
    }

    private function createJoinCondition($field1, $field2)
    {
        $field1 = $this->satinizarCampo($field1);
        $field2 = $this->satinizarCampo($field2);
        return "{$field1} = {$field2}";
    }

    private function getAliasCampos()
    {
        $resultado = [];
        $menu = $this->parametros['menu'];
        $id = 'aliascampos' . $menu;
        $store = $this->getStoreId($id);
        if (!isset($this->cache[$store]) || empty($this->cache[$store])) {
            $campos = $this->getCamposDeDatos();
            $aliascampos = array_filter(array_combine(array_column($campos, 'column_alias'), array_column($campos, 'column_tabledotname')));
            $this->cache[$store] = $aliascampos;
            $resultado = $this->cache[$store];
        } else {
            $resultado = $this->cache[$store];
        }
        return $resultado;
    }


    public function getQuerySelect()
    {
        $campos = $this->getCamposDeDatos();
        return array_column($campos, 'column_select');
    }

    public function getQueryTables()
    {
        $tables = $this->getTables();
        return array_column($tables, 'table_select');
    }


    /**
     * Trae datos(condiciones) de la tabla WHERE y del array de entrada where
     * Devuelve un array con un index 'OK', valores  OK o NOK, si es OK devuelve un index 'data' cuyo valor es
     * un string de condicion para el WHERE
     * @return array con dos indices OK (False|True) y data de la Base de Datos
     */
    public function getQueryWhere()
    {
        $condicionfromtable = $this->getQueryWhereFromTable();
        $condicionfrominput = $this->getQueryWhereFromInput();

        $condiciones = array_merge($condicionfromtable, $condicionfrominput);
        return $condiciones;
        $condiciones = array_map([$this, 'replaceCondition'], array_merge($condicionfromtable, $condicionfrominput));
        return implode(' AND ', $condiciones);
    }

    /**
     * Recupera las condiciones de la Base de Datos, de acuerdo al menu requerido
     * @param $parametros
     * @return array con dos indices OK (False|True) y data: string de condiciones
     */
    public function getQueryWhereFromTable()
    {
        $componente = $this->parametros['componente'];
        $menu = $this->parametros['menu'];
        $tabla = "#__{$componente}_cnf_where";
        $parametros['select'] = 'condicion';
        $parametros['tabla'] = $tabla;
        $parametros['where'] = 'menu = ' . (int)$menu;
        $parametros['order'] = 'orden ASC';
        $data = $this->getDataFromBD($parametros);
        return ($data['OK']) ? array_column($data['data'], 'condicion') : [];
    }


    /**
     * Recupera las condiciones de la variable  POST  datawhere
     * un string de condicion para el WHERE
     * @return array con dos indices OK (False|True) y data: string de condiciones
     */
    public function getQueryWhereFromInput()
    {
        $condiciones = $this->parametros['arraydecondiciones'];
        $condiciones = array_map(array($this, 'doWhereCondition'), $condiciones);
        return array_column(array_filter($condiciones), 'condition');
    }


    function doWhereCondition($row)
    {
        $aliascampos = ($this->getAliasCampos());
        $valor = $this->validateValue($row['type'], $row['value'], $row['operator']);
        $campo = $row['field'];
        $operador = $row['operator'];
        if (!array_key_exists($campo, $aliascampos) || !array_key_exists($operador, $this->operators) || !$valor) {
            $row['condition'] = false;
            return $row;
        }
        $campo = $this->satinizarCampo($aliascampos[$row['field']]);
        $row['condition'] = "{$campo} {$this->operators[$row['operator']]}  {$valor} ";
        return $row;
    }

    function validateValue($type, $value)
    {
        $valor = false;
        switch ($type) {
            case 'string':
                $valor = $this->satinizarValue((string)preg_replace('/[^A-Z_0-9]/i', '', $value));

                break;
            case 'int':
                preg_match('/-?[0-9]+/', (string)$value, $matches);
                $resultado['value'] = @ (int)$matches[0];
                if ($resultado['value'] != $value) {
                    $resultado['message'] = 'El valor no es valido';
                }
                break;
        }
        return $valor;
    }


    public function getDataToDataTable()
    {
        $campos = $this->getCamposDeDatos();
        $campos = array_filter(array_map([$this, 'getHeader'], $campos));
        return array('header' => array_column($campos, 'header'),
            'columns' => array_column($campos, 'columns'),
            'column_tabledotname' => array_column($campos, 'column_tabledotname')
        );
    }


    private function getHeader($row)
    {
        if ($row['listar'] == '1') {
            return false;
        }
        $resultado['columns'] = '{"name":"' . $row['descripcion'] . '","data": "' . $row['column_alias'] . '"}';
        $resultado['header'] = $row['descripcion'];
        $resultado['column_tabledotname'] = $row['column_tabledotname'];
        return $resultado;
    }


    public function getQueryOrder()
    {
        $columns = [];
        $campos = $this->getDataToDataTable()['column_tabledotname'];
        $arrayorder = $this->parametros['arrayorder'];
        foreach ($arrayorder as $field) {
            $index = (int)$field['column'];
            if ($index < count($campos)) {
                $direction = $field['dir'] == 'asc' ? 'ASC' : 'DESC';
                $columns[] = "{$campos[$index]}  {$direction}";
            }
        }
        return implode(",", $columns);
    }


    public function getParametersModelo()
    {

        $component = explode("_", $this->parametros['componente']);
        $component = ucfirst($component[1]);

        $campos = $this->getCamposDeDatos();
        $campos = array_map([$this, 'createArrayCampos'], $campos);
        $campos = '[' . implode(",\n\t\t\t\t\t\t\t\t\t", $campos) . ']';

        $header = $this->getDataToDataTable();
        $header = "['" . implode("',\n\t\t\t\t\t\t\t\t\t'", array_map([$this, 'satinizarCampo'], $header['column_tabledotname'])) . "']";


        $where = "['" . implode("','", $this->getQueryWhereFromTable()) . "']";


        $queryselect[] = "SELECT " . implode(",\n\t\t\t\t\t\t\t\t\t\t", $this->getQuerySelect());
        $queryselect[] = "\n\t\t\t\t\t\t\t\t\t" . implode("\n\t\t\t\t\t\t\t\t\t ", $this->getQueryTables());

        $parameters["element"] = "modelo";
        $parameters["component"] = $component;
        $parameters["view"] = $this->parametros['vista'];
        $parameters["campos"] = $campos;
        $parameters["header"] = $header;
        $parameters["queryselect"] = implode(' ', $queryselect);
        $parameters["conditionfromtable"] = $where;
        return $parameters;
    }

    private function createArrayCampos($row)
    {
        $alias = $row['column_alias'];
        $campo = $this->satinizarCampo($row['column_tabledotname']);
        $tipo = $row['column_type'];
        return "'{$alias}'=>['campo'=>'{$campo}','tipo'=>'{$tipo}']";
    }


    public function getParametersView()
    {
        $component = explode("_", $this->parametros['componente']);
        $component = ucfirst($component[1]);
        $parameters["element"] = "view";
        $parameters["component"] = $component;
        $parameters["view"] = $this->parametros['vista'];
        return $parameters;
    }

    public function getParametersTemplate()
    {
        $tabs = "\t\t\t";
        $parameters = $this->getDataToDataTable();
        $parameters["view"] = $this->parametros['vista'];
        $parameters['header'] = "<th>" . implode("</th>\n{$tabs}<th>",$parameters['header']). "</th>";
        $parameters['columns'] = "[" . implode(",\n{$tabs}{$tabs}",$parameters['columns']). "]";
        $parameters["element"] = "template";
        return $parameters;
    }


    public function getParametersController()
    {
        $tabs = "\t\t\t";
        $component = explode("_", $this->parametros['componente']);
        $component = ucfirst($component[1]);
        $parameters["component"] = $component;
        $parameters["view"] = $this->parametros['vista'];
        $parameters["element"] = "controller";
        return $parameters;
    }

}




