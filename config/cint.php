<?php

return [
    'sandbox' => (env('APP_ENV')=='production' || env('APP_ENV')=='prod')?false:true,
    'url_live' => 'http://api.cint.com/',
    'url_sandbox' => 'http://cdp.cintworks.net',
    'sandbox_key' => env('sandbox_key', ''),
    'sandbox_secret' => env('sandbox_secret', '')
];
