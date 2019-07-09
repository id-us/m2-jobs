<?php
namespace Idus\Jobs\Controller\Adminhtml\jobs;
/**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;


class Save extends \Magento\Backend\App\Action
{

	/**
	 * @param Action\Context $context
	 */
	public function __construct(Action\Context $context)
	{
		parent::__construct($context);
	}

	/**
	 * Save action
	 *
	 * @return \Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($data) {
			$model = $this->_objectManager->create('Idus\Jobs\Model\Jobs');

			$id = $this->getRequest()->getParam('job_id');
			if ($id) {
				$model->load($id);
			}

			$model->setData($data);

			try {
				$model->save();
				$this->messageManager->addSuccess(__('The Job has been saved.'));
				$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
				if ($this->getRequest()->getParam('back')) {
					return $resultRedirect->setPath('*/*/edit', ['job_id' => $model->getId(), '_current' => true]);
				}
				return $resultRedirect->setPath('*/*/');
			} catch (\Magento\Framework\Exception\LocalizedException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\RuntimeException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\Exception $e) {
				$this->messageManager->addException($e, __('Something went wrong while saving the Job.'));
			}

			$this->_getSession()->setFormData($data);
			return $resultRedirect->setPath('*/*/edit', ['job_id' => $this->getRequest()->getParam('job_id')]);
		}
		return $resultRedirect->setPath('*/*/');
	}
}
