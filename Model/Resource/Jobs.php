<?php
namespace Idus\Jobs\Model\Resource;

class Jobs extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('idus_jobs', 'job_id');
    }
}
?>