<?php

    class Staff{

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
				return false;
            }
			return true;
        }



        public function selectAll($table){
            if($this->db->query_builder("SELECT * FROM `{$table}` WHERE 1")) {
                return $this->db->results();
            }
        }

        
        public function fullnameOf($ID){
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

		// new-project
        public function allPRJO_req_detail(){
			$this->db->query_builder("SELECT form_ref_no, title, requested_by, noted_by, verified_by, 
			approved_by, type, date_created, lot_title, lot_cost, note
			FROM `project_request_forms`, `lots`
			WHERE project_request_forms.form_ref_no = lots.request_origin");

			$ProjData = $this->db->results();

			foreach($ProjData as $a){
				$exist = $this->db->query_builder("SELECT form_ref_no
				FROM `project_request_forms`, `project_logs`
				WHERE project_request_forms.form_ref_no = project_logs.referencing_to 
				AND referencing_to = '$a->form_ref_no' 
				AND EXISTS (SELECT * FROM `project_logs` WHERE
							remarks = 'START_PROJECT'
							AND project_logs.type = 'IN')");
					
				if($exist->count()){
					$log_data = true;
				}else{
					$log_data = false;
				}

				$this->db->query_builder("SELECT acronym FROM `units`, `enduser`
				WHERE units.ID = enduser.edr_designated_office
				AND enduser.edr_id = '$a->requested_by'");
				$unit = $this->db->first();	

				if($a->type === "PR"){
					$this->db->query_builder("SELECT lot_title, lot_cost, note, count(lot_content_for_pr.ID) as numReq
					FROM `project_request_forms`, `lots`, `lot_content_for_pr`
					WHERE lots.lot_id = lot_content_for_pr.lot_id_origin
					AND project_request_forms.form_ref_no = lots.request_origin
					AND form_ref_no = '$a->form_ref_no'");
					$LotData = $this->db->results();
				}elseif($a->type === "JO"){
					$this->db->query_builder("SELECT lot_title, lot_cost, note, count(lot_content_for_jo.ID) as numReq
					FROM `project_request_forms`, `lots`, `lot_content_for_jo`
					WHERE lots.lot_id = lot_content_for_jo.lot_id_origin
					AND project_request_forms.form_ref_no = lots.request_origin
					AND form_ref_no = '$a->form_ref_no'");
					$LotData = $this->db->results();
				}
				
				$lot = null;
				foreach($LotData as $b){
					$lot[] = [
						'l_title' => htmlspecialchars_decode($b->lot_title, ENT_QUOTES),
						'l_cost' => htmlspecialchars_decode($b->lot_cost, ENT_QUOTES),
						'note' => htmlspecialchars_decode($b->note, ENT_QUOTES),
						'numReq' => $b->numReq
					];
				}

				$data[] = [
					'id' => htmlspecialchars_decode($a->form_ref_no, ENT_QUOTES),
					'title' => htmlspecialchars_decode($a->title, ENT_QUOTES),
					'req_by' => $a->requested_by.":".htmlspecialchars_decode($this->fullnameOf($a->requested_by),ENT_QUOTES)." | ".$unit->acronym.":".$unit->acronym,
					'noted_by' => htmlspecialchars_decode($a->noted_by, ENT_QUOTES),
					'verified_by' => htmlspecialchars_decode($a->verified_by, ENT_QUOTES),
					'approved_by' => htmlspecialchars_decode($a->approved_by, ENT_QUOTES),
					'type' => $a->type,
					'date_created' => date('F j, Y', strtotime($a->date_created)),
					'lot_details' => $lot,
					'log_exist' => $log_data
				];
			}
			return $data;
        }



        public function data(){
            return $this->data;
        }


    }

?>