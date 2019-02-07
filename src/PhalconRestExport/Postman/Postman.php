<?php

namespace PhalconRestExport\Postman;

use Phalcon\Mvc\Router\Route;
use PhalconRest\Api\ApiCollection;

class Postman extends ApiCollection
{
    public $id;
    public $name;
    public $description;
    public $order = [];

    public $basePath;
    protected $requests = [];
    protected $folders  = [];

    public function __construct($name, $basePath)
    {
        $this->id = $this->uuid();
        $this->name = $name;
        $this->basePath = $basePath;
    }

    private function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function addManyRoutes(array $routes)
    {
        /** @var \Phalcon\Mvc\Router\Route $route */
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }

    public function addRoute(Route $route)
    {
        if (@unserialize($route->getName())) {
            return;
        }

        $name = $route->getName() ?: $route->getPattern();

        $request = $this->addRequest(new Request(
            $this->id,
            null,
            $this->uuid(),
            $name,
            null,
            $this->basePath . $route->getPattern(),
            $route->getHttpMethods(),
            'Authorization: Bearer {{authToken}}',
            null,
            "raw"
        ));
        $this->order[] = $request->id;
    }

    /**
     * @param Request $request
     * @return Request
     */
    public function addRequest(Request $request)
    {
        $this->requests[] = $request;
        return $request;
    }

    /**
     * @param Folder $folder
     * @return Folder
     */
    public function addFolder(Folder $folder)
    {
        $this->folders[] = $folder;
        return $folder;
    }

    public function addManyCollections(array $collections)
    {
        /** @var ApiCollection $collection */
        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }
    }

    public function addCollection(ApiCollection $collection)
    {
        $folder = $this->addFolder(new Folder(
            $this->uuid(),
            $collection->getName(),
            $collection->getDescription()
        ));

        foreach ($collection->getEndpoints() as $endpoint) {

            $request = $this->addRequest(new Request(
                $this->id,
                $folder->id,
                $this->uuid(),
                $collection->getPrefix() . $endpoint->getPath(),
                $endpoint->getDescription(),
                $this->basePath . $collection->getPrefix() . $endpoint->getPath(),
                $endpoint->getHttpMethod(),
                'Authorization: Bearer {{authToken}}',
                null,
                "raw"
            ));
            $folder->addRequest($request->id);
        }
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function getFolders()
    {
        return $this->folders;
    }
}
