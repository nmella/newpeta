<?php
/**
 * Unirgy LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unirgy.com/LICENSE-M1.txt
 *
 * @category   Unirgy
 * @package    Unirgy_Dropship
 * @copyright  Copyright (c) 2008-2009 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

class Unirgy_Dropship_Block_Adminhtml_Vendor_Statement_Edit_Tab_Rows extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('statement_rows');
        $this->setDefaultSort('row_id');
        $this->setUseAjax(true);
    }

    public function getStatement()
    {
        $statement = Mage::registry('statement_data');
        if (!$statement) {
            $statement = Mage::getModel('udropship/vendor_statement')->load($this->getStatementId());
            Mage::register('statement_data', $statement);
        }
        return $statement;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('udropship/vendor_statement_row')->getCollection()
            ->addFieldToFilter('statement_id', $this->getStatement()->getId());
        ;

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('row_id', array(
            'header'    => Mage::helper('udropship')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'row_id'
        ));
        $this->addColumn('order_increment_id', array(
            'header'    => Mage::helper('udropship')->__('Order ID'),
            'index'     => 'order_increment_id'
        ));
        $this->addColumn('order_created_at', array(
            'header'    => Mage::helper('udropship')->__('Order Date'),
            'index'     => 'order_created_at'
        ));
        $this->addColumn('po_increment_id', array(
            'header'    => Mage::helper('udropship')->__('PO ID'),
            'index'     => 'po_increment_id'
        ));
        $this->addColumn('po_created_at', array(
            'header'    => Mage::helper('udropship')->__('PO Date'),
            'index'     => 'po_created_at'
        ));
        $this->addColumn('po_statement_date', array(
            'header'    => Mage::helper('udropship')->__('PO Ready Date'),
            'index'     => 'po_statement_date'
        ));
        $this->addColumn('subtotal', array(
            'header'    => Mage::helper('udropship')->__('Subtotal'),
            'index' => 'subtotal',
            'type'  => 'price',
            'currency' => 'base_currency_code',
            'currency_code' => Mage::getStoreConfig('currency/options/base'),
        ));
        $this->addColumn('com_amount', array(
            'header'    => Mage::helper('udropship')->__('Com Amount'),
            'index' => 'com_amount',
            'type'  => 'price',
            'currency' => 'base_currency_code',
            'currency_code' => Mage::getStoreConfig('currency/options/base'),
        ));
        $this->addColumn('trans_fee', array(
            'header'    => Mage::helper('udropship')->__('Trans Fee'),
            'index' => 'trans_fee',
            'type'  => 'price',
            'currency' => 'base_currency_code',
            'currency_code' => Mage::getStoreConfig('currency/options/base'),
        ));
        /*
        foreach ($this->getStatement()->getWithholdOptions() as $wk=>$wl) {
            if ($this->getStatement()->hasWithhold($wk)) {
                $this->addColumn($wk, array(
                    'header'    => Mage::helper('udropship')->__($wl),
                    'index' => $wk,
                    'type'  => 'price',
                    'currency' => 'base_currency_code',
                    'currency_code' => Mage::getStoreConfig('currency/options/base'),
                ));
            }
        }
        */
        if ($this->getStatement()->getVendor()->getStatementTaxInPayout() != 'exclude_hide') {
        	$this->addColumn('tax', array(
                'header'    => Mage::helper('udropship')->__('Tax'),
                'index' => 'tax',
                'type'  => 'price',
                'currency' => 'base_currency_code',
                'currency_code' => Mage::getStoreConfig('currency/options/base'),
            ));
        }
    	if ($this->getStatement()->getVendor()->getStatementShippingInPayout() != 'exclude_hide') {
        	$this->addColumn('shipping', array(
                'header'    => Mage::helper('udropship')->__('Shipping'),
                'index' => 'shipping',
                'type'  => 'price',
                'currency' => 'base_currency_code',
                'currency_code' => Mage::getStoreConfig('currency/options/base'),
            ));
        }
        if ($this->getStatement()->getVendor()->getStatementDiscountInPayout() != 'exclude_hide') {
            $this->addColumn('discount', array(
                'header'    => Mage::helper('udropship')->__('Discount'),
                'index' => 'discount',
                'type'  => 'price',
                'currency' => 'base_currency_code',
                'currency_code' => Mage::getStoreConfig('currency/options/base'),
            ));
        }
        $this->addColumn('adj_amount', array(
            'header'    => Mage::helper('udropship')->__('Adjustment'),
            'index' => 'adj_amount',
            'type'  => 'price',
            'currency' => 'base_currency_code',
            'currency_code' => Mage::getStoreConfig('currency/options/base'),
        ));
        if (!Mage::helper('udropship')->isStatementAsInvoice()) {
            $this->addColumn('total_payout', array(
                'header'    => Mage::helper('udropship')->__('Total Payout'),
                'index' => 'total_payout',
                'type'  => 'price',
                'currency' => 'base_currency_code',
                'currency_code' => Mage::getStoreConfig('currency/options/base'),
            ));
            if (Mage::helper('udropship')->isUdpayoutActive()) {
                $this->addColumn('paid', array(
                    'header'    => Mage::helper('udropship')->__('Paid'),
                    'index' => 'paid',
                    'type' => 'options',
                    'options' => Mage::getSingleton('udropship/source')->setPath('yesno')->toOptionHash(),
                ));
            }
        } else {
            $this->addColumn('total_invoice', array(
                'header'    => Mage::helper('udropship')->__('Total Invoice'),
                'index' => 'total_invoice',
                'type'  => 'price',
                'currency' => 'base_currency_code',
                'currency_code' => Mage::getStoreConfig('currency/options/base'),
            ));
        }
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/rowGrid', array('_current'=>true));
    }
}
