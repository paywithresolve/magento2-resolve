<?php
/**
 * Resolve
 * NOTICE OF LICENSE
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to codemaster@resolvecommerce.com so we can send you a copy immediately.
 *
 * @category  Resolve
 * @package   Resolve_Resolve
 * @copyright Copyright (c) 2016 Resolve, Inc. (http://www.resolvecommerce.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Resolve\Resolve\Api;

/**
 * Interface CheckoutPaymentManagerInterface
 *
 * @package Resolve\Resolve\Api
 * @api
 */
interface CheckoutPaymentManagerInterface
{
    /**
     * Init payment
     *
     * @return bool|string
     */
    public function initPayment();

    /**
     * Verify resolve selection
     *
     * @return bool|mixed
     */
    public function verifyResolve();
}
