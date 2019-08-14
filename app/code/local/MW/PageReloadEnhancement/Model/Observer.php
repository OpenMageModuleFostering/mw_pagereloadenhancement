<?php
/**
 * @category   MW
 * @package    MW_PageReloadEnhancement
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */
class MW_PageReloadEnhancement_Model_Observer
{
    protected $_productListPages = array(
        'catalog_category_view',
        'catalogsearch_result_index',
        'catalogsearch_advanced_result'
    );

    public function addHandleLayout($observer)
    {
        if(!Mage::getStoreConfig("mw_pagereloadenhancement/general/enable"))
            return;

        /** @var $controller Mage_Catalog_CategoryController */
        $controller = $observer->getEvent()->getAction();

        $request = $controller->getRequest();
        if(!$request->isXmlHttpRequest())
            return;

        if(in_array($controller->getFullActionName(), $this->_productListPages))
        {
            /** @var $update Mage_Core_Model_Layout_Update */
            $update = $controller->getLayout()->getUpdate();

            if($request->getParam('aj', '') == 'l')
            {
                $update->addHandle('catalog_category_view_ajaxlist');
                // fix change category layout
                if($controller->getFullActionName() == 'catalog_category_view')
                {
                    $controller->getLayout()->getUpdate()->load();
                    $controller->generateLayoutXml()->generateLayoutBlocks()->renderLayout();
                    $controller->getResponse()->outputBody();
                    exit;
                }
            }
        }
    }
}