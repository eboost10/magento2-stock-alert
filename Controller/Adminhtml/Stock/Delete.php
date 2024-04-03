<?php
/**
 * @Copyright © EBoost. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author    eboost10@gmail.com
 * @project   Stock Alert
 */

declare(strict_types=1);

namespace EBoost\StockAlert\Controller\Adminhtml\Stock;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\ProductAlert\Model\StockFactory;

/**
 * Class Delete
 */
class Delete extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'EBoost_StockAlert::stock_remove';

    /**
     * @var StockFactory
     */
    private $stockFactory;

    /**
     * @param Context $context
     * @param StockFactory $stockFactory
     */
    public function __construct(
        Context $context,
        StockFactory $stockFactory
    ) {
        $this->stockFactory = $stockFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->stockFactory->create();
                $model->load($id);

                if ($model->getId()) {
                    $model->delete();
                }

                $this->messageManager->addSuccessMessage(__('The alert has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->messageManager->addErrorMessage(__('Can\'t find an alert to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
