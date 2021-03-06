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

namespace Resolve\Resolve\Gateway\Helper\Request;

use Magento\Payment\Gateway\ConfigInterface;

/**
 * Class Action
 */
class Action
{
    /**#@+
     * Define constants
     */
    const API_CHARGES_PATH = '/api/v2/charges/';
    const API_CHECKOUT_PATH = '/api/v2/checkout/';
    /**#@-*/

    /**
     * Action
     *
     * @var string
     */
    private $action;

    /**
     * Config
     *
     * @var ConfigInterface
     */
    private $config;

    /**
     * Constructor
     *
     * @param string $action
     * @param ConfigInterface $config
     */
    public function __construct($action, ConfigInterface $config)
    {
        $this->action = $action;
        $this->config = $config;
    }

    /**
     * Get request URL
     *
     * @param string $additionalPath
     * @return string
     */
    public function getUrl($additionalPath = '')
    {
        $gateway = $this->config->getValue('mode') == 'sandbox'
            ? \Resolve\Resolve\Model\Config::API_URL_SANDBOX
            : \Resolve\Resolve\Model\Config::API_URL_PRODUCTION;

        return $gateway . $additionalPath ;
    }
}
