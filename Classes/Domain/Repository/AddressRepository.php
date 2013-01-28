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
class Tx_WebModelImport_Domain_Repository_AddressRepository extends Tx_Extbase_Persistence_Repository {

	public function getZipcodeTypes () {
        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(true);
 
        $sql = 
        		'select distinct SUBSTRING(zipcode,1,1) as number 
					from tx_webmodelimport_domain_model_address
				 where hidden=0 and deleted=0
				 order by number';

        $query->statement($sql);
        $result = $query->execute();
        return $result;		
	}

	public function findByZipcodeType($zipcodeType) {
        $query = $this->createQuery();
 
        $sql = 
        		'select * from 
					tx_webmodelimport_domain_model_address
				 where hidden=0 and deleted=0
				 and  SUBSTRING(zipcode,1,1) = "'.$zipcodeType.'"';

        $query->statement($sql);
        $result = $query->execute();
        return $result;			
	}
	
}
?>