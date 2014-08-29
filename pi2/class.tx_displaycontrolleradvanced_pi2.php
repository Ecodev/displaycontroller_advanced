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
 * Plugin 'Display Controller (not cached)' for the 'displaycontroller_advanced' extension.
 */
class tx_displaycontrolleradvanced_pi2 extends tx_displaycontrolleradvanced {
	public $scriptRelPath	= 'pi2/class.tx_displaycontrolleradvanced_pi2.php';	// Path to this script relative to the extension dir.
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/displaycontroller_advanced/pi2/class.tx_displaycontrolleradvanced_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/displaycontroller_advanced/pi2/class.tx_displaycontrolleradvanced_pi2.php']);
}

?>