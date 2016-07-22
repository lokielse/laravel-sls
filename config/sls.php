<?php
return [
    'access_key_id'     => env('ALIYUN_ACCESS_KEY_ID'),
    'access_key_secret' => env('ALIYUN_ACCESS_KEY_SECRET'),
    /**
     * https://help.aliyun.com/document_detail/29008.html
     */
    'endpoint'          => env('SLS_ENDPOINT'),
    'project'           => env('SLS_PROJECT'),
    'store'             => env('SLS_STORE'),
    'topic'             => null,
];