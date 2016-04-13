<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:displaycontroller_advanced/locallang_db.xml:tx_displaycontrolleradvanced_providergroup',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => TRUE,
        'origUid' => 't3_origuid',
        'default_sortby' => 'ORDER BY uid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'extensions-displaycontroller_advanced-providergroup',
        ],
        'dividers2tabs' => 1,
    ],
    'feInterface' => [
        'fe_admin_fieldList' => 'hidden, title, description, sql_query, t3_mechanisms',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,title,description,sql_query,t3_mechanisms'
    ],
    'columns' => [
        'uid' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'content' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'max' => '30',
            ]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => '0'
            ]
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:dataquery/locallang_db.xml:tx_dataquery_queries.title',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'required,trim',
            ]
        ],
        'tx_displaycontroller_provider' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_provider',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['allowed'] : '',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'prepend_tname' => 1,
                'MM' => 'tx_displaycontrolleradvanced_components_mm',
                'MM_match_fields' => [
                    'component' => 'provider',
                    'rank' => 1,
                    'local_table' => 'tx_displaycontrolleradvanced_providergroup',
                ],
                'wizards' => [
                    'edit' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_dataprovider',
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                        'icon' => 'edit2.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'notNewRecords' => 1,
                        'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
                    ],
                ]
            ]
        ],
//		'tx_displaycontroller_filtertype' => array (
//			'exclude' => 0,
//			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_filtertype',
//			'config' => array (
//				'type' => 'radio',
//				'items' => array (
//					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_filtertype.I.0', ''),
//					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_filtertype.I.1', 'single'),
//					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_filtertype.I.2', 'list'),
//					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_filtertype.I.3', 'filter'),
//				),
//			)
//		),
        'tx_displaycontroller_datafilter' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_datafilter',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'] : '',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'prepend_tname' => 1,
                'MM' => 'tx_displaycontrolleradvanced_components_mm',
                'MM_match_fields' => [
                    'component' => 'filter',
                    'rank' => 1,
                    'local_table' => 'tx_displaycontrolleradvanced_providergroup',
                ],
                'wizards' => [
                    'edit' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_datafilter',
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                        'icon' => 'edit2.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'notNewRecords' => 1,
                        'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
                    ],
                ]
            ]
        ],
        'tx_displaycontroller_emptyfilter' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter',
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.0', ''],
                    ['LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'],
                ],
            ]
        ],
        'tx_displaycontroller_provider2' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_provider2',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'] : '',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'prepend_tname' => 1,
                'MM' => 'tx_displaycontrolleradvanced_components_mm',
                'MM_match_fields' => [
                    'component' => 'provider',
                    'rank' => 2,
                    'local_table' => 'tx_displaycontrolleradvanced_providergroup',
                ],
                'wizards' => [
                    'edit' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_dataprovider',
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                        'icon' => 'edit2.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'notNewRecords' => 1,
                        'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
                    ],
                ]
            ]
        ],
        'tx_displaycontroller_emptyprovider2' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyprovider2',
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.0', ''],
                    ['LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'],
                ],
            ]
        ],
        'tx_displaycontroller_datafilter2' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_datafilter2',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'] : '',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'prepend_tname' => 1,
                'MM' => 'tx_displaycontrolleradvanced_components_mm',
                'MM_match_fields' => [
                    'component' => 'filter',
                    'rank' => 2,
                    'local_table' => 'tx_displaycontrolleradvanced_providergroup',
                ],
                'wizards' => [
                    'edit' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_datafilter',
                        'module' => [
                            'name' => 'wizard_edit',
                        ],
                        'icon' => 'edit2.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'notNewRecords' => 1,
                        'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
                    ],
                ]
            ]
        ],
        'tx_displaycontroller_emptyfilter2' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter',
            'config' => [
                'type' => 'radio',
                'items' => [
                    ['LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.0', ''],
                    ['LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'],
                ],
            ]
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'hidden;;;;1-1-1, title;;1;;2-2-2,tx_displaycontroller_provider;; displaycontroller_advanced_1;;2-2-2,  tx_displaycontroller_provider2;; displaycontroller_advanced_2;;2-2-2, tx_displaycontroller_emptyprovider2 ']
    ],
    'palettes' => [
        'displaycontroller_advanced_1' => ['showitem' => 'tx_displaycontroller_datafilter, tx_displaycontroller_emptyfilter'],
        'displaycontroller_advanced_2' => ['showitem' => 'tx_displaycontroller_datafilter2, tx_displaycontroller_emptyfilter2'],
    ]
];