<?php
namespace PhilTurner\GroupPricesInvite\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\Result\PageFactory;

class Result extends Action
{
    /** @var PageFactory $resultPageFactory */
    protected $resultPageFactory;

    /**
     * Result constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->resultPageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * The controller action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $number = $this->getRequest()->getParam('number');

        $resultPage = $this->resultPageFactory->create();

        /** @var Messages $messageBlock */
        $messageBlock = $resultPage->getLayout()->createBlock(
            'Magento\Framework\View\Element\Messages',
            'answer'
        );
        if (is_numeric($number)) {
            $messageBlock->addSuccess($number . ' times 2 is ' . ($number * 2));
        }else{
            $messageBlock->addError('You didn\'t enter a number!');
        }

        $resultPage->getLayout()->setChild(
            'content',
            $messageBlock->getNameInLayout(),
            'answer_alias'
        );

        return $resultPage;
    }
}