<?php

namespace Idus\Jobs\Block\Adminhtml\Jobs\Edit\Tab;

/**
 * Jobs edit form main tab
 */
 /**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Idus\Jobs\Model\jobsFactory
     */
    protected $_jobsFactory;


    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Idus\Jobs\Model\jobsFactory $jobsFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Idus\Jobs\Model\JobsFactory $jobsFactory,
        \Idus\Storelocator\Model\StoresFactory $storesFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ){
        $this->_systemStore = $systemStore;
        $this->wysiwyg = $wysiwygConfig->getConfig();
        $this->_jobsFactory = $jobsFactory;
        $this->_storesFactory = $storesFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Idus\Jobs\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('jobs');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Job Settings')]);

        if ($model->getId()) {
            $fieldset->addField('job_id', 'hidden', ['name' => 'job_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('Code'),
                'title' => __('Code'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'store',
            'select',
            [
                'name' => 'store',
                'label' => __('Store'),
                'title' => __('Store'),
                'options' => $this->getStoreArray(),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'emails',
            'text',
            [
                'name' => 'emails',
                'label' => __('Emails'),
                'title' => __('Emails'),
                'required' => false,
                'note' => __('explode by comma'),
                'disabled' => $isElementDisabled
            ]
        );


        $fieldset->addField(
            'short_content',
            'editor',
            [
                'name' => 'short_content',
                'label' => __('Short Content'),
                'config'    => $this->wysiwyg,
                'title' => __('Short Content'),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Content'),
                'config'    => $this->wysiwyg,
                'title' => __('Content'),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['No','Yes'],
                'disabled' => $isElementDisabled
            ]
        );

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Job Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Job Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    public function getStoreArray(){
        $stores = array();
        $collection = $this->_storesFactory->create()->getCollection();
        $stores[''] = __('-- Select Store --');
        foreach($collection as $store){
            $stores[$store->getCode()] = $store->getTitle();
        }
        return $stores;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
