<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/primaryParentWithoutUrl.php',
    'primaryParentWithoutUrl',
    'primaryParentWithoutUrl',
    '',
    \Atoolo\Resource\ResourceLanguage::of('en'),
    new \Atoolo\Resource\DataBag([
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        'a' => [
                            'isPrimary' => true,
                            'url' => false
                        ]
                    ]
                ]
            ]
        ]
    ])
);
