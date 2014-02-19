<?php
	/**
	 *  Include list.
	 * 
	 * @copyright nXu
	 * @author Zsolt Fekete <nXu@nXu.hu>
	 */

	$dir = new DirectoryIterator(dirname(__FILE__));
	foreach ($dir as $fileinfo) {
		if (!$fileinfo->isDot()) {
			$filename = $fileinfo->getFilename();
			if($filename != "IncludeList.php"){
				require_once($filename);
			}
		}
	}
?>