<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

return array(
	'ctrl' => array (
		'title'     => 'LLL:EXT:displaycontroller_advanced/locallang_db.xml:tx_displaycontrolleradvanced_providergroup',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'default_sortby' => 'ORDER BY uid',
		'delete' => 'deleted',
		'enablecolumns' => array (
			'disabled' => 'hidden',
		),
		'typeicon_classes' => array(
			'default' => 'extensions-displaycontroller_advanced-providergroup',
		),
		'dividers2tabs' => 1,
	),
	'feInterface' => array (
		'fe_admin_fieldList' => 'hidden, title, description, sql_query, t3_mechanisms',
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden,title,description,sql_query,t3_mechanisms'
	),
	'columns' => array(
		'uid' => Array (
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		'content' => Array (
			'config' => Array (
				'type' => 'passthrough'
			)
		),
		't3ver_label' => array(
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:dataquery/locallang_db.xml:tx_dataquery_queries.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim',
			)
		),
		'tx_displaycontroller_provider' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_provider',
			'config' => array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['allowed'] : '',
				'size' => 1,
				'minitems' => 1,
				'maxitems' => 1,
				'prepend_tname' => 1,
				'MM' => 'tx_displaycontrolleradvanced_components_mm',
				'MM_match_fields' => array(
					'component' => 'provider',
					'rank' => 1,
					'local_table' => 'tx_displaycontrolleradvanced_providergroup',
				),
				'wizards' => array(
					'edit' => array(
						'type' => 'popup',
						'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_dataprovider',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'notNewRecords' => 1,
						'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
					),
				)
			)
		),
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
		'tx_displaycontroller_datafilter' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_datafilter',
			'config' => array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'] : '',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'prepend_tname' => 1,
				'MM' => 'tx_displaycontrolleradvanced_components_mm',
				'MM_match_fields' => array(
					'component' => 'filter',
					'rank' => 1,
					'local_table' => 'tx_displaycontrolleradvanced_providergroup',
				),
				'wizards' => array(
					'edit' => array(
						'type' => 'popup',
						'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_datafilter',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'notNewRecords' => 1,
						'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
					),
				)
			)
		),
		'tx_displaycontroller_emptyfilter' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter',
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.0', ''),
					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'),
				),
			)
		),
		'tx_displaycontroller_provider2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_provider2',
			'config' => array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider2']['config']['allowed'] : '',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'prepend_tname' => 1,
				'MM' => 'tx_displaycontrolleradvanced_components_mm',
				'MM_match_fields' => array(
					'component' => 'provider',
					'rank' => 2,
					'local_table' => 'tx_displaycontrolleradvanced_providergroup',
				),
				'wizards' => array(
					'edit' => array(
						'type' => 'popup',
						'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_dataprovider',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'notNewRecords' => 1,
						'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
					),
				)
			)
		),
		'tx_displaycontroller_emptyprovider2' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyprovider2',
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.0', ''),
					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'),
				),
			)
		),
		'tx_displaycontroller_datafilter2' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_datafilter2',
			'config' => array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => (isset($GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'])) ? $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'] : '',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'prepend_tname' => 1,
				'MM' => 'tx_displaycontrolleradvanced_components_mm',
				'MM_match_fields' => array(
					'component' => 'filter',
					'rank' => 2,
					'local_table' => 'tx_displaycontrolleradvanced_providergroup',
				),
				'wizards' => array(
					'edit' => array(
						'type' => 'popup',
						'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_datafilter',
						'script' => 'wizard_edit.php',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'notNewRecords' => 1,
						'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
					),
				)
			)
		),
		'tx_displaycontroller_emptyfilter2' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter',
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.0', ''),
					array('LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_emptyfilter.I.1', 'all'),
				),
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;;;1-1-1, title;;1;;2-2-2,tx_displaycontroller_provider;;' . $_EXTKEY . '_1;;2-2-2,  tx_displaycontroller_provider2;;' . $_EXTKEY . '_2;;2-2-2, tx_displaycontroller_emptyprovider2 ')
	),
	'palettes' => array(
		$_EXTKEY . '_1' => array('showitem' => 'tx_displaycontroller_datafilter, tx_displaycontroller_emptyfilter'),
		$_EXTKEY . '_2' => array('showitem' => 'tx_displaycontroller_datafilter2, tx_displaycontroller_emptyfilter2'),
	)
);