<?php

namespace PhalconRestExport;

use PhalconRest\Api\ApiCollection;
use PhalconRest\Api\ApiEndpoint;

class ExportCollection extends ApiCollection
{
    protected function initialize()
    {
        $this
            ->name('Export')
            ->handler(ExportController::class)

            ->endpoint(
                ApiEndpoint::get('/documentation.html', 'documentationHtml')
                    ->description('HTML Documentation of methods')
                    ->name('documentationHtml')
            )
            ->endpoint(
                ApiEndpoint::get('/documentation.json', 'documentationJson')
                    ->description('JSON with data for HTML Documentation')
                    ->name('documentationJson')
            )
            ->endpoint(
                ApiEndpoint::get('/postman.json', 'postmanJson')
                    ->description('JSON file for importing by postman')
                    ->name('postmanJson')
            )
        ;
    }
}
