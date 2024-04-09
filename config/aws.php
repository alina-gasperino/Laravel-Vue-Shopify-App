<?php

return [

    'dynamo' =>
        [
            'visits' => [
                'ttl' => env('DYNAMO_VISITS_TTL', '2592000'),
                'name' => env('DYNAMO_VISITS_NAME', 'simplepost_visits'),
            ]

        ]
];
