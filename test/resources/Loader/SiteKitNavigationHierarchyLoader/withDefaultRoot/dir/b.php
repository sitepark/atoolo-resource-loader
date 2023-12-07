<?php

return new \Atoolo\Resource\Resource(
    '/dir/b.php',
    'b',
    'b',
    '',
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
