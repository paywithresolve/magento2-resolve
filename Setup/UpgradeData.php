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

namespace Resolve\Resolve\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Framework\App\State;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;

/**
 * Upgrade data
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory ;
    /**
     * @var CategoryCollection
     */
    private $categoryCollection;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CollectionFactory $collectionFactory
     * @param CategoryCollection $categoryCollection
     * @param State $state
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
            EavSetupFactory $eavSetupFactory,
            CollectionFactory $collectionFactory,
            CategoryCollection $categoryCollection,
            State $state,
            ProductRepositoryInterface $productRepository,
            CategoryRepositoryInterface $categoryRepository
    ) {
        $state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productCollectionFactory = $collectionFactory;
        $this->categoryCollection = $categoryCollection;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        ;
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '0.4.2', '<')) {
            /**
             * Add attributes to the product eav/attribute
             */
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'resolve_product_mfp',
                    [
                            'type' => 'varchar',
                            'backend' => '',
                            'frontend' => '',
                            'label' => 'Multiple Financing Program value',
                            'input' => 'text',
                            'class' => '',
                            'source' => '',
                            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
                            'group' => 'General',
                            'visible' => 1,
                            'required' => 0,
                            'user_defined' => 0,
                            'searchable' => 0,
                            'filterable' => 0,
                            'comparable' => 0,
                            'visible_on_front' => 0,
                            'used_in_product_listing' => 0,
                            'unique' => 0,
                            'apply_to' => '',
                    ]
            );

            /**
             * Add attributes to the category eav/attribute
             */
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY,
                    'resolve_category_mfp',
                    [
                            'type' => 'varchar',
                            'label' => 'Multiple Financing Program value',
                            'input' => 'text',
                            'required' => 0,
                            'sort_order' => 100,
                            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                            'group' => 'General Information',
                            'is_used_in_grid' => 0,
                            'is_visible_in_grid' => 0,
                            'is_filterable_in_grid' => 0,
                    ]
            );
        }

        if (version_compare($context->getVersion(), '0.4.3', '<')) {
            /**
             * Add attributes to the product eav/attribute
             */
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'resolve_product_mfp_type',
                    [
                            'type' => 'int',
                            'backend' => '',
                            'frontend' => '',
                            'label' => 'Multiple Financing Program type',
                            'input' => 'select',
                            'class' => '',
                            'source' => 'Resolve\Resolve\Model\Entity\Attribute\Source\FinancingProgramType',
                            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
                            'group' => 'General',
                            'visible' => 1,
                            'required' => 0,
                            'user_defined' => 0,
                            'searchable' => 0,
                            'filterable' => 0,
                            'comparable' => 0,
                            'visible_on_front' => 0,
                            'used_in_product_listing' => 0,
                            'unique' => 0,
                            'apply_to' => '',
                    ]
            );
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'resolve_product_mfp_priority',
                    [
                            'type' => 'int',
                            'backend' => '',
                            'frontend' => '',
                            'label' => 'Multiple Financing Program priority',
                            'input' => 'text',
                            'class' => 'validate-number',
                            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
                            'group' => 'General',
                            'visible' => 1,
                            'required' => 0,
                            'user_defined' => 0,
                            'searchable' => 0,
                            'filterable' => 0,
                            'comparable' => 0,
                            'visible_on_front' => 0,
                            'used_in_product_listing' => 0,
                            'unique' => 0,
                            'apply_to' => '',
                    ]
            );

            /**
             * Add attributes to the category eav/attribute
             */
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY,
                    'resolve_category_mfp_type',
                    [
                            'type' => 'int',
                            'label' => 'Multiple Financing Program type',
                            'input' => 'select',
                            'required' => 0,
                            'sort_order' => 101,
                            'source' => 'Resolve\Resolve\Model\Entity\Attribute\Source\FinancingProgramType',
                            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                            'group' => 'General Information',
                            'is_used_in_grid' => 0,
                            'is_visible_in_grid' => 0,
                            'is_filterable_in_grid' => 0,
                    ]
            );
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY,
                    'resolve_category_mfp_priority',
                    [
                            'type' => 'int',
                            'label' => 'Multiple Financing Program priority',
                            'input' => 'text',
                            'class' => 'validate-number',
                            'required' => 0,
                            'sort_order' => 102,
                            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                            'group' => 'General Information',
                            'is_used_in_grid' => 0,
                            'is_visible_in_grid' => 0,
                            'is_filterable_in_grid' => 0,
                    ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'rs_product_mfp_start_date',
                    [
                            'type' => 'datetime',
                            'backend' => '',
                            'frontend' => '',
                            'label' => 'Start date for time based Financing Program value',
                            'input' => 'date',
                            'class' => '',
                            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
                            'group' => 'General',
                            'visible' => 1,
                            'required' => 0,
                            'user_defined' => 0,
                            'searchable' => 0,
                            'filterable' => 0,
                            'comparable' => 0,
                            'visible_on_front' => 0,
                            'used_in_product_listing' => 1,
                            'unique' => 0,
                            'apply_to' => '',
                    ]
            );

            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'resolve_product_mfp_end_date',
                    [
                            'type' => 'datetime',
                            'backend' => '',
                            'frontend' => '',
                            'label' => 'End date for time based Financing Program value',
                            'input' => 'date',
                            'class' => '',
                            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
                            'group' => 'General',
                            'visible' => 1,
                            'required' => 0,
                            'user_defined' => 0,
                            'searchable' => 0,
                            'filterable' => 0,
                            'comparable' => 0,
                            'visible_on_front' => 0,
                            'used_in_product_listing' => 1,
                            'unique' => 0,
                            'apply_to' => '',
                    ]
            );

            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY,
                    'rs_category_mfp_start_date',
                    [
                            'type' => 'datetime',
                            'label' => 'Start date for time based Financing Program value',
                            'input' => 'date',
                            'class' => '',
                            'required' => 0,
                            'sort_order' => 103,
                            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                            'group' => 'General Information',
                            'is_used_in_grid' => 0,
                            'is_visible_in_grid' => 0,
                            'is_filterable_in_grid' => 0,
                            'used_in_product_listing' => 1,
                    ]
            );

            $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Category::ENTITY,
                    'resolve_category_mfp_end_date',
                    [
                            'type' => 'datetime',
                            'label' => 'End date for time based Financing Program value',
                            'input' => 'date',
                            'class' => '',
                            'required' => 0,
                            'sort_order' => 104,
                            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                            'group' => 'General Information',
                            'is_used_in_grid' => 0,
                            'is_visible_in_grid' => 0,
                            'is_filterable_in_grid' => 0,
                            'used_in_product_listing' => 1,
                    ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {

            /**
             * Add attributes to the category eav/attribute
             */
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'resolve_category_promo_id',
                [
                    'type' => 'varchar',
                    'label' => 'Resolve Promo ID',
                    'input' => 'text',
                    'required' => 0,
                    'sort_order' => 105,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                    'group' => 'General Information',
                    'is_used_in_grid' => 0,
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 0,
                ]
            );

            /**
             * Add attributes to the product eav/attribute
             */
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'resolve_product_promo_id',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Resolve Promo ID',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
                    'group' => 'General',
                    'visible' => 1,
                    'required' => 0,
                    'user_defined' => 0,
                    'searchable' => 0,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'used_in_product_listing' => 1,
                    'unique' => 0,
                    'apply_to' => '',
                ]
            );
        }

        if (version_compare($context->getVersion(), '2.1.6', '<')) {

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'rs_category_mfp_start_date',
                [
                    'type' => 'datetime',
                    'label' => 'Start date for time based Financing Program value (Resolve)',
                    'input' => 'date',
                    'class' => '',
                    'required' => 0,
                    'sort_order' => 103,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_WEBSITE,
                    'group' => 'General Information',
                    'is_used_in_grid' => 0,
                    'is_visible_in_grid' => 0,
                    'is_filterable_in_grid' => 0,
                    'used_in_product_listing' => 1,
                ]
            );

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rs_product_mfp_start_date',
                [
                    'type' => 'datetime',
                    'label' => 'Start date for time based Financing Program value Resolve (Resolve)',
                    'input' => 'date',
                    'class' => '',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'group' => 'General',
                    'visible' => 1,
                    'required' => 0,
                    'user_defined' => 0,
                    'searchable' => 0,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'used_in_product_listing' => 1,
                    'unique' => 0,
                    'apply_to' => '',
                ]
            );

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'rs_product_mfp_end_date',
                [
                    'type' => 'datetime',
                    'label' => 'End date for time based Financing Program value Resolve (Resolve)',
                    'input' => 'date',
                    'class' => '',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'group' => 'General',
                    'visible' => 1,
                    'required' => 0,
                    'user_defined' => 0,
                    'searchable' => 0,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'used_in_product_listing' => 1,
                    'unique' => 0,
                    'apply_to' => '',
                ]
            );

            $productCollection = $this->productCollectionFactory
                ->create()
                ->addAttributeToSelect('*')
                ->load();

            foreach ($productCollection as $product) {
                if ($product->getData('resolve_product_mfp_start_date') || $product->getData('resolve_product_mfp_end_date')) {
                    $product->setData('rs_product_mfp_start_date', $product->getData('resolve_product_mfp_start_date'));
                    $product->setData('rs_product_mfp_end_date', $product->getData('resolve_product_mfp_end_date'));
                    $this->productRepository->save($product);
                }
            };

            $categoryCollection = $this->categoryCollection
                ->create()
                ->addAttributeToSelect('*')
                ->load();
            foreach ($categoryCollection as $catalog) {
                if ($catalog->getData('resolve_category_mfp_start_date')) {
                    $catalog->setData('rs_category_mfp_start_date', $catalog->getData('resolve_category_mfp_start_date'));
                    $this->categoryRepository->save($catalog);
                }
            };

            $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'resolve_category_mfp_start_date');
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'resolve_product_mfp_start_date');
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'resolve_product_mfp_end_date');
        }
    }
}
