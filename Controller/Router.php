<?php

namespace Idus\Jobs\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    const ROUTE = 'jobs';
    const ROUTE_CHILD = 'job';
    
    protected $objectManager;
    protected $idus;
    protected $config;
    protected $route;
    protected $routeChild;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;
    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Idus\Core\Helper\Data $idus,
        \Magento\Framework\App\ResponseInterface $response
    ) {
        $this->objectManager = $objectManager;
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->idus = $idus;
        $this->config = $this->idus->getConfigValue('jobs/jobs');
        if(!$this->route = $this->config['url_key']) $this->route = self::ROUTE;
        if(!$this->routeChild = $this->config['url_key_job']) $this->routeChild = self::ROUTE_CHILD;
    }
    /**
     * Validate and Match
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if ($request->getModuleName() == self::ROUTE) {
            return;
        }
        $identifier = trim($request->getPathInfo(), '/');

        $pathInfo = explode('/', $identifier);

        if ($pathInfo[0] != $this->route) return;
        if(count($pathInfo) == 1){
            $request->setModuleName(self::ROUTE)->setControllerName('index')->setActionName('index');
        }elseif(isset($pathInfo[1]) && $pathInfo[1] == $this->routeChild && isset($pathInfo[2])){
            $jobs = $this->objectManager->get('Idus\Jobs\Block\Jobs');
            $job_id = $pathInfo[2];
            $job = $jobs->getJob($job_id);
            if(!$job) return null;
            $request->setModuleName(self::ROUTE)->setControllerName('page')->setActionName('view')->setParam('job_id', $job_id);
        }else{
            return null;
        }

        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);

        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }
}