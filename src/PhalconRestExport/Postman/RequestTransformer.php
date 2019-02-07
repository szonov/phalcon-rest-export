<?php

namespace PhalconRestExport\Postman;

use PhalconRest\Transformers\Transformer;

class RequestTransformer extends Transformer
{
    public function transform(Request $request)
    {
        return [
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
        ];
    }
}
