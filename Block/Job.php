<?php
namespace Idus\Jobs\Block;
/**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */

class Job extends \Idus\Core\Block\Renders
{
    const ROUTE = 'jobs';
    const ROUTE_CHILD = 'job';


    public function idus(){
        return $this->idus;
    }

    public function getRenderHtml(){
        $path = 'jobs/layout_'.$this->getType();
        return $this->setRender($this,$path);
    }

    public function setRender($render,$mode){
        $modes = $this->getModes('jobs',$mode);
        $html = $this->renderStructure($modes,$render);
        return $html;
    }

    public function _prepareLayout(){
        $this->config = $this->idus->getConfigValue('jobs/jobs');
        if(!$this->route = $this->config['url_key']) $this->route = self::ROUTE;
        if(!$this->routeChild = $this->config['url_key_job']) $this->routeChild = self::ROUTE_CHILD;
        $this->pageConfig->getTitle()->set($this->getTitle());
        return parent::_prepareLayout();
    }

    public function getEnabled(){
        if(!$this->config['enabled']){
            return false;
        }
        return true;
    }

    public function getJobUrl($job_id){
        return $this->getUrl($this->route.'/'.$this->routeChild.'/'.$job_id);
    }
}
