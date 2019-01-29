<?php

namespace PhalconRest\Export\Documentation;

use PhalconRest\Transformers\Transformer;

class EndpointTransformer extends Transformer
{
    public function transform(Endpoint $endpoint)
    {
        return [
            'name' => $endpoint->getName(),
            'description' => $endpoint->getDescription(),
            'httpMethod' => $endpoint->getHttpMethod(),
            'path' => $endpoint->getPath(),
            'exampleResponse' => $endpoint->getExampleResponse(),
            'allowedRoles' => $endpoint->getAllowedRoles(),
            'conditions' => $endpoint->getConditions(),
        ];
    }
}
