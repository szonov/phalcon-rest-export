<?php

namespace PhalconRest\Export;

use Phalcon\Mvc\View\Simple as View;
use PhalconRest\Mvc\Controllers\CollectionController;

use PhalconRest\Export\Documentation\Documentation;
use PhalconRest\Export\Documentation\DocumentationTransformer;

use PhalconRest\Export\Postman\Postman;
use PhalconRest\Export\Postman\PostmanTransformer;

class ExportController extends CollectionController
{
    protected function getConfigValue($name, $default)
    {
        return ($this->di->has('config')) ? $this->di->get('config')->get($name, $default) : $default;
    }

    protected function getTitle()
    {
        return $this->getConfigValue('exportTitle', 'API');
    }

    protected function getHost()
    {
        $default = 'http' . ($this->request->isSecure()?'s':'') . '//' . $this->request->getHttpHost();

        return $this->getConfigValue('exportHost', $default);
    }

    protected function getDescription()
    {
        return $this->getConfigValue('exportDescription', 'Phalcon REST Api');
    }

    public function documentationJson()
    {
        $documentation = new Documentation($this->getTitle(), $this->getHost());
        $documentation->addManyCollections($this->application->getCollections());
        $documentation->addManyRoutes($this->application->getRouter()->getRoutes());

        return $this->createItemResponse($documentation, new DocumentationTransformer(), 'documentation');
    }

    public function postmanJson()
    {
        $postmanCollection = new Postman($this->getTitle(), $this->getHost());
        $postmanCollection->addManyCollections($this->application->getCollections());
        $postmanCollection->addManyRoutes($this->application->getRouter()->getRoutes());

        return $this->createItemResponse($postmanCollection, new PostmanTransformer());
    }

    public function documentation()
    {
        $view = new View();
        $view->setDI($this->di);
        $view->setViewsDir(__DIR__ . '/Views/');

        $view->setVar('title', $this->getTitle());
        $view->setVar('description', $this->getDescription());
        $view->setVar('documentationPath', './documentation.json');

        return $view->render('documentation');
    }
}
