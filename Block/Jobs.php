<?php
namespace Idus\Jobs\Block;
/**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */
class Jobs extends \Magento\Framework\View\Element\Template
{
    const ROUTE = 'jobs';
    const ROUTE_CHILD = 'job';
    /**
     * @var \Idus\Jobs\Model\jobsFactory
     */
    protected $_jobsFactory;
    protected $_status;
    public $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Idus\Jobs\Model\JobsFactory $jobsFactory,
        \Idus\Storelocator\Model\StoresFactory $storesFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Idus\Core\Helper\Data $idus
    ){
        $this->_jobsFactory = $jobsFactory;
        $this->_storesFactory = $storesFactory;
        $this->_objectManager = $objectManager;
        $this->idus = $idus;
        $this->config = $this->idus->getConfigValue('jobs/jobs');

        if(!isset($this->config['url_key']) || !$this->route = $this->config['url_key']) $this->route = self::ROUTE;
        if(!isset($this->config['url_key_job']) || !$this->routeChild = $this->config['url_key_job']) $this->routeChild = self::ROUTE_CHILD;

        parent::__construct($context);
    }
    
    public function _prepareLayout()
    {
        $description = false;
        if($job = $this->getJob()){
            if(isset($this->config['seo']) && isset($this->config['seo']['job_title']) && trim($this->config['seo']['job_title']) != ''){
                $title = str_replace('%job%',$job->getTitle(),$this->config['seo']['job_title']);
                $description = str_replace('%job%',$job->getTitle(),$this->config['seo']['job_description']);
            }else{
                $title = __('משרה').' '.$job->getTitle();
            }
        }else{
            if(isset($this->config['seo']) && isset($this->config['seo']['jobs_title']) && trim($this->config['seo']['jobs_title']) != ''){
                $title = $this->config['seo']['jobs_title'];
                $description = $this->config['seo']['jobs_description'];
            }else{
                $title = __('דרושים');
            }
        }

        $this->pageConfig->getTitle()->set($title);
        if($description) $this->pageConfig->setDescription($description);

        return parent::_prepareLayout();
    }

    public function getEnabled(){
        if(!$this->config['enabled']){
            return false;
        }
        return true;
    }
    
    public function getContent(){
        if(isset($this->config['content']) && trim(strip_tags($this->config['content']))){
            return $this->idus->getContentHtml($this->config['content']);
        }
        return false;
    }

    public function getAllCitis() {
        $citis = array();
        foreach ($this->getAllJobs() as $key => $job) {

            $jobCities = explode(',', $job->getCity());
            $jobAreas = explode(',', $job->getArea());
            foreach ($jobCities as $k => $jobCity) {

                $citis[$jobCity] = $jobAreas[$k] ?? $job->getArea();
            }
        }
        asort($citis);
        return $citis;
    }

    public function getAllAreas() {
        $areas = array();
        foreach ($this->getAllJobs() as $key => $job) {

            $jobAreas = explode(',', $job->getArea());
            foreach ($jobAreas as $jobArea) {

                $areas[$jobArea] = $jobArea;
            }
        }
        asort($areas);
        return $areas;
    }

    public function getJobHtml($job,$type){
        if(!isset($this->storeHtml)) $this->storeHtml = $this->getLayout()->createBlock('Idus\Jobs\Block\Job');
        return $this->storeHtml->setJob($job)->setType($type)->getRenderHtml();
    }

    public function getJobUrl($job_id){
        return $this->getUrl($this->route.'/'.$this->routeChild.'/'.$job_id);
    }

    public function getJob($job_id=false){
        if(!$job_id) $job_id = $this->getRequest()->getParam('job_id');
        if(isset($this->_getJob[$job_id]))return $this->_getJob[$job_id];
        
        if(!$job_id) return $this->_getJob[$job_id] = false;
        $collection = $this->_jobsFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        $collection->addFieldToFilter('job_id' ,$job_id );
        return $this->_getJob[$job_id] = $collection->getFirstItem();
    }

    public function getAllJobs() {

        if (isset($this->_getAllJobs)) return $this->_getAllJobs;
        $collection = $this->_jobsFactory->create()->getCollection();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('main_table.is_active', 1);
        $collection->getSelect()->group('main_table.job_id');

        $storesToFetch = [];
        foreach ($collection as $job) {

            $jobStores = explode(',', $job->getStore());
            $storesToFetch = array_merge($storesToFetch, $jobStores);
        }

        $storesCollection = $this->_storesFactory->create()->getCollection();
        $storesCollection->addFieldToSelect(['title', 'code', 'city', 'area']);
        $storesCollection->addFieldToFilter('code', ['in' => $storesToFetch]);

        $storesByCode = [];
        foreach ($storesCollection as $store) {

            $storesByCode[$store->getCode()] = $store->getData();
        }

        foreach ($collection as $job) {

            $jobStores = explode(',', $job->getStore());

            $jobCities = [];
            $jobAreas = [];
            foreach ($jobStores as $jobStore) {
                if(isset($storesByCode[$jobStore])){
                    $jobCities[] = @$storesByCode[$jobStore]['city'];
                    $jobAreas[] = @$storesByCode[$jobStore]['area'];
                }
            }

            $job->setCity(implode(',', $jobCities));
            $job->setArea(implode(',', $jobAreas));
        }

        $this->_getAllJobs = $collection;
        return $this->_getAllJobs;
    }

    public function getJobCityClass($job) {

        $cities = explode(',', $job->getCity());

        $cityClass = [];

        foreach ($cities as $city) {

            $cityClass[] = 'city_' . substr(md5($city), 0, 5);
        }

        return implode(' ', $cityClass);
    }

    public function getJobAreasClass($job) {

        $areas = explode(',', $job->getArea());

        $areasClass = [];

        foreach ($areas as $area) {

            $areasClass[] = 'area_' . substr(md5($area), 0, 5);
        }

        return implode(' ', $areasClass);
    }
}
