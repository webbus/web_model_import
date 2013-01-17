<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'import',	// Submodule key
		'',						// Position
		array(
			'Import' => 'import, upload',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_import.xml',
		)
	);

}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Import Model');

t3lib_extMgm::addLLrefForTCAdescr('tx_webmodelimport_domain_model_import', 'EXT:web_model_import/Resources/Private/Language/locallang_csh_tx_webmodelimport_domain_model_import.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_webmodelimport_domain_model_import');
$TCA['tx_webmodelimport_domain_model_import'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:web_model_import/Resources/Private/Language/locallang_db.xml:tx_webmodelimport_domain_model_import',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => '',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Import.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_webmodelimport_domain_model_import.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_webmodelimport_domain_model_address', 'EXT:web_model_import/Resources/Private/Language/locallang_csh_tx_webmodelimport_domain_model_address.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_webmodelimport_domain_model_address');
$TCA['tx_webmodelimport_domain_model_address'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:web_model_import/Resources/Private/Language/locallang_db.xml:tx_webmodelimport_domain_model_address',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,street,zipcode,city,',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Address.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_webmodelimport_domain_model_address.gif'
	),
);

?>