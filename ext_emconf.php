<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Advanced Controller  - Tesseract project',
    'description' => 'This extension manages relations between Tesseract components and produces output in the FE.  More info on http://www.typo3-tesseract.com',
    'category' => 'plugin',
    'author' => 'Francois Suter (Cobweb), Fabien Udriot (Ecodev)',
    'author_email' => 'typo3@cobweb.ch,fabien.udriot@ecodev.ch',
    'state' => 'obsolete',
    'version' => '1.4.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'cms' => '',
                    'typo3' => '7.6.0-7.99.99',
                    'tesseract' => '1.4.0-0.0.0',
                    'displaycontroller' => '',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                ],
        ],
    'suggests' =>
        [
        ]
];
