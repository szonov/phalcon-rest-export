<?php

namespace PhalconRestExport\Postman;

use PhalconRest\Transformers\Transformer;

class FolderTransformer extends Transformer
{
    public function transform(Folder $folder)
    {
        return [
            'id' => $folder->id,
            'name' => $folder->name,
            'description' => $folder->description,
            'order' => $folder->order,
        ];
    }
}
