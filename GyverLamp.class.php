<?php

	class GyverLamp {
		private $socket, $ip, $port, $connected;
		
		/**
		 * Main constructor of GyverLamp Object
		 *
		 * @param string $ip IP of GyverLamp
		 * @param int $port Port of GyverLamp
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return GyverLamp Object
		*/
		public function __construct(string $ip, int $port) {
			$this->ip = $ip;
			$this->port = $port;
			$this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			$this->connected = @socket_connect($this->socket, $this->ip, $this->port);
		}
		
		/**
		 * Checking status of connection
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return boolean, connected status
		*/
		public function isConnected() {
			return $this->connected;
		}
	
		/**
		 * Getting RAW information of Lamp
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return RAW info string from GyverLamp
		*/
		public function getInfoRaw() {
			return $this->sendMessage('GET');
		}
		
		/**
		 * Getting parsed as array information of Lamp
		 *
		 * @param string $info=null pre-received RAW Info
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, 
		 * [
		 *   'mode_id' => ???, 
		 *   'enabled' => ???, 
		 *   'brightness' => ???, 
		 *   'speed' => ???, 
		 *   'scale => ???'
		 * ], or false if wrong info passed
		*/
		public function getInfo($info = null) {
			$info = explode(' ', $info == null ? $this->getInfoRaw() : $info);
			if (array_shift($info) == 'CURR') {
				return [
					'mode_id'=> $info[0],
					'enabled'=> $info[4] == 1,
					'brightness'=> $info[1],
					'speed'=> $info[2],
					'scale'=> $info[3]
				];
			}
			return false;
		}

		/**
		 * Set brightness of current Lamp mode
		 *
		 * @param string $br New brightness, 0-255 integer values
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getInfo PHPDoc
		*/
		public function setBrightness(int $br) {
			$br = min(max($br, 0), 255);
			return $this->getInfo($this->sendMessage('BRI'.$br));
		}

		/**
		 * Set speed of current Lamp mode
		 *
		 * @param string $speed New speed, 0-255 integer values
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getInfo PHPDoc
		*/
		public function setSpeed(int $speed) {
			$speed = min(max($speed, 0), 255);
			return $this->getInfo($this->sendMessage('SPD'.$speed));
		}

		/**
		 * Set scale of current Lamp mode
		 *
		 * @param string $scale New scale, 0-255 integer values
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getInfo PHPDoc
		*/
		public function setScale(int $scale) {
			$scale = min(max($scale, 0), 255);
			return $this->getInfo($this->sendMessage('SCA'.$scale));
		}

		/**
		 * Set new Lamp mode
		 *
		 * @param array $mode Mode description, from LampModes Class 
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getInfo PHPDoc
		*/
		public function setMode(array $mode) {
			return $this->setModeRaw($mode['mode_id']);
		}

		/**
		 * Set new lamp mode
		 *
		 * @param int $mode Mode ID, from LampModes Class 
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getInfo PHPDoc
		*/
		public function setModeRaw(int $mode) {
			$mode = min(max($mode, 0), 255);
			return $this->getInfo($this->sendMessage('EFF'.$mode));
		}

		/**
		 * Toggle Lamp power. If enabled, will disabled; if disabled, will enabled
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return boolean new Lamp power status
		*/
		public function togglePower() {
			$mode = !$this->getInfo()['enabled'];
			return $this->setPower($mode)['enabled'];
		}
		
		/**
		 * Set Lamp power status
		 *
		 * @param bool $power New status. true = enabled, false = disabled
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getInfo PHPDoc
		*/
		public function setPower(bool $power) {
			return $this->getInfo($this->sendMessage('P_' . ($power ? 'ON' : 'OFF')));
		}
	
		/**
		 * Get RAW Alarms Data from Lamp
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return string Alarms Data from Lamp
		*/
		public function getAlarmsRaw() {
			return $this->sendMessage('ALM_GET');
		}
	
		/**
		 * Get RAW Alarms Data from Lamp
		 *
		 * @param string $info=null pre-received RAW Alarms Data
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array Alarms Data from GyverLamp, 
		 * [
		 *   'alarms' => [
		 *     [0..7] => [
		 *       'enabled' => true ? false,
		 *       'time' => ???
		 *     ]
		 *   ], 
		 *   'alarms_mode' => ???
		 * ], or false if wrong data passed
		*/
		public function getAlarms($info = null) {
			$info = explode(' ', $info == null ? $this->getAlarmsRaw() : $info);
			if (array_shift($info) == 'ALMS') {
				$alarms_info = [
					'alarms'=> [],
					'alarms_mode'=> 0
				];
				for ($i = 0; $i < 7; $i++) {
					if (!isset($alarms_info['alarms'][$i])) $alarms_info['alarms'][$i] = [];
					$alarms_info['alarms'][$i]['enabled'] = array_shift($info) == 1;
				}
				for ($i = 0; $i < 7; $i++) {
					if (!isset($alarms_info['alarms'][$i])) $alarms_info['alarms'][$i] = [];
					$alarms_info['alarms'][$i]['time'] = array_shift($info);
				}
				$alarms_info['alarms_mode'] = array_shift($info)-1;
				
				return $alarms_info;
			}
			return false;
		}
		
		/**
		 * Set Lamp mode of Dawn (RAW)
		 *
		 * @param int $dawn New dawn mode
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getAlarms PHPDoc
		*/
		public function setDawnModeRaw(int $dawn) {
			$dawn = min(max($dawn+1, 1), 9);
			$this->sendMessage('DAWN' . $dawn);
			return $this->getAlarms();
		}
		
		/**
		 * Set Lamp mode of Dawn
		 *
		 * @param array $mode New dawn mode, from DawnModes Class 
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getAlarms PHPDoc
		*/
		public function setDawnMode(array $mode) {
			return $this->setDawnModeRaw($mode['mode_id']);
		}
		
		/**
		 * Set Lamp Alarm status (enable / disable)
		 *
		 * @param int $id Alarm day ID (0 = Monday, 6 = Sunday)
		 * @param bool $status New Alarm status. true = enabled, false = disabled
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getAlarms PHPDoc
		*/
		public function setAlarmEnabled(int $id, bool $status) {
			$id = min(max($id+1, 1), 7);
			$this->sendMessage('ALM_SET' . $id . ($status ? 'ON' : 'OFF'));
			return $this->getAlarms();
		}
		
		/**
		 * Set Lamp Alarm time (RAW, in minutes)
		 *
		 * @param int $id Alarm day ID (0 = Monday, 6 = Sunday)
		 * @param int $time New Alarm time. From 0 to 1440 minutes.
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getAlarms PHPDoc
		*/
		public function setAlarmTimeRaw(int $id, int $time) {
			$id = min(max($id+1, 1), 7);
			$time = min(max($time, 0), 1440-1);
			$this->sendMessage('ALM_SET' . $id . $time);
			return $this->getAlarms();
		}
		
		/**
		 * Set Lamp Alarm time (in HH:MM format)
		 *
		 * @param int $id Alarm day ID (0 = Monday, 6 = Sunday)
		 * @param string $time New Alarm time. In HH:MM format, for example: "21:50"
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array info from GyverLamp, described in @getAlarms PHPDoc, or false if wrong time passed
		*/
		public function setAlarmTime(int $id, string $time) {
			$time = explode(':', $time);
			if (count($time) == 2) {
				$hour = min(max((int)$time[0], 0), 23);
				$minute = min(max((int)$time[1], 0), 59);
				return $this->setAlarmTimeRaw($id, ($hour * 60) + $minute);
			}
			return false;
		}
		
		/**
		 * Send command to connected GyverLamp
		 *
		 * @param string $msg Message to send
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return string response from Lamp
		*/
		private function sendMessage(string $msg) {
			socket_send($this->socket, $msg, strlen($msg), 0);
			socket_recv($this->socket, $read, 4096, 0);
			return $read;
		}
		
		/**
		 * Destruct GyverLamp Object and close socket
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return null
		*/
		public function __destruct() {
			socket_close($this->socket);
		}
	}