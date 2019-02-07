<?php

namespace PhalconRestExport\Documentation;

use PhalconRest\Transformers\Transformer;

class CollectionTransformer extends Transformer
{
    public $defaultIncludes = [
        'endpoints'
    ];

    public function transform(Collection $collection)
    {
        return [
            'name' => $collection->getName(),
            'description' => $collection->getDescription(),
            'prefix' => $collection->getPath(),
            'fields' => $collection->getFields()
        ];
    }

    public function includeEndpoints(Collection $collection)
    {
        return $this->collection($collection->getEndpoints(), new EndpointTransformer);
    }
}
