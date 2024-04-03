<?php
/**
 * Created by Steven.
 */

namespace EBoost\StockAlert\Block\Catalog\Product;


use Magento\Framework\View\Element\Template;

class View extends \Magento\ProductAlert\Block\Product\View
{
    protected $_template = 'catalog/product/view.phtml';

    public function getProduct()
    {
        return parent::getProduct();
    }

    protected function _toHtml()
    {
        if (!$this->_helper->isStockAlertAllowed() || !$this->getProduct() || $this->getProduct()->isAvailable()) {
            return '';
        }
        return parent::_toHtml();
    }
}