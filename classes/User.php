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
        public function Doc_projData($REQ){
            $REQ = explode(":", $REQ);
            if($REQ[1] === "PR"){
                if($this->db->query_builder("SELECT form_ref_no, title, requested_by, date_created, noted_by, verified_by, approved_by
                FROM `project_request_forms`, `lots`, `lot_content_for_pr`
                WHERE project_request_forms.form_ref_no = lots.request_origin 
                AND lots.lot_id = lot_content_for_pr.lot_id_origin 
                AND form_ref_no = '$REQ[0]'")){
                    return $this->db->first();
                }
            }elseif($REQ[1] === "JO"){
                if($this->db->query_builder("SELECT form_ref_no, title, requested_by, date_created, noted_by, verified_by, approved_by
                FROM `project_request_forms`, `lots`, `lot_content_for_jo`
                WHERE project_request_forms.form_ref_no = lots.request_origin 
                AND lots.lot_id = lot_content_for_jo.lot_id_origin 
                AND form_ref_no = '$REQ[0]'")){
                    return $this->db->first();
                }
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
        public function PRJO_num_lots($REQ){
            $REQ = explode(":", $REQ);
            if($REQ[1] === "PR"){
                if($this->db->query_builder("SELECT form_ref_no, lot_no, lot_title, count(ID) as 'number_of_items', lot_cost
                FROM `project_request_forms`, `lots`, `lot_content_for_pr`
                WHERE project_request_forms.form_ref_no = lots.request_origin
                AND lots.lot_id = lot_content_for_pr.lot_id_origin
                AND form_ref_no = '$REQ[0]'
                GROUP BY lot_id_origin")){
                    return $this->db->results();
                }
            }elseif($REQ[1] === "JO"){
                if($this->db->query_builder("SELECT form_ref_no, lot_no, lot_title, count(ID) as 'number_of_items', lot_cost, note
                FROM `project_request_forms`, `lots`, `lot_content_for_jo`
                WHERE project_request_forms.form_ref_no = lots.request_origin
                AND lots.lot_id = lot_content_for_jo.lot_id_origin
                AND form_ref_no = '$REQ[0]'
                GROUP BY lot_id_origin")){
                    return $this->db->results();
                }
            }
        }
        // pr-jo-doc.php
        public function PRJO_itemsPerLot($ID, $LOT_NO, $REQ){
            if($REQ === "PR"){
                if($this->db->query_builder("SELECT stock_no, unit, item_description, quantity, unit_cost, total_cost 
                FROM `lot_content_for_pr`, `lots`, `project_request_forms`
                WHERE project_request_forms.form_ref_no = lots.request_origin
                AND lots.lot_id = lot_content_for_pr.lot_id_origin
                AND form_ref_no = '$ID'
                AND lot_no = '$LOT_NO'")){
                    return $this->db->results();
                }
            }elseif($REQ === "JO"){
                if($this->db->query_builder("SELECT header, tags
                FROM `lot_content_for_jo`, `lots`, `project_request_forms`
                WHERE project_request_forms.form_ref_no = lots.request_origin
                AND lots.lot_id = lot_content_for_jo.lot_id_origin
                AND form_ref_no = '$ID'
                AND lot_no = '$LOT_NO'")){
                    return $this->db->results();
                }
            }
        }   

        public function userData($ID){
            if ($this->db->query_builder("SELECT edr_id, edr_fname, edr_mname, edr_lname, concat(edr_fname,edr_lname), concat(edr_fname,' ' ,edr_lname), edr_ext_name, edr_email, phone, office_name, edr_job_title, username, group_id, name as 'group_name', permission
            FROM `enduser`, `units`, `edr_account`, `group`

            WHERE
            enduser.edr_designated_office = units.ID AND
            enduser.edr_id = edr_account.account_id AND
            edr_account.group_ = group.group_id AND
            edr_id = '{$ID}'
            ")) {
                return $this->db->first();
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
		
        public function fullnameOfEnduser($ID){ //for enduser use
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

		public function projectHistory($originRefno, $currentRefno){
			if($this->db->query_builder("SELECT referencing_to, remarks, logdate, project_logs.type
			FROM `projects`, `project_logs`
			WHERE referencing_to = '{$originRefno}' OR
			referencing_to = '{$currentRefno}' GROUP BY ID
			")){
				return $this->db->results();
			}

			return false;
		}

		public function numberOfLots($refno){
			if($this->db->query_builder("SELECT COUNT(lot_id) as 'numberOfLots', lot_no FROM
			`lots` WHERE request_origin = '{$refno}'
			GROUP BY request_origin")){
				return $this->db->first();
			}
		}//for counting the number of lots

		public function getContent($refno, $type, $lot){
			if($type == "PR"){
				if($this->db->query_builder("SELECT lot_no as 'from_lot', lot_title, ID as 'identifier', lot_id_origin, stock_no, unit, item_description, quantity, unit_cost, total_cost
				FROM
				`lots`, `lot_content_for_pr`
				WHERE
				lots.lot_id = lot_content_for_pr.lot_id_origin AND
				request_origin = '{$refno}' AND
				lot_no = '{$lot}'
				")){
					return $this->db->results();
				}
			}else{
				if($this->db->query_builder("SELECT lot_no as 'from_lot', lot_title, ID as 'identifier', lot_id_origin, header, tags, note, lot_cost
				FROM
				`lots`, `lot_content_for_jo`
				WHERE
				lots.lot_id = lot_content_for_jo.lot_id_origin AND
				request_origin = '{$refno}' AND
				lot_no = '{$lot}'
				")){
					return $this->db->results();
				}
			}
		}//for fetching lot content

		public function myRequests($user, $registered = false){

			if($registered){
				if($this->db->query_builder("SELECT form_ref_no, title, type, date_created, COUNT(lots.lot_id) as 'number_of_lots'
				FROM project_request_forms, lots
				WHERE 
				project_request_forms.form_ref_no = lots.request_origin AND
				EXISTS
				(SELECT * FROM `project_logs` WHERE project_request_forms.form_ref_no = project_logs.referencing_to AND remarks = 'START_PROJECT') AND requested_by ='{$user}'
				
				GROUP BY request_origin")){
					return $this->db->results();
				}
			}else{
				if($this->db->query_builder("SELECT form_ref_no, title, type, date_created, COUNT(lots.lot_id) as 'number_of_lots'
				FROM project_request_forms, lots
				WHERE 
				project_request_forms.form_ref_no = lots.request_origin AND
				NOT EXISTS
				(SELECT * FROM `project_logs` WHERE project_request_forms.form_ref_no = project_logs.referencing_to AND remarks = 'START_PROJECT') AND requested_by ='{$user}'
				
				GROUP BY request_origin")){
					return $this->db->results();
				}
			}


		}

		public function logLastUpdated($ID){ //to get the data when the last update of the project
			if($this->db->query_builder("SELECT *, COUNT(*) as 'result' FROM `project_logs` WHERE referencing_to = '{$ID}' ORDER BY logdate DESC LIMIT 1")){
				return $this->db->first();
			}
		}
		
		public function like($table, $column, $particular){
			if($this->db->query_builder("SELECT * FROM `{$table}` WHERE $column LIKE '{$particular}'")){
				return $this->db->results();
			}
		}

        public function update($table, $particular, $identifier, $fields){
            if(!$this->db->update($table, $particular, $identifier, $fields)){
                throw new Exception("Error Updating Request", 1);
                return false;
            }
            return true;
		}

		public function selectAll($table){
            if($this->db->query_builder("SELECT * FROM `{$table}` WHERE 1")) {
                return $this->db->results();
            }
		}		

		public function get($table, $where){	
			if($this->db->get($table, $where)){
				return $this->db->first();
			}
			return false;
		}		
		
		public function getAll($table, $where){	
			if($this->db->get($table, $where)){
				return $this->db->results();
			}
			return false;
		}

        public function exist(){
            return (!empty($this->data)) ? true : false;
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