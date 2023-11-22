<?php

return new \Atoolo\Resource\Resource(
    '/b.php',
    'b',
    'b',
    '',
    [
        'base' => [
            'tree' => [
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
