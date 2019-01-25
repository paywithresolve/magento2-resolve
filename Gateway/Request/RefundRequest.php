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

namespace Resolve\Resolve\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Resolve\Resolve\Gateway\Helper\Util;

/**
 * Class RefundRequest
 */
class RefundRequest extends AbstractDataBuilder
{
    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     * @throws \InvalidArgumentException
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var PaymentDataObjectInterface $payment */
        $paymentDataObject = $buildSubject['payment'];
        $payment = $paymentDataObject->getPayment();
        $chargeId = $payment->getAdditionalInformation(self::CHARGE_ID);
        $amountInCents = Util::formatToCents($buildSubject['amount']);
        $order = $payment->getOrder();
        if($order) {
            $storeId = $order->getStoreId();
        }
        if (!$storeId) {
            $storeId = null;
        }
        return [
            'body' => [
                'amount' => $amountInCents,
                'charge_id' => $chargeId
            ],
            'path' => "refund",
            'storeId' => $storeId
        ];
    }
}
