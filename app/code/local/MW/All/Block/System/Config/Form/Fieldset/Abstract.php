<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */


abstract class MW_All_Block_System_Config_Form_Fieldset_Abstract extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    protected $_whizData;
    protected $_whizUrl;
    protected $_whizCacheKey;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return '<div id="' . $element->getId() . '">' . $this->getWhizData() . '</div>';
    }

    protected function getWhizData()
    {
        if (!is_null($this->_whizData))
            return $this->_whizData;
        $connection = $this->_getCurlConnection($this->_whizUrl);
        $content = $connection->read();

        if ($content !== false) {
            $content = preg_split('/^\r?$/m', $content, 2);
            $content = trim($content[1]);
            Mage::app()->saveCache($content, $this->_whizCacheKey);
        }
        else {
            if (!$content = Mage::app()->loadCache($this->_whizCacheKey)) {
                return 'Temporarily cannot connect to remote server. Please try again later.';
            }
        }

        $connection->close();
        $this->_whizData = $content;
        return $this->_whizData;
    }

    protected function _getCurlConnection($url_path, $params = array())
    {
        $url_params = array();
        foreach ($params as $key => $val) {
            $url_params[] = urlencode($key) . '=' . urlencode($val);
        }
        $url_path = rtrim($url_path) . (count($url_params) ? ('?' . implode('&', $url_params)) : '');

        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array('timeout' => 10));
        $curl->write(Zend_Http_Client::GET, $url_path);

        return $curl;
    }
}
