<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'))) {
	connection::failed();
	echo 'Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action (jeedomonial)';
	die();
}

if (isset($_GET['test'])) {
	echo 'OK';
	die();
}

if (isset($_GET['trame'])) {


	$trame = str_replace('Y', '*', $_GET['trame']);
	$trame = str_replace('Z', '#', $trame);
	log::add ('domonial','event','Receive to Jeedom : '.$trame);
	
	$tramedecrypt=domonial::decrypt_trame($trame);
	log::add('domonial', 'debug', 'Jeedomonial_Equipement : ' . print_r($tramedecrypt, true));
	
	foreach ($tramedecrypt as $key => $value) {
				if (is_null($value)) {
					$tramedecrypt[$key] = "NULL";
				} else {
					$tramedecrypt[$key] = $value;
				}
	}
	
	
	$domonial = domonial::byLogicalId($tramedecrypt["Site"], 'domonial');
	if (!is_object($domonial)) {
			log::add('domonial', 'debug', 'Jeedomonial_Aucun équipement trouvé pour : ' . $tramedecrypt["Site"] ."\n");
			die();
	}
	else {
		$domonialcmd = $domonial->getCmd('info', 'etat');
					if (isset($domonialcmd)){
						$domonialcmd->event($tramedecrypt["Action"]);
						$domonialcmd->save();
					}
		$domonialcmd = $domonial->getCmd('info', 'trame');
					if (isset($domonialcmd)){			
						$domonialcmd->event($tramedecrypt["trame"]);
						$domonialcmd->save();
					}
	}
}