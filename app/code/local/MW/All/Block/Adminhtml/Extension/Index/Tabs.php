<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */
class MW_All_Block_Adminhtml_Extension_Index_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('extension_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mwall')->__('Mage Whiz'));
    }
    protected function _beforeToHtml()
    {
        $this->addTab('extension_section', array(
            'label' => Mage::helper('mwall')->__('Extension Management'),
            'title' => Mage::helper('mwall')->__('Extension Management')   ,
            'content' => $this->getLayout()->createBlock('mwall/adminhtml_extension_index_tab_extension')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}