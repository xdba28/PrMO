<?php

    class User{

            private $db,
                    $data, 
                    $sessionName,
                    $cookieName,
                    $accountType,
                    $userType,
                    $isLoggedIn = false;

            public function __construct($user = null){
                $this->db = DB::getInstance();
                
                $this->sessionName = Config::get('session/session_name');   //$_SESSION['user'];
                $this->cookieName = Config::get('remember/cookie_name');    //$_COOKIE['hash'];


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
                        $_SESSION['accounttype'] = $this->data()->newAccount;

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
        
		// pr-jo-doc.php
		public function PRdoc_projData($pr_num){
			if($this->db->query_builder("SELECT form_ref_no, title, requested_by, date_created, 
			lot_title, lot_cost, stock_no, unit, item_description, quantity, unit_cost, total_cost
			FROM `project_request_forms`, `lots`, `lot_content_for_pr`
			WHERE project_request_forms.form_ref_no = lots.request_origin 
			AND lots.lot_id = lot_content_for_pr.lot_id_origin 
			AND form_ref_no = '$pr_num'")){
                return $this->db->first();
			}
		}

		// pr-jo-doc.php
		public function user_data($ID){
            if($this->db->query_builder("SELECT edr_id, edr_fname, edr_mname, edr_lname, 
						edr_ext_name, acronym, office_name, edr_job_title, edr_email, phone
           		FROM `enduser`, `units`
           		WHERE enduser.edr_designated_office = units.ID AND edr_id = '$ID'")){
                return $this->db->first();
            }
		}
		
		// pr-jo-doc.php
		public function PR_num_lots($ID){
			if($this->db->query_builder("SELECT count(project_request_forms.form_ref_no) as lots, form_ref_no, lot_no, lot_title
			FROM `project_request_forms`, `lots`
			WHERE project_request_forms.form_ref_no = lots.request_origin
			AND project_request_forms.form_ref_no = '$ID'")){
				return $this->db->first();
			}
		}

		// pr-jo-doc.php
		public function PR_itemsPerLot($PR_ID, $LOT_NO){
			if($this->db->query_builder("SELECT stock_no, unit, item_description, quantity, unit_cost, total_cost 
			FROM `lot_content_for_pr`, `lots`, `project_request_forms`
			WHERE project_request_forms.form_ref_no = lots.request_origin
			AND lots.lot_id = lot_content_for_pr.lot_id_origin
			AND form_ref_no = '$PR_ID'
			AND lot_no = '$LOT_NO'")){
				return $this->db->results();
			}
		}        


        public function fullname(){
            $user = Session::get($this->sessionName);

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

        public function exist(){
            return (!empty($this->data)) ? true : false;
        }    

        public function data(){
            return $this->data;
        }

        public function logout(){

            $this->db->delete('users_session', array('user_id', '=', $this->data()->account_id));

            Session::delete($this->sessionName);
            Session::delete("accounttype");
            Cookie::delete($this->cookieName);
            
        }
        
        public function isLoggedIn(){
            return $this->isLoggedIn;
        }

    }



?>