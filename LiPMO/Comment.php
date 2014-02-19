<?php
	/**
	 *  Represents a sample entity.
	 * 
	 * @copyright nXu
	 * @author Zsolt Fekete <nXu@nXu.hu>
	 */
	 
	// Includes
	include_once 'DBEntity.php';
	
	class Comment extends DBEntity {
	
		/**
		 * Initializes a new instance of the Comment class.
		 * @return void;
		 */
		public function __construct() {
			$this->tableName = "comments";
		}
	
		// Properties (table columns)
		public $problemId;
		public $userId;
		public $date;
		public $comment;
	}
?>