<?php

namespace PhalconRestExport;

use Phalcon\Mvc\View\Simple as View;
use PhalconRest\Mvc\Controllers\CollectionController;

use PhalconRestExport\Documentation\Documentation;
use PhalconRestExport\Documentation\DocumentationTransformer;

use PhalconRestExport\Postman\Postman;
use PhalconRestExport\Postman\PostmanTransformer;

class ExportController extends CollectionController
{
    protected function getViewParams()
    {
        $config = $this->di->has('config') ? $this->di->get('config')->get('export', []) : [];

        return [
            'title' => $config['title'] ?? 'API',
            'description' => $config['description'] ?? 'Phalcon REST Api',
            'host' => $config['host'] ?? $this->request->getScheme() . '://' . $this->request->getHttpHost()
        ];
    }

    protected function getViewDir()
    {
        return __DIR__ . '/Views/';
    }

    public function documentationJson()
    {
        $vars = $this->getViewParams();
        $documentation = new Documentation($vars['title'], $vars['host']);
        $documentation->addManyCollections($this->application->getCollections());
        $documentation->addManyRoutes($this->application->getRouter()->getRoutes());

        return $this->createItemResponse($documentation, new DocumentationTransformer(), 'documentation');
    }

    public function postmanJson()
    {
        $vars = $this->getViewParams();
        $postmanCollection = new Postman($vars['title'], $vars['host']);
        $postmanCollection->addManyCollections($this->application->getCollections());
        $postmanCollection->addManyRoutes($this->application->getRouter()->getRoutes());

        return $this->createItemResponse($postmanCollection, new PostmanTransformer);
    }

    public function documentationHtml()
    {
        $view = new View();
        $view->setDI($this->di);
        $view->setViewsDir($this->getViewDir());
        $view->setVars($this->getViewParams());
        $view->setVar('documentationPath', './documentation.json');

        return $view->render('documentation');
    }
}
