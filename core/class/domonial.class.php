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
		$return['log'] = 'domonialcmd';
		$return['state'] = 'nok';
		$pid_file = '/tmp/domonial.pid';
		if (file_exists($pid_file)) {
			if (posix_getsid(trim(file_get_contents($pid_file)))) {
				$return['state'] = 'ok';
			} else {
				shell_exec('sudo rm -rf ' . $pid_file . ' 2>&1 > /dev/null;rm -rf ' . $pid_file . ' 2>&1 > /dev/null;');
			}
		}
		$return['launchable'] = 'ok';
		return $return;
	}

	public static function deamon_start($_debug = false) {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		log::remove('domonialcmd');
		

		$domonial_path = realpath(dirname(__FILE__) . '/../../ressources/domonialcmd');

		//Initialisation du fichier protocol
		/*$protocol = file_get_contents($rfxcom_path . '/protocol_tmpl.xml');
		if (file_exists($rfxcom_path . '/protocol.xml')) {
			unlink($rfxcom_path . '/protocol.xml');
		}
		if (file_exists($rfxcom_path . '/protocol.xml')) {
			throw new Exception(__('Impossible de supprimer le fichier :', __FILE__) . $rfxcom_path . '/protocol.xml', 'cantWriteProtcolFile');
		}
		$protocol_replace = array();
		for ($i = 0; $i < 25; $i++) {
			$protocol_replace['#state' . $i . '#'] = config::byKey('protocol::' . $i, 'rfxcom', 0);
			if ($protocol_replace['#state' . $i . '#'] === '') {
				$protocol_replace['#state' . $i . '#'] = 0;
			}
		}
		file_put_contents($rfxcom_path . '/protocol.xml', str_replace(array_keys($protocol_replace), array_values($protocol_replace), $protocol));
		if (!file_exists($rfxcom_path . '/protocol.xml')) {
			throw new Exception(__('Impossible d\'écrire le fichier :', __FILE__) . $rfxcom_path . '/protocol.xml', 'cantWriteProtcolFile');
		}
		*/
		if (file_exists('/tmp/config_domonial.xml')) {
			unlink('/tmp/config_domonial.xml');
		}
		$enable_logging = (config::byKey('enableLogging', 'domonial', 0) == 1) ? 'yes' : 'no';
		if (file_exists(log::getPathToLog('domonialcmd') . '.message')) {
			unlink(log::getPathToLog('domonialcmd') . '.message');
		}
		if (!file_exists(log::getPathToLog('domonialcmd') . '.message')) {
			touch(log::getPathToLog('domonialcmd') . '.message');
		}
		$replace_config = array(
			'#sockethost#' => config::byKey('sockethost', 'domonial'),
            '#socketport#' => config::byKey('socketport', 'domonial', 55003),
            '#log_path#' => log::getPathToLog('domonialcmd'),
            '#enable_log#' => $enable_logging,
            '#pid_path#' => '/tmp/domonial.pid',
		    '#trigger_url#' => network::getNetworkAccess('internal', 'proto:127.0.0.1:port:comp') . '/plugins/domonial/core/php/jeedomonial.php',
			'#apikey#' => config::byKey('api'),
			'#BanCanauxDomonial#' => config::byKey('bandomonialcanaux', 'domonial')
		);

		$config = template_replace($replace_config, file_get_contents($domonial_path . '/config_tmpl.xml'));
		file_put_contents('/tmp/config_domonial.xml', $config);
		chmod('/tmp/config_domonial.xml', 0777);
		if (!file_exists('/tmp/config_domonial.xml')) {
			throw new Exception(__('Impossible de créer : ', __FILE__) . '/tmp/config_domonial.xml');
		}
		$cmd = '/usr/bin/python ' . $domonial_path . '/domonialcmd.py -l -o /tmp/config_domonial.xml';
		if (log::getLogLevel('boxio')=='100') {
			$cmd .= ' -D';
		}
		log::add('domonialcmd', 'info', 'Lancement démon domonialcmd : ' . $cmd);
		$result = exec($cmd . ' >> ' . log::getPathToLog('domonialcmd') . ' 2>&1 &');
		if (strpos(strtolower($result), 'error') !== false || strpos(strtolower($result), 'traceback') !== false) {
			log::add('domonialcmd', 'error', $result);
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
			log::add('domonialcmd', 'error', 'Impossible de lancer le démon domonial, vérifiez le log domonialcmd', 'unableStartDeamon');
			return false;
		}
		message::removeAll('domonial', 'unableStartDeamon');
		log::add('domonialcmd', 'info', 'Démon domonial lancé');
		return true;
	}

	public static function deamon_stop() {
		$pid_file = '/tmp/domonial.pid';
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
		$params = preg_split('/[#]/', $trame);
		$local = preg_split('/[\*]/', $params[1]);
		$site = substr($local[0],0,8);
		$action = substr($local[0],9,2);
		$ret_trame["SDS"] = $params[0];
		$ret_trame["Site"] = $site;
		
		if ($action == 63) {
			$ret_trame["Action"] = "Activée";
		} elseif ($action == 64) {
			$ret_trame["Action"] = "Désactivée";
		} else {
			$ret_trame["Action"] = $action;
		}
		$ret_trame["Autre1"] = $local[1];
		$ret_trame["Autre2"] = trim($params[2],"*");
		$ret_trame["Autre3"] = trim($params[3],"*");
		$ret_trame["Autre4"] = trim($params[4],"*");
		$ret_trame["Autre5"] = trim($params[5],"*");
		$ret_trame["CRC"] = trim($params[6],"*");

		return $ret_trame;
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
