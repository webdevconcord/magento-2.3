<?php

namespace Concordpay\Payment\Controller\Url;

use Magento\Authorizenet\Model\DirectPost;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class ConcordpaySuccess
 *
 * @package Concordpay\Payment\Controller\Url
 */
class ConcordpaySuccess extends Action
{
    /** @var PageFactory */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Load the page defined.
     *
     * @return void
     */
    public function execute()
    {
        $this->_redirect('checkout/onepage/success');
    }
}
