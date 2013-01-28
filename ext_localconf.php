<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Address',
	array(
		'Address' => 'list, show',
	),
	// non-cacheable actions
	array(
		'Address' => '',
	)
);

?>