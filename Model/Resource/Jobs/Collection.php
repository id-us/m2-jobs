<?php
namespace Idus\Jobs\Model\Resource\Jobs;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Idus\Jobs\Model\Jobs', 'Idus\Jobs\Model\Resource\Jobs');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>