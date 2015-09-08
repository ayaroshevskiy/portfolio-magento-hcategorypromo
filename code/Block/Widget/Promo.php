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
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Holoflek
 * @package     Holoflek_CategoryPromo
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Widget to display promo block of category.
 *
 * @category   Holoflek
 * @package    Holoflek_CategoryPromo
 * @author     Altexsoft Team <sales@altexsoft.com>
 */

class Holoflek_CategoryPromo_Block_Widget_Promo
    extends Mage_Core_Block_Html_Link
    implements Mage_Widget_Block_Interface
{
    /**
     * Entity model name which must be used to retrieve entity specific data.
     * @var null|Mage_Catalog_Model_Resource_Eav_Mysql4_Abstract
     */
    protected $_categoryResource = null;

    /**
     * Prepared href attribute
     *
     * @var string
     */
    protected $_href;

    /**
     * Prepared Category Title
     *
     * @var string
     */
    protected $_categoryTitle;

    /**
     * Prepared Category Description
     *
     * @var string
     */
    protected $_categoryDescription;

    /**
     * Prepared Category Image Url
     *
     * @var string
     */
    protected $_categoryImageUrl;

    /**
     * Initialize category model
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_categoryResource = Mage::getResourceSingleton('catalog/category');
    }

    /**
     * Prepare url using passed id and return it
     * or return false if path was not found.
     *
     * @return string|false
     */
    public function getHref()
    {
        if (!$this->_href) {

            if($this->hasStoreId()) {
                $store = Mage::app()->getStore($this->getStoreId());
            } else {
                $store = Mage::app()->getStore();
            }

            $idPath = explode('/', $this->_getData('id_path'));

            if (isset($idPath[0]) && isset($idPath[1]) && $idPath[0] == 'category') {
                $categoryId = $idPath[1];
                if ($categoryId) {
                    /** @var $helper Mage_Catalog_Helper_Category */
                    $helper = $this->_getFactory()->getHelper('catalog/category');
                    $category = Mage::getModel('catalog/category')->load($categoryId);
                    $parentCategory = $category->getParentCategory();
                    if ($parentCategory) {
                        $this->_href = $parentCategory->getUrl() . '?cat=' . $categoryId;
                    } else {
                        $this->_href = $helper->getCategoryUrl($category);
                    }
                }
            }
        }

        if ($this->_href) {
            return $this->_href;
        }

        return false;
    }

    /**
     * Prepare category title using category ID.
     *
     * @return string
     */
    public function getCategoryTitle()
    {
        if ($this->hasStoreId()) {
            $store = Mage::app()->getStore($this->getStoreId());
        } else {
            $store = Mage::app()->getStore();
        }

        if (!$this->_categoryTitle && $this->_categoryResource) {
            $idPath = explode('/', $this->_getData('id_path'));
            if (isset($idPath[1])) {
                $id = $idPath[1];
                if ($id) {
                    $this->_categoryTitle = $this->_categoryResource
                        ->getAttributeRawValue($id, 'name', $store);
                }
            }
        }

        return $this->_categoryTitle;
    }

    /**
     * Prepare extra class for category title to set color.
     *
     * @return string
     */
    public function getCategoryTitleColorClass(){

        return ' title-' . $this->_getData('title_color');
    }

    /**
     * Prepare category description using category ID.
     *
     * @return string
     */
    public function getCategoryDescription()
    {
        if ($this->hasStoreId()) {
            $store = Mage::app()->getStore($this->getStoreId());
        } else {
            $store = Mage::app()->getStore();
        }

        if (!$this->_categoryDescription && $this->_categoryResource) {
            $idPath = explode('/', $this->_getData('id_path'));
            if (isset($idPath[1])) {
                $id = $idPath[1];
                if ($id) {
                    $this->_categoryDescription = $this->_categoryResource
                        ->getAttributeRawValue($id, 'description', $store);
                }
            }
        }

        return $this->_categoryDescription;
    }

    /**
     * Prepare extra class for category description to set color.
     *
     * @return string
     */
    public function getCategoryDescriptionColorClass(){

        return ' description-' . $this->_getData('description_color');
    }

    /**
     * Prepare extra class for widget width.
     *
     * @return string
     */
    public function getWidthClass(){

        return ' width-' . $this->_getData('width');
    }

    /**
     * Prepare category image URL using category ID.
     *
     * @return string
     */
    public function getCategoryImageUrl()
    {
        if ($this->hasStoreId()) {
            $store = Mage::app()->getStore($this->getStoreId());
        } else {
            $store = Mage::app()->getStore();
        }

        if (!$this->_categoryImageUrl && $this->_categoryResource) {
            $idPath = explode('/', $this->_getData('id_path'));
            if (isset($idPath[1])) {
                $id = $idPath[1];
                if ($id) {
                    $categoryImage = $this->_categoryResource
                        ->getAttributeRawValue($id, 'image', $store);
                    if ($categoryImage) {
                        $this->_categoryImageUrl = Mage::getBaseUrl('media') . 'catalog/category/' . $categoryImage;
                    }
                }
            }
        }

        return $this->_categoryImageUrl;
    }

    /**
     * Render block HTML
     * or return empty string if url can't be prepared
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->getHref()) {
            return parent::_toHtml();
        }
        return '';
    }
}
