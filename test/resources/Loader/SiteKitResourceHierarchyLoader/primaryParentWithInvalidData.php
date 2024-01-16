<?php

declare(strict_types=1);

return new \Atoolo\Resource\Resource(
    '/primaryParentWithoutUrl.php',
    'primaryParentWithoutUrl',
    'primaryParentWithoutUrl',
    '',
    [
        'base' => [
            'trees' => [
                'category' => [
                    'parents' => [
                        'a' => 'invalid'
                    ]
                ]
            ]
        ]
    ]
);
