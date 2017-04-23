<?php
/**
 * This file is part of short-messenger.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

return [

    'default' => 'aliyun',

    'providers' => [

        'aliyun' => [
            'region'     => 'cn-hangzhou',
            'access_id'  => env('ALIYUN_SMS_ACCESS_ID', 'LTAIS3OWhRUo1dWN'),
            'access_key' => env('ALIYUN_SMS_ACCESS_KEY', 'k6Hsk2rvkWtXcEjI4PDfz9VQAnfXcm'),
        ],

        'sendcloud' => [
            'sms_user' => env('SENDCLOUD_SMS_USER', ''),
            'sms_key'  => env('SENDCLOUD_SMS_KEY', ''),
        ],
    ],
];
