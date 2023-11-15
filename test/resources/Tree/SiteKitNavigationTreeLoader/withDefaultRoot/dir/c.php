<?php

return new \Atoolo\Resource\Resource(
    '/dir/c.php',
    'c',
    'c',
    '',
    [
        'base' => [
            'tree' => [
                'navigation' => [
                    'parents' => [
                        'b' => [
                            'isPrimary' => true,
                            'url' => '/dir/b.php'
                        ],
                        'a' => [
                            'url' => '/dir/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
