<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:23 PM
 */


defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/models/crear.php';

JLoader::registerNamespace('Respect', JPATH_COMPONENT . '/helpers/validator');

//use Respect\Validation\Validator as v;

class InvoicesModelFacturasdetalles extends JModelAdmin
{
    protected $text_prefix = 'COM_INVOICES';
    protected $tabla;
    protected $data;

    public function guardarData($camposdedatos, $tablas)
    {
        $resultado = array();
        $jinput = JFactory::getApplication()->input;
        $empresa = $jinput->get('empresa', 0, 'INT');
        $cliente = $jinput->get('cliente', 0, 'INT');
        $factura = $this->crearFactura($empresa, $cliente);
        $items = $jinput->get('items', 0, 'ARRAY');
        $referencias = array();
        $total = 0;
        foreach ($items as $item) {
            $referencia = $this->traerDatosDeTabla($item['idreferencia'], 'productosreferencias');
            $vrunitario = $referencia['costo'];
            $vrtotal = $vrunitario * $item['cantidad'] ;
            $total = $total + $vrtotal;
            $data = array('id' => 0,
                'idfactura' => $factura,
                'idreferencia' => $item['idreferencia'],
                'valorunitario' => $vrunitario,
                'valortotal' => $vrtotal,
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion']);
           if (!$this->save($data)) {
               $resultado['error'] = 'Error salvando detalles';
               $resultado['data'] = $data;
           }

        }
        $this->actualizarFactura($factura);
        $this->actualizarEmpresa($empresa);
        $resultado['factura'] = $factura;
        return $resultado;
    }


    public function __construct($config = array())
    {
        $jinput = JFactory::getApplication()->input;
        $menu = $jinput->get('menu', 0, 'INT');
        $helper = new HelperComponent;
        $tablas = $helper->getTablas($menu);
        $tabla = $tablas[0]['TABLE_NAME'];
        $this->tabla = substr(strrchr($tabla, "_"), 1);
        parent::__construct($config);
    }


    public function getTable($type = '', $prefix = 'InvoicesTable', $config = array())
    {
        $type = $this->tabla;
        return JTable::getInstance($type, $prefix, $config);
    }


    public function getForm($data = array(), $loadData = true)
    {
    }

    protected function prepareTable($table)
    {
        //$table->pais = htmlspecialchars_decode($table->pais,ENT_QUOTES);
    }


    public function validateFormData($camposdedatos)
    {
        $msg = array();
        return $msg;
    }


    private function crearFactura($empresa,$cliente)
    {
        $rowempresa = $this->traerDatosDeTabla($empresa,'empresas');
        $rowcliente = $this->traerDatosDeTabla($cliente,'empresasclientes');
        $vencimiento = 0;
        if (!empty($rowcliente)){
            $vencimiento = $rowcliente['facturasvencimiento'];
        }
        $vencimiento = "now +{$vencimiento} day";

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $columns = array('id',
            'idempresa',
            'idcliente',
            'fechacreacion',
            'fechavencimiento',
            'nrofactura',
           );

        $values = array(0,
            $empresa,
            $cliente,
            "'".new JDate('now')."'",
            "'".new JDate($vencimiento)."'",
            "'{$rowempresa['prefijofactura']} {$rowempresa['nrofactura']} {$rowempresa['sufijofactura']}'"
            );
        $query
            ->insert($db->quoteName('#__invoices_facturasencabezados'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));

        $db->setQuery($query);
        $db->execute();
        return $db->insertid();

    }

    private function traerDatosDeTabla($id,$tabla)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__invoices_'.$tabla));
        $query->where($db->quoteName('id') . ' = ' . $id);
        $db->setQuery($query);
        $row = $db->loadAssoc();
        return $row;
    }

    private function actualizarEmpresa($empresa)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array($db->quoteName('nrofactura') . ' = nrofactura + 1');
        $conditions = array($db->quoteName('id') . ' = '. $empresa );
        $query->update($db->quoteName('#__invoices_empresas'))->set($fields)->where($conditions);
        $db->setQuery($query);
        return $db->execute();

    }

    private function actualizarFactura($factura)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('sum(valortotal)');
        $query->from($db->quoteName('#__invoices_facturasdetalles'));
        $query->where($db->quoteName('idfactura') . ' = ' . $factura);
        $query->group($db->quote('idfactura'));
        $db->setQuery($query);
        $total = $db->loadResult();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array($db->quoteName('neto'). '='. $total ,$db->quoteName('impuestos') . '='.($total*16)/100,$db->quoteName('total') . '='.($total*116)/100,$db->quoteName('saldo') . '='.($total*116)/100);
        $conditions = array($db->quoteName('id') . ' = '. $factura);
        $query->update($db->quoteName('#__invoices_facturasencabezados'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $return = $db->execute();

    }


}