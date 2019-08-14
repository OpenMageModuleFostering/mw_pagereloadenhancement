<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */
class MW_All_Block_Adminhtml_Extension_Index_Tab_Extension extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_cached = array();

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mw_all/extension/index.phtml');
        $this->setDestElementId('edit_form');
        $this->setShowGlobalIcon(false);
    }

    protected function getExtensionInfo($moduleName)
        {
            if (!sizeof($this->_cached)) {
                if ($cached_extensions = Mage::getModel('mwall/feed_extension')->getFedExtensions()) {
                    $this->_cached = $cached_extensions;
                }
            }
            if (array_key_exists($moduleName, $this->_cached)) {
                $data = array(
                    'url' => @$this->_cached[$moduleName]['url'],
                    'display_name' => @$this->_cached[$moduleName]['display_name'],
                    'latest_version' => @$this->_cached[$moduleName]['version']
                );
                return new Varien_Object($data);
            }
            return new Varien_Object();
        }
        protected function toVersionNumber($v)
        {
            $digits = @explode(".", $v);
            $version = 0;
            if (is_array($digits)) {
                $count = count($digits);
                for($i = 0; $i < $count; $i++){
                    $v = $digits[$i];
                    $version += $v * pow(10, $count - $i - 1);
                }
            }
            return $version;
        }
        public function getExtensions()
        {
            $extensions = array();
            $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
            sort($modules);

            foreach ($modules as $moduleName) {
                if (strstr($moduleName, 'MW_') === false) {
                    continue;
                }

                if ($moduleName == 'MW_Core' || $moduleName == 'MW_All') {
                    continue;
                }

                // Detect installed version
                $version = (Mage::getConfig()->getModuleConfig($moduleName)->version);
                $status = (Mage::getConfig()->getModuleConfig($moduleName)->active);
                $status = $status == 'true' ? 1: 0;
                $extensionInfo = $this->getExtensionInfo($moduleName);
                $upgradable = ($this->toVersionNumber($extensionInfo->getLatestVersion()) - $this->toVersionNumber($version)) > 0;
                $extensions[] = new Varien_Object(array(
                                                        'version' => $version,
                                                        'name' => $moduleName,
                                                        'active' => $status,
                                                        'extension_info' => $extensionInfo,
                                                        'upgradable' => $upgradable,
                                                        'icon' => new Varien_Object(array(
                                                            'src' => $this->getSkinUrl($upgradable ? 'mw/all/images/update.png' : ($status?'mw/all/images/right.png':'mw/all/images/wrong.png')),
                                                            'title' => ($upgradable ? 'Update available' : ($status?'Enabled and Up to date':'Disabled and Up to date')),
                                                        ))
                                                  ));
            }
            return $extensions;
        }
}