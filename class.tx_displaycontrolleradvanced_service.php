<?php

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Service for the 'displaycontrolleradvanced' extension.
 */
class tx_displaycontrolleradvanced_service extends tx_tesseract_controllerbase {
	/**
	 * This method is expected to return the primary provider related to the given display controller instance
	 *
	 * @throws Exception
	 * @return    tx_tesseract_dataprovider    Reference to an object implementing the DataProvider interface
	 */
	public function getRelatedProvider() {
			// Get table where the relation to the provider is stored
		$mmTable = $GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_provider']['config']['MM'];
			// Get the provider-relation record
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', $mmTable, "uid_local = '" . $this->uid . "' AND component = 'provider' AND rank = '1'");
		$numRows = count($rows);
		if ($numRows == 0) {
			throw new Exception('No provider found');
        } else {
				// Create an instance of the appropriate service
			$provider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstanceService('dataprovider', $rows[0]['tablenames']);
				// NOTE: loadData() may throw an exception, but we just let it pass at this point
			$provider->loadData(array('table' => $rows[0]['tablenames'], 'uid' => $rows[0]['uid_foreign']));
			return $provider;
        }
    }
}
