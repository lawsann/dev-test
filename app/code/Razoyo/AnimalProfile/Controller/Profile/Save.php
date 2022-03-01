<?php

namespace Razoyo\AnimalProfile\Controller\Profile;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class Save implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * Constructor
     * @param RequestInterface $request,
     * @param ResultFactory $resultFactory
     * @param MessageManagerInterface $messageManager
     * @param CustomerSession $customerSession
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        MessageManagerInterface $messageManager,
        CustomerSession $customerSession
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        $actionResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $animal = $this->request->getPostValue('animal', true);

        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addError(__('Please log in to choose your animal profile.'));
            return $actionResult->setPath('customer/account/login');
        }

        if (!$animal) {
            $this->messageManager->addError(__('Please inform the choosen animal profile.'));
            return $actionResult->setPath('*/*/view');
        }

        try {
            $this->customerSession->getCustomer()
                ->setAnimalProfile($animal)
                ->save();

            $this->messageManager->addSuccess(__('Animal profile successfully saved.'));

        } catch (LocalizedException $e) {
            $this->messageManager->addError(__('Could not save the animal profile. Error: %1', $e->getMessage()));
        }
        
        return $actionResult->setPath('*/*/view');
    }
}
