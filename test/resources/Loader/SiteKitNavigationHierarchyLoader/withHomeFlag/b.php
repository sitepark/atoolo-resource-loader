<?php

return new \Atoolo\Resource\Resource(
    '/b.php',
    'b',
    'b',
    '',
    \Atoolo\Resource\ResourceLanguage::of('de_DE'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'navigation' => [
                    'parents' => [
                        'a' => [
                            'url' => '/a.php'
                        ]
                    ]
                ]
            ]
        ]
    ])
);
