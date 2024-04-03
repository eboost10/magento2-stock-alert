<?php
/**
 * Created by Steven.
 */

namespace EBoost\StockAlert\Block\Catalog\Product;


use Magento\Framework\View\Element\Template;

/**
 * @method getProductCollection()
 * @method StockAlert setProductCollection($productCollection)
 * @method getContainerHtmlSelector()
 * @method StockAlert setContainerHtmlSelector($selector)
 * @method getOptions()
 * @method StockAlert setOptions($options)
 */
class StockAlert extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'EBoost_StockAlert::catalog/product/stock-alert.phtml';

    public function getAllNotifyProducts() {
        $productsCollection = $this->getProductCollection();
        $productData = [];

        if ($productsCollection) {
            foreach ($productsCollection as $_product) {
                if (!$_product->isSaleable() && !$_product->isAvailable()) {
                    $productData[] = $_product->getId();
                }
            }
        }

        return $productData;
    }
}