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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class domonial extends eqLogic {
    /*     * *************************Attributs****************************** */
	
	
    /*     * ***********************Methode static*************************** */
	
	public static function deamon_info() {
		$return = array();
		$return['log'] = 'domoniald';
		$return['state'] = 'nok';
		$pid_file = jeedom::getTmpFolder('domonial') . '/domoniald.pid';
		if (file_exists($pid_file)) {
			$pid = trim(file_get_contents($pid_file));
			if (is_numeric($pid) && posix_getsid($pid)) {
				$return['state'] = 'ok';
			} else {
				shell_exec(system::getCmdSudo() . 'rm -rf ' . $pid_file . ' 2>&1 > /dev/null;rm -rf ' . $pid_file . ' 2>&1 > /dev/null;');
			}
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start($_debug = false) {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		$domonial_path = realpath(dirname(__FILE__) . '/../../ressources/domoniald');
		
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		log::remove('domoniald');
		
		$cmd = '/usr/bin/python3 ' . $domonial_path . '/domoniald.py';
		$cmd .= ' --loglevel ' . log::convertLogLevel(log::getLogLevel('domonial'));
		$cmd .= ' --sockethost ' . config::byKey('sockethost', 'domonial');
		$cmd .= ' --socketport ' . config::byKey('socketport', 'domonial');
		$cmd .= ' --callback ' . network::getNetworkAccess('internal', 'proto:127.0.0.1:port:comp') . '/plugins/domonial/core/php/jeedomonial.php';
		$cmd .= ' --apikey ' . jeedom::getApiKey('domonial');
		$cmd .= ' --pid ' . jeedom::getTmpFolder('domonial') . '/domoniald.pid';
		$cmd .= ' --ban "' . config::byKey('bandomonialcanaux', 'domonial').'"';
		$cmd .= ' --cycle ' . config::byKey('cycle', 'domonial');
		
		log::add('domoniald', 'info', 'Lancement démon domoniald : ' . $cmd);
		$result = exec($cmd . ' >> ' . log::getPathToLog('domoniald') . ' 2>&1 &');
		if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
			log::add('domoniald', 'error', $result);
			return false;
		}

		$i = 0;
		while ($i < 30) {
			$deamon_info = self::deamon_info();
			if ($deamon_info['state'] == 'ok') {
				break;
			}
			sleep(1);
			$i++;
		}
		if ($i >= 30) {
			log::add('domoniald', 'error', 'Impossible de lancer le démon domonial, vérifiez le log domoniald', 'unableStartDeamon');
			return false;
		}
		message::removeAll('domoniald', 'unableStartDeamon');
		sleep(2);
		log::add('domoniald', 'info', 'Démon domonial lancé');
		return true;
	}

	public static function deamon_stop() {
		$pid_file = jeedom::getTmpFolder('domonizl') . '/domoniald.pid';;
		if (file_exists($pid_file)) {
			$pid = intval(trim(file_get_contents($pid_file)));
			system::kill($pid);
		}
		system::fuserk(config::byKey('socketport', 'domonial', 55003));
	}
	
/*	public static function cronDaily() {
        if (config::byKey('allowStartDeamon', 'domonial', 1) == 1 {
			self::deamon_stop();
			sleep 10;
			self::deamon_start();
		}
    }*/

	public static function decrypt_trame($trame) {
	
		/*
		 // FONCTION : DECRYPTAGE D'UNE TRAME AU FORMAT LISIBLE
		// PARAMS : $trame=string
		// RETURN : array(
				"trame" => string,
				"SDS" => string,
				"Site" => string,
				"Action" => string,
				"Autre1" => string,
				"Autre2" => string,
				"Autre3" => string,
				"Autre4" => string,
				"Autre5" => string,
				"CRC" => string,
		*/		
		
		$ret_trame = array(
					"trame" => $trame,
					"SDS" => 'UNKNOWN',
					"Site" => 'UNKNOWN',
					"Action" => 'UNKNOWN',
					"Autre1" => 'UNKNOWN',
					"Autre2" => 'UNKNOWN',
					"Autre3" => 'UNKNOWN',
					"Autre4" => 'UNKNOWN',
					"Autre5" => 'UNKNOWN',
					"CRC" => 'UNKNOWN',
					"date" => date("Y-m-d H:i:s", time())
		);
		
		$find_trame = false;
		$trame = preg_split('/!/', $trame);
		$crc = $trame[1];
		$params = preg_split('/[#]/', $trame[0]);
		$local = preg_split('/[\*]/', $params[1]);
		$site = substr($local[0],0,8);
		$action = substr($local[0],9,2);
	
		$ret_trame["SDS"] = $params[0];
		$ret_trame["Site"] = $site;
		
		$tailleparams = count($params);
		
		for ($i=2; $i<$tailleparams; $i++) {
			$j=$i-1;
			$ret_trame["Autre".$j] = trim($params[$i],"*");
		}
		
		$result=domonial::checkaction($action);
		if ($result) {
			$ret_trame["Action"] = $result;
		} else {
			$ret_trame["Action"] = $action;
		}
		
		$ret_trame["Info"] = $local[1];
		$ret_trame["CRC"] = $crc;

		return $ret_trame;
	}
	
	public static function checkaction($action) {
	
		$sqld = "SELECT * FROM `config` WHERE `plugin` = 'domonial' and value='".$action."'";
		$result =  DB::Prepare($sqld, array(), DB::FETCH_TYPE_ALL);
		$return = preg_split('/::/', $result[0]['key']);
		$typeaction =  substr($return[1],-1);
		if ($typeaction=='A') {
			$etat='Activation';
		} else {
			$etat='Désactivation';
		}
		$return = $etat.' '.substr($return[1],0,-1);
		return $return;
	}

/*     * *********************Methode d'instance************************* */

	public function preInsert() {
		if ($this->getLogicalId() == '') {
			for ($i = 0; $i < 20; $i++) {
				$logicalId = strtoupper(str_pad(dechex(mt_rand()), 8, '0', STR_PAD_LEFT));
				$result = eqLogic::byLogicalId($logicalId, 'domonial');
				if (!is_object($result)) {
					$this->setLogicalId($logicalId);
					break;
				}
			}
		}
	}

/*     * **********************Getteur Setteur*************************** */
} 	

class domonialCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    /*     * *********************Methode d'instance************************* */

/*	public function execute($_options = null) {
    }
*/
    /*     * **********************Getteur Setteur*************************** */

}
?>
