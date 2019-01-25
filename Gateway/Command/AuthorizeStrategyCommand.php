<?php
/**
 * OnePica
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to codemaster@onepica.com so we can send you a copy immediately.
 *
 * @category    Resolve
 * @package     Resolve_Resolve
 * @copyright   Copyright (c) 2014 One Pica, Inc. (http://www.onepica.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Resolve\Resolve\Gateway\Command;

use Magento\Payment\Gateway\Command;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;
use Magento\Payment\Gateway\Helper\SubjectReader;

class AuthorizeStrategyCommand implements CommandInterface
{
    /**
     * Pre authorize
     */
    const PRE_AUTHORIZE = 'pre_authorize';

    /**
     * Order authorize
     */
    const ORDER_AUTHORIZE = 'order_authorize';

    const CHECKOUT_TOKEN = 'checkout_token';
    /**
     * Command pool
     *
     * @var Command\CommandPoolInterface
     */
    private $commandPool;

    /**
     * Constructor
     *
     * @param Command\CommandPoolInterface $commandPool
     */
    public function __construct(
        Command\CommandPoolInterface $commandPool
    ) {
        $this->commandPool = $commandPool;
    }

    /**
     * Executes command basing on business object
     *
     * @param array $commandSubject
     * @return
     * @throws LocalizedException
     */
    public function execute(array $commandSubject)
    {
        /** @var PaymentDataObjectInterface $payment */
        $payment = $commandSubject['payment'];
        $token = $payment->getPayment()->getAdditionalInformation(self::CHECKOUT_TOKEN);
        $payment->getPayment()->setAdditionalInformation('charge_id', $token);
        $payment->getPayment()->setTransactionId($token)->setIsTransactionClosed(0);
        return $payment;
    }
}
