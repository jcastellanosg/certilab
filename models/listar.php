<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */
class CertilabModelListar extends JModelList
{

    public $menu;
    public $arraycolumnas;
    public $arrayorden;
    public $orden;
    public $querytotal;
    private $helper;
    private $vista;
    private $layout;
    private $id;
    private $campo;

    public function __construct($config = array())
    {
        $jinput = JFactory::getApplication()->input;
        $this->menu = $jinput->get('menu', 0, 'int');
        $this->arraycolumnas = $jinput->get('columns', null, 'ARRAY');
        $this->arrayorden = $jinput->get('order', null, 'ARRAY');
        $this->vista = $jinput->get('view', '', 'WORD');
        $this->layout = $jinput->get('layout', '', 'WORD');
        $this->id = $jinput->get('id', 0, 'INT');
        $this->campo = $jinput->get('campo', '', 'CMD');
        $this->helper = new HelperFacturacion();
        parent::__construct($config);
    }


    public function getTotalDb()
    {
       try {
                $total = (int)$this->_getListCount($this->querytotal);
            } catch (RuntimeException $e) {
                ManejarEventos::manejarEvento(array("clase" => "InvoicesModelListar", "evento" => 1, "id" => 1, "message" => $this->querytotal, "type" => 1));
                $total = 0;
                return $e->getMessage();
            }
        return $total;
    }


    public function getKeyActual()
    {
        return array($this->campo, $this->id);
    }

    /***********************/
    /*  Empezar de nuevo   */
    /***********************/


    /**
     * Arma el query para los campos que se va mostrar
     * @return string: Un query listo ara ejecutar
     */
    public function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $dispatcher = JEventDispatcher::getInstance();
        $select = $this->getCamposDeQuerySelect();
        if (empty($select)) {
            ManejarEventos::manejarEvento(array("clase" => "InvoicesModelListar", "id" => 1, "exception" => $query->dump()));
        } else {
            $query->select($db->quoteName($select[0], $select[1]));
            if ($this->getQueryTables($query)) {
                $this->querytotal = clone($query);

                $orden = $this->getQueryOrder($this->arraycolumnas, $this->arrayorden);
                if ($orden) {
                    $query->order($orden);
                }
                $where = $this->getQueryWhere($this->arraycolumnas);
                if (!empty($where)) {
                    $query->where($where);
                }
                $this->setPaginacion($this);
            }
        }
        return $query;

    }

    /**
     * Del Arreglode campos selecciona los campospor ID y Campo
     * El campos es elnombre en la base de datos y ID se usra de manera interna en el html
     * @return array
     */
    public function getCamposDeQuerySelect()
    {
        $resultado = array();
        $alias = array();
        $campos = array();
        $tabla = $this->getCamposDeDatos();
        if (empty($tabla)) {
            ManejarEventos::manejarEvento(array("clase" => "InvoicesModelListar", "id" => 1));
        } else {
            foreach ($tabla as $campo) {
                if (($campo['listar'] || $campo['editar']) && $campo['campo'] != 'x') {
                    $campos[] = $campo['campo'];
                    $alias[] = $campo['id'];
                }
            }
            $resultado = array($campos, $alias);
        }
        return $resultado;
    }

    /**
     * Recupera los registros de la tabladecamposde datos de cache
     * @return array un arreglo asociativo con los campos
     */
    public function getCamposDeDatos()
    {
        $campos = array();
        $id = 'getTablaDeDatos' . $this->menu;
        $store = $this->getStoreId($id);
        if (!isset($this->cache[$store])) {
            $tabla = $this->helper->getCamposDeDatos($this->menu);
            // Cambiar el tipo del campo si ya tiene un valor sedebe hacer oculto (Viene en la url)
            // en el foreach cada row es una copia para modificarla se debe hacer por referencia
            foreach ($tabla as &$rows) {
                if ($rows['id'] == $this->campo) {
                    $rows['formacreacion'] = 'oculto';
                }
            }
            if ($tabla) {
                $this->cache[$store] = $tabla;
                $campos = $this->cache[$store];
            }
        } else {
            $campos = $this->cache[$store];
        }
        $campos = $this->helper->getCamposDeDatos($this->menu);

        return $campos;
    }

    /**
     * @param JDatabaseQuery $query , este es el query actual,donde se agregaran las tablas de las que se va a seleccionar
     * los campos de datos
     * @return object
     */
    public function getQueryTables(JDatabaseQuery $query)
    {
        $resultado = false;
        $db = $this->getDbo();
        $tablas = $this->getTablas($this->menu);
        if (empty($tablas)) {
            ManejarEventos::manejarEvento(array("clase" => "InvoicesModelListar", "id" => 1, "exception" => $query->dump()));
        } else {
            $i = 0;
            foreach ($tablas as $tabla) {
                if (!$i) {
                    $query->from($db->quotename($tabla['tabla'], $tabla['alias']));
                } else {
                    $query->join($tabla['tipo'], $db->quotename($tabla['tabla'], $tabla['alias']) . " " . $tabla['condicion']);
                }
                $i++;
            }
            $resultado = true;
        }
        return $resultado;
    }

    /**
     * Recupera los registros de la tabladecamposde datos de cache
     *     * @return: un arreglo asociativo con los nombres delas tablas
     */
    public function getTablas()
    {
        $tablas = array();
        $store = $this->getStoreId('getTablas' . $this->menu);
        if (!isset($this->cache[$store])) {
            $tabla = $this->helper->getTablas($this->menu);
            if ($tabla) {
                $this->cache[$store] = $tabla;
                $tablas = $this->cache[$store];
            }
        } else {
            $tablas = $this->cache[$store];
        }
        return $tablas;
    }

    /**
     * @param $arraycolumnas :  Datatables envia dos array este es el nombre de todas las columnas que tiene definidas
     * @param $arrayorden : Este es un array con las columnas por las que se debe ordenar
     * @return string en formato tabla.campo ASC/DESC, abla.campo ASC/DESC para agrefgrloalquery como parte del
     * query
     */
    public function getQueryOrder($arraycolumnas, $arrayorden)
    {
        $camposdeseleccion = "";
        $coma = "";
        if (!empty($arrayorden)) {
            foreach ($arrayorden as $orden) {
                $indice = $orden['column'];
                if (is_numeric($indice)) {
                    $direccion = $orden['dir'] == "asc" ? " asc " : " desc ";
                    $campo = $this->getCampoValido($arraycolumnas[$indice]['data']);
                    if (!empty($campo)) {
                        $camposdeseleccion .= "{$coma} {$campo} {$direccion}";
                        $coma = ",";
                    }
                }
            }
        }
        return $camposdeseleccion;
    }

    /**
     * Nombre del campo en formato tabla_campo
     * A partir del campo id tabla_campo obtiene elnombre correcto del campo con elformato tabla.campo validando
     * que el campo exist en la tabla;
     * @campo El valor del campo quese requiere validar
     */
    public function getCampoValido($campo)
    {
        $campovalido = "";
        $campos = $this->getCamposDeDatos();
        foreach ($campos as $row) {
            if (trim($row['id']) == trim($campo)) {
                $campovalido = $row['campo'];
            }
        }
        return $campovalido;
    }
    public function getNombreCampoValido($campo)
    {
        $campovalido = "";
        $campos = $this->getCamposDeDatos();
        foreach ($campos as $row) {
            if (trim($row['campo']) == trim($campo)) {
                $campovalido = $row['campo'];
            }
        }
        return $campovalido;
    }

    public function getQueryWhere($arraycolumnas)
    {
        $camposdeseleccion = $this->campoMasterDetail();
        if (isset($arraycolumnas)) {
            foreach ($arraycolumnas as $rows) {
                $campo = trim($rows['search']['value']);
                if ($campo != "") {
                    $resultado = $this->recuperarValorDelWhere($campo);
                    if (!empty($resultado))
                        $camposdeseleccion[] = $resultado;
                }
            }
        }

        $camposwhere = $this->helper->getCamposWhere($this->menu);
        foreach ($camposwhere as $campo) {
            $camposdeseleccion[] = $campo[0];
        }
        $jinput = JFactory::getApplication()->input;
        $campo = $jinput->get('campo', '', 'string');
        $valor = $jinput->get('id', 0, 'INT');
        $campo = $this->getNombreCampoValido($campo);
        if ($campo != "") {
            $camposdeseleccion[] = $campo . " = " . $valor;
        }

        $camposdeseleccion = implode(" AND ", $camposdeseleccion);
        return $camposdeseleccion;

    }

    private function recuperarValorDelWhere($campo)
    {
        $camposdeseleccion = "";
        $campo = explode("#", $campo);
        $campos = $this->getCamposDeDatos();
        foreach ($campos as $row) {
            if (trim($row['id']) == trim($campo[0])) {
                $funciontraervalor = "getValue" . trim(ucfirst($row['formabusqueda']));
                $camposdeseleccion = HelperTemplates::getInstance()->{$funciontraervalor}($row['campo'], $campo);
                break;
            }
        }
        return $camposdeseleccion;
    }

    public function campoMasterDetail()
    {
        $camposdeseleccion = array();
        if ($this->campo != '') {
            $campo = $this->getCampoValido($this->campo);
            if ($campo) {
                $camposdeseleccion[] = "{$campo} = {$this->id}";
            }
        }
        return $camposdeseleccion;
    }


    public function setPaginacion()
    {
        $jinput = JFactory::getApplication()->input;
        $start = $jinput->get('start', '0', 'INT');
        $limit = $jinput->get('length', '0', 'INT');
        $this->setState('list.start', $start);
        $this->setState('list.limit', $limit);
    }

    private function aplicarFormato($rows)
    {
        $formatos = array();
        $campos = $this->getCamposDeDatos();
        foreach ($campos as $campo) {
            if (!empty($campo['formato'])) {
                $formatos[$campo['id']] = $campo['formato'];
            }
        }

        foreach ($rows as &$row) {
            foreach ($formatos as $key => $value) {
                switch ($value) {
                    case 'imagen' :
                        $thumbnail = "";
                        $imagen = $row->{$key};
                        if (strpos($key,'_foto') >= 0 ){
                            $thumbnail = str_replace('_foto','_thumbnail',$key);
                            $thumbnail = $row->{$thumbnail};
                        }
                        if(strrpos($imagen, ".gif") > 0 || strrpos($imagen, ".jp") > 0 || strrpos($imagen, ".png") > 0 ) {
                            $row->{$key} = "<a href='{$imagen}' class='fancybox-button' data-rel='fancybox-button'>
											<img src='{$thumbnail}' alt=''>
										</a>";
                        } else {
                            $row->{$key} = "<a href='{$imagen}'><img src='{$thumbnail}' alt=''></a>";
                        }
                        break;
                    case 'imgfancy' :
                        $thumbnail = $row->{$key};
                        $imagen = str_replace("/thumbnail/","/",$thumbnail);
                        if(strrpos($imagen , ".gif") > 0 || strrpos($imagen , ".jp") > 0 || strrpos($imagen , ".png") > 0 )
                        {
                            $row->{$key} = "<a href='{$imagen}' class='fancybox-button' data-rel='fancybox-button'>
											<img src='{$thumbnail}' alt=''>
										</a>";
                        } else {
                            $row->{$key} = "<a href='{$imagen}'>Documento</a>";
                        }
                        break;
                    case 'money' :
                        $imagen = $row->{$key};
                        $row->{$key} = "<img src='{$imagen}' >";
                        break;
                    case 'switchbox' :
                        $data = $row->{$key};
                        $row->{$key} = $data == 1 ? '<p class="text-success">Activo</p>':'<p class="text-danger">Inactivo</p>';
                        break;
                }
            }
        }
        return $rows;
    }

    public function getItemsMoreLinks()
    {
        $rows = parent::getItems();
        if (!empty($rows)) {
            $rows = $this->aplicarFormato($rows);
            $campoid = $this->getCampoID();
            $links = (array)$this->getLinks();
            if (count($links) > 0) {
                foreach ($rows as &$row) {
                    foreach ($links as $link) {
                        if (!$link['boton']) {
                            $id = $link['descripcion'];
                            if (strpos($link['url'], '#ID#'))
                                $link['url'] = str_replace("#ID#", $row->{$campoid}, $link['url']);
                            $row->{$id} = $link['url'];
                        }
                    }
                }
            }
        }
        return $rows;
    }


    public function getCampoID()
    {
        $tablas = $this->getTablas($this->menu);
        return "{$tablas[0]['alias']}_id";
    }

    public function getLinks()
    {
        $links = array();
        // Traer la tabla de cache.
        $store = $this->getStoreId('getLinks' . $this->menu);
        // Try to load the data from internal storage.
        if (!isset($this->cache[$store])) {
            $tabla = $this->helper->getLinks($this->menu);
            if ($tabla) {
                foreach ($tabla as &$link) {
                    if ($link['url'] != "#") {
                        $url = "?option=" . $this->option;
                        $url .= $link['vista'] != trim("") ? "&view=" . $link['vista'] : "";
                        $url .= $link['layout'] != trim("") ? "&layout=" . $link['layout'] : "";
                        $url .= $link['menulink'] != trim("") ? "&menu=" . $link['menulink'] : "";
                        $url .= $link['campo'] != trim("") ? "&campo=" . $link['campo'] . "&id=#ID#" : "";
                        $url .= $link['task'] != trim("") ? "&task=" . $link['task'] : "";
                        $url = "href='" . JURI::current() . $url . $link['url'] . "'";
                    } else {
                        $url = "";
                    }
                    $onclick = "";
                    if (trim($link['onclick']) != "")
                        $onclick = "onclick='{$link['onclick']};return false;' ";
                    $datatag = trim($link['modelo']) != "" ? "data-facturacion-tipo = 'accion' data-facturacion-accion ='{$link['modelo']}'" : "";
                    $link['url'] = "<a  {$url} {$onclick} data-facturacion-tipo = 'accion' data-facturacion-accion ='{$link['modelo']}' ><i {$datatag} class='{$link['icono']}'></i></a>";
                    // $link['url'] = "<a class='lnk_{$link['descripcion']}' {$url} {$onclick} >{$link['icono']}</a>";
                }
                $this->cache[$store] = $tabla;
                $links = $this->cache[$store];
            }
        } else {
            $links = $this->cache[$store];
        }
        return $links;
    }

}



