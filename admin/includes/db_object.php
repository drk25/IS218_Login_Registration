<?php
//parent class
	class Db_object {
		
		public $errors = array();
		public $upload_errors = array(
		0        => "There is no error",
		1   	 => "The upload file exceeds the upload_max_filesize directive in php.ini",
		2  		 => "The upload file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
		3    	 => "The upload file was only partially uploaded.",
		4        => "No file was uploaded.",
		6		 => "Missing a temporary folder.",
		7 		 => "Failed to write file to disk.",
		8	     => "A PHP extension stopped the file upload."
		);


			
public static function find_all(){
	global $database;
	return static::find_by_query("SELECT * FROM ". static::$db_table. " ");
					
}
				
public static function find_by_id($id){
	global $database;
	$result_array = static::find_by_query("SELECT * FROM ". static::$db_table. " WHERE id = $id LIMIT 1");
		
	//tenary behaviour
	//if result_array != empty then "?" return array_shift(result_array) else ":" return false 
	//return !empty($result_array) ? array_shift($result_array) : false;
	
	if(!empty($result_array)) {
		$first_item = array_shift($result_array);
		return $first_item;
	}
	else {
		return false;
	}
			
}

public static function find_by_query($sql) {
	global $database;
	
	$result_set = $database->query($sql);
	
	$object_array = array();
	
	//fetch db return each records adds into row and pushes inside $object_array
	while ($row = mysqli_fetch_array($result_set)) {
		$object_array[] = static::instantation($row);
	}
	return $object_array;	
}

public static function instantation($the_record) {
	
	//use get_called_class
	$calling_class  = get_called_class();
	
	//cant instantiate this using self
	$object = new $calling_class;
	//for each record
//	$object->id = $found_user['id'];

//loops through each records and assigns to $boject	
	foreach ($the_record as $attribute => $value) {
		//to make sure it has the values
		if($object->has_the_attribute($attribute)) {
			//attribute=username value=password
			$object->$attribute = $value;
		}
	}
	return $object;
}		
private function has_the_attribute($attribute) {
	//pick all the attributes from the top
	$object_properties = get_object_vars($this);
		
	//return T/F if attribute exists in $object_properties
	return array_key_exists($attribute, $object_properties);
}

protected function properties() {
	//to returns all the properties from the top
	$properties = array();
	
	foreach (static::$db_table_fields as $db_field) {
		
		if(property_exists($this, $db_field)) {
			$properties[$db_field] = $this->$db_field;
		}
	}
	return $properties;
}

protected function clean_properties() {
	global $database;
//loop through all properties(db_table_fields) and assign it to $clean_properties array.	
	$clean_properties = array();
	foreach ($this->properties() as $key => $value) {
		$clean_properties[$key] = $database->escape_string($value);
		
	}
	return $clean_properties;
}




public function save() {
	// check to see if id is in db returns to update method else create method
	return isset($this->id) ? $this->update() : $this->create();
}

//CRUD Method
//create read update delete
public function create() { 
	global $database;
	
	$properties = $this->clean_properties();
	/*
seperating each keys with coma built in function implode 
array_keys built in function to pull keys names 
as properties which are on the top
	*/
	$sql  = "INSERT INTO " .static::$db_table. "(". implode(",", array_keys($properties)).")";
	$sql .= "VALUES ('".implode("', '", array_values($properties)) ."')";
	//if query is true insert new id and return true
	//this function is in database.php
	if($database->query($sql)) {
		$this->id = $database->insert_id();
		return true;
	} else {
		
		return false;
	}

}

public function update() { 
	
	global $database;
	$properties = $this->clean_properties();
	
	$properties_pairs = array();
	foreach ($properties as $key => $value) {
		$properties_pairs[] = "{$key}='{$value}'";
	} 

	$sql  = "UPDATE  " .static::$db_table. " SET ";
	$sql .= implode(",", $properties_pairs ); 
	$sql .= " WHERE id= " .$database->escape_string($this->id);

	$database->query($sql);
	//make sure fields were affected and/or modified is true ? return t : f
	return (mysqli_affected_rows($database->connection) == 1) ? true : false;
}

public function delete() {
	global $database;
	
	$sql = "DELETE FROM ".static::$db_table." ";
	$sql .= "WHERE id=". $database->escape_string($this->id);
	$sql .= " LIMIT 1";
	
	$database->query($sql);

	return (mysqli_affected_rows($database->connection) == 1) ? true : false;
}
public static function count_all() { 
	global $database;
	
	$sql = "SELECT COUNT(*) FROM ".static::$db_table;
	$result_set = $database->query($sql);
	$row = mysqli_fetch_array($result_set);
	
	return array_shift($row);
	
	}
		
}//end class
?>