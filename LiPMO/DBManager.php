<?php
	/**
	 * Class for general database management using PDO.
	 * 
	 * @copyright nXu
	 * @author Zsolt Fekete <nXu@nXu.hu>
	 */
	 
	 require_once('Config.php');
	
	class DBManager
	{	
		// PDO object instance
		private $pdo;
		
		/**
		 * 	Initializes a new instance of the DatabaseManager class.
		 * 	@return void
		 */
		public function __construct(){
                    // Create PDO instance
                    $connectionString = "mysql:host=" . LIPMO_SQL_HOST . ";dbname=" . LIPMO_SQL_DATABASE;
                    $this->pdo = new PDO($connectionString, LIPMO_SQL_USER, LIPMO_SQL_PASSWORD);
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		
		/**
		 * Inserts a new entry to a table.
		 * @param string $tableName Name of the table. 
		 * @param array $values Values as an associative array in (Column => Value) format.
		 * @return integer 
		 */
		public function insert($tableName, $values) {
			// Implode the array to (col1, col2, col3)
			$cols = implode(', ', array_keys($values));
			
			// Get the values to another array
			$vals = array_values($values);
			
			// Set placeholders to the query
			$placeholder = "";
			$c = count($vals);
			for ($i = 0; $i < $c; $i++) {
				$placeholder .= "?";
				if ($i < $c - 1) {
					// Not the last element, add comma
					$placeholder .= ", ";
				}
			} 
			
			// Prepare PDO and use placeholder for values
			$statement = $this->pdo->prepare("INSERT INTO $tableName ($cols) VALUES ($placeholder)");
			
			// Bind values, execute query and return the index of the inserted entry
			$statement->execute($vals);
			
			return $this->pdo->lastInsertId();
		}
		
		/**
		 * Selects all entries from a table and fetches it to given entity class instances. 
		 * Can be limited to implement paging.
		 * 
		 * @param string $tableName	Name of the table.
		 * @param string $className Name of the class to fetch to.
		 * @param multitype $start [Optional] Index of the starting element.
		 * @param multitype $limit [Optional] Amount of entries to select.
		 * @return array Fetched entities.
		 */
		public function selectAll($tableName, $className, $start = false, $limit = false) {
			// Create query string
			$queryString = "SELECT * FROM $tableName";
			if (is_int($start) && is_int($limit)) {
				$queryString .= " LIMIT $start, $limit";
			}
			
			// Execute query
			return $this->executeQueryAndFetch($queryString, $className);
		}
		
		/**
		 * Selects entries from a table fetched to a given entity class instance.
		 * 
		 * @param string $tableName Name of the table. 
		 * @param string $className Name of the class to fetch to.
		 * @param string $columns Columns to select.
		 * @param string $criteria Critierias of the selection with named / positional parameters.
		 * @param array  $params Parameter array.
		 * @param multitype $start [Optional] Index of the starting element.
		 * @param multitype $limit [Optional] Amount of entries to select.
		 * @return array Fetched entities.
		 */
		public function select($tableName, $className, $columns, $criteria, $params, $start = false, $limit = false){
			// Criteria should be a PDO-friendly query with positional or named parameters
			// Params should be either a sequential array of values for positional parameters 
			//     or an associative array for named parameters
			
			
			// Create query string
			$queryString = "SELECT $columns FROM $tableName WHERE $criteria"; 
			if (is_int($start) && is_int($limit)) {
				$queryString .= " LIMIT $start, $limit";
			}
			
			// Execute query
			return $this->executeQueryAndFetch($queryString, $className, $params);
		}
		
		/**
		 * Selects entries with the given id from a table fetched to a give entity class instance.
		 * @param unknown $tableName Name of the table.
		 * @param unknown $className Name of the class to fetch to.
		 * @param unknown $id		 Id of the entities to select.
		 * @return multitype Null on error / nothing found, otherwise fetched object.
		 */
		public function selectById($tableName, $className, $id) {
			// Sanitize id
			$id = intval($id);
			
			// Create a query string
			$queryString = "SELECT * FROM $tableName WHERE `id` = $id LIMIT 1";
			
			//Execute query
			return $this->executeQueryAndFetch($queryString, $className);
		}
		
		/**
		 * Deletes an entry with the given id.
		 * @param string $tableName	Name of the table.
		 * @param int $id			Id of the entry to delete.
		 * @return bool True if successful, false on error.
		 */
		public function delete($tableName, $id) {
			// Sanitize id
			$id = intval($id);
			
			// Execute query
			$statement = $this->pdo->prepare("DELETE FROM $tableName WHERE id = $id");
			return $statement->execute();
		}
		
		/**
		 * Updates an entry with the given entry.
		 * @param string $tableName Name of the table.
		 * @param array $values     Key-value pairs of the values to update.
		 * @return True on success, false on failure
		 */
		public function update($tableName, $values) {
			// Create the string
			$setStrings = "";
			
			// Cut the id
			$uid = intval($values["id"]);
			unset($values["id"]);
			
			// Create query string with named parameters
			foreach ($values as $key => $val) {
				if($key == "id") {
					continue;
				}
				
				$setStrings .= "$key = :$key, ";
			}
			
			// Cut the last ", "
			$setStrings = substr($setStrings, 0, -2);
			
			// Execute query
			$statement = $this->pdo->prepare("UPDATE $tableName SET $setStrings WHERE id = $uid");
			return $statement->execute($values);
		}
		
		/**
		 * Executes a query with given parameters and fetches the result to a given class.
		 * 
		 * @param string $queryString Query string.
		 * @param sring $className Name of the class to fetch to.
		 * @param array $params Parameter array (can be null if no parameters given).
		 * @return multitype Query result.
		 */
		private function executeQueryAndFetch($queryString, $className, $params = null) {
			$statement = $this->pdo->prepare($queryString);
			if ($params != null) {
				$statement->execute($params);
			}
			else {
				$statement->execute();
			}
				
			return $statement->fetchAll(PDO::FETCH_CLASS, $className);
		}
	}
	
?>
