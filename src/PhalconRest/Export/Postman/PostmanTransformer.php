<?php

namespace PhalconRest\Export\Postman;

use PhalconRest\Transformers\Transformer;

class PostmanTransformer extends Transformer
{
    protected $useAuthHeader;

    protected $defaultIncludes = [
        'folders',
        'requests',
    ];

    public function __construct($useAuthHeader = true)
    {
        $this->useAuthHeader = $useAuthHeader;
    }

    public function transform(Postman $collection)
    {
        return [
            'id' => $collection->id,
            'name' => $collection->name,
            'description' => $collection->description,
            'order' => $collection->order,
        ];
    }

    public function includeFolders(Postman $collection)
    {
        return $this->collection($collection->getFolders(), new FolderTransformer());
    }

    public function includeRequests(Postman $collection)
    {
        return $this->collection($collection->getRequests(), new RequestTransformer($this->useAuthHeader));
    }
}
