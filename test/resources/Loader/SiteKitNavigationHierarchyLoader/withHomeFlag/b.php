<?php

return new \Atoolo\Resource\Resource(
    '/b.php',
    'b',
    'b',
    '',
    [
        'base' => [
            'trees' => [
                'navigation' => [
                    'parents' => [
                        'a' => [
                            'url' => '/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
