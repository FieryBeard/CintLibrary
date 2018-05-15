<?php

return [
    'sandbox' => (app()->environment()=='production' || app()->environment()=='prod')?false:true,
    'url_live' => 'http://api.cint.com/',
    'url_sandbox' => 'http://cdp.cintworks.net',
    'sandbox_key' => env('sandbox_key', ''),
    'sandbox_secret' => env('sandbox_secret', '')
];
