<?php

namespace Concordpay\Payment\Block\Form;

/**
 * Abstract class for Concordpay payment method form
 */
abstract class Concordpay extends \Magento\Payment\Block\Form
{
    protected $_instructions;
    protected $_template = 'html/concordpay_form.phtml';
}
