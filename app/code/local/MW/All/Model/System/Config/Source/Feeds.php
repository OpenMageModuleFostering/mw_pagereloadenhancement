<?php
/**
 * @category   MW
 * @package    MW_All
 * @version    1.0.0
 * @copyright  Copyright (c) 2012 Mage Whiz. (http://www.magewhiz.com)
 */

class MW_All_Model_System_Config_Source_Feeds
{
    public function toOptionArray()
    {
        $arr = array();
        foreach (Mage::helper('mwall/config')->getUpdateTypes() as $k=>$v) {
            $arr[] = array('value'=>$k, 'label'=>$v);
        }
        return $arr;
    }
}