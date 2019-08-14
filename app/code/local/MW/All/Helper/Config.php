<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */


class MW_All_Helper_Config extends Mage_Core_Helper_Abstract
{
    const UPDATE_TYPE_CUSTOMER_PROMOTIONS = 'CUSTOMER_PROMOTIONS';
    const UPDATE_TYPE_NEW = 'NEW';
    const UPDATE_TYPE_UPDATE = 'UPDATE';
    const UPDATE_TYPE_UPDATE_FOR_INSTALL_ONLY = 'INSTALLED_UPDATE';
    const UPDATE_TYPE_OTHERS = 'OTHERS';

    const WHIZ_EXTENSION_SHOWCASE_URL = 'http://www.magewhiz.com/mwextension/';
    const WHIZ_THEME_SHOWCASE_URL = 'http://www.magewhiz.com/mwtheme/';
    const WHIZ_EXTENSION_SHOWCASE_CACHE_KEY = 'whiz_extension_showcase_cache_key';
    const WHIZ_THEME_SHOWCASE_CACHE_KEY = 'whiz_theme_showcase_cache_key';

    public function getUpdateTypes(){
        return array(
            self::UPDATE_TYPE_CUSTOMER_PROMOTIONS => 'Latest Customer Promotions',
            self::UPDATE_TYPE_NEW => 'Latest Theme and Extension Releases',
            self::UPDATE_TYPE_UPDATE => 'Latest Theme and Extension Updates',
            self::UPDATE_TYPE_UPDATE_FOR_INSTALL_ONLY => 'Installed extensions updates',
            self::UPDATE_TYPE_OTHERS => 'Other information',
        );
    }

    public function getConfigurableUpdateTypes() {
        return Mage::getModel('mwall/feed_update')->getUpdateTypes();
    }
}
