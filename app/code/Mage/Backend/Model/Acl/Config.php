<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Backend
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Backend Acl Config model
 *
 * @category    Mage
 * @package     Mage_Backend
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Backend_Model_Acl_Config implements Mage_Core_Model_Acl_Config_ConfigInterface
{
    const CACHE_ID = 'backend_acl_resources';

    const ACL_RESOURCE_ALL = 'Mage_Adminhtml::all';

    /**
     * @var Mage_Core_Model_Config
     */
    protected $_config;

    /**
     * @var Mage_Core_Model_Cache_Type_Config
     */
    protected $_configCacheType;

    /**
     * @var Magento_Acl_Config_Reader
     */
    protected $_reader;

    /**
     * Module configuration reader
     *
     * @var Mage_Core_Model_Config_Modules_Reader
     */
    protected $_moduleReader;

    /**
     * @param Mage_Core_Model_Config $config
     * @param Mage_Core_Model_Cache_Type_Config $configCacheType
     * @param Mage_Core_Model_Config_Modules_Reader $moduleReader
     */
    public function __construct(
        Mage_Core_Model_Config $config,
        Mage_Core_Model_Cache_Type_Config $configCacheType,
        Mage_Core_Model_Config_Modules_Reader $moduleReader
    ) {
        $this->_config = $config;
        $this->_configCacheType = $configCacheType;
        $this->_moduleReader = $moduleReader;
    }

    /**
     * Retrieve list of acl files from each module
     *
     * @return array
     */
    protected function _getAclResourceFiles()
    {
        $files = $this->_moduleReader
            ->getModuleConfigurationFiles('adminhtml' . DIRECTORY_SEPARATOR . 'acl.xml');
        return (array) $files;
    }

    /**
     * Reader object initialization
     *
     * @return Magento_Acl_Config_Reader
     */
    protected function _getReader()
    {
        if (null === $this->_reader) {
            $aclResourceFiles = $this->_getAclResourceFiles();
            $this->_reader = $this->_config->getModelInstance('Magento_Acl_Config_Reader',
                array('configFiles' => $aclResourceFiles)
            );
        }
        return $this->_reader;
    }

    /**
     * Return ACL Resources loaded from cache if enabled or from files merged previously
     *
     * @return DOMNodeList
     */
    public function getAclResources()
    {
        $aclResourcesXml = $this->_loadAclResourcesFromCache();
        if ($aclResourcesXml && is_string($aclResourcesXml)) {
            $aclResources = new DOMDocument();
            $aclResources->loadXML($aclResourcesXml);
        } else {
            $aclResources = $this->_getReader()->getAclResources();
            $this->_saveAclResourcesToCache($aclResources->saveXML());
        }
        $xpath = new DOMXPath($aclResources);
        return $xpath->query('/config/acl/resources/*');
    }

    /**
     * Load ACL resources from cache
     *
     * @return null|string
     */
    private function _loadAclResourcesFromCache()
    {
        return $this->_configCacheType->load(self::CACHE_ID);
    }

    /**
     * Save ACL resources into the cache
     *
     * @param $data
     * @return Mage_Backend_Model_Acl_Config
     */
    private function _saveAclResourcesToCache($data)
    {
        $this->_configCacheType->save($data, self::CACHE_ID);
        return $this;
    }
}
