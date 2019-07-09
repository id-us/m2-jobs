<?php
namespace Idus\Jobs\Controller\Adminhtml\jobs;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Idus_Jobs::jobs');
        $resultPage->addBreadcrumb(__('idus'), __('idus'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Jobs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Jobs'));

        return $resultPage;
    }
}
?>
