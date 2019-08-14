<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */


class MW_All_Block_Notification_Popup extends Mage_Adminhtml_Block_Notification_Window
{
    protected function _toHtml()
    {
        if (!Mage::getStoreConfig('mwall/whiz/hide_notice')) {
            $this->setTemplate('mw_all/notification/popup.phtml');
        }
        return parent::_toHtml();
    }
}
