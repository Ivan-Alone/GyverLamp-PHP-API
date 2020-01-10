<?php

	class LampModes {
		private static $modes = [
			[
				'mode_id' => 0,
				'mode_name'=> [
					'ru'=> 'Конфетти',
					'en'=> 'Confetti'
				]
			], [
				'mode_id'=> 1,
				'mode_name'=> [
					'ru'=> 'Огонь', 
					'en'=> 'Fire'
				]
			], [
				'mode_id'=> 2,
				'mode_name'=> [
					'ru'=> 'Вертикальная радуга', 
					'en'=> 'Vertical rainbow'
				]
			], [
				'mode_id'=> 3,
				'mode_name'=> [
					'ru'=> 'Горизонтальная радуга', 
					'en'=> 'Horizontal rainbow'
				]
			], [
				'mode_id'=> 4,
				'mode_name'=> [
					'ru'=> 'Смена цвета', 
					'en'=> 'Color change'
				]
			], [
				'mode_id'=> 5,
				'mode_name'=> [
					'ru'=> 'Безумие 3D', 
					'en'=> '3D Madness'
				]
			], [
				'mode_id'=> 6,
				'mode_name'=> [
					'ru'=> 'Облака 3D', 
					'en'=> 'Clouds 3D'
				]
			], [
				'mode_id'=> 7,
				'mode_name'=> [
					'ru'=> 'Лава 3D', 
					'en'=> 'Lava 3D'
				]
			], [
				'mode_id'=> 8,
				'mode_name'=> [
					'ru'=> 'Плазма 3D', 
					'en'=> '3D plasma'
				]
			], [
				'mode_id'=> 9,
				'mode_name'=> [
					'ru'=> 'Радуга 3D', 
					'en'=> 'Rainbow 3D'
				]
			], [
				'mode_id'=> 10,
				'mode_name'=> [
					'ru'=> 'Павлин 3D', 
					'en'=> 'Peacock 3D'
				]
			], [
				'mode_id'=> 11,
				'mode_name'=> [
					'ru'=> 'Зебра 3D', 
					'en'=> 'Zebra 3D'
				]
			], [
				'mode_id'=> 12,
				'mode_name'=> [
					'ru'=> 'Лес 3D', 
					'en'=> 'Forest 3D'
				]
			], [
				'mode_id'=> 13,
				'mode_name'=> [
					'ru'=> 'Океан 3D', 
					'en'=> 'Ocean 3D'
				]
			], [
				'mode_id'=> 14,
				'mode_name'=> [
					'ru'=> 'Цвет', 
					'en'=> 'Color'
				]
			], [
				'mode_id'=> 15,
				'mode_name'=> [
					'ru'=> 'Снег', 
					'en'=> 'Snow'
				]
			], [
				'mode_id'=> 16,
				'mode_name'=> [
					'ru'=> 'Матрица', 
					'en'=> 'Matrix'
				]
			], [
				'mode_id'=> 17,
				'mode_name'=> [
					'ru'=> 'Светлячки', 
					'en'=> 'Fireflies'
				]
			]
		];
		
		/**
		 * Get Lamp mode array by english mode Name
		 *
		 * @param string $name English name of mode you need
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array Lamp mode or false, if mode not found
		*/
		public static function byName(string $name) {
			foreach (static::$modes as $mode) {
				if ($mode['mode_name']['en'] == $name) {
					return $mode;
				}
			}
			return false;
		}
		
		/**
		 * Get Lamp mode array by ID
		 *
		 * @param int $id ID of mode you need
		 *
		 * @author Ivan_Alone
		 * @copyright 2020 Ivan_Alone
		 *
		 * @return array Lamp mode or false, if mode not found
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