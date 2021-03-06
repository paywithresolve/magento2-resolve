<?php
namespace Resolve\Resolve\Block\Adminhtml\Rule\Grid\Renderer;
class Stores extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $hlp = $om->get('Resolve\Resolve\Helper\Data');
        $sys = $om->get('Magento\Store\Model\System\Store');
        $stores = $row->getData('stores');
        if (!$stores) {
            return __('Restricts in All');
        }
        
        $html = '';
        $data = $sys->getStoresStructure(false, explode(',', $stores));
        foreach ($data as $website) {
            $html .= $website['label'] . '<br/>';
            foreach ($website['children'] as $group) {
                $html .= str_repeat('&nbsp;', 3) . $group['label'] . '<br/>';
                foreach ($group['children'] as $store) {
                    $html .= str_repeat('&nbsp;', 6) . $store['label'] . '<br/>';
                }
            }
        }
        return $html;
    }
}