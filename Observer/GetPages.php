<?php

namespace Idus\Jobs\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class GetPages implements ObserverInterface {

    protected $idus;
    protected $config;

    public function __construct(
        \Idus\Core\Helper\Data $idus
    ) {
        $this->idus = $idus;
        $this->config = $this->idus->getConfigValue('jobs/jobs');
    }

    public function execute(Observer $observer) {
        
        if (!isset($this->config['enabled']) || !$this->config['enabled']) return;
        $title = __('דרושים');
        if (isset($this->config['seo']) && isset($this->config['seo']['jobs_title']) && trim($this->config['seo']['jobs_title']) != '') {
            $title = $this->config['seo']['jobs_title'];
        }
        $jobs = [
            'title' => $title,
            'url' => $this->config['url_key'],
            'is_active' => true,
        ];

        $instance = $observer->getInstance();
        $instance->getAdditionalPages['jobs'] = $jobs;
    }
}