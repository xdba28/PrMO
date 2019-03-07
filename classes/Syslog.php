<?php

	Class Syslog{

		public static function put($action,$path = null, $attempt = "success", $ID = null, $givenusername = null){

			
			
			if((is_null($ID)) && (is_null($givenusername))){

				$sessionUser = Session::get(Config::get('session/session_name'));
				$username = Session::get('username');
	
					$log  = "User:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
					"Action:".$action.PHP_EOL.
					"Attempt:".$attempt.PHP_EOL.
					"Username:".$sessionUser.":".$username.PHP_EOL.
					"-------------------------".PHP_EOL;
					$finalPath = (is_null($path)?'../../data/logfiles/' : $path);
					file_put_contents($finalPath.date("F.Y").'.txt', $log, FILE_APPEND);

			}else{

					$log  = "User:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
					"Action:".$action.PHP_EOL.
					"Attempt:".$attempt.PHP_EOL.
					"Username:".$ID.":".$givenusername.PHP_EOL.
					"-------------------------".PHP_EOL;
					$finalPath = (is_null($path)?'../../data/logfiles/' : $path);
					file_put_contents($finalPath.date("F.Y").'.txt', $log, FILE_APPEND);


			}


		}

	}

?>