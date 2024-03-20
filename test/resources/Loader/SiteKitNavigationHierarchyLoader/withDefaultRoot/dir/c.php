<?php

return new \Atoolo\Resource\Resource(
    '/dir/c.php',
    'c',
    'c',
    '',
    'de_DE',
    [
        'base' => [
            'trees' => [
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
