<?php
namespace Idus\Jobs\Block\Adminhtml\Jobs\Edit;

/**
 * Admin page left menu
 */
 /**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('jobs_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Job Information'));
    }
}
