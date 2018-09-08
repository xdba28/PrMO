<?php

    class Input{
        public static function exists($type = 'POST'){
            switch($type){
                case 'POST':
                    return (!empty($_POST)) ? true : false;
                    break;
                case 'GET':
                    return (!empty($_GET)) ? true : false;
                    break;          
                default:
                    return false;
                    break;          
            }
        }

		public static function get($item){
            if(isset($_POST[$item])){
				if(is_numeric($item)){
					$filter = filter_input(INPUT_POST, $item, FILTER_SANITIZE_NUMBER_FLOAT);
				}elseif(is_string($item)){
					$filter = filter_input(INPUT_POST, $item, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				}
                return $filter;
            }else if(isset($_GET[$item])){
				if(is_numeric($item)){
					$filter = filter_input(INPUT_GET, $item, FILTER_SANITIZE_NUMBER_FLOAT);
				}elseif(is_string($item)){
					$filter = filter_input(INPUT_GET, $item, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				}
                return $filter;
            }else{
                return ''; 
            }
		}


?>