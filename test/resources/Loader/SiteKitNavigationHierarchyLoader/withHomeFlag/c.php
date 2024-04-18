<?php

return new \Atoolo\Resource\Resource(
    '/c.php',
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
                            'url' => '/b.php'
                        ],
                        'a' => [
                            'url' => '/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ])
);
