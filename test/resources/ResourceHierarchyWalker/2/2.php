<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/2/2.php',
    '2-2',
    '2-2',
    '',
    \Atoolo\Resource\ResourceLanguage::of('en'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        '2' => [
                            'url' => '/2.php'
                        ]
                    ]
                ]
            ]
        ]
    ])
);
