<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'https://events.gamerzbyte.com/payresponse/*',
        'https://dev.gamerzbyte.com/payresponse/*',
        'http://nextgenbuddy.com/ESportsEventsManagement/gamerzbyte-web/payresponse/*',
        'http://localhost/EsportsEventsManagement/gamerzbyte-web/index.php/payresponse/*',

        'https://events.gamerzbyte.com/app/payresponse/*',
        'https://dev.gamerzbyte.com/app/payresponse/*',
        'http://nextgenbuddy.com/ESportsEventsManagement/gamerzbyte-web/app/payresponse/*',
        'http://localhost/EsportsEventsManagement/gamerzbyte-web/index.php/app/payresponse/*'
    ];
}
