<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */


class MW_All_Block_System_Config_Form_Fieldset_Theme extends MW_All_Block_System_Config_Form_Fieldset_Abstract
{
    protected $_whizData;
    protected $_whizUrl;
    protected $_whizCacheKey;

    public function _construct(){
        $this->_whizUrl = MW_All_Helper_Config::WHIZ_THEME_SHOWCASE_URL;
        $this->_whizCacheKey = MW_All_Helper_Config::WHIZ_THEME_SHOWCASE_CACHE_KEY;
    }
}
