<?php

namespace Idus\Jobs\Block\System\Form\Field;

class Brands implements \Magento\Framework\Option\ArrayInterface
{

    public function __construct(
        \Idus\Storelocator\Model\StoresFactory $storesFactory,
        \Idus\Core\Helper\Data $idus
    ) {
        $this->_storesFactory = $storesFactory;
        $this->idus = $idus;
    }

    public function toOptionArray()
    {
        $stores = [];
        $collection = $this->_storesFactory->create()->getCollection();
        $collection->addFieldToSelect('brand');
        $collection->getSelect()->group('brand');
        foreach ($collection as $store) {
            $stores[$store->getBrand()] = $store->getBrand();
        }
        return $stores;
    }
}
