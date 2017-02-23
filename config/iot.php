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
    'litewaverf' => ['*'],
];

