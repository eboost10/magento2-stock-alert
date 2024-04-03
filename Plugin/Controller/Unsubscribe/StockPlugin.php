<?php
/**
 * Created by Steven.
 */
declare(strict_types=1);

namespace EBoost\StockAlert\Plugin\Controller\Unsubscribe;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\MessageInterface;

class StockPlugin
{
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    public function __construct(
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
    }


    public function afterDispatch(\Magento\ProductAlert\Controller\Unsubscribe\Stock $subject, $result)
    {
        if ($subject->getRequest()->isAjax()) {
            $response = [
                'errors' => false,
                'messages' => [],
            ];
            // echo get_class($result);
            $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            if ($result instanceof \Magento\Framework\Controller\ResultInterface) {
                // Not login
                $errorMessages = [];
                /** @var \Magento\Framework\Message\MessageInterface $message */
                foreach ($this->messageManager->getMessages()->getItems() as $message) {
                    // echo $message->getText();
                    if ($message->getType() !== MessageInterface::TYPE_SUCCESS) {
                        $errorMessages[] = $message->getText();
                        $this->messageManager->getMessages()->deleteMessageByIdentifier(MessageInterface::DEFAULT_IDENTIFIER);
                    }
                }

                if ($errorMessages) {
                    $response['errors'] = true;
                    $response['error_messages'] = $errorMessages;
                } else {
                    $response['errors'] = false;
                }
            } else {
                $response['errors'] = true;
                $response['require_login'] = true;
            }
            $subject->getResponse()->clearHeader('Location');
            $jsonResult->setData($response);

            return $jsonResult;
        }

        return $result;
    }
}