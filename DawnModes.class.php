<?php

	class DawnModes {
		private static $modes = [
			[
				'mode_id'=> 0,
				'mode_minutes'=> 5
			], [
				'mode_id'=> 1,
				'mode_minutes'=> 10
			], [
				'mode_id'=> 2,
				'mode_minutes'=> 15
			], [
				'mode_id'=> 3,
				'mode_minutes'=> 20
			], [
				'mode_id'=> 4,
				'mode_minutes'=> 25
			], [
				'mode_id'=> 5,
				'mode_minutes'=> 30
			], [
				'mode_id'=> 6,
				'mode_minutes'=> 40
			], [
				'mode_id'=> 7,
				'mode_minutes'=> 50
			], [
				'mode_id'=> 8,
				'mode_minutes'=> 60
			]
		];
		
		/**
		 * Get Dawn mode array by minutes
		 *
		 * @param int $minutes Minutes, that mode you need. Note that only 9 modes supported!
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array Dawn mode or false, if mode not found
		*/
		public static function byMinutes(int $minutes) {
			foreach (static::$modes as $mode) {
				if ($mode['mode_minutes'] == $minutes) {
					return $mode;
				}
			}
			return false;
		}
		
		/**
		 * Get Dawn mode array by ID
		 *
		 * @param int $id ID of mode you need
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array Dawn mode or false, if mode not found
		*/
		public static function byID(int $id) {
			foreach (static::$modes as $mode) {
				if ($mode['mode_id'] == $id) {
					return $mode;
				}
			}
			return false;
		}
	}