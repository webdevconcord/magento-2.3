<?php declare(strict_types=1);

namespace Concordpay\Payment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

abstract class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'concordpay';
}
