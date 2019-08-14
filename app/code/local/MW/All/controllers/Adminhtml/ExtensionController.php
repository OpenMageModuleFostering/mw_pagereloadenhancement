<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */
class MW_All_Adminhtml_ExtensionController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('extensionManagement/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Extension Management'), Mage::helper('adminhtml')->__('Extension Management'));

        return $this;
    }

    public function indexAction() {
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        Mage::register('extension_data', $data);

        $this->loadLayout();
        $this->_setActiveMenu('extensionManagement/items');

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Extension Management'), Mage::helper('adminhtml')->__('Extension Management'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('mwall/adminhtml_extension_index'))->_addLeft($this->getLayout()->createBlock('mwall/adminhtml_extension_index_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($modules = $this->getRequest()->getPost('modules')) {
            $model = Mage::getModel('mwall/feed_extension');

            try {
                $model->saveModules($modules);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mwall')->__('Extensions was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($modules);
            }

        }
        $this->_redirect('*/*/');
    }
}