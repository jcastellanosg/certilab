<?php 

/*------------------------------------------------------------------------
# com_finances - Invoice Manager for Joomla
# ------------------------------------------------------------------------
# author        Germinal Camps
# copyright       Copyright (C) 2012 JoomlaContentStatistics.com. All Rights Reserved.
# @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites:       http://www.JoomlaContentStatistics.com
# Technical Support:  Forum - http://www.JoomlaContentStatistics.com/forum
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access'); 

$separator = ";" ;

?><?php echo JText::_('INVOICE_NUM'); ?><?php echo $separator; ?><?php echo JText::_('RECIPIENT_NAME'); ?><?php echo $separator; ?><?php echo JText::_('COMPANY'); ?><?php echo $separator; ?><?php echo JText::_('EMAIL'); ?><?php echo $separator; ?><?php echo JText::_('LINKED_TO_JOOMLA_USER'); ?><?php echo $separator; ?><?php echo JText::_('INVOICE_DATE'); ?><?php echo $separator; ?><?php echo JText::_('SUBTOTAL'); ?><?php echo $separator; ?><?php foreach($this->taxes as $tax){ ?><?php echo $tax->name; ?><?php echo $separator; ?><?php } ?><?php echo JText::_('TOTAL'); ?><?php echo $separator; ?><?php echo JText::_('STATUS'); ?><?php echo $separator; ?><?php echo "\n"; ?><?php
  $k = 0;
  for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
    $row = &$this->items[$i];
	
	$link 		= JRoute::_( 'index.php?option=com_invoices&controller=invoice&task=edit&cid[]='. $row->id );
	if($row->publish){
		$publicat = JHTML::image('administrator/components/com_invoices/assets/images/tick.png','Published');
		$link_publicat = JRoute::_('index.php?option=com_invoices&controller=invoice&task=unpublish&cid[]='. $row->id); 
	}
	else{
		$publicat = JHTML::image('administrator/components/com_invoices/assets/images/publish_x.png','Not Published');
		$link_publicat = JRoute::_('index.php?option=com_invoices&controller=invoice&task=publish&cid[]='. $row->id); 
	}
	
	$subtotal += $row->subtotal ;
	$total += $row->total ;
	$total_paid += $row->total_paid ;
	
	$total_payments = $row->total_paid + $row->total_unpaid ;
	
    ?><?php echo $row->invoice_num; ?><?php echo $separator; ?><?php echo $row->to_name; ?><?php echo $separator; ?><?php echo $row->to_company; ?><?php echo $separator; ?><?php echo $row->to_email; ?><?php echo $separator; ?><?php echo $row->username; ?><?php echo $separator; ?><?php echo JHTML::_('date', $row->invoice_date, JText::_('DATE_FORMAT_LC3')); ?><?php echo $separator; ?><?php echo InvoicesHelper::format_simple($row->subtotal); ?><?php echo $separator; ?><?php foreach($this->taxes as $tax){ 
	     if(isset($row->display_taxes[$tax->id])){
				echo InvoicesHelper::format_simple($row->display_taxes[$tax->id]);
				$total_taxes[$tax->id] += $row->display_taxes[$tax->id] ;
			}
			else echo '0'; 

			echo $separator;
		} 
		?><?php echo InvoicesHelper::format_simple($row->total); ?><?php echo $separator; ?><?php echo JText::_($row->status); ?><?php echo $separator; ?><?php echo "\n"; ?><?php
    

    $k = 1 - $k;
  }
  ?>