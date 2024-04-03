<?php
/**
 * Created by Steven.
 */

namespace EBoost\StockAlert\CustomerData;


use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\ProductAlert\Model\ResourceModel\Stock\CollectionFactory as StockCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class StockAlert implements SectionSourceInterface
{
    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;
    /**
     * @var StockCollectionFactory
     */
    protected $stockColFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * StockAlert constructor.
     * @param CurrentCustomer $currentCustomer
     * @param StockCollectionFactory $stockColFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        StockCollectionFactory $stockColFactory,
        StoreManagerInterface $storeManager
    )
    {
        $this->currentCustomer = $currentCustomer;
        $this->stockColFactory = $stockColFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getSectionData()
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return [];
        }
        /** @var \Magento\ProductAlert\Model\ResourceModel\Stock\Collection $collection */
        $collection = $this->stockColFactory->create();
        $collection->addWebsiteFilter($this->storeManager->getStore()->getWebsiteId())
            ->addStatusFilter(0)
            ->addFieldToFilter('customer_id', $this->currentCustomer->getCustomerId())
            ->getSelect()->reset('columns')->columns('product_id');
        $result = $collection->getConnection()->fetchCol($collection->getSelect());
        return ['product_ids' => $result];
    }
}