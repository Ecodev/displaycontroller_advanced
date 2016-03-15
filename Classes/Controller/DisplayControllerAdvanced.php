<?php
namespace Tesseract\DisplaycontrollerAdvanced\Controller;
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

use Cobweb\Expressions\ExpressionParser;
use Tesseract\Displaycontroller\Utility\Debugger;
use Tesseract\Tesseract\Component\DataProviderInterface;
use Tesseract\Tesseract\Frontend\PluginControllerBase;
use Tesseract\Tesseract\Tesseract;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Plugin 'Display Controller (cached)' for the 'displaycontrolleradvanced' extension.
 */
class DisplayControllerAdvanced extends PluginControllerBase
{

    public $prefixId = 'tx_displaycontroller'; // Same as class name
    public $extKey = 'displaycontroller_advanced';    // The extension key.

    /**
     * Contains a reference to the frontend Data Consumer object
     *
     * @var \Tesseract\Tesseract\Service\FrontendConsumerBase
     */
    protected $consumer;

    /**
     * @var bool FALSE if Data Consumer should not receive the structure
     */
    protected $passStructure = array();

    /**
     * @var array General extension configuration
     */
    protected $extensionConfiguration = array();

    /**
     * @var bool Debug to output or not
     */
    protected $debugToOutput = FALSE;

    /**
     * @var bool Debug to devlog or not
     */
    protected $debugToDevLog = FALSE;

    /**
     * @var int Minimum level of message to be logged. Default is all.
     */
    protected $debugMinimumLevel = -1;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Read the general configuration and initialize the debug flags
        $this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
        if (!empty($this->extensionConfiguration['debug'])) {
            $this->debug = TRUE;
            switch ($this->extensionConfiguration['debug']) {
                case 'output':
                    $this->debugToOutput = TRUE;
                    break;
                case 'devlog':
                    $this->debugToDevLog = TRUE;
                    break;
                case 'both':
                    $this->debugToOutput = TRUE;
                    $this->debugToDevLog = TRUE;
                    break;

                // Turn off all debugging if no valid value was entered
                default:
                    $this->debug = FALSE;
                    $this->debugToOutput = FALSE;
                    $this->debugToDevLog = FALSE;
            }
        }
        // Make sure the minimum debugging level is set and has a correct value
        if (isset($this->extensionConfiguration['minDebugLevel'])) {
            $level = intval($this->extensionConfiguration['minDebugLevel']);
            if ($level >= -1 && $level <= 3) {
                $this->debugMinimumLevel = $level;
            }
        }
    }

    /**
     * This method performs various initialisations
     *
     * @return    void
     */
    protected function init($conf)
    {
        // Merge the configuration of the pi* plugin with the general configuration
        // defined with plugin.tx_displaycontrolleradvanced (if defined)
        if (isset($GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.'])) {
            $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->prefixId . '.'];
            ArrayUtility::mergeRecursiveWithOverrule($this->conf, $conf);
        } else {
            $this->conf = $conf;
        }

        // Override standard piVars definition
        $this->piVars = GeneralUtility::_GPmerged($this->prefixId);
        // Finally load some additional data into the parser
        $this->loadParserData();
    }

    /**
     * This method loads additional data into the parser, so that it is available for Data Filters
     * and other places where expressions are used
     *
     * @return    void
     */
    protected function loadParserData()
    {
        // Load plug-in's variables into the parser
        ExpressionParser::setVars($this->piVars);
        // Load specific configuration into the extra data
        $extraData = array();
        if (is_array($this->conf['context.'])) {
            $extraData = GeneralUtility::removeDotsFromTS($this->conf['context.']);
        }
        // Allow loading of additional extra data from hooks
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['setExtraDataForParser'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['setExtraDataForParser'] as $className) {
                $hookObject = &GeneralUtility::getUserObj($className);
                $extraData = $hookObject->setExtraDataForParser($extraData, $this);
            }
        }
        // Add the extra data to the parser and to the TSFE
        if (count($extraData) > 0) {
            ExpressionParser::setExtraData($extraData);
            // TODO: this should not stay
            // This was added so that context can be available in the local TS of the templatedisplay
            // We must find another solution so that the templatedisplay's TS can use the ExpressionParser
            $GLOBALS['TSFE']->tesseract = $extraData;
        }
    }

    /**
     * The main method of the plugin
     * This method uses a controller object to find the appropriate Data Provider
     * The data structure from the Data Provider is then passed to the appropriate Data Consumer for rendering
     *
     * @param    string $content : the plugin's content
     * @param    array $conf : the plugin's TS configuration
     * @return    string        The content to display on the website
     */
    public function main($content, $conf)
    {
        $this->init($conf);
        $content = '';

        $providerGroups = $this->getDataProviderGroups($this->cObj->data['uid']);

        foreach ($providerGroups as $this->data) {
            // $this->passStructure should be unique for each loop
            $this->providerGroupUid = $this->data['uid'];
            $this->passStructure[$this->providerGroupUid] = TRUE;
            // Handle the secondary provider first
            $secondaryProvider = null;
            if (!empty($this->data['tx_displaycontroller_provider2'])) {
                // Get the secondary data filter, if any
                $secondaryFilter = $this->getEmptyFilter();
                if (!empty($this->data['tx_displaycontroller_datafilter2'])) {
                    $secondaryFilter = $this->defineAdvancedFilter('secondary');
                }
                // Get the secondary provider if necessary,
                // i.e. if the process was not blocked by the advanced filter (by setting the passStructure flag to false)
                if ($this->passStructure[$this->providerGroupUid]) {
                    try {
                        $secondaryProviderData = $this->getAdvancedComponent('provider', 2);
                        try {
                            $secondaryProvider = $this->getDataProvider($secondaryProviderData);
                            $secondaryProvider->setDataFilter($secondaryFilter);
                        } // Something happened, skip passing the structure to the Data Consumer
                        catch (Exception $e) {
                            $this->passStructure[$this->providerGroupUid] = FALSE;
                            if ($this->debug) {
                                echo 'Secondary provider set passStructure to false with the following exception: ' . $e->getMessage();
                            }
                        }
                    } catch (Exception $e) {
                        // Nothing to do if no secondary provider was found
                    }
                }
            }

            // Handle the primary provider
            // Define the filter (if any)
            try {

                // Get the secondary data filter, if any
                $filter = $this->getEmptyFilter();
                if (!empty($this->data['tx_displaycontroller_datafilter'])) {
                    $filter = $this->defineAdvancedFilter('primary');
                }
            } catch (Exception $e) {
                // Issue warning (error?) if a problem occurred with the filter
                if ($this->debug) {
                    echo 'The primary filter threw the following exception: ' . $e->getMessage();
                }
            }

            // Get the primary data provider
            try {
                $primaryProviderData = $this->getAdvancedComponent('provider', 1);
                if ($this->passStructure[$this->providerGroupUid]) {
                    try {
                        $primaryProvider = $this->getDataProvider($primaryProviderData, isset($secondaryProvider) ? $secondaryProvider : null);

                        $primaryProvider->setDataFilter($filter);
                        // If the secondary provider exists and the option was chosen
                        // to display everything in the primary provider, no matter what
                        // the result from the secondary provider, make sure to set
                        // the empty data structure flag to false, otherwise nothing will display
                        if (isset($secondaryProvider) && !empty($this->data['tx_displaycontroller_emptyprovider2'])) {
                            $primaryProvider->setEmptyDataStructureFlag(FALSE);
                        }
                    } // Something happened, skip passing the structure to the Data Consumer
                    catch (Exception $e) {
                        $this->passStructure[$this->providerGroupUid] = FALSE;
                        if ($this->debug) {
                            echo 'Primary provider set passStructure to false with the following exception: ' . $e->getMessage();
                        }
                    }
                }

                // Get the data consumer
                try {
                    if (!isset($consumerData)) {
                        // Get the consumer's information
                        $consumerData = $this->getComponentData('consumer');
                    }
                    try {

                        if (!isset($this->consumer)) {
                            // Get the corresponding Data Consumer component
                            $this->consumer = Tesseract::getComponent(
                                'dataconsumer',
                                $consumerData['tablenames'],
                                array('table' => $consumerData['tablenames'], 'uid' => $consumerData['uid_foreign']),
                                $this
                            );
                            $typoscriptConfiguration = isset($GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->consumer->getTypoScriptKey()]) ? $GLOBALS['TSFE']->tmpl->setup['plugin.'][$this->consumer->getTypoScriptKey()] : array();
                            $this->consumer->setTypoScript($typoscriptConfiguration);
                            $this->consumer->setDataFilter($filter);
                        }
                        // If the structure shoud be passed to the consumer, do it now and get the rendered content
                        if ($this->passStructure[$this->providerGroupUid]) {
                            // Check if Data Provider can provide the right structure for the Data Consumer
                            if ($primaryProvider->providesDataStructure($this->consumer->getAcceptedDataStructure())) {
                                // Get the data structure and pass it to the consumer
                                $structure = $primaryProvider->getDataStructure();
                                // Check if there's a redirection configuration
                                $this->handleRedirection($structure);
                                // Pass the data structure to the consumer
                                $this->consumer->setDataStructure($structure);
                            } else {
                                // TODO: Issue error if data structures are not compatible between provider and consumer
                            }
                        }
                        // If no structure should be passed (see defineFilter()),
                        // don't pass structure :-), but still do the rendering
                        // (this gives the opportunity to the consumer to render its own error content, for example)
                        // This is achieved by not calling startProcess(), but just getResult()
                        else {
                            $content = $this->consumer->getResult();
                        }
                    } catch (Exception $e) {
                        if ($this->debug) {
                            echo 'Could not get the data consumer. The following exception was returned: ' . $e->getMessage();
                        }
                    }
                } catch (Exception $e) {
                    if ($this->debug) {
                        echo 'An error occurred querying the database for the data consumer.';
                    }
                }
            } catch (Exception $e) {
                if ($this->debug) {
                    echo 'An error occurred querying the database for the primary data provider.';
                }
            }

        } // endforeach

        // Start the processing and get the rendered data
        $this->consumer->startProcess();
        $content = $this->consumer->getResult();

        // If debugging to output is active, prepend content with debugging messages
        if ($this->debugToOutput && isset($GLOBALS['BE_USER'])) {
            /** @var $debugger Debugger */
            $debugger = NULL;
            // If a custom debugging class is declared, get an instance of it
            if (!empty($this->extensionConfiguration['debugger'])) {
                $debugger = GeneralUtility::makeInstance(
                    $this->extensionConfiguration['debugger'],
                    $GLOBALS['TSFE']->getPageRenderer()
                );
            }
            // If no custom debugger class is defined or if it was not of the right type,
            // instantiate the default class
            if ($debugger === NULL || !($debugger instanceof Debugger)) {
                $debugger = GeneralUtility::makeInstance(
                    'Tesseract\Displaycontroller\Utility\Debugger',
                    $GLOBALS['TSFE']->getPageRenderer()
                );
            }
            $content = $debugger->render($this->messageQueue) . $content;
        }
        return $content;
    }

    /**
     * This method gets a filter structure from a referenced Data Filter
     *
     * @param    string $type : type of filter, either primary (default) or secondary
     * @return    array    A filter structure
     */
    protected function defineAdvancedFilter($type = 'primary')
    {
        $filter = array();
        // Define rank based on call parameter
        $rank = 1;
        $checkField = 'tx_displaycontroller_emptyfilter';
        if ($type == 'secondary') {
            $rank = 2;
            $checkField = 'tx_displaycontroller_emptyfilter2';
        }
        // Get the data filter
        try {
            // Get the filter's information
            $filterData = $this->getAdvancedComponent('filter', $rank);
            // Get the corresponding Data Filter component
            /** @var $datafilter tx_tesseract_datafilter */
            $datafilter = Tesseract::getComponent(
                'datafilter',
                $filterData['tablenames'],
                array('table' => $filterData['tablenames'], 'uid' => $filterData['uid_foreign']),
                $this
            );

            // Initialise the filter
            $filter = $this->initFilter($filterData['uid_foreign']);
            // Pass the cached filter to the DataFilter
            $datafilter->setFilter($filter);
            try {
                $filter = $datafilter->getFilterStructure();

                // Store the filter in session
                $cacheKey = $this->prefixId . '_filterCache_' . $filterData['uid_foreign'] . '_providergroup_' . $this->data['uid'] . '_' . $GLOBALS['TSFE']->id;

                $GLOBALS['TSFE']->fe_user->setKey('ses', $cacheKey, $filter);
                // Here handle case where the "filters" part of the filter is empty
                // If the display nothing flag has been set, we must somehow stop the process
                // The Data Provider should not even be called at all
                // and the Data Consumer should receive an empty (special?) structure
                if (count($filter['filters']) == 0 && empty($this->data[$checkField])) {
                    $this->passStructure[$this->providerGroupUid] = FALSE;
                }
            } catch (Exception $e) {
                echo 'Error getting filter: ' . $e->getMessage();
            }
        } catch (Exception $e) {
            throw new Exception('No data filter found');
        }
        return $filter;
    }

    /**
     * This method is used to return a clean, empty filter
     *
     * @return    array    Empty filter structure
     */
    protected function getEmptyFilter()
    {
        return array('filters' => array());
    }

    /**
     * This method is used to initialise the filter
     * This can be either an empty array or some structure already stored in cache
     *
     * @param    mixed $key : a string or a number that identifies a given filter (for example, the uid of a DataFilter record)
     * @return    array    A filter structure or an empty array
     */
    protected function initFilter($key = '')
    {
        $filter = array();
        $clearCache = isset($this->piVars['clear_cache']) ? $this->piVars['clear_cache'] : GeneralUtility::_GP('clear_cache');
        // If cache is not cleared, retrieve cached filter
        if (empty($clearCache)) {
            if (empty($key)) {
                $key = 'default';
            }
            $cacheKey = $this->prefixId . '_filterCache_' . $key . '_providergroup_' . $this->data['uid'] . '_' . $GLOBALS['TSFE']->id;
            $cache = $GLOBALS['TSFE']->fe_user->getKey('ses', $cacheKey);
            if (isset($cache)) {
                $filter = $cache;
            }
        }
        // Declare hook for extending the initialisation of the filter
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['extendInitFilter'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$this->extKey]['extendInitFilter'] as $className) {
                $hookObject = GeneralUtility::getUserObj($className);
                $filter = $hookObject->extendInitFilter($filter, $this);
            }
        }
        return $filter;
    }

    /**
     * This method checks whether a redirection is defined
     * If yes and if the conditions match, it performs the redirection
     *
     * @param    array $structure : a SDS
     * @return    void
     */
    protected function handleRedirection($structure)
    {
        if (isset($this->conf['redirect.']) && !empty($this->conf['redirect.']['enable'])) {
            // Initialisations
            $redirectConfiguration = $this->conf['redirect.'];
            // Load general SDS information into registers
            $GLOBALS['TSFE']->register['sds.totalCount'] = $structure['totalCount'];
            $GLOBALS['TSFE']->register['sds.count'] = $structure['count'];
            // Create a local cObject for handling the redirect configuration
            $localCObj = GeneralUtility::makeInstance('tslib_cObj');
            // If there's at least one record, load it into the cObject
            if ($structure['count'] > 0) {
                $localCObj->start($structure['records'][0]);
            }

            // First interpret the enable property
            $enable = FALSE;
            if (!empty($redirectConfiguration['enable'])) {
                if (isset($this->conf['redirect.']['enable.'])) {
                    $enable = $this->cObj->stdWrap($this->conf['redirect.']['enable'], $this->conf['redirect.']['enable.']);
                } else {
                    $enable = $this->conf['redirect.']['enable'];
                }
            }

            // If the redirection is indeed enabled, continue
            if ($enable) {
                // Get the result of the condition
                $condition = FALSE;
                if (isset($redirectConfiguration['condition.'])) {
                    $condition = $localCObj->checkIf($redirectConfiguration['condition.']);
                }
                // If the condition was true, calculate the URL
                if ($condition) {
                    $url = '';
                    if (isset($redirectConfiguration['url.'])) {
                        $redirectConfiguration['url.']['returnLast'] = 'url';
                        $url = $localCObj->typoLink('', $redirectConfiguration['url.']);
                    }
                    header('Location: ' . GeneralUtility::locationHeaderUrl($url));
                }
            }
        }
    }

    /**
     * This method is used to retrieve any of the advanced components related to the controller
     * An exception is thrown if none is found
     *
     * @param    string $component : type of component (provider, consumer, filter)
     * @param    integer $rank : level of the component (1 = primary, 2 = secondary)
     * @return    array    Database record from the MM-table linking the controller to its components
     */
    protected function getAdvancedComponent($component, $rank = 1)
    {
        $componentData = array();
        $hasComponent = FALSE;
        $whereClause = "component = '" . $component . "' AND rank = '" . $rank . "'";
        // If the content element has been localized, check for component
        // as related to localized uid
        if (!empty($this->cObj->data['_LOCALIZED_UID'])) {
            $where = $whereClause . " AND uid_local = '" . $this->cObj->data['_LOCALIZED_UID'] . "'";
            $res = $this->getDatabaseConnection()->exec_SELECTquery('*', 'tx_displaycontrolleradvanced_components_mm', $where);
            if ($res && $this->getDatabaseConnection()->sql_num_rows($res) > 0) {
                $componentData = $this->getDatabaseConnection()->sql_fetch_assoc($res);
                $hasComponent = TRUE;
            }
        }
        // If no localized relation exists, check for component as related
        // to original uid
        if (!$hasComponent) {
            $where = $whereClause . " AND uid_local = '" . $this->data['uid'] . "'";
            $res = $this->getDatabaseConnection()->exec_SELECTquery('*', 'tx_displaycontrolleradvanced_components_mm', $where);
            $request = $this->getDatabaseConnection()->SELECTquery('*', 'tx_displaycontrolleradvanced_components_mm', $where);
            if ($res && $this->getDatabaseConnection()->sql_num_rows($res) > 0) {
                $componentData = $this->getDatabaseConnection()->sql_fetch_assoc($res);
                $hasComponent = TRUE;
            }
        }

        if (!$hasComponent) {
            $message = 'No component of type ' . $component . ' and level ' . $rank . ' found';
            throw new Exception($message, 1265577739);
        }
        return $componentData;
    }

    /**
     * This method is used to retrieve any of the components related to the controller
     * An exception is thrown if none is found
     *
     * @param    string $component : type of component (provider, consumer, filter)
     * @param    integer $rank : level of the component (1 = primary, 2 = secondary)
     * @return    array    Database record from the MM-table linking the controller to its components
     */
    protected function getComponentData($component, $rank = 1)
    {
        $componentData = array();
        $hasComponent = FALSE;
        $whereClause = "component = '" . $component . "' AND rank = '" . $rank . "'";
        // If the content element has been localized, check for component
        // as related to localized uid
        if (!empty($this->cObj->data['_LOCALIZED_UID'])) {
            $where = $whereClause . " AND uid_local = '" . $this->cObj->data['_LOCALIZED_UID'] . "'";
            $res = $this->getDatabaseConnection()->exec_SELECTquery('*', 'tx_displaycontroller_components_mm', $where);
            if ($res && $this->getDatabaseConnection()->sql_num_rows($res) > 0) {
                $componentData = $this->getDatabaseConnection()->sql_fetch_assoc($res);
                $hasComponent = TRUE;
            }
        }
        // If no localized relation exists, check for component as related
        // to original uid
        if (!$hasComponent) {
            $where = $whereClause . " AND uid_local = '" . $this->cObj->data['uid'] . "'";
            $res = $this->getDatabaseConnection()->exec_SELECTquery('*', 'tx_displaycontroller_components_mm', $where);
            if ($res && $this->getDatabaseConnection()->sql_num_rows($res) > 0) {
                $componentData = $this->getDatabaseConnection()->sql_fetch_assoc($res);
                $hasComponent = true;
            }
        }
        if (!$hasComponent) {
            $message = 'No component of type ' . $component . ' and level ' . $rank . ' found';
            throw new Exception($message, 1265577739);
        }
        return $componentData;
    }

    /**
     * This method gets the data provider group
     *
     * @param    int $uid : the uid of the current tt_content record
     * @return    array    array containing provider groups
     */
    public function getDataProviderGroups($uid)
    {
        $whereClause = 'content = ' . $uid . ' ';
        $whereClause .= $GLOBALS['TSFE']->sys_page->enableFields('tx_displaycontrolleradvanced_providergroup');
        $providerGroups = $this->getDatabaseConnection()->exec_SELECTgetRows('*', 'tx_displaycontrolleradvanced_providergroup', $whereClause);

        return $providerGroups;
    }

    /**
     * Gets a data provider.
     *
     * If a secondary provider is defined, it is fed into the first one
     *
     * @param array $providerInfo Information about a provider related to the controller
     * @param DataProviderInterface $secondaryProvider An instance of an object with a DataProvider interface
     * @return DataProviderInterface Object with a DataProvider interface
     */
    public function getDataProvider($providerInfo, DataProviderInterface $secondaryProvider = null)
    {
        // Get the related data providers
        $numProviders = count($providerInfo);
        if ($numProviders == 0) {
            // No provider, throw exception
            throw new Exception('No provider was defined', 1269414211);
        } else {
            // Get the Data Provider Component
            /** @var $provider DataProviderInterface */
            $provider = Tesseract::getComponent(
                'dataprovider',
                $providerInfo['tablenames'],
                array('table' => $providerInfo['tablenames'], 'uid' => $providerInfo['uid_foreign']),
                $this
            );
            // If a secondary provider is defined and the types are compatible,
            // load it into the newly defined provider
            if (isset($secondaryProvider)) {
                if ($secondaryProvider->providesDataStructure($provider->getAcceptedDataStructure())) {
                    $inputDataStructure = $secondaryProvider->getDataStructure();
                    // If the secondary provider returned no list of items, force primary provider to return an empty structure
                    if ($inputDataStructure['count'] == 0) {
                        $provider->setEmptyDataStructureFlag(true);

                        // Otherwise pass structure to primary provider
                    } else {
                        $provider->setDataStructure($inputDataStructure);
                    }
                } // Providers are not compatible, throw exception
                else {
                    throw new Exception('Incompatible structures between primary and secondary providers', 1269414231);
                }
            }
            return $provider;
        }
    }

    /**
     * Returns a pointer to the database.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

}
