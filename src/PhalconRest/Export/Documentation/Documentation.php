<?php

namespace PhalconRest\Export\Documentation;

use Phalcon\Mvc\Router\Route;
use PhalconRest\Api\ApiCollection;
use PhalconRest\Api\ApiResource;
use PhalconRest\Mvc\Plugin;
use PhalconRest\Transformers\ModelTransformer;

class Documentation extends Plugin
{
    public $name;
    public $basePath;
    protected $routes = [];
    protected $collections = [];

    public function __construct($name, $basePath)
    {
        $this->name = $name;
        $this->basePath = $basePath;
    }

    public function addManyRoutes(array $routes)
    {
        /** @var Route $route */
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }

    public function addRoute(Route $route)
    {
        $routeName = $route->getName();
        if ($routeName)
        {
            $parts = explode('!', $routeName, 3);
            if (@unserialize($parts[2] ?? null)) {
                return;
            }
        }
        $this->routes[] = $route;
    }

    public function addManyCollections(array $collections)
    {
        /** @var ApiCollection $collection */
        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }
    }

    public function addCollection(ApiCollection $apiCollection)
    {
        $aclRoles = $this->acl->getRoles();

        $collection = new Collection();
        $collection->setName($apiCollection->getName());
        $collection->setDescription($apiCollection->getDescription());
        $collection->setPath($apiCollection->getPrefix());

        // Set fields
        if ($apiCollection instanceof ApiResource) {

            if ($modelClass = $apiCollection->getModel()) {

                if ($transformerClass = $apiCollection->getTransformer()) {

                    /** @var ModelTransformer $transformer */
                    $transformer = new $transformerClass;

                    if ($transformer instanceof ModelTransformer) {

                        $transformer->setModelClass($modelClass);

                        $responseFields = $transformer->getResponseProperties();
                        $dataTypes = $transformer->getModelDataTypes();

                        $fields = [];

                        foreach ($responseFields as $field) {
                            $fields[$field] = array_key_exists($field,
                                $dataTypes) ? $dataTypes[$field] : ModelTransformer::TYPE_UNKNOWN;
                        }

                        $collection->setFields($fields);
                    }
                }
            }
        }

        // Add endpoints
        foreach ($apiCollection->getEndpoints() as $apiEndpoint) {
            $endpoint = new Endpoint();
            $endpoint->setName($apiEndpoint->getName());
            $endpoint->setDescription($apiEndpoint->getDescription());
            $endpoint->setHttpMethod($apiEndpoint->getHttpMethod());
            $endpoint->setPath($apiEndpoint->getPath());
            $endpoint->setExampleResponse($apiEndpoint->getExampleResponse());
            $endpoint->setConditions($apiEndpoint->getAclRules());

            $allowedRoleNames = [];

            $allowedRoles = $this->acl->whichRolesHaveAccess($apiCollection->getName(), $apiEndpoint->getName());
            foreach ($allowedRoles as $role => $conditions)
            {
                if (!empty($conditions))
                    $role .= '*';

                $allowedRoleNames[] = $role;
            }

//            foreach ($aclRoles as $role)
//            {
//                $conditions = [];
//                if ( $this->acl->isAllowed($role, $apiCollection->getName(), $apiEndpoint->getName(), $conditions) )
//                {
//                    if (!empty($conditions))
//                        $role .= '*';
//
//                    $allowedRoleNames[] = $role;
//                }
//            }

            $endpoint->setAllowedRoles($allowedRoleNames);

            $collection->addEndpoint($endpoint);
        }

        $this->collections[] = $collection;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getCollections()
    {
        return $this->collections;
    }
}
