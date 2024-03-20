<?php

return new \Atoolo\Resource\Resource(
    '/dir/b.php',
    'b',
    'b',
    '',
    'de_DE',
    [
        'base' => [
            'trees' => [
                'navigation' => [
                    'parents' => [
                        'a' => [
                            'url' => '/dir/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ]
);
