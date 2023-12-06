<?php

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
                        'a' => [
                            'isPrimary' => true,
                            'url' => false
                        ]
                    ]
                ]
            ]
        ]
    ]
);
