<?php
namespace Resolve\Resolve\Model\Adminhtml\Source;

use Magento\Framework\Option\ArrayInterface;
use Resolve\Resolve\Model\CheckoutFlowType;

/**
 * Class PaymentCheckoutFlow
 * Source model for the system configuration
 * retrieve payment checkout flow options.
 *
 * @package Resolve\Resolve\Model\Adminhtml\Source
 */
class PaymentCheckoutFlow implements ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => CheckoutFlowType::CHECKOUT_FLOW_REDIRECT,
                'label' => __('Redirect'),
            ],
            [
                'value' => CheckoutFlowType::CHECKOUT_FLOW_MODAL,
                'label' => __('Modal'),
            ]
        ];
    }
}
