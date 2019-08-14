<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */
class MW_All_Block_Adminhtml_Extension_Index extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'mwall';
        $this->_controller = 'adminhtml_extension';
        $this->_mode = 'index';

        $this->_removeButton('back');
        $this->_removeButton('reset');
    }

    public function getHeaderText()
    {
        return Mage::helper('mwall')->__('Mage Whiz Extension Management');
    }


}