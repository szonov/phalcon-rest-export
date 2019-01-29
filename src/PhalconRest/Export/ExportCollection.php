<?php

namespace PhalconRest\Export;

use PhalconRest\Api\ApiCollection;
use PhalconRest\Api\ApiEndpoint;

class ExportCollection extends ApiCollection
{
    protected function initialize()
    {
        $this
            ->name('export')
            ->handler(ExportController::class)

            ->endpoint(
                ApiEndpoint::get('/documentation.html', 'documentation')
                    ->description('HTML Documentation of methods')
            )
            ->endpoint(
                ApiEndpoint::get('/documentation.json', 'documentationJson')
                    ->description('JSON with data for HTML Documentation')
            )
            ->endpoint(
                ApiEndpoint::get('/postman.json', 'postmanJson')
                    ->description('JSON file for importing by postman')
            )
        ;
    }
}
