#!/usr/bin/python3
# coding=UTF-8

# ------------------------------------------------------------------------------
#	
#	domoniald.PY
#	
#	Copyright (C) 2016-2018 Aurelien Pages, apages2@free.fr
#	
#	This program is free software: you can redistribute it and/or modify
#	it under the terms of the GNU General Public License as published by
#	the Free Software Foundation, either version 3 of the License, or
#	(at your option) any later version.
#	
#	This program is distributed in the hope that it will be useful,
#	but WITHOUT ANY WARRANTY; without even the implied warranty of
#	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#	GNU General Public License for more details.
#	
#	You should have received a copy of the GNU General Public License
#	along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#	$Date: 2016-05-18 22:48:10 +0200$
#
# ------------------------------------------------------------------------------

__author__ = "Aurélien PAGES"
__copyright__ = "Copyright 2016-2018, Aurelien PAGES"
__license__ = "GPL"
__version__ = "0.2"
__maintainer__ = "Aurélien PAGES"
__email__ = "apages2@free.fr"
__status__ = "Stable"
__date__ = "$Date: 2023-08-29 22:48:10 +0200$"

# Default modules
import shared
import logging
import string
import sys
import os

import re
import signal
import traceback
import argparse
from os.path import join

# JEEDOM modules

try:
	from jeedom.jeedom import *
except ImportError:
	print("Error: importing module jeedom.jeedom")
	sys.exit(1)

# ----------------------------------------------------------------------------

def shutdown():
	# clean up PID file after us
	logging.debug("Shutdown")

	try:
		os.remove(_pidfile)
	except:
		pass
	try:
		jeedom_socket.close()
	except:
		pass

	logging.debug("Exit 0")
	sys.stdout.flush()
	os._exit(0)

def handler(signum=None, frame=None):
	logging.debug("Signal %i caught, exiting..." % int(signum))
	shutdown()

def listen(address, port=55003):
	"""
	Listen to interne Socket and process data, exit with CTRL+C
	"""
	message = None
	byte = None
	buffer = None
	message = b""
	ackok = 1
	limit = 1
	logging.debug("Start listening...")
	serversocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	serversocket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	
	try:
		serversocket.bind((address, port))
		serversocket.listen(5)
	except Exception as err:
		logging.error("Error starting socket server.")
		logging.error("Error: %s" % str(err))
		shutdown()
	
	while 1 :
		clientsocket, clienthost = serversocket.accept()
		clientsocket.settimeout(10)
		logging.debug("New Connection from %s" % str(clienthost))
		try :
			message = message + clientsocket.recv(1)
			check1 = re.search(b"^S", message)
			if message:
				if check1 is None:
					logging.debug("Erreur de Trame. Fermeture de la connexion client : %s" % str(clienthost))
					clientsocket.close()
					message = None
					byte = None
					buffer = None
					message = b""
				if check1:
					check = re.search(b"SDS(\d{4}#.*\!\w{4})", message )
					while check is None:
						message = message + clientsocket.recv(1)
						check = re.search(b"SDS(\d{4}#.*\!\w{4})", message )
					if check:
						logging.debug("%s" %str(message))
						action=message[17:19]
						logging.debug("Code %s" %str(action.decode('utf-8')))
						bans = _banid.split (";")
						for ban in bans:
							checkacq = re.search(str(ban), action.decode('utf-8'))
							if checkacq:
								ackok = 0								
							else:
								if ackok == 0:
									ackok = 0
								else :
									ackok = 1
								
						if ackok == 0:
							logging.debug("Canal banni, pas d'acquittement")	
						if ackok == 1:
							SDS=message[3:7]
							SDSfinal=int(message[6:7])
							SDShigh=int(message[3:5])
							SDSlow=int(message[5:7])
							incr=int(SDS)
							if SDSfinal == 0:
								incr+=1
								SDSlow+=1
							if SDShigh == 30:
								Reponselow = 'E2'
							if SDShigh == 31:
								Reponselow = 'E3'
							if SDShigh == 32:
								Reponselow = 'E0'
							if SDShigh == 33:
								Reponselow = 'E1'
							if SDShigh == 34:
								Reponselow = 'DE'
							if SDShigh == 35:
								Reponselow = 'DF'
							if SDShigh == 36:
								Reponselow = 'DC'
							if SDShigh == 37:
								Reponselow = 'DD'
							if SDShigh == 38:
								Reponselow = 'EA'
							if SDShigh == 39:
								Reponselow = 'EB'
							if SDSlow == 1:
								Reponsehigh = 'D4'
							if SDSlow == 11:
								Reponsehigh = 'D3'
							if SDSlow == 21:
								Reponsehigh = 'D6'
							if SDSlow == 31:
								Reponsehigh = 'D5'
							if SDSlow == 41:
								Reponsehigh = 'D0'
							if SDSlow == 51:
								Reponsehigh = 'CF'
							if SDSlow == 61:
								Reponsehigh = 'D2'
							if SDSlow == 71:
								Reponsehigh = 'D1'
							if SDSlow == 81:
								Reponsehigh = 'CC'
							if SDSlow == 91:
								Reponsehigh = 'CB'
							
							ack=Reponsehigh+Reponselow
							reponse='SDS%d!%s\n' % (incr, ack)
							reponseb=bytes(reponse, 'utf-8')
							clientsocket.send(reponseb)
							logging.debug("Acquittement : "+str(reponse))
							
							
							
						shared.JEEDOM_COM.send_change_immediate({'trame' : message.decode('utf-8')});
						logging.debug("Fin de Transmission. Fermeture connection client : %s" % str(clienthost))
						clientsocket.close()
						message = None
						byte = None
						buffer = None
						message = b""
						ackok = 1
						limit = 1
		
		except socket.timeout:
			logging.debug("Timeout for %s" % str(clienthost))
			clientsocket.close()
			message = None
			byte = None
			buffer = None
			message = b""
			ackok = 1
			limit = 1
			pass		

_log_level = "error"
_socket_port = 55003
_socket_host = '127.0.0.1'
_pidfile = ''
_banid = ''
_apikey = ''
_callback = 'http://127.0.0.1:80/plugins/domonial/core/php/jeedomonial.php'
_cycle = 0.3


parser = argparse.ArgumentParser(description='Domonial Daemon for Jeedom plugin')

parser.add_argument("--sockethost", type=str, help="ip address for server")
parser.add_argument("--socketport", type=str, help="Socketport for server")
parser.add_argument("--loglevel", type=str, help="Log Level for the daemon")
parser.add_argument("--callback", type=str, help="Callback URL")
parser.add_argument("--apikey", type=str, help="Apikey")
parser.add_argument("--cycle", help="Cycle to send event", type=str)
parser.add_argument("--pid", type=str, help="Pid file")
parser.add_argument("--ban", type=str, help="Canaux Bannis")
parser.add_argument("--version", help="Print domonial version information")
args = parser.parse_args()

# ----------------------------------------------------------
#PARAMETER

if args.sockethost:
	_socket_host = args.sockethost
if args.socketport:
	_socket_port = int(args.socketport)
if args.loglevel:
	_log_level = args.loglevel
if args.callback:
	_callback = args.callback
if args.apikey:
	_apikey = args.apikey
if args.ban:
	_banid = args.ban
if args.pid:
	_pidfile = args.pid
if args.cycle:
	_cycle = float(args.cycle)

jeedom_utils.set_log_level(_log_level)

logging.info('Start Domoniald')
logging.info('Log level : '+str(_log_level))
logging.info('Socket host : '+str(_socket_host))
logging.info('Socket port : '+str(_socket_port))
logging.info('PID file : '+str(_pidfile))
logging.info('Apikey : '+str(_apikey))
logging.info('Callback : '+str(_callback))
logging.info('BanId : '+str(_banid))
logging.info('Cycle : '+str(_cycle))

# ----------------------------------------------------------
# VERSION PRINT
if args.version:
	print_version()

logging.debug("Python version: %s.%s.%s" % sys.version_info[:3])
logging.debug("DOMONIALD Version: " + __version__)
logging.debug(__date__.replace('$', ''))

signal.signal(signal.SIGINT, handler)
signal.signal(signal.SIGTERM, handler)

try:
	jeedom_utils.write_pid(str(_pidfile))
	shared.JEEDOM_COM = jeedom_com(apikey=_apikey, url=_callback, cycle=_cycle)
	if not shared.JEEDOM_COM.test():
		logging.error('Network communication issues. Please fixe your Jeedom network configuration.')
		shutdown()
	# jeedom_socket = jeedom_socket(port=_socket_port,address=_socket_host)
	listen(_socket_host,int(_socket_port))

except Exception as e:
	logging.error('Fatal error : '+str(e))
	logging.debug(traceback.format_exc())
	shutdown()




# ------------------------------------------------------------------------------
# END
# ------------------------------------------------------------------------------

