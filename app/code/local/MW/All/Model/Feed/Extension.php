<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */


class MW_All_Model_Feed_Extension extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH = 'mwall/mwnotifications/use_https';
    const XML_FEED_URL_PATH = 'mwall/mwnotifications/feed_extension_url';
    const XML_FREQUENCY_PATH = 'mwall/mwnotifications/frequency';
    const XML_LAST_UPDATE_PATH = 'mwall/mwnotifications/last_update';

    public function getLastUpdate()
    {
        return Mage::app()->loadCache('mw_all_extension_feed_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'mw_all_extension_feed_lastcheck');
        return $this;
    }
    public function getFedExtensions()
    {
        return unserialize(Mage::app()->loadCache('mw_all_extension_feed_extensions'));
    }

    public function setFedExtensions($data)
    {
        Mage::app()->saveCache(serialize($data), 'mw_all_extension_feed_extensions');
        return $this;
    }
    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
                . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
        }
        return $this->_feedUrl;
    }

    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }

    public function checkUpdate()
    {
        if (($this->getFrequency() + $this->getLastUpdate()) > time() && sizeof($this->getFedExtensions())) {
            return $this;
        }

        $feedData = array();

        try {
            $feedXml = $this->getFeedData();
            if (!$feedXml) return false;
            foreach ($feedXml->children() as $item) {
                $feedData[(string)$item->name] = array(
                    'display_name' => (string)$item->display_name,
                    'version' => (string)$item->version,
                    'url' => (string)$item->url
                );
            }
        } catch (Exception $e) {
            return false;
        }

        $this->setLastUpdate();
        $this->setFedExtensions($feedData);
        return $feedData;
    }
    protected function _save($moduleName, $status)
    {
        $etcDir = Mage::getBaseDir('etc');
        $moduleFile = $etcDir . DS . 'modules' . DS . "{$moduleName}.xml";

        $dom = new DOMDocument();
        $dom->load($moduleFile);

        $nodeStatus = $dom->getElementsByTagName('active')->item(0);
        $oldstatus = $nodeStatus->nodeValue;
        $status = $status ? 'true': 'false';
        if($oldstatus != $status)
            $nodeStatus->nodeValue = $status;
        $dom->save($moduleFile);
    }
    public function saveModules($modules)
    {
        foreach($modules as $moduleName => $status)
        {
            $this->_save($moduleName, $status);
        }
        Mage::app()->cleanCache();
        Mage::app()->getCacheInstance()->flush();

        foreach (Mage::app()->getCacheInstance()->getTypes() as $type) {
            Mage::app()->getCacheInstance()->cleanType($type->getId());
        }
    }

}