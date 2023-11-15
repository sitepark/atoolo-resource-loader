<?php

return new \Atoolo\Resource\Resource(
    '/b.php',
    'b',
    'b',
    '',
    [
        'base' => [
            'tree' => [
                'category' => [
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
