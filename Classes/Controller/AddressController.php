<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Philipp Buss <buss@typo3-hamburg-berlin.de>, Typo3 Hamburg Berlin.
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package web_model_import
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_WebModelImport_Controller_AddressController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * addressRepository
	 *
	 * @var Tx_WebModelImport_Domain_Repository_AddressRepository
	 */
	protected $addressRepository;

	/**
	 * injectAddressRepository
	 *
	 * @param Tx_WebModelImport_Domain_Repository_AddressRepository $addressRepository
	 * @return void
	 */
	public function injectAddressRepository(Tx_WebModelImport_Domain_Repository_AddressRepository $addressRepository) {
		$this->addressRepository = $addressRepository;
	}

	/**
	 * 
	 * Bei gewähltem zipcodeType wird gefiltert (0...,1...,2...)
	 * ansonsten werden alle Adressen angezeigt
	 * 
	 * @param string $zipcodeType
	 * @dontvalidate $zipcodeType
	 */
	
	public function listAction($zipcodeType = NULL) {
		$zipcodeTypes = $this->addressRepository->getZipcodeTypes();
		$htmlZipcodeTypes = array();
		$htmlZipcodeTypes[] = "";
		foreach($zipcodeTypes as $tmp) 
			$htmlZipcodeTypes[] = array(
				"label" => $tmp["number"].'.....',
				"id" => $tmp["number"]
		   );

		if($zipcodeType) $addresses = $this->addressRepository->findByZipcodeType(str_replace('.','',$zipcodeType));
		else $addresses = $this->addressRepository->findAll();

		$this->view->assign('selectedZipcodeType', $zipcodeType);
		$this->view->assign('addresses', $addresses);
		$this->view->assign('zipcodeTypes', $htmlZipcodeTypes);
	}

	
	public function x($output) {
		echo '<pre>'.print_r($output,1).'</pre>';
		die();
		
	}	
	
	/**
	 * 
	 * Übersetzen aus language.xml
	 * @param string $key
	 */
	protected function t($key) {
		return Tx_Extbase_Utility_Localization::translate($key, $this->extensionName);
	}	
	
	/**
	 * action show
	 *
	 * @param Tx_WebModelImport_Domain_Model_Address $address
	 * @return void
	 */
	public function showAction(Tx_WebModelImport_Domain_Model_Address $address) {
		$this->view->assign('address', $address);
	}

}
?>