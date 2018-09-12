<?php 
	class Token{

		public static function generate($name){
			return Session::put($name, md5(uniqid()));
		}

		public static function check($tokenName, $token){
			
			if(Session::exists($tokenName) && $token === Session::get($tokenName)){
				Session::delete($tokenName);
				return true;
			}

			return false;
		}

	}

?>