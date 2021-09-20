<?php

namespace Concordpay\Payment\Block\Widget;

/**
 * Abstract class for Cash On Delivery and Bank Transfer payment method form
 */

use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\OrderFactory;
use Concordpay\Payment\Model\Concordpay;

/**
 * Class Redirect
 * @package Concordpay\Payment\Block\Widget
 */
class Redirect extends Template
{
    /**
     * @var Concordpay
     */
    protected $Config;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var Config
     */
    protected $_orderConfig;

    /**
     * @var Context
     */
    protected $httpContext;

    /**
     * @var string
     */
    protected $_template = 'html/concordpay_form.phtml';

    /**
     * Get frontend checkout session object.
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckout()
    {
        return $this->_checkoutSession;
    }

    /**
     * @param Template\Context                $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param Session                         $customerSession
     * @param OrderFactory                    $orderFactory
     * @param Config                          $orderConfig
     * @param Context                         $httpContext
     * @param Concordpay                      $paymentConfig
     * @param array                           $data
     */
    public function __construct(
        Template\Context                $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        Session                         $customerSession,
        OrderFactory                    $orderFactory,
        Config                          $orderConfig,
        Context                         $httpContext,
        Concordpay                      $paymentConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->_orderFactory    = $orderFactory;
        $this->_orderConfig     = $orderConfig;
        $this->_isScopePrivate  = true;
        $this->httpContext      = $httpContext;
        $this->Config           = $paymentConfig;
        $this->_getOrder();
    }

    /**
     * Get instructions text from config.
     *
     * @return null|string
     */
    public function getGateUrl()
    {
        return $this->Config->getGateUrl();
    }

    /**
     * Get the amount to be paid.
     *
     * @return float|null
     */
    public function getAmount()
    {
        $orderId = $this->_checkoutSession->getLastOrderId();
        if ($orderId) {
            $incrementId = $this->_checkoutSession->getLastRealOrderId();

            return $this->Config->getAmount($incrementId);
        }

        return null;
    }

    /**
     * Get form data.
     *
     * @return string|null
     */
    public function getPostData()
    {
        $orderId = $this->_getOrder()->getData('entity_id');
        if ($orderId) {
            $fields = $this->Config->getPostData($orderId);

            return $this->Config->getFormFields($fields);
        }

        return null;
    }

    /**
     * Get Pay URL.
     *
     * @return string
     */
    public function getPayUrl()
    {
        $baseUrl = $this->getUrl("concordpay/url");

        //print_R ($baseUrl);die;
        return "{$baseUrl}concordpaysuccess";
    }

    /**
     * @return mixed
     */
    public function getRealOrderId()
    {
        $lastorderId = $this->_checkoutSession->getLastOrderId();

        return $lastorderId;
    }

    /**
     * @return bool|Order
     */
    public function getOrder()
    {
        if ($this->_checkoutSession->getLastRealOrderId()) {
            $order = $this->_orderFactory->create()->loadByIncrementId(
                $this->_checkoutSession->getLastRealOrderId()
            );

            return $order;
        }

        return false;
    }

    /**
     * @return bool|\Magento\Sales\Api\Data\OrderAddressInterface|\Magento\Sales\Model\Order\Address|null
     */
    public function getShippingInfo()
    {
        $order = $this->getOrder();
        if ($order) {
            $address = $order->getShippingAddress();

            return $address;
        }
        return false;
    }

    /**
     * Get order object
     *
     * @return Order
     */
    protected function _getOrder()
    {
        if (!$this->_order) {
            $incrementId  = $this->_getCheckout()->getLastRealOrderId();
            $this->_order = $this->_orderFactory->create()->loadByIncrementId($incrementId);
        }

        return $this->_order;
    }

    /**
     * @return Order|null
     */
    public function getLastOrder()
    {
        $orderId  = $this->_checkoutSession->getLastOrderId();
        $order    = $this->_orderFactory->create();
        $resource = $order->getResource()->load($order, $orderId);

        return $order;
    }

    /**
     * @param string $template
     * @return Template
     */
    public function setTemplate($template)
    {
        $order = $this->getLastOrder();
        if (!$order) {
            return parent::setTemplate('');
        }
        $payment = $order->getPayment();
        if (!$payment) {
            return parent::setTemplate('');
        }

        $method = $payment->getMethodInstance();
        if ($method && $method->getCode() === Concordpay::METHOD_CODE) {
            return parent::setTemplate($template);
        }

        return parent::setTemplate('');
    }
}
