<?php

namespace Concordpay\Payment\Controller\Url;

use Magento\Authorizenet\Model\DirectPost;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class ConcordpayService.
 *
 * @package Concordpay\Payment\Controller\Url
 */
class ConcordpayService extends Action implements CsrfAwareActionInterface
{
    /** @var PageFactory */
    protected $resultPageFactory;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * ConcordpayService constructor.
     *
     * @param Context           $context
     * @param PageFactory       $resultPageFactory
     * @param DriverInterface   $driver
     */
    public function __construct(
        Context         $context,
        PageFactory     $resultPageFactory,
        DriverInterface $driver
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->driver = $driver;
        parent::__construct($context);
    }

    /**
     * Load the page defined.
     *
     * @return void
     */
    public function execute()
    {
        // Load model.
        /* @var $paymentMethod DirectPost */
        $paymentMethod = $this->_objectManager->create('Concordpay\Payment\Model\Concordpay');

        // Get request data.
        $callback = json_decode($this->driver->fileGetContents('php://input'), true);
        $data = [];
        foreach ($callback as $key => $val) {
            $data[$key] = $val;
        }

        $paymentMethod->processResponse($data);
    }

    /**
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
