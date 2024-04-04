<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/root.php',
    'root',
    'root',
    '',
    [
        'base' => [
            'trees' => [
                'category' => [
                    'children' => [
                        '1 1' => [
                            'id' => '1',
                            'url' => '/1.php'
                        ],
                        '2' => [
                            'id' => '2',
                            'url' => '/2.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
