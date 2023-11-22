<?php

return new \Atoolo\Resource\Resource(
    '/c.php',
    'c',
    'c',
    '',
    [
        'base' => [
            'tree' => [
                'category' => [
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
