<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TecnonView
 *
 * @author augusto
 */
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\ServerUrl;
use Admin\Entity\View;
use Admin\Entity\Tree;
use Admin\Entity\Toolbar;
use Admin\Entity\Panel;

class ViewHelper extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Recebe EntityManager do Doctrine.
     * 
     * @var EntityManegr
     */
    protected $entityManager;
    
    public $serviceLocator;
	
    /**
     * Retorna uma Entidade de trabalho para doctrine.
     * 
     * @return EntityManager.
     */
    public function __construct(EntityManager $em) 
    {
        $this->entityManager = $em;      
    }
    
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) 
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    /**
     * Retorna uma Entidade de trabalho para doctrine.
     * 
     * @return EntityManager.
     */
    public function getEntityManager()
    {
        if ( !$this->entityManager ) 
        {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        
        return $this->entityManager;
    }
    
    /**
     * Cria nova view recebendo o conteúdo por parâmetro
     * 
     * @param String html $insideElements
     */
    public function __invoke(View $view)
    {
        $viewRender = "<div class='row'>";
        
        if ( $view->getTree() instanceof Tree )
        {
            $viewRender .= $this->TreeHelper($view->getTree());
        }
        
        if ( $view->getToolbar() instanceof Toolbar )
        {
            $viewRender .= $this->ToolbarHelper($view->getToolbar());
        }
        
        if ( $view->getPanel() instanceof Panel )
        {
            $viewRender .= $this->PanelHelper($view->getPanel());
        }
        
        $viewRender = "</div>";
        return $viewRender;
    }
    
    /**
     * Retorna a url atual.
     * 
     * @return String
     */
    public function getCurrentUrl()
    {
        $serverUrl = new ServerUrl();
        $currentUrl = $serverUrl->__invoke(true);
        
        return $currentUrl;
    }
    
    /**
     * Retorna a url completa da rota atual.
     * 
     * @return string
     */
    public function getCurrentRouteUrl()
    {
        $currentUrl = $this->getCurrentUrl();
        $expCurrentUrl = explode('/', $currentUrl);
        $baseUrl = $expCurrentUrl[0] . '//' . $expCurrentUrl[2] . '/';
        
        $sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
        $router = $sm->get('Router');
        $request = $sm->get('Request');
        $routeMatch = $router->match($request);
        $viewRoute = $routeMatch->getParam('module') . '/' . $routeMatch->getMatchedRouteName() . '/';
        
        return $baseUrl . $viewRoute;
    }
}

?>
