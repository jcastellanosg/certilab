<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Properties model class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jea
 */

require_once JPATH_COMPONENT . '/models/listar.php';

class InvoicesModelFacturapdf extends InvoicesModelListar
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getFacturaEncabezado($factura)
    {
        return $this->traerDatosDeTabla($factura, 'facturasencabezados');
    }

    public function getFacturaDetalle($factura)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('p.producto','r.referencia','f.cantidad','f.valorunitario','f.valortotal','f.descripcion'),
                                      array('Producto','Referencia','Cantidad','vrUnitario','vrTotal','Descripcion' )));
        $query->from($db->quoteName('#__invoices_facturasdetalles','f'));
        $query->join('LEFT', $db->quoteName('#__invoices_productosreferencias', 'r') . ' ON (' . $db->quoteName('r.id') . ' = ' . $db->quoteName('f.idreferencia') . ')');
        $query->join('LEFT', $db->quoteName('#__invoices_productos', 'p') . ' ON (' . $db->quoteName('p.id') . ' = ' . $db->quoteName('r.idproducto') . ')');
        $query->where($db->quoteName('idfactura') . ' = ' . $factura);
        $db->setQuery($query);
        $row = $db->loadAssocList();
        return $row;
    }

    public function getEmpresa($empresa)
    {
        return $this->traerDatosDeTabla($empresa, 'empresas');
    }

    public function getCliente($cliente)
    {
        return $this->traerDatosDeTabla($cliente, 'empresasclientes');
    }


    private function traerDatosDeTabla($id, $tabla)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__invoices_' . $tabla));
        $query->where($db->quoteName('id') . ' = ' . $id);
        $db->setQuery($query);
        $row = $db->loadAssoc();
        return $row;
    }

}




