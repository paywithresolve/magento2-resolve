<?php
namespace Resolve\Resolve\Block\Promotion;

use Magento\Framework\View\Element\Template;
use Resolve\Resolve\Model\Ui\ConfigProvider;
use Resolve\Resolve\Model\Config;
use Resolve\Resolve\Helper\Payment;
use Resolve\Resolve\Helper\AsLowAs;
use Resolve\Resolve\Helper\Rule;
use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * Class Aslowas
 *
 * @package Resolve\Resolve\Block\Promotion
 */
abstract class AslowasAbstract extends \Magento\Framework\View\Element\Template
{
    /**
     * Data which should be converted to json from the Block data.
     *
     * @var array
     */
    protected $data = ['logo', 'script', 'public_api_key'];

    /**
     * Colors which could be set in "data-resolve-color".
     *
     * @var array
     */
    protected $dataColors = ['blue', 'black'];

    /**
     * Resolve config model payment
     *
     * @var \Resolve\Resolve\Model\Config
     */
    protected $resolvePaymentConfig;

    /**
     * Resolve config provider
     *
     * @var \Resolve\Resolve\Model\Ui\ConfigProvider
     */
    protected $configProvider;

    /**
     * Resolve payment model instance
     *
     * @var Payment
     */
    protected $resolvePaymentHelper;

    /**
     * Block type
     *
     * @var string
     */
    protected $type;

    /**
     * Position of "As Low As" block
     *
     * @var string
     */
    protected $position;

    /**
     * Placement of "As Low As" block
     *
     * @var string
     */
    protected $placement;

    /**
     * AsLowAs helper
     *
     * @var string
     */
    protected $asLowAsHelper;

    /**
     * Rule helper
     *
     * @var string
     */
    protected $ruleHelper;


    /**
     * Category collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Inject block init data
     *
     * @param Template\Context $context
     * @param ConfigProvider   $configProvider
     * @param Config           $configResolve
     * @param Payment          $helperResolve
     * @param array            $data
     * @param AsLowAs          $asLowAs
     * @param Rule             $rule
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        Template\Context $context,
        ConfigProvider $configProvider,
        Config $configResolve,
        Payment $helperResolve,
        array $data = [],
        AsLowAs $asLowAs,
        Rule $rule,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        if (isset($data['position']) && $data['position']) {
            $this->position = $data['position'];
        }

        $currentWebsiteId = $context->getStoreManager()->getStore()->getWebsiteId();
        $this->resolvePaymentConfig = $configResolve;
        $this->resolvePaymentConfig->setWebsiteId($currentWebsiteId);
        $this->configProvider = $configProvider;
        $this->resolvePaymentHelper = $helperResolve;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->asLowAsHelper = $asLowAs;
        $this->ruleHelper = $rule;

        if (isset($data['type'])) {
            $this->type = $data['type'];
        }

        $this->placement = isset($data['placement']) ? (int)$data['placement']: 0;
        parent::__construct($context, $data);
    }

    /**
     * Get all needed data for As Low As
     * before render this block.
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->process();
        return parent::_beforeToHtml();
    }

    /**
     * Verify if "As Low As" allowed in the position
     *
     * @param $position
     * @return int|mixed
     */
    protected function isAllowed($position)
    {
        return $this->resolvePaymentConfig->isAslowasEnabled($position) && $this->resolvePaymentConfig->isCurrencyValid() && $this->ruleHelper->isAslowasAllowedPerRule($position);
    }

    /**
     * Get specified data about As Low AS
     * from context of the block and convert it
     * to json format.
     *
     * @return string
     */
    public function getWidgetData()
    {
        if ($this->data && $this->resolvePaymentConfig->getAsLowAsLogo() &&
            $this->resolvePaymentConfig->getAsLowAsMonths()) {
            return $this->convertToJson($this->data);
        }
        return '';
    }

    /**
     * Get data-attribute for resolve logo color
     *
     * @return string
     */
    public function getDataResolveColor()
    {
        if(in_array($this->getData('logo'), $this->dataColors)) {
            return 'data-resolve-color="' . $this->getData('logo')  . '"';
        }
        return '';
    }

    /**
     * Specify all data for As Low AS widget.
     * Assign the data to the context of the block for simple
     * converting it to json format.
     */
    public function process()
    {
        if ($this->resolvePaymentConfig->getAsLowAsLogo() && $this->resolvePaymentConfig->getAsLowAsMonths()) {
            $this->setData('apr', $this->resolvePaymentConfig->getAsLowAsApr());
            $this->setData('months', $this->resolvePaymentConfig->getAsLowAsMonths());
            $this->setData('logo', $this->resolvePaymentConfig->getAsLowAsLogo());

            $configProvider = $this->configProvider->getConfig();
            if ($configProvider['payment'][ConfigProvider::CODE]) {
                $config = $configProvider['payment'][ConfigProvider::CODE];
                $this->setData('script', $config['script']);
                $this->setData('public_api_key', $config['apiKeyPublic']);
            }
            // Set max and min options amounts from payment configuration
            $this->setData('min_order_total', $this->getPaymentConfigValue('min_order_total'));
            $this->setData('max_order_total', $this->getPaymentConfigValue('max_order_total'));
        }
    }

    /**
     * Get is defined value from configuration
     *
     * @param string $value
     * @return bool|mixed
     */
    public function getPaymentConfigValue($value)
    {
        return $this->resolvePaymentConfig->getConfigData($value) ?
            $this->resolvePaymentConfig->getConfigData($value): false;
    }

    /**
     * Apply specific validation before show the block on front.
     * If validation will not passed, don't show this block in general.
     *
     * @return string|void
     */
    protected function _toHtml()
    {
        $isAllowed = $this->isAllowed($this->position);
        $placement = $this->resolvePaymentConfig->getAlaPlacement();
        if ($this->placement != $placement) {
            return '';
        }
        if ($this->validate() && $isAllowed) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Validate block before showing on front
     * Every child block class should have own validation rules
     * which will be applied before showing the block.
     *
     * @return boolean
     */
    abstract public function validate();

}
