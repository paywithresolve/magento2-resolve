<?php
namespace Resolve\Resolve\Block\Adminhtml\Rule\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('resolve_rule_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Payment Restriction Rules Options'));
    }
}