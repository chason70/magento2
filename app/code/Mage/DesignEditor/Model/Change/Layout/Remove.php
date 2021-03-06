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
 * @package     Mage_DesignEditor
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Layout remove change model
 */
class Mage_DesignEditor_Model_Change_Layout_Remove extends Mage_DesignEditor_Model_Change_LayoutAbstract
{
    /**
     * Layout directive associated with this change
     */
    const LAYOUT_DIRECTIVE_REMOVE = 'remove';

    /**
     * Class constructor
     *
     * @param Varien_Simplexml_Element|array $data
     */
    public function __construct($data = array())
    {
        if ($data instanceof Varien_Simplexml_Element) {
            $data = $this->_getAttributes($data);
        }

        parent::__construct($data);
    }

    /**
     * Get data to render layout update directive
     *
     * @return array
     */
    public function getLayoutUpdateData()
    {
        return array('name' => $this->getData('element_name'));
    }

    /**
     * Get layout update directive for given layout change
     *
     * @return string
     */
    public function getLayoutDirective()
    {
        return self::LAYOUT_DIRECTIVE_REMOVE;
    }

    /**
     * Get attributes from XML layout update
     *
     * @param Varien_Simplexml_Element $layoutUpdate
     * @return array
     */
    protected function _getAttributes(Varien_Simplexml_Element $layoutUpdate)
    {
        $attributes = array();
        if ($layoutUpdate->getAttribute('name') !== null) {
            $attributes['element_name'] = $layoutUpdate->getAttribute('name');
        }
        $attributes = array_merge($attributes, parent::_getAttributes($layoutUpdate));

        return $attributes;
    }
}
