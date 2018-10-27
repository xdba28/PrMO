<?php

	class Date{

		public static function translate($string = null, $option){

			if($option == "now"){
				return date('Y-m-d H:i:s');
			}else{
				$time = strtotime($string);
			}

			switch ($option) {
				case '1':
					$translated_date = date("F j, Y / g:i A", $time);
					break;

				case 'month':
					$translated_date = date("F, Y");
					break;		
				case 'weekno':
					$translated_date = "Week no: ".date("W"). " of ".date('Y');
					break;
				default:
					# code...
					break;
			}

			return $translated_date;

		}

	}

?>