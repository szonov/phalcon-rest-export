<?php

namespace PhalconRest\Export\Postman;

use PhalconRest\Transformers\Transformer;

class RequestTransformer extends Transformer
{
    protected $useAuthHeader;

    public function __construct($useAuthHeader = true)
    {
        $this->useAuthHeader = $useAuthHeader;
    }

    public function transform(Request $request)
    {
        return array_filter([
            'collectionId' => $request->collectionId,
            'folder' => $request->folderId,
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'method' => $request->method,
            'headers' => $request->headers,
            'data' => $request->data,
            'dataMode' => $request->dataMode,
        ], function ($key) {
            return $key !== 'headers' || $this->useAuthHeader;
        }, ARRAY_FILTER_USE_KEY);
    }
}
