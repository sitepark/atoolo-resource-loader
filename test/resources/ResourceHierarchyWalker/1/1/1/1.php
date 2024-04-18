<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/1-1-1-1.php',
    '1-1-1-1',
    '1-1-1-1',
    '',
    \Atoolo\Resource\ResourceLanguage::of('en'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        '1-1-1' => [
                            'url' => '/1/1/1.php'
                        ]
                    ],
                ]
            ]
        ]
    ])
);
