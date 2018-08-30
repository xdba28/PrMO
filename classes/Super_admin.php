<?php

    class Super_admin{

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

        public function find($user = null, $table, $field_name){//dinamic find for super admin

            if($user){
                $data = $this->db->get($table , array($field_name, '=', $user));

                    if($data->count()){
                        $this->data = $data->first();

                        return true;
                    }
            }

            return false;
            
        }

        public function findById($user = null){ //find using ID only in prnl_account

            if($user){
                $data = $this->db->get('prnl_account', array('account_id', '=', $user));

                    if($data->count()){
                        $this->data = $data->first();

                        return true;
                    }
            }

            return false;
            
        }        

        public function register($table, $fields = array()){
            if(!$this->db->insert($table, $fields)){
                throw new Exception('There was a problem registering new user');
            }

        }

        public function requests(){
            if ($this->db->query_builder("SELECT account_requests.ID, fname, midle_name, last_name, ext_name, email, employee_id, `status`, remarks, contact, office_name, submitted FROM `account_requests`, `units` WHERE account_requests.designation = units.ID")) {
                return $this->db->results();
            }
        }

        public function personnels(){
            if ($this->db->query_builder("SELECT prnl_id, prnl_fname, prnl_mname, prnl_lname, prnl_ext_name, prnl_email, phone, office_name, prnl_job_title, prnl_assigned_phase, group_id, name, permission, status
            FROM `personnel`, `units`, `prnl_account`, `group`
            
            WHERE
            personnel.prnl_designated_office = units.ID AND
            personnel.prnl_id = prnl_account.account_id AND
            prnl_account.group = group.group_id
            ")) {
                return $this->db->results();
            }
        }
		
        public function registered_users(){
            if ($this->db->query_builder("SELECT * FROM `edr_account` WHERE 1")) {
                return $this->db->results();
            }
        }	


		public function update_request($remarks, $ID){
			if(!$this->db->query_builder("UPDATE account_requests SET remarks = '$remarks', `status` = 'reviewed' WHERE ID ='$ID'")){
				throw new Exception('There was a problem updating request');
			}
		}



        public function data(){
            return $this->data;
        }


    }

?>