<?php

//Register the autoload feature.
spl_autoload_register('__autoLoadSearch');

//Basedir directory.
$baseDirsAutoLoad = array('./bl', './includes', './layout');

/**
 * Searches in a recursive way alle base dirs and its child dirs for an matching classname.
 * Enter description here ...
 * @param string $className Classname to match.
 */
function __autoLoadSearch($classname) {
	//Get the global base dir autoload.
	LoggerManager::writeVerbose("Starting search for $classname");
	global $baseDirsAutoLoad;
	foreach($baseDirsAutoLoad as $baseDir) {
		//Return if success.
		if(iterClassSearch($classname, $baseDir) == true) 
			return;
			

	}
}

function iterClassSearch($classname, $dir) {
	foreach(scandir($dir) as $dirItem) {	
		//Does the classname match the dir item?		
		if("$classname.php" == $dirItem) {
			//Load the file
			LoggerManager::writeVerbose("Found $classname... Trying to include it!");
			include_once "$dir/$dirItem";
			//Done, returning success.
			return true;
		}
		elseif($dirItem !== '.' && $dirItem !== '..' && is_dir("$dir/$dirItem")) {
			//Found something in the sub dir, returning success.
			//LoggerManager::writeVerbose("Going into dir $dir/$dirItem..."); 
			if(iterClassSearch($classname, "$dir/$dirItem") == true) {
				return true;
			}
		}
	}
	//Nothing found, returning false to continue the search.
	return false;
}

?>