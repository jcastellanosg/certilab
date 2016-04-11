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

class InvoicesModelClientesmedidas extends InvoicesModelListar
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getProductosMedidas($idcliente)
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('m.id','m.medida','p.id'),
            array('id','medida','producto')));
        $query->from($db->quoteName('#__invoices_empresas','e'));
        $query->join('INNER', $db->quoteName('#__invoices_productos', 'p') . ' ON (' . $db->quoteName('p.idempresa') . ' = ' . $db->quoteName('e.id') . ')');
        $query->join('INNER', $db->quoteName('#__invoices_productosmedidas', 'm') . ' ON (' . $db->quoteName('m.idproducto') . ' = ' . $db->quoteName('p.id') . ')');
        $query->where($db->quoteName('e.id') . ' = ' . "(select idempresa FROM don_invoices_empresasclientes WHERE id = {$idcliente})");
        $db->setQuery($query);
        $row = $db->loadAssocList();
        return $row;
    }

    public function getProductos($idcliente)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('p.id','p.producto'),
            array('id','medida','producto')));
        $query->from($db->quoteName('#__invoices_empresas','e'));
        $query->join('INNER', $db->quoteName('#__invoices_productos', 'p') . ' ON (' . $db->quoteName('p.idempresa') . ' = ' . $db->quoteName('e.id') . ')');
        $query->where($db->quoteName('e.id') . ' = ' . "(select idempresa FROM don_invoices_empresasclientes WHERE id = {$idcliente})");
        $db->setQuery($query);
        $row = $db->loadAssocList();
        return $row;
    }

}




