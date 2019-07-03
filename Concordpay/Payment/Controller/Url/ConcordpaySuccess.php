<?php

namespace Concordpay\Payment\Controller\Url;

use Magento\Framework\App\Action\Action;

class ConcordpaySuccess extends Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    /**
     * Load the page defined
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_redirect('checkout/onepage/success');
    }
}
