<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | This determines the default session driver for storing session data.
    | You can choose drivers like "file", "cookie", "database", "redis", etc.
    | Update based on your application's requirements.
    |
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | The number of minutes a session remains active before expiring. Set
    | 'expire_on_close' to true to make the session expire when the browser
    | is closed.
    |
    */

    'lifetime' => env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | Enable encryption to secure session data. This will encrypt all session
    | data before storing it. Recommended for sensitive applications.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', true),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | The directory where session files are stored when using the "file" driver.
    | Ensure this directory is writable by the web server.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection and Table
    |--------------------------------------------------------------------------
    |
    | Specify the database connection and table to use when using the "database"
    | session driver. Make sure the table is migrated properly.
    |
    */

    'connection' => env('SESSION_CONNECTION', null),

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | Specify the cache store for session data when using drivers like "redis".
    | This should match one of the cache stores in the cache configuration.
    |
    */

    'store' => env('SESSION_STORE', null),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | For some session drivers, old session data must be swept manually.
    | The "lottery" determines the probability of this happening on a request.
    | The default value is [2, 100], which means 2% of requests will trigger it.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | The name of the session cookie. This can be customized for your app
    | but doesn't provide a security benefit.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | The path for which the session cookie is available. Usually set to '/'.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Define the domain for the session cookie. Setting this to your root domain
    | (e.g., example.com) allows sharing cookies across subdomains.
    |
    */

    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | Setting this to true ensures cookies are only sent over secure HTTPS
    | connections. Enable this in production to enhance security.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Prevent JavaScript from accessing cookies by setting this to true. This
    | protects against certain types of XSS attacks.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | Determines the Same-Site attribute for cookies to control cross-site
    | behavior. Use "lax" for security and cross-origin support, or "none"
    | if cookies are accessed in cross-origin contexts.
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Enable this for partitioned cookies in cross-origin contexts. This is only
    | valid when Same-Site is set to "none" and Secure is true.
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
