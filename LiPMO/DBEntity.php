<?php	
	/**
	 * Abstract class representing an entity stored in 
	 * a database (= a table).
	 * 
	 * @copyright nXu
	 * @author Zsolt Fekete <nXu@nXu.hu>
	 */

	// Includes
	include_once('DBManager.php');

	abstract class DBEntity {
		public $tableName;
		public $id;
		
		/**
		 * Inserts the entity into the database.
		 * @return multitype Id of the inserted entity if successful, otherwise false.
		 */
		public function insert() {
			$dbm = new DBManager();
			
			return $dbm->insert($this->tableName, $this->toKeyValuePairs(false));
		}

		/**
		 * Selects all entities (can be limited) from the database.
		 * @param int $start Starting index of the entities to select.
		 * @param int $limit Amount of entities to select.
		 *
		 * @return array Array of all selected entities.
		 */
		public function selectAll($start = false, $limit = false) {
			$dbm = new DBManager();
			
			return $dbm->selectAll($this->tableName, get_class($this), $start, $limit);
		}
		
		/**
		 * Selects an entity by its id.
		 * @param int $id Id of the entity to select.
		 * @return multitype Selected entity or null.
		 */
		public function selectById($id){
			$dbm = new DBManager();
			
			$retVal = $dbm->selectById($this->tableName, get_class($this), $id);

			if (count($retVal) == 1) {
				return $retVal[0];
			}
			else {
				return null;
			}
		}
		
		/**
		 * Selects all entities that match the defined condition.
		 * 
		 * @param string $condition Condition in string format.
		 * @param array $params Parameter array.
		 * @param multitype $start 	Starting index of the results or false to list all.
		 * @param multitype $limit  Limit of the result amount or false to list all.
		 * @return array All entities matching the defined condition or null when nothing found.
		 */
		public function select($condition, $params, $start = false, $limit = false) {
			$dbm = new DBManager();
			
			$retVal = $dbm->select($this->tableName, get_class($this), "*", $condition, $params, $start, $limit);

			if (count($retVal) == 1) {
				return $retVal[0];
			}
			elseif (count($retVal) == 0) {
				return null;
			}
			else {
				return $retVal;
			}
		}
		
		/**
		 * Deletes the entity from the database.
		 * @return bool True if successful, false on failure.
		 */
		public function delete() {
			$dbm = new DBManager();
			return $dbm->delete($this->tableName, $this->id);
		}
		
		/**
		 * Updates the entity in the database.
		 * @return bool True if successful, otherwise false.
		 */
		public function update() {
			// Check if its in the database
			if ($this->selectById($this->id) == null) {
				return false;
			}
			
			// Update it
			$dbm = new DBManager();
			return $dbm->update($this->tableName, $this->toKeyValuePairs(true));
		}
		
		/**
		 * Converts the entity to an array of key-value pairs with the
		 * database column titles as keys.
		 * @param bool $includeId A value indicating whether the id should be 
		 * 					      included in the array.
		 * @return array Array of key-value pairs which can be used in database 
		 *               operations.
		 */
		private function toKeyValuePairs($includeId) {
			$retVal = (array) $this;
			
			// Remove the tableName property
			unset($retVal["tableName"]);
			
			return $retVal;
		}

	}
?>