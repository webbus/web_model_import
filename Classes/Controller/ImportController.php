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
class Tx_WebModelImport_Controller_ImportController extends Tx_Extbase_MVC_Controller_ActionController {


	const FILES_INDEX = 'tx_webmodelimport_web_webmodelimportimport';
	const FILE_INPUT_FIELD = 'import_file';
	
	protected $allowedFileTypes;
	
	protected $csvSeperator;
	protected $csvSkipFirstLine;
	protected $systemEncoding;
	
	protected $selectedModel;
	
	protected $modelConfiguration;
	
	protected $modelConfigurationArray = array(
		'Tx_WebModelImport_Domain_Model_Address' => array(
			'nrOfColumns' => 4	
			)
	);
	/**
	 * @var Tx_WebModelImport_Domain_Repository_AddressRepository
	 */
		
	protected $addressRepository;
	/**
	 * 
	 * Dependency Injection
	 * @param Tx_WebModelImport_Domain_Repository_AddressRepository $addressRepository
	 */
	public function injectAddressRepository(Tx_WebModelImport_Domain_Repository_AddressRepository $addressRepository) {
		$this->addressRepository = $addressRepository;
	}
	
	/**
	 * Initialisierung von Konfigurationen aus dem Typo3 backend Template
	 * @see typo3/sysext/extbase/Classes/MVC/Controller/Tx_Extbase_MVC_Controller_ActionController::initializeAction()
	 */
	
	protected function initializeAction() {
		$this->selectedModel = $this->settings["selectedModel"];
		$this->modelConfiguration = $this->modelConfigurationArray[$this->selectedModel];
		$this->allowedFileTypes = $this->arraytolower(explode(",",$this->settings["allowedFileTypes"]));
		$this->csvSeperator = $this->settings["csvSeperator"];
		$this->csvSkipFirstLine = $this->settings["csvSkipFirstLine"];
		$this->systemEncoding = $this->settings["systemEncoding"];
	}
	
	/**
	 * Übergabe der Settings
	 * @see typo3/sysext/extbase/Classes/MVC/Controller/Tx_Extbase_MVC_Controller_ActionController::initializeView()
	 */
	protected function initializeView() {
		$this->view->assign("settings",$this->settings);
	}
	
	
	/**
	 * Action für den Import. Leitet nur auf View weiter
	 *
	 * @return void
	 */
	public function importAction() {

	}

	/**
	 * Action für den Upload. Leitet auf den Import View weiter sowohl im Fehler- wie auch im Erfolgsfall
	 *
	 * @return void
	 */
	public function uploadAction() {
		
		$models = array();
		$fileName = $_FILES[self::FILES_INDEX]['name'][self::FILE_INPUT_FIELD];
		$tmpFilePath = $_FILES[self::FILES_INDEX]['tmp_name'][self::FILE_INPUT_FIELD];
		
		
		//Keine Datei hochgeladen
		if($fileName == '') {
			$this->flashMessageContainer->add($this->t('tx_webmodelimport_import_error_nofile'),'',  t3lib_Flashmessage::ERROR);
			$this->forward("import");
		} 

		//Keine CSV Datei
		$fileType = strtolower(substr($fileName,strlen($fileName)-3));
		if(!in_array($fileType,$this->allowedFileTypes)) {
			$this->flashMessageContainer->add($this->t('tx_webmodelimport_import_error_wrongfiletype').implode(',',$this->allowedFileTypes),'',  t3lib_Flashmessage::ERROR);
			$this->forward("import");
		} 		
		
		//Datei einlesen
		$rows = file ($tmpFilePath);
		$rowCounter = 1;
 
		switch($fileType) {
			
			case 'csv': 

				foreach ($rows as $row) {
					
					if($this->systemEncoding == 'utf8')	$row = utf8_encode($row);
					
					$errorLineInfo = $this->t('tx_webmodelimport_import_error_lineinfo').$rowCounter.' . '.$this->t('tx_webmodelimport_import_error_canceled');
					
					//Erste Zeile wegen Überschriften überspringen
					//Oder Leerzeile
					if(trim($row) != '' && trim(str_replace($this->csvSeperator,'', $row)) != '' && ($rowCounter > 1 || $this->csvSkipFirstLine == '0')) {
							
						$modelValues = explode($this->csvSeperator, $row);	
	
						if(sizeof($modelValues) == $this->modelConfiguration['nrOfColumns'] ) {
							
							switch ($this->selectedModel) {
								
								case 'Tx_WebModelImport_Domain_Model_Address': 
									
									$model = new Tx_WebModelImport_Domain_Model_Address();
									$model->setName($this->clean($modelValues[0]));
									$model->setStreet($this->clean($modelValues[1]));
									$model->setZipcode($this->clean($modelValues[2]));
									$model->setCity($this->clean($modelValues[3]));
									
									$models[] = $model;
									break;
							}
						}
						
						else {	
							
							$this->flashMessageContainer->add(
								$this->t('tx_webmodelimport_import_error_wrongnrofcolumns').$this->modelConfiguration['nrOfColumns'].'<br/>'.
								$errorLineInfo
								,'',  t3lib_Flashmessage::ERROR);

							$this->flashMessageContainer->add(
								$row
								,'',  t3lib_Flashmessage::ERROR);
								
							$this->forward("import");
						}
					}
					$rowCounter++;
				}

				
				//Speichern
				$saveCounter = 1;
				foreach($models as $model) {
					
					switch ($this->selectedModel) {
								
						case 'Tx_WebModelImport_Domain_Model_Address':

							$this->addressRepository->add($model);
							break;
					}
					
					$saveCounter++;
				}
				$this->flashMessageContainer->add($this->t('tx_webmodelimport_import_success').$saveCounter);
		}
		$this->forward("import");
	}

	/***************************************************Hilfsfunktionen**************************************************************/
	
	
	/**
	 * 
	 * Übersetzen aus language.xml
	 * @param string $key
	 */
	protected function t($key) {
		return Tx_Extbase_Utility_Localization::translate($key, $this->extensionName);
	}
	/**
	 * 
	 * Vorbearbeitung von Eingaben durch den Benutzer
	 * @param $text
	 */
	protected function clean($text) {
		return trim($text);
	}
	
  /**
   * Großschreibung der Arraywerte in Kleinbuchstaben verwandeln  
   * @param $array
   * @param $include_leys
   */	
  function arraytolower($array, $include_leys=false) {
   
    if($include_leys) {
      foreach($array as $key => $value) {
        if(is_array($value))
          $array2[strtolower($key)] = arraytolower($value, $include_leys);
        else
          $array2[strtolower($key)] = strtolower($value);
      }
      $array = $array2;
    }
    else {
      foreach($array as $key => $value) {
        if(is_array($value))
          $array[$key] = arraytolower($value, $include_leys);
        else
          $array[$key] = strtolower($value);  
      }
    }
   
    return $array;
  } 	
	
}
?>