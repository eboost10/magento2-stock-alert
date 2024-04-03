<?php
/**
 * Created by Steven.
 */

namespace EBoost\StockAlert\Helper;


use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;
    /**
     * @var \Magento\ProductAlert\Helper\Data
     */
    protected $productAlertHelper;

    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\ProductAlert\Helper\Data $productAlertHelper,
        Context $context)
    {

        parent::__construct($context);

        $this->layout = $layout;
        $this->productAlertHelper = $productAlertHelper;
    }

    public function renderStockStatus($productCollection, $containerHtmlSelector = '.products.wrapper', $options = [])
    {
        if (!$this->productAlertHelper->isStockAlertAllowed()) {
            return '';
        }

        return $this->layout->createBlock(\EBoost\StockAlert\Block\Catalog\Product\StockAlert::class)
            ->setProductCollection($productCollection)
            ->setContainerHtmlSelector($containerHtmlSelector)
            ->setOptions($options)
            ->toHtml();
    }
}