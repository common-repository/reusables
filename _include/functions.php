<?php

function get_roles($name = true){

	$wp_user_roles = get_editable_roles();
	
	if($name === false){
	
		if(!empty($wp_user_roles)){
			foreach($wp_user_roles as $role => $info){
				$roles[$role] = $role;
			}
		}
		
		$roles["guest"] = "guest";
		
	} else {
	
		if(!empty($wp_user_roles)){
			foreach($wp_user_roles as $role => $info){
				$roles[$role] = $info["name"];
			}
		}
		
		$roles["guest"] = "Guest";
		
	}
	
	return $roles;
	
}


function get_currentuser_roles(){
	
	if(is_user_logged_in()){
	
		global $current_user;
		get_currentuserinfo();
		$roles = array();
			
		foreach($current_user->roles as $key => $role){
			$roles[] = $role;
		}
	
	} else {
		
		$roles = array("guest");
		
	}
	
	return $roles;
	
}

/**

	get_reus()
	
	Template function that returns the reusable object.

*/
function get_reus($id, $meta = null){

	global $reusables;
	return $reusables->get_reus($id, $meta);

}

/**

	reus()
	
	Template function that outputs the content of the reusable.

*/
function reus($id, $meta = null, $echo = true){

	global $reusables;	
	if($echo == false){
		return $reusables->reus($id, $meta, false);
	} else {
		$reusables->reus($id, $meta);	
	}
	
}

/**

	add_reus()
	
	Template function that allows users to create reusables, returning its id.

*/
function add_reus($name, $content, $roles = null, $author = null){

	global $reusables;
	return $reusables->add_reus($name, $content, $roles, $author);

}
?>