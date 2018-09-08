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
                throw new Exception('There was a problem registering new user', 1);
            }

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

        public function pr_jo_requests(){
            if($this->db->query_builder("SELECT form_ref_no, title, requested_by, type, date_created FROM `project_request_forms`, `enduser` WHERE project_request_forms.requested_by =  enduser.edr_id")){
                return $this->db->results();
            }
        }

        public function pr_jo_particulars(){
            //something
        }



        public function data(){
            return $this->data;
        }


    }

?>