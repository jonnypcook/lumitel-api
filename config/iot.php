<?php

return [
    /*
     |--------------------------------------------------------------------------
     | IOT Consumer Details
     |--------------------------------------------------------------------------
     |
     | use this configuration file to add details required for
     | iot connectivity
     |
     */
    'liteip' => [
        'api' => [
            'project' => 'http://portal.liteip.com/8p3/GetProjectID.aspx',
            'drawing' => 'http://portal.liteip.com/8p3/GetDrawingID.aspx?ProjectID=%d',
            'device' => 'http://portal.liteip.com/8p3/GetDevice.aspx?DrawingID=%d&E3Only=%d',
        ]
    ],
    'litewaverf' => [
        'credentials' => [
            'secret' => 'cab2c73132e764003cb3',
            'clientId' => 'e629f50f-d87a-4e02-8f01-f5cc7127d241'
        ],
        'api' => [
            'discovery' => 'https://pub-api.lightwaverf.com/api/com/discovery',
            'command' => 'https://pub-api.lightwaverf.com/api/com/device/%d/command/%s/%s',
            'auth' => 'https://auth.lightwaverf.com/token',
            'device-data' => 'https://pub-api.lightwaverf.com/api/com/device/%d/data/%s?from=%s&to=%s&perpage=%d&page=%d',
        ]
    ],
];
