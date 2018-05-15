<?php

return [
    'sandbox' => (app()->environment()=='production' || app()->environment()=='prod')?false:true,
    'url_live' => 'http://api.cint.com/',
    'url_sandbox' => 'http://cdp.cintworks.net',
    'sandbox_key' => 'f4bb1cc4-f3b1-4d04-a74a-3144029938e0',
    'sandbox_secret' => 'kDKkaEpOu8Oad'
];
