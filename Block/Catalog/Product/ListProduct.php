<?php
/**
 * Created by Steven.
 */

namespace EBoost\StockAlert\Block\Catalog\Product;


use Magento\Framework\View\Element\Template;

class ListProduct extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    public function __construct(
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->catalogLayer = $layerResolver->get();
    }

    public function getProductCollection() {
        $productsCollection = $this->catalogLayer->getProductCollection();

        return $productsCollection;
    }
}