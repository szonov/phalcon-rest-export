<?php

namespace PhalconRest\Export\Documentation;

use PhalconRest\Transformers\Transformer;

class DocumentationTransformer extends Transformer
{
    public $defaultIncludes = [
        'routes',
        'collections'
    ];

    public function transform(Documentation $documentation)
    {
        return [
            'name' => $documentation->name,
            'basePath' => $documentation->basePath
        ];
    }

    public function includeRoutes(Documentation $documentation)
    {
        return $this->collection($documentation->getRoutes(), new RouteTransformer);
    }

    public function includeCollections(Documentation $documentation)
    {
        return $this->collection($documentation->getCollections(), new CollectionTransformer);
    }
}
