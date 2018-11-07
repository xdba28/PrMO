<?php
    //this class is for all purpose use of all personnel accounts
    class Admin{

            private $db,
                    $data, 
                    $sessionName,
                    $cookieName,
                    $accountType,
                    $userType,
                    $isLoggedIn = false;

            public function __construct($user = null){  //should be copied in user.php class * always inst
                $this->db = DB::getInstance();

                $this->sessionName = Config::get('session/session_name');  
                $this->cookieName =  Config::get('remember/cookie_name');             
                

                if(!$user){                                             //checks if the new User() is defined or not
                    if(Session::exists($this->sessionName)){            //validate if session actually exist and setted   

                        $user = Session::get($this->sessionName);
                    
                        if($this->findById($user)){                         //if there is a matching row of the current user from the database
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
                $data = $this->db->get('prnl_account', array('username', '=', $user));

                    if($data->count()){
                        $this->data = $data->first();

                        return true;
                    }
            }

            return false;
            
        }

        public function findById($user = null){ //find using ID

            if($user){
                $data = $this->db->get('prnl_account', array('account_id', '=', $user));

                    if($data->count()){
                        $this->data = $data->first();

                        return true;
                    }
            }

            return false;
            
        }

        public function login($username = null, $password = null){
		
            $user =  $this->find($username);
                
                if($user){


						if($this->data()->userpassword === Hash::make($password, $this->data()->salt)){

							if($this->data()->status == "DEACTIVATED"){
								return false;
							}else{
								Session::put($this->sessionName, $this->data()->account_id);
								$_SESSION['accounttype'] = $this->data()->newAccount;

								return true;
							}

						}
					
                    

                }				

            return false;
		}
		
		public function dashboard_procurement_entries($option){



			switch ($option) {
				case 'year':

					//current year
					$year =  date('Y');
					//first day of lastyear
					$firstDayLastYear = date('Y-m-d H:i:s', strtotime('first day of january last year'));
					//date this day last year
					$dateTodayLastYear = date('Y-m-d H:i:s', strtotime('-1 year'));

					//first day of the year
					$firstDayThisYear = date('Y-m-d H:i:s', strtotime('first day of january this year'));
					//until now
					$today = date('Y-m-d H:i:s');					

					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered BETWEEN '{$firstDayLastYear}' AND '{$dateTodayLastYear}'")){
						$previousEntries = $this->db->first()->entries;
					}
					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered BETWEEN '{$firstDayThisYear}' AND '{$today}'")){
						$currentEntries = $this->db->first()->entries;
					}

					if($this->db->query_builder("SELECT COUNT(project_id) as 'totalEntries' FROM `projects` WHERE date_registered LIKE '{$year}%'")){
						$totalEntries = $this->db->first()->totalEntries;
					}

					break;
				case 'month':

					$currentMonth = date('Y-m');
					$previousMonth = date('Y-m',strtotime("- 1 month"));

					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered LIKE '{$previousMonth}%'")){
						$previousEntries = $this->db->first()->entries;
					}
					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered LIKE '{$currentMonth}%'")){
						$currentEntries = $this->db->first()->entries;
					}

					if($this->db->query_builder("SELECT COUNT(project_id) as 'totalEntries' FROM `projects` WHERE date_registered LIKE '{$currentMonth}%'")){
						$totalEntries = $this->db->first()->totalEntries;
					}

					break;
				case 'week':

					// current week
					$weekNo = date('W');
					$startOftheWeek = date('Y-m-d', strtotime('monday this week'))." 00:00:00";
					$endOftheWeek = date('Y-m-d', strtotime('sunday this week'))." 23:59:59";

					//last week
					$previousWeek = date('W', strtotime('- 1 week'));
					$startOftheLastWeek = date('Y-m-d', strtotime('monday last week'))." 00:00:00";
					$endOftheLastWeek = date('Y-m-d', strtotime('sunday last week'))." 23:59:59";				

					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered BETWEEN '{$startOftheLastWeek}' AND '{$endOftheLastWeek}'")){
						$previousEntries = $this->db->first()->entries;
					}
					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered BETWEEN '{$startOftheWeek}' AND '{$endOftheWeek}'")){
						$currentEntries = $this->db->first()->entries;
					}

					if($this->db->query_builder("SELECT COUNT(project_id) as 'totalEntries' FROM `projects` WHERE date_registered BETWEEN '{$startOftheWeek}' AND '{$endOftheWeek}'")){
						$totalEntries = $this->db->first()->totalEntries;
					}


					break;
				case 'day':

					$currentDay = date('Y-m-d');
					$previousDay = date('Y-m-d',strtotime("- 1 day"));

					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered LIKE '{$previousDay}%'")){
						$previousEntries = $this->db->first()->entries;
					}
					if($this->db->query_builder("SELECT COUNT(project_id) as 'entries' FROM `projects` WHERE date_registered LIKE '{$currentDay}%'")){
						$currentEntries = $this->db->first()->entries;
					}			

					if($this->db->query_builder("SELECT COUNT(project_id) as 'totalEntries' FROM `projects` WHERE date_registered LIKE '{$currentDay}%'")){
						$totalEntries = $this->db->first()->totalEntries;
					}

					break;
			}

				
			
			if($previousEntries == "0"){
				
				$entriesReport = array($totalEntries, "No Comparison Data available from previous {$option}.");
				return $entriesReport;
		
			}else{

				$percentCalculation = $this->calculateDifferencePercentage($previousEntries, $currentEntries);
				$entriesReport =  array($totalEntries, $percentCalculation);
				return $entriesReport;

			}
		}

		private function calculateDifferencePercentage($originalNumber, $newNumber){

			$raw = (($newNumber - $originalNumber) / $originalNumber * 100);
			$newFormat = number_format($raw,1);

			return $newFormat;
		}


        public function profile($id){

            if($this->db->query_builder("SELECT * FROM `prnl_account`, `personnel`, `units` WHERE prnl_account.account_id = personnel.prnl_id AND personnel.prnl_designated_office = units.ID AND prnl_id = '$id' ")){
                return $this->db->first();
            }
		}

        public function userData($ID){
            if ($this->db->query_builder("SELECT prnl_id, prnl_fname, prnl_mname, prnl_lname, concat(prnl_fname,prnl_lname), concat(prnl_fname,' ' ,prnl_lname), prnl_ext_name, prnl_email, phone, office_name, prnl_job_title, username, group_id, name as 'group_name', permission
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
					
					$myArray = ["0" => $fullname, "1" => $temp->prnl_job_title];
					$json =  json_encode($myArray, JSON_FORCE_OBJECT);
                   
                    return $json;
                }

            return false;
        }
		
        public function fullnameOf($ID){ //for personnel use
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
		
		//this functions is used for outgoing documents from outgoing to outgoing register
		public function transfer($refno, $name){
			if(!$this->db->query_builder("INSERT INTO `outgoing_register`(`project`, `transmitting_to`, `specific_office`, `remarks`, `transactions`, `date_registered`, `released_by`) SELECT project, transmitting_to, specific_office, remarks, transactions, NOW(), '{$name}' FROM `outgoing` WHERE project = '$refno'")){
				return true;
			}
			return false;
		}

		public function listNotification(){
            $user = Session::get($this->sessionName);
			$this->db->query_builder("SELECT message, datecreated, seen, href FROM notifications WHERE recipient = '{$user}' ORDER BY ID DESC");
			$notifList = $this->db->results();
			$this->db->query_builder("SELECT COUNT(seen) as seen FROM notifications WHERE recipient = '{$user}' and seen = '0'");
			$nofitCount = $this->db->first();
			$notif = [
				'list' => $notifList,
				'count' => $nofitCount
			];
			return $notif;
		}

        public function register($table, $fields = array()){
            if(!$this->db->insert($table, $fields)){
				throw new Exception('There was a problem registering data', 1);
				return true;
			}
			return false;
        }

        public function update($table, $particular, $identifier, $fields){
            if(!$this->db->update($table, $particular, $identifier, $fields)){
				throw new Exception("Error Updating Request", 1);
				return true;
			}
			return false;
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

		public function delete($table, $where){
			if(!$this->db->delete($table, $where)){
				return $true;
			}
			return false;
		}

        public function exist(){
            return  (!empty($this->data)) ? true : false;
		}
		
		public function selectAll($table){
            if($this->db->query_builder("SELECT * FROM `{$table}` WHERE 1")) {
                return $this->db->results();
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

        public function logout(){

            Session::delete($this->sessionName);
            Session::delete("accounttype");
            Cookie::delete($this->cookieName);
        }
        
        public function isLoggedIn(){
            return $this->isLoggedIn;
        }







    }

?>