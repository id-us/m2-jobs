<?php
namespace Idus\Jobs\Block\Adminhtml\Jobs;
/**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Idus\Jobs\Model\jobsFactory
     */
    protected $_jobsFactory;

    /**
     * @var \Idus\Jobs\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Idus\Jobs\Model\jobsFactory $jobsFactory
     * @param \Idus\Jobs\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Idus\Jobs\Model\JobsFactory $jobsFactory,
        \Idus\Storelocator\Model\StoresFactory $storesFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_jobsFactory = $jobsFactory;
        $this->moduleManager = $moduleManager;
        $this->_storesFactory = $storesFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        // parent::_construct();
        // $this->setId('postGrid');
        // $this->setDefaultSort('job_id');
        // $this->setDefaultDir('DESC');
        // $this->setSaveParametersInSession(true);
        // $this->setUseAjax(true);
        // $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_jobsFactory->create()->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'job_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'job_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index' => 'title'
            ]
        );

        $this->addColumn(
            'code',
            [
                'header' => __('Code'),
                'index' => 'code'
            ]
        );

        $this->addColumn(
            'store',
            [
                'header' => __('Store'),
                'index' => 'store',
                'type' => 'options',
                'options' => $this->getStoreArray()
            ]
        );

        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => ['No','Yes']
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit'
                        ],
                        'field' => 'job_id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('job_id');
        $this->getMassactionBlock()->setTemplate('Idus_Core::idus/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('jobs');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('jobs/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('jobs/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => ['No','Yes']
                    ]
                ]
            ]
        );

        return $this;
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
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('jobs/*/index', ['_current' => true]);
    }

    /**
     * @param \Idus\Jobs\Model\jobs|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'jobs/*/edit',
            ['job_id' => $row->getId()]
        );
    }
}