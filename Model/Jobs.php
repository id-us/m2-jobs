<?php
namespace Idus\Jobs\Model;

class Jobs extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Idus\Jobs\Model\Resource\Jobs');
    }
}
?>