<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */
class MW_All_Model_Feed_Update extends Mage_AdminNotification_Model_Feed
{
    const XML_USE_HTTPS_PATH = 'mwall/mwnotifications/use_https';
    const XML_FEED_URL_PATH = 'mwall/mwnotifications/feed_update_url';
    const XML_FREQUENCY_PATH = 'mwall/mwnotifications/frequency';
    const XML_LAST_UPDATE_PATH = 'mwall/mwnotifications/last_update';

    public function getLastUpdate()
    {
        return Mage::app()->loadCache('mw_all_updates_feed_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'mw_all_updates_feed_lastcheck');
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
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }

        $feedData = array();

        $feedXml = $this->getFeedData();
        if (!$feedXml) return false;
        foreach ($feedXml->children() as $item) {
            if ($this->isAllowUpdate($item)) {
                $feedData[] = array(
                    'severity' => (int)$item->severity,
                    'date_added' => $this->getDate((string)$item->pubDate),
                    'title' => (string)$item->title,
                    'description' => (string)$item->description,
                    'url' => (string)$item->link,
                );
            }

            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedData));
            }
        }


        $this->setLastUpdate();

        return $this;
    }

    public function isAllowUpdate($item)
    {
        $updateTypes = $this->getUpdateTypes();
        $types = @explode(",", (string)$item->type);
        $extensions = @explode(",", (string)$item->extensions);
        $isUpdateForInstallOnly = array_search(MW_All_Helper_Config::UPDATE_TYPE_UPDATE_FOR_INSTALL_ONLY, $types) !== false && sizeof($types) == 1;

        foreach ($types as $type) {
            if (array_search($type, $updateTypes) !== false) {
                if($isUpdateForInstallOnly) {
                    foreach ($extensions as $ext) {
                        if ($this->isExtensionInstalled($ext)) {
                            return true;
                        }
                    }
                    return false;
                }
                return true;
            }
        }
        return false;
    }
    public function getUpdateTypes()
    {
        if (!$this->getData('update_types')) {
            $types = @explode(',', Mage::getStoreConfig('mwall/mwnotifications/update_types'));
            $this->setData('update_types', $types);
        }
        return $this->getData('update_types');
    }
    public function isExtensionInstalled($extension_code)
    {
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        foreach ($modules as $moduleName) {
            if ($moduleName == $extension_code) {
                return true;
            }
        }
        return false;
    }
}