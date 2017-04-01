#!/usr/bin/python
# coding=UTF-8

# ------------------------------------------------------------------------------
#	
#	alarme.PY
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
__status__ = "Development-Beta-1"
__date__ = "$Date: 2016-05-18 22:48:10 +0200$"

# Default modules
import signal
import string
import sys
import os
import logging
import traceback
import xml.dom.minidom as minidom
from optparse import OptionParser
import socket
import select
import re
import time
import Queue

# DOMONIALCMD modules

try:
	from lib.domonial_utils import *
except ImportError:
	print "Error: module lib/domonial_utils not found"
	sys.exit(1)
	
try:
	from lib.domonial_command import *
except ImportError:
	print "Error: module lib/domonial_command not found"
	sys.exit(1)	
	
	
# ----------------------------------------------------------------------------

# ------------------------------------------------------------------------------
# VARIABLE CLASSS
# ------------------------------------------------------------------------------

	
class config_data:
	def __init__(
		self,
		trigger = "",
		trigger_timeout = 10,
		loglevel = "info",
		logfile = "domonialcmd.log",
		program_path = "",
		sockethost = "",
		socketport = "",
		daemon_pidfile = "domonial.pid",
		log_msg = False,
		log_msgfile = "",
		BanCanaux = "", 
		):

		self.trigger_timeout = trigger_timeout
		self.loglevel = loglevel
		self.logfile = logfile
		self.program_path = program_path
		self.sockethost = sockethost
		self.socketport = socketport
		self.daemon_pidfile = daemon_pidfile
		self.log_msg = log_msg
		self.log_msgfile = log_msgfile
		self.trigger = trigger
		self.trigger_timeout = trigger_timeout
		self.BanCanaux = BanCanaux
		
class cmdarg_data:
	def __init__(
		self,
		configfile = "",
		action = "",
		rawcmd = "",
		device = "",
		createpid = False,
		pidfile = "",
		printout_complete = True,
		):

		self.configfile = configfile
		self.action = action
		self.rawcmd = rawcmd
		self.device = device
		self.createpid = createpid
		self.pidfile = pidfile
		self.printout_complete = printout_complete
		
class trigger_data:
	def __init__(
		self,
		data = ""
		):

		self.data = data
		
def shutdown():
	# clean up PID file after us
	logger.debug("Shutdown")

	if cmdarg.createpid:
		logger.debug("Removing PID file " + str(cmdarg.pidfile))
		os.remove(cmdarg.pidfile)

	logger.debug("Exit 0")
	sys.stdout.flush()
	os._exit(0)

# ----------------------------------------------------------------------------

def handler(signum=None, frame=None):
	if type(signum) != type(None):
		logger.debug("Signal %i caught, exiting..." % int(signum))
		shutdown()

# ----------------------------------------------------------------------------
		
def daemonize():

	try:
		pid = os.fork()
		if pid != 0:
			sys.exit(0)
	except OSError, e:
		raise RuntimeError("1st fork failed: %s [%d]" % (e.strerror, e.errno))

	os.setsid() 

	prev = os.umask(0)
	os.umask(prev and int('077', 8))

	try:
		pid = os.fork() 
		if pid != 0:
			sys.exit(0)
	except OSError, e:
		raise RuntimeError("2nd fork failed: %s [%d]" % (e.strerror, e.errno))

	dev_null = file('/dev/null', 'r')
	os.dup2(dev_null.fileno(), sys.stdin.fileno())

	if cmdarg.createpid == True:
		pid = str(os.getpid())
		logger.debug("Writing PID " + pid + " to " + str(cmdarg.pidfile))
		file(cmdarg.pidfile, 'w').write("%s\n" % pid)

# ----------------------------------------------------------------------------

def read_config( configFile, configItem):
	"""
	Read item from the configuration file
	"""
	logger.debug('Open configuration file')
	logger.debug('File: ' + configFile)
	
	xmlData = ""
	if os.path.exists( configFile ):

		#open the xml file for reading:
		f = open( configFile,'r')
		data = f.read()
		f.close()
	
		# xml parse file data
		logger.debug('Parse config XML data')
		try:
			dom = minidom.parseString(data)
		except:
			print "Error: problem in the config.xml file, cannot process it"
			logger.debug('Error in config.xml file')
			
		# Get config item
		logger.debug('Get the configuration item: ' + configItem)
		
		try:
			xmlTag = dom.getElementsByTagName( configItem )[0].toxml()
			logger.debug('Found: ' + xmlTag)
			xmlData = xmlTag.replace('<' + configItem + '>','').replace('</' + configItem + '>','')
			logger.debug('--> ' + xmlData)
		except:
			logger.debug('The item tag not found in the config file')
			
			
		logger.debug('Return')
 		
	else:
		logger.error("Error: Config file does not exists. Line: " + _line())
 		
	return xmlData

# ----------------------------------------------------------------------------

def print_version():
	"""
	Print DOMONIALCMD version, build and date
	"""
	logger.debug("print_version")
	print "DOMONIALCMD Version: " + __version__
	print __date__.replace('$', '')
	logger.debug("Exit 0")
	sys.exit(0)
	
# ----------------------------------------------------------------------------

def option_listen(address, port=55003):
	"""
	Listen to interne Socket and process data, exit with CTRL+C
	"""
	action = {'packettype' : "00", 'apikey' : str(config.apikey)}
	message = None
	byte = None
	buffer = None
	message = b""
	ackok = 1
	limit = 1
	logger.debug("Start listening...")
	logger.debug("address/port [%s:%d]" % (address, port))
	serversocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	serversocket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	
	try:
		serversocket.bind((address, port))
		serversocket.listen(5)
	except Exception as err:
		logger.error("Error starting socket server.")
		logger.error("Error: %s" % str(err))
		print "Error: can not start server socket, another instance already running?"
		exit(1)
	
	   
	while 1 :
		clientsocket, clienthost = serversocket.accept()
		clientsocket.settimeout(10)
		logger.debug("New Connection from %s" % str(clienthost))
		try :
			message = message + clientsocket.recv(1)
			check1 = re.search(b"^S", message)
			if message:
				if check1 is None:
					logger.debug("Erreur de Trame. Fermeture de la connexion client : %s" % str(clienthost))
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
						logger.debug("%s" %str(message))
						timestamp = time.strftime('%Y-%m-%d %H:%M:%S')
						if cmdarg.printout_complete == True:
							print "------------------------------------------------"
							print "Incoming message from socket"
							print "Send\t\t\t= " + message
							print "Date/Time\t\t= " + timestamp
						typetrame=message[17:19]
						logger.debug("Type de trame %s" %str(typetrame))
						bans = config.BanCanaux.split (";")
						for ban in bans:
							logger.debug("Canal banni %s" %str(ban))
							checkacq = re.search(ban, typetrame)
							if checkacq:
								ackok = 0								
							else:
								if ackok == 0:
									ackok = 0
								else :
									ackok = 1
								
						if ackok == 0:
							logger.debug("Canal banni, pas d'acquittement")	
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
							if SDSlow == 01:
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
							clientsocket.send(reponse)
						prm = message.replace('*', 'Y')
						prm = prm.replace('#', 'Z')
						action['trame'] = str(prm)
						command = Command(config.trigger_url,action)
						command.run(timeout=config.trigger_timeout)
						if config.log_msg == True:
							try: 
								file = open(config.log_msgfile,"a+")
								file.write("---------------------------------\n")
								file.write(time.strftime("%Y-%m-%d %H:%M:%S")+' Received data from jeedom : => '+message+'\n')
								file.close()
							except Exception, e:
								logger.error("Error when trying to write message log")
								logger.error("Exception: %s" % str(e))
								pass	
						logger.debug("Fin de Transmission. Fermeture connection client : %s" % str(clienthost))
						clientsocket.close()
						message = None
						byte = None
						buffer = None
						message = b""
						ackok = 1
						limit = 1
		
		except socket.timeout:
			logger.debug("Timeout for %s" % str(clienthost))
			clientsocket.close()
			message = None
			byte = None
			buffer = None
			message = b""
			ackok = 1
			limit = 1
			pass		
# ----------------------------------------------------------------------------

def read_configfile():
	"""
	Read items from the configuration file
	"""
	if os.path.exists( cmdarg.configfile ):

		# ----------------------
		# TRIGGER
		config.trigger_url = read_config( cmdarg.configfile, "trigger_url")
		config.apikey = read_config( cmdarg.configfile, "apikey")
		config.trigger_timeout = read_config( cmdarg.configfile, "trigger_timeout")

		# ----------------------
		# BAN CANAUX
		config.BanCanaux = read_config( cmdarg.configfile, "BanCanaux")
		logger.debug("Ban_Canaux: " + str(config.BanCanaux))
		
		# ----------------------
		# SOCKET SERVER EXTERNE
		config.sockethost = read_config( cmdarg.configfile, "sockethost")
		config.socketport = read_config( cmdarg.configfile, "socketport")
		logger.debug("SocketHost: " + str(config.sockethost))
		logger.debug("SocketPort: " + str(config.socketport))

		# -----------------------
		# DAEMON
		config.daemon_pidfile = read_config( cmdarg.configfile, "daemon_pidfile")
		logger.debug("Daemon_pidfile: " + str(config.daemon_pidfile))

		# ------------------------
		# LOG MESSAGES
		if (read_config(cmdarg.configfile, "log_msg") == "yes"):
			config.log_msg = True
		else:
			config.log_msg = False
		config.log_msgfile = read_config(cmdarg.configfile, "log_msgfile")
		
	else:
		# config file not found, set default values
		print "Error: Configuration file not found (" + cmdarg.configfile + ")"
		logger.error("Error: Configuration file not found (" + cmdarg.configfile + ")")

# ----------------------------------------------------------------------------

def logger_init(configFile, name, debug):
	"""

	Init loghandler and logging
	
	Input: 
	
		- configfile = location of the config.xml
		- name	= name
		- debug = True will send log to stdout, False to file
		
	Output:
	
		- Returns logger handler
	
	"""
	program_path = os.path.dirname(os.path.realpath(__file__))
	dom = None
	
	if os.path.exists( configFile ):

		# Read config file
		f = open( configFile,'r')
		data = f.read()
		f.close()

		try:
			dom = minidom.parseString(data)
		except Exception, e:
			print "Error: problem in the config.xml file, cannot process it"
			print "Exception: %s" % str(e)
			
		if dom:
		
			# Get loglevel from config file
			try:
				xmlTag = dom.getElementsByTagName( 'loglevel' )[0].toxml()
				loglevel = xmlTag.replace('<loglevel>','').replace('</loglevel>','')
			except:
				loglevel = "INFO"

			# Get logfile from config file
			try:
				xmlTag = dom.getElementsByTagName( 'logfile' )[0].toxml()
				logfile = xmlTag.replace('<logfile>','').replace('</logfile>','')
			except:
				logfile = os.path.join(program_path, "domonialcmd.log")
			
			loglevel = loglevel.upper()
			
			#formatter = logging.Formatter(fmt='%(asctime)s - %(levelname)s - %(module)s - %(message)s')
			formatter = logging.Formatter('%(asctime)s - %(threadName)s - %(module)s:%(lineno)d - %(levelname)s - %(message)s')
			
			if debug:
				loglevel = "DEBUG"
				handler = logging.StreamHandler()
			else:
				handler = logging.FileHandler(logfile)
							
			handler.setFormatter(formatter)
			
			logger = logging.getLogger(name)
			logger.setLevel(logging.getLevelName(loglevel))
			logger.addHandler(handler)
			
			return logger

# ----------------------------------------------------------------------------
		
def main():

	global logger

	# Get directory of the domonialcmd script
	config.program_path = os.path.dirname(os.path.realpath(__file__))

	parser = OptionParser()
	parser.add_option("-l", "--listen", action="store_true", dest="listen", help="Listen for messages")
	parser.add_option("-o", "--config", action="store", type="string", dest="config", help="Specify the configuration file")
	parser.add_option("-V", "--version", action="store_true", dest="version", help="Print domonialcmd version information")
	parser.add_option("-D", "--debug", action="store_true", dest="debug", default=False, help="Debug printout on stdout")
	(options, args) = parser.parse_args()

	# ----------------------------------------------------------
	# VERSION PRINT
	if options.version:
		print_version()

	# ----------------------------------------------------------
	# CONFIG FILE
	if options.config:
		cmdarg.configfile = options.config
	else:
		cmdarg.configfile = os.path.join(config.program_path, "config.xml")

	# ----------------------------------------------------------
	# LOGHANDLER
	if options.debug:
		logger = logger_init(cmdarg.configfile,'domonialcmd', True)
	else:
		logger = logger_init(cmdarg.configfile,'domonialcmd', False)
	
	logger.debug("Python version: %s.%s.%s" % sys.version_info[:3])
	logger.debug("DOMONIALCMD Version: " + __version__)
	logger.debug(__date__.replace('$', ''))

	# ----------------------------------------------------------
	# PROCESS CONFIG.XML
	logger.debug("Configfile: " + cmdarg.configfile)
	logger.debug("Read configuration file")
	read_configfile()
	
	# ----------------------------------------------------------
	# DAEMON
	if options.listen:
		logger.debug("Daemon")
		logger.debug("Check PID file")
		
		if config.daemon_pidfile:
			cmdarg.pidfile = config.daemon_pidfile
			cmdarg.createpid = True
			logger.debug("PID file '" + cmdarg.pidfile + "'")
		
			if os.path.exists(cmdarg.pidfile):
				print("PID file '" + cmdarg.pidfile + "' already exists. Exiting.")
				logger.debug("PID file '" + cmdarg.pidfile + "' already exists.")
				logger.debug("Exit 1")
				sys.exit(1)
			else:
				logger.debug("PID file does not exists")

		else:
			print("You need to set the --pidfile parameter at the startup")
			logger.error("Command argument --pidfile missing. Line: " + _line())
			logger.debug("Exit 1")
			sys.exit(1)

		logger.debug("Check platform")
		if sys.platform == 'win32':
			print "Daemonize not supported under Windows. Exiting."
			logger.error("Daemonize not supported under Windows. Line: " + _line())
			logger.debug("Exit 1")
			sys.exit(1)
		else:
			logger.debug("Platform: " + sys.platform)
			
			try:
				logger.debug("Write PID file")
				file(cmdarg.pidfile, 'w').write("pid\n")
			except IOError, e:
				logger.error("Line: " + _line())
				logger.error("Unable to write PID file: %s [%d]" % (e.strerror, e.errno))
				raise SystemExit("Unable to write PID file: %s [%d]" % (e.strerror, e.errno))

			logger.debug("Start daemon")
			daemonize()

	# ----------------------------------------------------------
	# LISTEN
	if options.listen:
		option_listen(config.sockethost,int(config.socketport))

	logger.debug("Exit 0")
	sys.exit(0)
	
# ------------------------------------------------------------------------------


if __name__ == '__main__':

	# Init shutdown handler
	signal.signal(signal.SIGINT, handler)
	signal.signal(signal.SIGTERM, handler)

	# Init objects
	config = config_data()
	cmdarg = cmdarg_data()

	main()

# ------------------------------------------------------------------------------
# END
# ------------------------------------------------------------------------------

