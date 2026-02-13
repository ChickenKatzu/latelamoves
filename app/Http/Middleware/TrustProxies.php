<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (Railway, Cloudflare, dll)
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * Use all forwarded headers
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
