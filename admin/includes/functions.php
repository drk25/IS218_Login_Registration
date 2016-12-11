<?php
	
	
	//for scanning application and find any undeclared user
	//passes by $class 
	function classAutoLoader($class) {
		
		$class = strtolower($class);
		
		$path = "includes/{$class}.php";		

	if(is_file($path) && !class_exists($class)) {
		include $path;
	}
		
//		if(file_exists($path)) {
//			require_once($path);
//			
//		}
//		else {
//			die(" This file name {$class}.php was not found.");
//		}
	}
	
	spl_autoload_register('classAutoLoader');
	
	
	function redirect($location) {
		header("Location: {$location}");
	}
?>