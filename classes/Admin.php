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

		// mosty used to check if a PR/JO form is already registered as a project
		public function like($table, $column, $key){
			if($this->db->query_builder("SELECT * FROM `{$table}` WHERE {$column} LIKE '%{$key}%'")){
				if($this->db->count()){
					return $this->db->first();
				}
				return false;
			}
			return false;
		}

		//using like
		public function searchProject($key){

			if($this->db->query_builder("SELECT * FROM `projects` WHERE project_ref_no LIKE '%{$key}%' OR project_title LIKE '%{$key}%'")){
				return $this->db->results();
			}
			return false;

		}

		public function logLastUpdated($ID){ //to get the data when the last update of the project
			if($this->db->query_builder("SELECT *, COUNT(*) as 'result' FROM `project_logs` WHERE referencing_to = '{$ID}' GROUP BY ID ORDER BY logdate DESC")){
				return $this->db->first();
			}
			return false;
		}

		//project details important updates
		public function importantUpdates($ID){ 
			if($this->db->query_builder("SELECT * FROM `project_logs` WHERE (remarks LIKE 'ISSUE%' OR remarks LIKE 'AWARD%' OR remarks LIKE 'SOLVE%') AND referencing_to ='{$ID}' ORDER BY logdate DESC")){
				if($this->db->count()){
					return $this->db->results();
				}else{
					return false;
				}
				
			}
			
		}	
		
		public function projectHistory($originRefno, $currentRefno){

			$noOfOrigin = count($originRefno);

			if($noOfOrigin > 1){
					$imploded = implode("' OR referencing_to = '", $originRefno);
					$filteredSql = "referencing_to ='" .$imploded. "'";
			}else{
				$filteredSql = "referencing_to = '{$originRefno[0]}'";
			}

			if($this->db->query_builder("SELECT referencing_to, remarks, logdate, project_logs.type
			FROM `projects`, `project_logs`
			WHERE $filteredSql OR
			referencing_to = '{$currentRefno}' GROUP BY ID ORDER BY logdate DESC
			")){
				return $this->db->results();
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
			$this->db->query_builder("SELECT message, datecreated, seen, href FROM `notifications` WHERE recipient = '{$user}' OR recipient = 'group5' ORDER BY ID DESC");
			$notifList = $this->db->results();
			$this->db->query_builder("SELECT COUNT(seen) as seen FROM `notifications` WHERE recipient = '{$user}' and seen = '0'");
			$nofitCount = $this->db->first();
			$notif = [
				'list' => $notifList,
				'count' => $nofitCount
			];
			return $notif;
		}

		// resort-items project
		public function projectDetails($id){
			$this->db->query_builder("SELECT request_origin, project_title, ABC, MOP, end_user FROM `projects` WHERE project_ref_no = '{$id}'");
			$project = $this->db->first();

			$pj_id = json_decode($project->request_origin);

			foreach($pj_id as $val){
				$this->db->query_builder("SELECT request_origin, lot_id, lot_title, lot_cost, note, type FROM `project_request_forms`, `lots`
					WHERE project_request_forms.form_ref_no = lots.request_origin
					AND request_origin = '{$val}'");
				$pj_details = $this->db->results();

				$lot = null;
				foreach($pj_details as $a){
					if($a->type === "PR"){
						$this->db->query_builder("SELECT ID, stock_no, unit, item_description, quantity, unit_cost, total_cost 
							FROM `lot_content_for_pr`, `lots`
							WHERE lot_content_for_pr.lot_id_origin = lots.lot_id
							AND lot_id_origin = '{$a->lot_id}'");
						$lot_details = $this->db->results();

						$type = $a->type;
	
						$l_details = null;
						foreach($lot_details as $b){
							$l_details[] = [
								'id' => $b->ID,
								'stock_no' => $b->stock_no,
								'unit'=> $b->unit,
								'desc' => $b->item_description,
								'qty' => $b->quantity,
								'uCost' => $b->unit_cost,
								'tCost' => $b->total_cost
							];
						}
					}elseif($a->type === "JO"){
						$this->db->query_builder("SELECT ID, header, tags
							FROM `lot_content_for_jo`, `lots`
							WHERE lot_content_for_jo.lot_id_origin = lots.lot_id
							AND lot_id_origin = '{$a->lot_id}'");
						$lot_details = $this->db->results();

						$type = $a->type;
	
						$l_details = null;
						foreach($lot_details as $b){
							$l_details[] = [
								'id' => $b->ID,
								'header' => $b->header,
								'tags' => $b->tags
							];
						}
					}

					$lot[] = [
						'l_id' => $a->lot_id,
						'l_title' => $a->lot_title,
						'l_cost' => $a->lot_cost,
						'l_note' =>$a->note,
						'lot_items' => $l_details
					];
				}
	
				$details[] = [
					'type' => $type,
					'refno' => $id,
					'req_origin' => $val,
					'title' => $project->project_title,
					'MOP' => $project->MOP,
					'ABC' => $project->ABC,
					'lots' => $lot
				];
			}
			return $details;
		}

		public function pre_evalDate($gds){
			$this->db->query_builder("SELECT * FROM `project_logs`
				WHERE referencing_to = '{$gds}'
				AND remarks = 'Project details of {$gds} is being evaluated.'
				ORDER BY logdate DESC");
			return $this->db->results();
		}

		// unused
		public function findCanvass($gds, $title, $cost, $mode, $type){
			if($this->db->query_builder("SELECT id FROM `canvass_forms`
				WHERE gds_reference = '{$gds}' 
				AND title = '{$title}' 
				AND cost = '{$cost}'
				AND mop = '{$mode}'
				AND type = '{$type}'")){
					return $this->db->first()->id;
			}else{
				return false;
			}
		}

		public function selectCanvassForm($gds, $title, $canvass_id){
			$this->db->query_builder("SELECT id, gds_reference, title, cost, type, per_item, mop
				FROM `canvass_forms` 
				WHERE gds_reference = '{$gds}'
				AND title = '{$title}'
				AND id = '{$canvass_id}'");

			$canvassDetail = $this->db->first();
			
			if($canvassDetail->type === "PR"){
				$this->db->query_builder("SELECT canvass_items_pr.id as item_id, canvass_forms_id, stock_no, unit, item_description,
					quantity, unit_cost, total_cost, mode, item_fail, awarded
					FROM `canvass_items_pr`, `canvass_forms`
					WHERE canvass_forms.id = canvass_items_pr.canvass_forms_id
					AND canvass_forms_id = '{$canvassDetail->id}'");
				$canvassItems = $this->db->results();
			}elseif($canvassDetail->type === "JO"){
				$this->db->query_builder("SELECT canvass_items_jo.id as item_id, canvass_forms_id, header, tags, mode, item_fail, awarded
					FROM `canvass_items_jo`, `canvass_forms`
					WHERE canvass_forms.id = canvass_items_jo.canvass_forms_id
					AND canvass_forms_id = '{$canvassDetail->id}'");
				$canvassItems = $this->db->results();
			}

			$Details = (object) [
				'CanvassDetails' => $canvassDetail,
				'items' => $canvassItems
			];

			return $Details;
		}

		public function getOffered($gds, $canvass_form_id, $item_id){
			$this->db->query_builder("SELECT cvsp_id, item_id, name, offered, price, canvass_supplier.remark as lot_remark, canvass_quotation.remark as item_remark
				FROM `canvass_forms`, `canvass_supplier`, `canvass_quotation`, `supplier`
				WHERE canvass_forms.id = canvass_supplier.form_id
				AND canvass_supplier.supplier = supplier.s_id
				AND canvass_quotation.supplier_id = canvass_supplier.cvsp_id
				AND gds_reference = '{$gds}'
				AND canvass_forms.id = '{$canvass_form_id}'
				AND item_id = '{$item_id}'");
			return $this->db->results();
		}

		public function getSuppliers($gds, $form_id){
			$this->db->query_builder("SELECT * FROM `canvass_supplier`, `canvass_forms`, `supplier`
				WHERE canvass_forms.id = canvass_supplier.form_id
				AND supplier.s_id = canvass_supplier.supplier
				AND gds_reference = '{$gds}'
				AND form_id = '{$form_id}'
				AND award = '0'");
			return $this->db->results();
		}

		public function abstractSuppliers($gds, $form_id){
			$this->db->query_builder("SELECT * FROM `canvass_supplier`, `canvass_forms`, `supplier`
				WHERE canvass_forms.id = canvass_supplier.form_id
				AND supplier.s_id = canvass_supplier.supplier
				AND gds_reference = '{$gds}'
				AND form_id = '{$form_id}'");
			return $this->db->results();
		}
		

		public function awardUpdateItem($supplier_id, $item_id, $type, $canvass_id){
			if($type === "PR"){
				if($this->db->query_builder("UPDATE canvass_quotation SET award_selected = '1'
					WHERE supplier_id = '{$supplier_id}'
					AND item_id = '{$item_id}'") && $this->db->query_builder("UPDATE canvass_items_pr
						SET awarded = '1' WHERE id = '{$item_id}' AND canvass_forms_id = '{$canvass_id}'")){
					return true;
				}else{
					return false;
				}
			}else if($type === "JO"){
				if($this->db->query_builder("UPDATE canvass_quotation SET award_selected = '1'
					WHERE supplier_id = '{$supplier_id}'
					AND item_id = '{$item_id}'") && $this->db->query_builder("UPDATE canvass_items_jo
						SET awarded = '1' WHERE id = '{$item_id}' AND canvass_forms_id = '{$canvass_id}'")){
					return true;
				}else{
					return false;
				}
			}
		}

		public function checkProjectAward($gds){
			$award = true;
			$this->db->query_builder("SELECT * FROM `canvass_forms` WHERE gds_reference = '{$gds}'");
			$lots = $this->db->results();
			$lot_details = NULL;
			foreach($lots as $lot){
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND form_id = '{$lot->id}'");
				$suppliers = $this->db->results();

				foreach($suppliers as $supplier){
					// echo "<------------Supplier -------------->";
					// echo "<pre>".print_r($supplier)."</pre>";
					if($supplier->per_item == "0"){
						// per lot
						if($supplier->award == "1"){
							$award = true;
							break;
						}
					}else{
						if($supplier->type == "PR"){
							$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_items_pr`
								WHERE canvass_forms.id = canvass_items_pr.canvass_forms_id
								AND canvass_forms_id = '{$supplier->id}'");
							$items = $this->db->results();
							foreach($items as $item){
								// echo "<---------------- Item ------------------>";
								// echo "<pre>".print_r($item)."</pre>";
								if($item->awarded == "0"){
									$award = false;
								}
							}
						}else{
							$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_items_jo`
								WHERE canvass_forms.id = canvass_items_jo.canvass_forms_id
								AND canvass_forms_id = '{$supplier->id}'");
							$items = $this->db->results();
							foreach($items as $item){
								// echo "<---------------- Item ------------------>";
								// echo "<pre>".print_r($item)."</pre>";
								if($item->awarded == "0"){
									$award = false;
								}
							}
						}
					}
				}
			}
			// echo "<------------ Result ----------->".PHP_EOL;
			// echo "<------------ Result ----------->".PHP_EOL;
			// echo "???? : ".$award;
			return $award;
		}

		public function abstract($gds, $id){
			$this->db->query_builder("SELECT * FROM `canvass_forms` 
				WHERE gds_reference = '{$gds}'
				AND id = '{$id}'");
			$canvass = $this->db->first();

			if($canvass->type === "PR"){

				$this->db->query_builder("SELECT canvass_items_pr.id as item_id, canvass_forms_id, unit, item_description as descr, quantity, unit_cost, total_cost, item_fail
					FROM `canvass_forms`, `canvass_items_pr`
					WHERE canvass_forms.id = canvass_items_pr.canvass_forms_id
					AND canvass_forms_id = '{$canvass->id}'");
				$items = $this->db->results();

			}else if($canvass->type === "JO"){

				$this->db->query_builder("SELECT canvass_items_jo.id as item_id, canvass_forms_id, header, tags, mode, item_fail
					FROM `canvass_forms`, `canvass_items_jo`
					WHERE canvass_forms.id = canvass_items_jo.canvass_forms_id
					AND canvass_forms_id = '{$canvass->id}'");
				$items = $this->db->results();

			}

			$this->db->query_builder("SELECT cvsp_id, supplier, remark FROM `canvass_supplier`, `canvass_forms`
				WHERE canvass_supplier.form_id = canvass_forms.id
				AND form_id = '{$canvass->id}'");
			$suppliers = $this->db->results();

			if($canvass->per_item){

				// by item
				foreach($suppliers as $key => $supplier){
					$supplier_prices = [];
					foreach($items as $item){
						$this->db->query_builder("SELECT q_id, item_id, price FROM  `canvass_supplier`, `canvass_quotation`
							WHERE canvass_supplier.cvsp_id = canvass_quotation.q_id
							AND item_id = '{$item->item_id}'
							AND supplier_id = '{$supplier->cvsp_id}'");
						array_push($supplier_prices, $this->db->first());
					}
					$price[$key] = $supplier_prices;
				}

			}else{

				// by lot
				// foreach($suppliers as $key => $supplier){
				// 	$supplier_prices = [];
				// 	foreach($items as $item){
				// 		$this->db->query_builder("SELECT q_id, item_id, price FROM  `canvass_supplier`, `canvass_quotation`
				// 			WHERE canvass_supplier.cvsp_id = canvass_quotation.q_id
				// 			AND item_id = '{$item->item_id}'
				// 			AND supplier_id = '{$supplier->cvsp_id}'");
				// 		array_push($supplier_prices, $this->db->first());
				// 	}
				// 	$price[$key] = $supplier_prices;
				// }

			}


			// $Details = (object) [
			// 	'canvass' => $canvass,
			// 	'items' => $items,
			// 	'supplier' => $suppliers,
			// 	'prices' => $price
			// ];
			
			// return $Details;
			// return $canvass;
		}

		public function awardSupplier($cvsp_id){
			$this->db->query_builder("SELECT * FROM `canvass_supplier`, `supplier`
				WHERE canvass_supplier.supplier = supplier.s_id
				AND cvsp_id = '{$cvsp_id}'");
			return $this->db->first();
		}

		public function getallawardedItems($gds, $supplier_id, $perItem){
			if($perItem){
				// per item
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `canvass_items_pr`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND canvass_forms.id = canvass_items_pr.canvass_forms_id
					AND gds_reference = '{$gds}'
					AND cvsp_id = '{$supplier_id}'
					AND awarded = '1'
					GROUP BY canvass_items_pr.id");
				return $this->db->results();
			}else{
				// per lot
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `canvass_items_pr`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND canvass_forms.id = canvass_items_pr.canvass_forms_id
					AND gds_reference = '{$gds}'
					AND cvsp_id = '{$supplier_id}'
					AND award = '1'
					GROUP BY canvass_items_pr.id");
				return $this->db->results();
			}
		}

		public function getawardedJO($gds, $supplier_id, $perItem){
			if($perItem){
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `canvass_items_jo`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND canvass_forms.id = canvass_items_jo.canvass_forms_id
					AND gds_reference = '{$gds}'
					AND cvsp_id = '{$supplier_id}'
					AND awarded = '1'
					GROUP BY canvass_items_jo.id");
				return $this->db-results();
			}else{
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `canvass_items_jo`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND canvass_forms.id = canvass_items_jo.canvass_forms_id
					AND gds_reference = '{$gds}'
					AND cvsp_id = '{$supplier_id}'
					AND award = '1'
					GROUP BY canvass_items_jo.id");
				return $this->db->results();
			}
		}

		public function getSupplierTotal($gds, $lot){
			$this->db->query_builder("SELECT SUM(price) as total, name, canvass_supplier.remark as remark FROM `canvass_forms`, `canvass_supplier`, `canvass_quotation`, `supplier`
				WHERE canvass_forms.id = canvass_supplier.form_id
				AND canvass_supplier.cvsp_id = canvass_quotation.supplier_id
				AND canvass_supplier.supplier = supplier.s_id
				AND gds_reference = '{$gds}'
				AND canvass_forms.id = '{$lot}'
				GROUP BY canvass_supplier.supplier");
			return $this->db->results();
		}

		public function getPublication($gds, $form_id){
			$this->db->query_builder("SELECT * FROM `canvass_forms`, `projects`
				WHERE canvass_forms.gds_reference = projects.project_ref_no
				AND gds_reference = '{$gds}'
				AND id = '{$form_id}'");
			return $this->db->first();
		}

		public function checkDocuments($gds){
			$this->get('projects', array('project_ref_no', '=', $gds));
			$request = $this->db->first();

			$documents['request'] = [];
			foreach(json_decode($request->request_origin) as $req_doc){

				$this->get('project_request_forms', array('form_ref_no', '=', $req_doc));
				$req_details = $this->db->first();

				array_push($documents['request'], [
					'title' => $request->project_title,
					'ref_no' => $req_doc,
					'type' => $req_details->type
				]);
			}

			if($request->accomplished >= 3){
				$documents['technical'] = true;

				$this->getAll('canvass_forms', array('gds_reference', '=', $gds));
				$canvass_forms = $this->db->results();

				if(count($canvass_forms) !== 0){
					$documents['canvass_forms'] = [];

					foreach($canvass_forms as $key => $canvass){
						array_push($documents['canvass_forms'], [
							'canvass_id' => $canvass->id,
							'title' => $canvass->title,
							'type' => $canvass->type,
							'publication' => []
						]);

						foreach(json_decode($canvass->mop, true) as $key2 => $mop){
							array_push($documents['canvass_forms'][$key]['publication'], [
								'mode_index' => $key2,
								'no' => $mop['no'],
								'mode' => $mop['mode']
							]);
						}


						// check if abstract available
							// print Abstract
						if($request->accomplished >= 5){
							$documents['canvass_forms'][$key]['abstract'] = true;

							// bac reso on award and failure
							// check if all items fail or lot fail
							if($canvass->lot_fail_option === "1"){
								$documents['canvass_forms'][$key]['fail'] = true;
							}

							// find an awarded supplier
							$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `supplier`
								WHERE canvass_forms.id = canvass_supplier.form_id
								AND canvass_supplier.supplier = supplier.s_id
								AND gds_reference = '{$gds}'
								AND canvass_forms.id = '{$canvass->id}'
								AND award = '1'");
							$documents['canvass_forms'][$key]['noa'] = $this->db->results();
							
							

							

							// check if NOA available
								// print NOA
								// print LO / PO
								// print OS
							
						}
					}					
				}
			}
			return $documents;
		}

		public function docOrderItems($gds, $supplier_id, $per_item){
			if($per_item){
				// per item
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `supplier`, `canvass_items_pr`, `canvass_quotation`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND canvass_supplier.supplier = supplier.s_id
					AND canvass_quotation.supplier_id = canvass_supplier.cvsp_id
					AND canvass_forms.id = canvass_items_pr.canvass_forms_id
					AND gds_reference = '{$gds}'
					AND cvsp_id = '{$gds}'
					AND awarded = '1'
					GROUP BY canvass_items_pr.id");
				return $this->db->results();
			}else{
				// lot
				$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `supplier`, `canvass_items_pr`, `canvass_quotation`
					WHERE canvass_forms.id = canvass_supplier.form_id
					AND canvass_supplier.supplier = supplier.s_id
					AND canvass_quotation.supplier_id = canvass_supplier.cvsp_id
					AND canvass_forms.id = canvass_items_pr.canvass_forms_id
					AND gds_reference = '{$gds}'
					AND cvsp_id = '{$supplier_id}'
					AND award = '1'
					GROUP BY canvass_items_pr.id");
				return $this->db->results();
			}
		}

		public function getDeliveryDetails($gds, $supplier_id){
			$this->db->query_builder("SELECT * FROM `canvass_forms`, `canvass_supplier`, `award`
				WHERE canvass_forms.id = canvass_supplier.form_id
				AND canvass_forms.id = award.canvass_form_id
				AND gds_reference = '{$gds}'
				AND cvsp_id = '{$supplier_id}'");
			return $this->db->first();
		}
		
		// request-gen.php PRINTING OF REQUEST FORM
		public function requestDetails($id){
			$this->db->query_builder("SELECT form_ref_no, title, purpose, requested_by, date_created FROM `project_request_forms` WHERE form_ref_no = '{$id}'");
			$project = $this->db->first();

			$this->db->query_builder("SELECT edr_id, edr_fname, edr_mname, edr_lname, 
				edr_ext_name, acronym, office_name, edr_job_title, edr_email, phone
				FROM `enduser`, `units`
				WHERE enduser.edr_designated_office = units.ID AND edr_id = '{$project->requested_by}'");
			$enduserDetails = (array) $this->db->first();

			$this->db->get("units", array("office_name", "=", $enduserDetails['office_name']));
			$officeSignatories = (array) $this->db->first();

			$enduserDetails['signatories'] = $officeSignatories;

			
			$this->db->query_builder("SELECT request_origin, lot_id, lot_title, lot_cost, note, type FROM `project_request_forms`, `lots`
				WHERE project_request_forms.form_ref_no = lots.request_origin
				AND request_origin = '{$project->form_ref_no}'");
			$pj_details = $this->db->results();

			$lot = null;
			foreach($pj_details as $a){
				if($a->type === "PR"){
					$this->db->query_builder("SELECT ID, stock_no, unit, item_description, quantity, unit_cost, total_cost 
						FROM `lot_content_for_pr`, `lots`
						WHERE lot_content_for_pr.lot_id_origin = lots.lot_id
						AND lot_id_origin = '{$a->lot_id}'");
					$lot_details = $this->db->results();

					$type = $a->type;

					$l_details = null;
					foreach($lot_details as $b){
						$l_details[] = [
							'id' => $b->ID,
							'stock_no' => $b->stock_no,
							'unit'=> $b->unit,
							'desc' => $b->item_description,
							'qty' => $b->quantity,
							'uCost' => $b->unit_cost,
							'tCost' => $b->total_cost
						];
					}
				}elseif($a->type === "JO"){
					$this->db->query_builder("SELECT ID, header, tags
						FROM `lot_content_for_jo`, `lots`
						WHERE lot_content_for_jo.lot_id_origin = lots.lot_id
						AND lot_id_origin = '{$a->lot_id}'");
					$lot_details = $this->db->results();

					$type = $a->type;

					$l_details = null;
					foreach($lot_details as $b){
						$l_details[] = [
							'id' => $b->ID,
							'header' => $b->header,
							'tags' => $b->tags
						];
					}
				}

				$lot[] = [
					'l_id' => $a->lot_id,
					'l_title' => $a->lot_title,
					'l_cost' => $a->lot_cost,
					'l_note' =>$a->note,
					'lot_items' => $l_details
				];
			}

			$details = [
				'type' => $type,
				'refno' => $id,
				'title' => $project->title,
				'lots' => $lot,
				'purpose' => $project->purpose,
				'end_user' => $enduserDetails,
				'date' => $project->date_created
			];
			
			return $details;
		}

		public function ongoing_projects(){
			if($this->db->query_builder("SELECT * FROM `projects` WHERE project_status = 'PROCESSING' OR project_status = 'PAUSED'")){
				if($this->db->count()){
					return $this->db->results();
				}
				return false;
			}
			return false;
		}

		// used for recomputing PR lots costs
		public function recompute($origin, $lot){
			if($this->db->query_builder("SELECT 
			SUM(total_cost) as 'recomputed_total'
			
			FROM 
			`lots`, `lot_content_for_pr`
			WHERE lot_content_for_pr.lot_id_origin = lots.lot_id AND
			lot_no = '{$lot}' AND request_origin = '{$origin}'")){
				return $this->db->first()->recomputed_total;
			}
			return false;
		}		

		public function dashboardReports($ID = null){
			$reports = [];

			// List of current projects (processing and paused)
			if($this->db->query_builder("SELECT * FROM `projects` WHERE project_status = 'PROCESSING' OR project_status = 'PAUSED'")){
				if($this->db->count()){
					$reports["current_projects"] = $this->db->results();
				}
			}

			//all revision requests of revision requests
			if($this->selectAll("form_update_requests")){
				if($this->db->count()){
					$reports["revision_requests"] = $this->db->results();
				}
			}

			// documents in the outgoing queue
			if($this->selectAll("outgoing")){
				if($this->db->count()){
					$reports["outgoing"] = $this->db->results();
				}
			}

			// documents in the outgoing register queue
			if($this->selectAll("outgoing_register")){
				if($this->db->count()){
					$reports["released"] = $this->db->results();
				}
			}

			// logs created today
			$dateToday = date('Y-m-d');
			if($this->db->query_builder("SELECT * FROM `project_logs` WHERE logdate LIKE '{$dateToday}%'")){
				if($this->db->count()){
					$reports["logs_today"] = $this->db->results();
				}

			}

			// all project request forms
			if($this->selectAll("project_request_forms")){
				if($this->db->count()){
					$reports["request_forms"] = $this->db->results();
				}
			}
			


			



			if(!empty($reports)){
				return $reports;
			}



		}

		// modal pre procurement evaluation registration
		public function checkProjectIssue($id){
			if($this->db->query_builder("SELECT remarks FROM `project_logs`
			WHERE remarks LIKE '%ISSUE^pre-procurement%' AND referencing_to = '{$id}'")){
				if($this->db->count()){
					return $this->db->results();
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getContent($refno, $type, $lot){
			if($type == "PR"){
				if($this->db->query_builder("SELECT lot_id, ID, lot_no as 'from_lot', lot_title, ID as 'identifier', lot_id_origin, stock_no, unit, item_description, quantity, unit_cost, total_cost
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
				if($this->db->query_builder("SELECT lot_id, ID, lot_no as 'from_lot',  lot_title, ID as 'identifier', lot_id_origin, header, tags, note, lot_cost
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

		public function evaluation($ID){
			if($this->db->query_builder("SELECT *, DATEDIFF(implementation_date, date_registered) as 'remaining_days' FROM `projects` WHERE proposed_evaluator = '{$ID}' AND accomplished < 3")){
				if($this->db->count()){
					return $this->db->results();
				}else{
					return 0;
				}
			}
			return false;
		}

		public function count($table, $where){
			if($this->db->basic_count($table, $where)){
				if($this->db->count()){
					return $this->db->first();
				}
				return false;
			}
			return false;
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
				if($this->db->count()){
					return $this->db->first();
				}
				return false;
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

		public function lastId(){
			return $this->db->lastId();
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