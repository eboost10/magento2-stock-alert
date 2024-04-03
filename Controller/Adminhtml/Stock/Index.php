<?php
/**
 * @Copyright © EBoost. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author    eboost10@gmail.com
 * @project   Stock Alert
 */

namespace EBoost\StockAlert\Controller\Adminhtml\Stock;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;

/**
 * Class Index
 * Grid view for stock notifications
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'EBoost_StockAlert::stock';

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("Stock Notification Subscribers"));
        return $resultPage;
    }
}
