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
				throw new Exception('There was a problem registering new user', 1);
				return false;
            }
			return true;
		}

		// account-request.php , xhr-req-approve.php
        public function requests($ID = null){
			if($ID !== null)
			{
				if($this->db->query_builder("SELECT account_requests.ID, fname, midle_name, last_name, ext_name, email, employee_id, `status`, remarks, contact, office_name, submitted 
				FROM `account_requests`, `units` 
				WHERE account_requests.designation = units.ID
				AND account_requests.ID = '$ID'")){
					return $this->db->first();
				}
			}
            elseif($this->db->query_builder("SELECT account_requests.ID, fname, midle_name, last_name, ext_name, email, employee_id, `status`, remarks, contact, office_name, submitted FROM `account_requests`, `units` WHERE account_requests.designation = units.ID")){
				return $this->db->results();
			}
		}

		//xhr-req-approve.php
		public function get($name, $fields = array()){
			if($this->db->get($name, $fields)){
				return $this->db->first();
			}else return false;
		}

		public function delPersn($table, $where = array()){
			if($this->db->delete($table, $where)){
				return true;
			}else return false;
		}

        public function personnels(){
            if ($this->db->query_builder("SELECT prnl_id, prnl_fname, prnl_mname, prnl_lname, prnl_ext_name, prnl_email, phone, office_name, prnl_job_title, prnl_assigned_phase, group_id, name, permission, status
            FROM `personnel`, `units`, `prnl_account`, `group`
            
            WHERE
            personnel.prnl_designated_office = units.ID AND
            personnel.prnl_id = prnl_account.account_id AND
            prnl_account.group_ = group.group_id
            ")) {
                return $this->db->results();
            }
        }

        public function personnelData($ID){
            if ($this->db->query_builder("SELECT prnl_id, prnl_fname, prnl_mname, prnl_lname, prnl_ext_name, prnl_email, phone, office_name, prnl_job_title, prnl_assigned_phase, username, group_id, name as 'group_name', permission, status
            FROM `personnel`, `units`, `prnl_account`, `group`

            WHERE
            personnel.prnl_designated_office = units.ID AND
            personnel.prnl_id = prnl_account.account_id AND
            prnl_account.group_ = group.group_id AND
            prnl_id = '{$ID}'
            ")) {
                return $this->db->first();
            }
		}
		
		public function userOverview(){
			if($this->db->query_builder("SELECT 
			ID, office_name, acronym, campus, note, verifier, approving, COUNT(edr_id) as 'registered_users', (SELECT COUNT(*) FROM `enduser`) as 'overall_users'
			FROM
			`units`, `enduser`
			WHERE
			units.ID = enduser.edr_designated_office
			GROUP BY ID")){
				return $this->db->results();
			}
		}

		public function unitUsers($unit){
			if($this->db->query_builder("SELECT 
			account_id, username, userpassword, newAccount , edr_designated_office, current_specific_office
			
			FROM 
			`enduser`, `edr_account` 
			
			WHERE
			enduser.edr_id = edr_account.account_id AND
			edr_designated_office = '{$unit}'
			")){
				return $this->db->results();
			}

		}
		
        public function registered_users(){
            if($this->db->query_builder("SELECT * FROM `edr_account` WHERE 1")) {
                return $this->db->results();
            }
        }	

        public function selectAll($table){
            if($this->db->query_builder("SELECT * FROM `{$table}` WHERE 1")) {
                return $this->db->results();
            }
        }


		public function update_request($remarks, $ID){
			if(!$this->db->query_builder("UPDATE account_requests SET remarks = '$remarks', `status` = 'reviewed' WHERE ID ='$ID'")){
				throw new Exception('There was a problem updating request', 1);
			}
        }
        
        public function fullname(){
            $user = Session::get($this->sessionName);

            $data = $this->db->get('personnel', array('prnl_id', '=', $user));
                if($data->count()){
                    $temp = $data->first();
                    
                    if($temp->prnl_ext_name == 'XXXXX'){
                        $fullname = $temp->prnl_fname .' '.$temp->prnl_mname.' '.$temp->prnl_lname;
                    }else{
                        $fullname = $temp->prnl_fname .' '.$temp->prnl_mname.' '.$temp->prnl_lname.' '.$temp->prnl_ext_name;
                    }
                   
                    return $fullname;
                }

            return false;
        }
        
        public function fullnameOf($ID){
            $user = $ID;

            $data = $this->db->get('personnel', array('prnl_id', '=', $user));
                if($data->count()){
                    $temp = $data->first();
                    
                    if($temp->prnl_ext_name == 'XXXXX'){
                        $fullname = $temp->prnl_fname .' '.$temp->prnl_mname.' '.$temp->prnl_lname;
                    }else{
                        $fullname = $temp->prnl_fname .' '.$temp->prnl_mname.' '.$temp->prnl_lname.' '.$temp->prnl_ext_name;
                    }
                   
                    return $fullname;
                }

            return false;
		}
	
		public function fullnameOfEnduser($ID){
            $user = $ID;

            $data = $this->db->get('enduser', array('edr_id', '=', $user));
                if($data->count()){
                    $temp = $data->first();
                    
                    if($temp->edr_ext_name == 'XXXXX'){
                        $fullname = $temp->edr_fname .' '.$temp->edr_mname.' '.$temp->edr_lname;
                    }else{
                        $fullname = $temp->edr_fname .' '.$temp->edr_mname.' '.$temp->edr_lname.' '.$temp->edr_ext_name;
                    }
                   
                    return $fullname;
                }

            return false;
        }
        
        public function update($table, $particular, $identifier, $fields){
            if(!$this->db->update($table, $particular, $identifier, $fields)){
                throw new Exception("Error Updating Request", 1);
            }
        }

		public function startTrans(){
			$this->db->startTrans();
		}

		public function endTrans(){
			$this->db->endTrans();
		}

        public function data(){
            return $this->data;
        }


    }

?>