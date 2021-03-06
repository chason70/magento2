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
 * @category    Magento
 * @package     Mage_Core
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Integrity_Modular_CodePoolConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Config
     */
    protected $_config;

    protected function setUp()
    {
        $this->_config = Mage::getSingleton('Mage_Core_Model_Config');
    }

    public function testCodePoolConfigNode()
    {
        $result = array();
        $modulesConfig = $this->_config->getNode('modules');
        /** @var $moduleConfig Varien_Simplexml_Element */
        foreach ($modulesConfig->children() as $moduleConfig) {
            if (array_key_exists('codePool', $moduleConfig->asArray())) {
                $result[] = $moduleConfig->getName();
            }
        }
        $this->assertEquals(array(), $result, 'Specified modules contain obsolete codePool configuration');
    }
}
