<?php
	/**
	 *  Represents a sample entity.
	 * 
	 * @copyright nXu
	 * @author Zsolt Fekete <nXu@nXu.hu>
	 */
	 
	// Includes
	include_once 'DBEntity.php';
	
	class Sample extends DBEntity {
	
		/**
		 * Initializes a new instance of the Sample class.
		 * @return void;
		 */
		public function __construct() {
			$this->tableName = "sample";
		}
	
		// Properties (table columns)
		public $sampleName;
		public $sampleText;
		public $date;
	}
?>