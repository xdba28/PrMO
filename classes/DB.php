<?php

    class DB{
        public static $instance = null;
        private $pdo,
                $query,
                $error = false,
                $results,
                $error_info,
                $count = 0;
        

        private function __construct(){         //connection to the database
            try{
				$this->pdo =  new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),  config::get('mysql/username'),  config::get('mysql/password'));
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                die($e->getMessage());
            }
        }


        public static function getInstance(){       //self instance of DB class
            if (!isset(self::$instance)) {
                self::$instance = new DB();
            }
            return self::$instance;
        }

//===================================QUERY BUILDERS====================================

        public function query_builder($sql, $params = array()){       //query builder
            $this->error = false;

            if ($this->query = $this->pdo->prepare("$sql")){
                $x = 1;
                if (count($params)) {
                    foreach ($params as $param) {
                        $this->query->bindValue($x,$param);
                        $x++;
                    }
				}

				$statement =  substr($sql, 0, 6);
				if($statement == "SELECT"){	//this resolves the problem caught in the PDOException related to using fetchAll unnecessarily
					if ($this->query->execute()){ //this return errors when executing insert or update becase this fetches data based on the query while on the other hand you have nothing to fetch when your query is insert or update
						$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
						$this->count = $this->query->rowCount();
					}else{
						$this->error = true;
					}
				}else{
					if ($this->query->execute()){ //this return errors when executing insert or update becase this fetches data based on the query while on the other hand you have nothing to fetch when your query is insert or update					
						$this->count = $this->query->rowCount();
					}else{
						$this->error = true;
					}
				}
				

    
                return $this;
            }
        }


        public function action($action, $table, $where = array()){

            if(count($where)){
                $operators = ['=', '>', '<', '>=', '<='];

                $field      = $where[0];
                $operator   = $where[1];
                $value      = $where[2];

                if(in_array($operator, $operators)){
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                    if(!$this->query_builder($sql, array($value))->error()){
                        return $this;
                    }
                }
            }
                return false;
        }
//===================================QUERY BUILDERS END=================================
//================================FOUR ESSENTIAL FUNCTIONS==============================
    public function get($table,$where){
        return $this->action('SELECT *',$table,$where);
    }

    public function delete($table,$where){
        return $this->action('DELETE',$table,$where);
    }

    public function insert($table, $fields = array()){
        if(count($fields)){

            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach($fields as $field){

                $values.='?';
                
                    if($x < count($fields)){
                        $values.=', ';
                    }

                $x++;

            }

            $sql = "INSERT INTO {$table} (`". implode('`, `', $keys) ."`) VALUES ({$values})";
           
             if(!$this->query_builder($sql, $fields)->error()){
                 return true;
             }
            
        }
        return false;
    }

    public function update($table, $particular, $identifier, $fields){
        if(count($fields)){
            $column = '';
            $x = 1;

            foreach($fields as $name => $value){
                $column .= "{$name} = ?";
                    if($x < count($fields)){
                        $column .= ', ';
                    }
                $x++;
			}
		

        $sql = "UPDATE {$table} SET {$column} WHERE {$particular} = '{$identifier}'";

            if(!$this->query_builder($sql, $fields)->error()){
                return true;
            }
        }
        return false;
        
    }

//==============================FOUR ESSENTIAL FUNCTIONS END============================

	// basic counting
	public function basic_count($table,$where){
		return $this->action("SELECT count(*) as 'counted_result'",$table,$where);
	}




		public function startTrans(){
			return $this->pdo->beginTransaction();
		}

		public function endTrans(){
			return $this->pdo->commit();
		}

		public function lastId(){
			return $this->pdo->lastInsertId();
		}

        public function results(){
            return $this->results;
        }
        public function error(){
            return $this->error;
        }
        public function count(){
            return $this->count;
        }
        public function first(){
            return $this->results()[0];
        }

    }


?>