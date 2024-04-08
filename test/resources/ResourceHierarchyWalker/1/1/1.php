<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/1/1/1.php',
    '1-1-1',
    '1-1-1',
    '',
    '',
    [
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        '1-1' => [
                            'url' => '/1/1.php'
                        ]
                    ],
                    'children' => [
                        '1-1-1-1' => [
                            'id' => '1-1-1-1',
                            'url' => '/1/1/1/1.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
