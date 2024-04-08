<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/2.php',
    '2',
    '2',
    '',
    [
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        'root' => [
                            'url' => '/root.php'
                        ]
                    ],
                    'children' => [
                        '2-1' => [
                            'id' => '2-1',
                            'url' => '/2/1.php'
                        ],
                        '2-2' => [
                            'id' => '2-2',
                            'url' => '/2/2.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
