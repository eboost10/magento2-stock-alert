<?php
/**
 * @Copyright © EBoost. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author    eboost10@gmail.com
 * @project   Stock Alert
 */
declare(strict_types=1);

namespace EBoost\StockAlert\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class AlertActions
 * Builds view customer, product links, delete links for each item.
 */
class AlertActions extends Column
{
    /**
     * Url path
     */
    public const URL_PATH_CUSTOMER = 'customer/index/edit';
    public const URL_PATH_PRODUCT = 'catalog/product/edit';
    public const URL_PATH_DELETE = 'eboost_stockalert/stock/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * AlertActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldId = $this->getDataByPath('config/indexField') ?? 'alert_stock_id';
            $delRoutePath = $this->getDataByPath('options/delRoutePath') ?? static::URL_PATH_DELETE;

            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['customer_id'])) {
                    $item[$this->getData('name')]['edit_customer'] = [
                        'href' => $this->urlBuilder->getUrl(
                            static::URL_PATH_CUSTOMER,
                            [
                                'id' => $item['customer_id'],
                            ]
                        ),
                        'label' => __('View Customer'),
                        '__disableTmpl' => true,
                        'target' => '_blank'
                    ];
                }
                if (isset($item['product_id'])) {
                    $item[$this->getData('name')]['edit_product'] = [
                        'href' => $this->urlBuilder->getUrl(
                            static::URL_PATH_PRODUCT,
                            [
                                'id' => $item['product_id'],
                            ]
                        ),
                        'label' => __('View Product'),
                        '__disableTmpl' => true,
                        'target' => '_blank'
                    ];
                }

                $item[$this->getData('name')]['remove_entity'] = [
                    'href' => $this->urlBuilder->getUrl(
                        $delRoutePath,
                        [
                            'id' => $item[$fieldId],
                        ]
                    ),
                    'label' => __('Remove Alert'),
                    '__disableTmpl' => true,
                ];
            }
        }
        
        return $dataSource;
    }
}
