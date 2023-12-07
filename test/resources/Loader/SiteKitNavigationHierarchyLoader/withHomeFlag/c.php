<?php

return new \Atoolo\Resource\Resource(
    '/c.php',
    'c',
    'c',
    '',
    [
        'base' => [
            'trees' => [
                'navigation' => [
                    'parents' => [
                        'b' => [
                            'isPrimary' => true,
                            'url' => '/b.php'
                        ],
                        'a' => [
                            'url' => '/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
