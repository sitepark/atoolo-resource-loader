<?php

return new \Atoolo\Resource\Resource(
    '/dir/c.php',
    'c',
    'c',
    '',
    \Atoolo\Resource\ResourceLanguage::of('de_DE'),
    new \Atoolo\Resource\DataBag([
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
    ])
);
