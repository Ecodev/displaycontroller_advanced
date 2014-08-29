<?php
// $Id: ext_tables.php 86260 2014-08-29 12:22:56Z fab1en $
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Add new columns to tt_content
	//
	// A note about MM_match_fields:
	// This structure makes use of a lot of additional fields in the MM table
	// "component" defines whether the related component is a consumer, a provider and a filter
	// "rank" defines the position of the component in the relation chain (1, 2, 3, ...)
	// "local_table" and "local_field" are set so that the relation can be reversed-engineered
	// when looking from the other side of the relation (i.e. the component). They help
	// the component know to which record from which table it is related and in which
	// field to find the type of controller (which is matched to a specific datacontroller service)
$tempColumns = array(
	'tx_displaycontroller_consumer' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller/locallang_db.xml:tt_content.tx_displaycontroller_consumer',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => (isset( $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_consumer']['config']['allowed'])) ?  $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_consumer']['config']['allowed'] : '',
			'size' => 1,
			'minitems' => 1,
			'maxitems' => 1,
			'prepend_tname' => 1,
			'MM' => 'tx_displaycontroller_components_mm',
			'MM_match_fields' => array(
				'component' => 'consumer',
				'rank' => 1,
				'local_table' => 'tt_content',
				'local_field' => 'CType'
			),
			'wizards' => array(
				'edit' => array(
					'type' => 'popup',
					'title' => 'LLL:EXT:displaycontroller/locallang_db.xml:wizards.edit_dataconsumer',
					'script' => 'wizard_edit.php',
					'icon' => 'edit2.gif',
					'popup_onlyOpenIfSelected' => 1,
					'notNewRecords' => 1,
					'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1,resizable=yes'
				),
			)
		)
	),
	'tx_displaycontrolleradvanced_providergroup' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:displaycontroller_advanced/locallang_db.xml:tt_content.tx_displaycontrolleradvanced_providergroup',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_displaycontrolleradvanced_providergroup',
			'foreign_field' => 'content',
		)
	),
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns, 1);

	// Define showitem property for both plug-ins
$showItem = 'CType;;4;button,hidden,1-1-1, header;;3;;2-2-2,linkToTop;;;;3-3-3';
$showItem .= ', --div--;LLL:EXT:displaycontroller/locallang_db.xml:tabs.dataobjects, tx_displaycontroller_consumer;;;;1-1-1, tx_displaycontrolleradvanced_providergroup;;;;2-2-2';
$showItem .= ', --div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime';

 $GLOBALS['TCA']['tt_content']['types'][$_EXTKEY . '_pi1']['showitem'] = $showItem;
 $GLOBALS['TCA']['tt_content']['types'][$_EXTKEY . '_pi2']['showitem'] = $showItem;

	// Register icons for content type
	// Define classes and register icon files with Sprite Manager
 $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$_EXTKEY . '_pi1'] =  'extensions-displaycontroller_advanced-type-controller';
 $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$_EXTKEY . '_pi2'] =  'extensions-displaycontroller_advanced-type-controller';

	// Register icon in the BE and for FE editing (code taken from TemplaVoilà)
if (TYPO3_MODE == 'BE' ||
	(TYPO3_MODE == 'FE' && isset($GLOBALS['BE_USER']) && method_exists($GLOBALS['BE_USER'], 'isFrontendEditingActive')  && $GLOBALS['BE_USER']->isFrontendEditingActive())
) {
	$icons = array(
		'type-controller' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Images/displaycontroller_advanced_typeicon.png',
		'providergroup' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Images/tx_displaycontrolleradvanced_providergroup.png',
	);
	t3lib_SpriteManager::addSingleIcons($icons, $_EXTKEY);
}

	// Add context sensitive help (csh) for the new fields
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_content', 'EXT:' . $_EXTKEY . '/locallang_csh_ttcontent.xml');

	// Register plug-ins (pi1 is cached, pi2 is not cached)
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:displaycontroller_advanced/locallang_db.xml:tt_content.CType_pi1',
		$_EXTKEY . '_pi1',
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Images/displaycontroller_advanced_typeicon.png'
	),
	'CType'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:displaycontroller_advanced/locallang_db.xml:tt_content.CType_pi2',
		$_EXTKEY . '_pi2',
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Images/displaycontroller_advanced_typeicon.png'
	),
	'CType'
);

	// Register the name of the table linking the controller and its components
#$T3_VAR['EXT']['tesseract']['controller_mm_tables'][] = 'tx_displaycontrolleradvanced_components_mm';

	// Define main TCA for table tx_displaycontrolleradvanced_providergroup
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_displaycontrolleradvanced_providergroup');

	// ATM, defines here allowed data type + wizard
 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_provider']['config']['allowed'] .= ',tx_dataquery_queries';
 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_datafilter']['config']['allowed'] .= ',tx_datafilter_filters';
 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'] .= ',tx_datafilter_filters';

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tagpackprovider')) {
	 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_provider2']['config']['allowed'] .= ',tx_tagpackprovider_selections';
}

	// Add a wizard for adding a dataquery
$addDataqueryWizard = array(
						'type' => 'script',
						'title' => 'LLL:EXT:dataquery/locallang_db.xml:wizards.add_dataquery',
						'script' => 'wizard_add.php',
						'icon' => 'EXT:dataquery/res/icons/add_dataquery_wizard.gif',
						'params' => array(
								'table' => 'tx_dataquery_queries',
								'pid' => '###CURRENT_PID###',
								'setValue' => 'append'
							)
						);
 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_provider']['config']['wizards']['add_dataquery'] = $addDataqueryWizard;


$addDatafilteryWizard = array(
						'type' => 'script',
						'title' => 'LLL:EXT:datafilter/locallang_db.xml:wizards.add_datafilter',
						'script' => 'wizard_add.php',
						'icon' => 'EXT:datafilter/res/icons/add_datafilter_wizard.gif',
						'params' => array(
								'table' => 'tx_datafilter_filters',
								'pid' => '###CURRENT_PID###',
								'setValue' => 'append'
							)
						);
 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_datafilter']['config']['wizards']['add_datafilter'] = $addDatafilteryWizard;
 $GLOBALS['TCA']['tx_displaycontrolleradvanced_providergroup']['columns']['tx_displaycontroller_datafilter2']['config']['wizards']['add_datafilter2'] = $addDatafilteryWizard;
