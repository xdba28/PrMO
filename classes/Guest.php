<?php

    class Guest{

        private $db = null,
                $data;


        public function __construct(){  //should be copied in user.php class * always inst
            $this->db = DB::getInstance();
        }        

        public function AllUnits(){
            if ($this->db->query_builder("SELECT * FROM units")) 
            return $this->db->results();
        }

        public function request($table, $fields = array()){
            if(!$this->db->insert($table, $fields)){
                throw new Exception('There was a problem registering request');
            }

        }
        
        






        public function data(){
            return $this->data();
		}
		
		public function startTrans(){
			$this->db->startTrans();
		}

		public function endTrans(){
			$this->db->endTrans();
		}

    }




?>