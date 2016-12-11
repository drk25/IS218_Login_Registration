<?php

	
class User extends Db_object {
	//abstract the table name	
	protected static $db_table = "users";
	protected static $db_table_fields = array('username', 'password', 'first_name', 'last_name','user_image');
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $user_image;
	public $upload_directory = "images";
	public $image_placeholder = "http://placehold.it/400x400&text=image";
	
	
	public function set_file($file) {
				
		if(empty($file) || !$file || !is_array($file)) {
			$this->errors[] = "There was no file uploaded here.";
			return false;
		}
			//0 = there is no error. 
		 elseif($file['error'] !=0) {
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		} else {
			
			$this->user_image = basename($file['name']);
			$this->type     = $file['type'];
			$this->tmp_path = $file['tmp_name'];
			$this->size     = $file['size'];
		}
		
	}
	
	public function save_user_image() {
			if($this->id) {
				$this->update();
			} else {
				
				if(!empty($this->errors)) {
					return false;
				}
				if(empty($this->user_image) || empty($this->tmp_path)) {
					$this->errors[] = "The file was not available";
					return false;
				}
				//target path  admin/images/user_image.jpg
				$target_path = SITE_ROOT . DS . 'admin'. DS . $this->upload_directory . DS . $this->user_image;

				if(file_exists($target_path)) {
					$this->errors[] = "The file {$this->user_image} already exists.";
					return false;
				}


				if(move_uploaded_file($this->tmp_path, $target_path)) {
					if($this->create()) {
						unset($this->tmp_path);
						return true;
					}
				} else {
					$this->errors[] = "The file directory does not have permission.";
					return false;
				}
			}
	}

	
	public function upload_photo() {
			
		if(!empty($this->errors)) {
			return false;
		}
		if(empty($this->user_image) || empty($this->tmp_path)) {
			$this->errors[] = "The file was not available";
			return false;
		}
		//target path  admin/images/user_image.jpg
		$target_path = SITE_ROOT . DS . 'admin'. DS . $this->upload_directory . DS . $this->user_image;
		
		if(file_exists($target_path)) {
			$this->errors[] = "The file {$this->user_image} already exists.";
			return false;
		}
		
		if(move_uploaded_file($this->tmp_path, $target_path)) {
			unset($this->tmp_path);
			return true;
		} else {
			$this->errors[] = "The file directory does not have permission.";
			return false;
		}
	}

	
	public function image_path_placeholder () {
		//if no user image then put a placeholder else go to images and place img
		return empty($this->user_image) ? $this->image_placeholder : $this->upload_directory. DS . $this->user_image;
	}
	
	
		


public static function verify_user($username, $password) {
	global $database;
	
	$username = $database->escape_string($username);
	$password = $database->escape_string($password);
	
	$sql  = "SELECT * FROM ". self::$db_table. " WHERE ";
	$sql .= "username = '{$username}' ";
	$sql .= "AND password = '{$password}' ";	
	$sql .= "LIMIT 1";
	
	$result_array = self::find_by_query($sql);
	//if not empty do array shift else return false
		return !empty($result_array) ? array_shift($result_array) : false;	
 
}

public static function create_user($first_name, $last_name, $username, $password) {
	
	global $database;
	
	$first_name = $database->escape_string($first_name);
	$last_name  = $database->escape_string($last_name);
	$username   = $database->escape_string($username);
	$password   = $database->escape_string($password);
	
	$sql = "INSERT INTO " . self::$db_table . " (first_name, last_name, username, password)";
	$sql .= " VALUES('$first_name','$last_name','$username','$password')";
	
	$result = $database->query($sql);
	
	return $result ? TRUE : FALSE;
	
}


public function ajax_save_user_image($user_image, $user_id) {
	$this->user_image = $user_image;
	$this->id = $user_id;
	$this->save();
	global $database;
	
	$user_image = $database->escape_string($user_image);
	$user_id = $database->escape_string($user_id);
	
	$sql  = "UPDATE ". self::$db_table . " SET user_image = '{$this->user_image}' ";
	$sql .= " WHERE id = {$this->id} ";
	$update_image = $database->query($sql);
	
	echo $this->image_path_placeholder(); 
	
	
}
public function delete_photo() {
	if($this->delete()) {
		$target_path = SITE_ROOT.DS. 'admin' . DS .$this->upload_directory. DS . $this->user_image;
		return 	unlink($target_path) ? true : false;

	} else {
		return false;
	}
}
}//class end
	
	
?>