<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:35 PM
 */

defined('_JEXEC') or die;
class InvoicesTablePaises extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__invoices_paises', 'id', $db);
    }
    public function bind($array, $ignore = '')
    {
        return parent::bind($array, $ignore);
    }
    public function store($updateNulls = false)
    {
        return parent::store($updateNulls);
    }
}