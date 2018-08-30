<?php

    class User{

        private $db,
                $data, 
                $sessionName,
                $cookieName,
                $isLoggedIn;

        public function __construct($user = null){
            $this->db = DB::getInstance();
            
            $this->sessionName = Config::get('session/session_name');   //$_SESSION['user'];
            $this->cookieName = Config::get('remember/cookie_name');   //$_COOKIE['hash'];

                if(!$user){                                             //checks if the new User() is defined or not
                    if(Session::exists($this->sessionName)){            //validate if session actually exist and setted   

                        $user = Session::get($this->sessionName);
                    
                        if($this->findById($user)){                             //if there is a matching row of the current user from the database
                            $this->isLoggedIn = true;                       //we initialize the "isLoggedIn" to true
                        }else{
                            //process logout, illegal access
                        }
                    }
                }else{
                    $this->findById($user);
                }
        }

        public function find($user = null){//find using username

            if($user){
                $data = $this->db->get('edr_account', array('username', '=', $user));

                    if($data->count()){
                        $this->data = $data->first();

                        return true;
                    }
            }

            return false;
            
        }

        public function findById($user = null){ //find using ID

            if($user){
                $data = $this->db->get('edr_account', array('account_id', '=', $user));

                    if($data->count()){
                        $this->data = $data->first();

                        return true;
                    }
            }

            return false;
            
        }

        public function login($username = null, $password = null, $remember = false){
			
			if(!$username && !$password && $this->exist()){
                //log the user in by the existing cookie in the browser

                Session::put($this->sessionName, $this->data()->account_id);

			}else{
				
            $user =  $this->find($username);
           
                if($user){

                    if($this->data()->userpassword === Hash::make($password, $this->data()->salt)){
                        Session::put($this->sessionName, $this->data()->account_id);
                            
                        if($remember){

                            
                            $hash = Hash::unique();
                            $hashCheck = $this->db->get('users_session', array('user_id', '=', $this->data()->account_id));


                            if(!$hashCheck->count()){
                                $this->db->insert('users_session' ,array(
                                    
                                    'user_id' =>  $this->data()->account_id,
                                    'hash' => $hash

                                ));
                            }else{
                                $hash = $hashCheck->first()->hash;
                            }

                                Cookie::put($this->cookieName, $hash, Config::get('remember/cookie_expiry'));
                        }
            

                        return true;
                    }
                }				
				
			}
            
    
            return false;
        }

        public function register($table, $fields = array()){
            if(!$this->db->insert($table, $fields)){
                throw new Exception('There was a problem registering fields');
           }       
        
        }
        
        public function ro_ln_composite($request_origin, $lot_no){//ro = "request origin", ln = "lot number"
            if($this->db->query_builder("SELECT lot_id FROM `lots` WHERE request_origin='$request_origin' AND lot_no=$lot_no")){
                 return $this->db->first();
            }
        }


        public function exist(){
            return  (!empty($this->data)) ? true : false;
        }

        public function data(){
            return $this->data;
        }

        public function logout(){

            $this->db->delete('users_session', array('user_id', '=', $this->data()->account_id));

            Session::delete($this->sessionName);
            Cookie::delete($this->cookieName);
        }
        
        public function isLoggedIn(){
            return $this->isLoggedIn;
        }

    }



?>