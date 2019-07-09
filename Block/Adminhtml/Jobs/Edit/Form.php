<?php

namespace Idus\Jobs\Block\Adminhtml\Jobs\Edit;

/**
 * Adminhtml jobs edit form block
 *
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{     
    protected function _construct()
    {
        parent::_construct();
        $this->setId('Idus_Jobs_form');
        $this->setTitle(__('Job Information'));
    }


    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post' , 'enctype' => 'multipart/form-data']]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
