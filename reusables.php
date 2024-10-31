<?php
/*
Plugin Name: WordPress Reusables
Plugin URI: http://www.ooeygui.net
Description: Manage content more efficiently by storing and editing in a single location on WordPress. Allow non-technical users to update site elements without touching theme files.
Version: 3.1.0
Author: Ian Whitcomb
Author URI: http://www.ooeygui.net
*/

global $wpdb;

include_once("_include/class.wp-plugins.php");
include_once("_include/functions.php");


/**

	Reusables Class
	
	This is the reusables plugin main class.

*/

class Reusables extends Plugin {


	/**
	
		activate()
		
		What to do when we activate the plugin.
	
	*/
	public function activate(){
	
		// Setup the database.
		global $wpdb;
				
		if(@is_file(ABSPATH."/wp-admin/upgrade-functions.php")) {
			include_once(ABSPATH."/wp-admin/upgrade-functions.php");
		} elseif(@is_file(ABSPATH."/wp-admin/includes/upgrade.php")) {
			include_once(ABSPATH."/wp-admin/includes/upgrade.php");
		} else {
			die("Unable to locate \"wp-admin/upgrade-functions.php\" and/or \"wp-admin/includes/upgrade.php\"");
		}
			
		$charset_collate = "";
		if($wpdb->supports_collation()) {
		
			if(!empty($wpdb->charset)) {
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			
			if(!empty($wpdb->collate)) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
			
		}
						
		if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."reus_roles'") != $wpdb->prefix."reus_roles") {
			
			$tbl["reus_roles"] = "CREATE TABLE ".$wpdb->prefix."reus_roles (" .
				"id bigint(20) NOT NULL auto_increment," .
				"reus_id bigint(20) NOT NULL," .
				"role varchar(64) NOT NULL," . 
				"PRIMARY KEY  (id)" .
				") $charset_collate;";
				
			dbDelta($tbl["reus_roles"]);
			
		}
		
		if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."post_reus'") != $wpdb->prefix."post_reus") {
		
			$tbl["post_reus"] = "CREATE TABLE ".$wpdb->prefix."post_reus (" .
				"id bigint(20) NOT NULL auto_increment," .
				"post_id bigint(20) NOT NULL," .
				"reus_id bigint(20) NOT NULL," .
				"region_id bigint(20) NOT NULL," .
				"weight int(5) NOT NULL," .
				"meta text NULL," . 
				"PRIMARY KEY  (id)" .
				") $charset_collate;";
				
			dbDelta($tbl["post_reus"]);
			
		}
		
		if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."reus_regions'") != $wpdb->prefix."reus_regions") {
			$tbl["reus_regions"] = "CREATE TABLE ".$wpdb->prefix."reus_regions (" .
				"id bigint(20) NOT NULL," .
				"name varchar(256) NOT NULL," . 
				"PRIMARY KEY  (id)" .
				") $charset_collate;";
				
			dbDelta($tbl["reus_regions"]);
		
		}
		
		// Port old version 2.0 reusables data
		if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."reus'") == $wpdb->prefix."reus") {
			
			$old_reusables = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."reus");

			if(!empty($old_reusables)){
				foreach($old_reusables as $reus){
					$this->add_reus($reus->reus_name, $reus->reus_content, get_roles(false), $reus->reus_author);
				}				
			}
			
			$wpdb->query("DROP TABLE ".$wpdb->prefix."reus");

		}
		
		// Set default plugin capabilities.
		
		$roles = get_roles(false);
				
		foreach($roles as $role){
			
			$$role =& get_role($role);
				
			if(isset($$role)){
								
				if(isset($$role->capabilities["edit_posts"]) or isset($$role->capabilities["edit_pages"])){
					$$role->add_cap("edit_reusables");
					$$role->add_cap("insert_reusables");
				}
		
				if(isset($$role->capabilities["edit_others_posts"]) or isset($$role->capabilities["edit_others_pages"])){
					$$role->add_cap("edit_others_reusables");
				}
			
			}
			
		}
		
		
		
				
	}
	
	
	
	
	
	/**
	
		admin_head()
		
	*/
	public function admin_head(){
	
		echo '
		<link rel="stylesheet" href="'.plugins_url().'/'.$this->directory.'/style.css" />
		<script language="javascript" type="text/javascript" src="'.plugins_url().'/'.$this->directory.'/js/jquery.event.drag.js"></script>
		<script language="javascript" type="text/javascript" src="'.plugins_url().'/'.$this->directory.'/js/jquery.event.drop.js"></script>
		<script language="javascript" type="text/javascript" src="'.plugins_url().'/'.$this->directory.'/js/scripts.js"></script>
		
		<script>
			reusables_url = "'.plugins_url().'/'.$this->directory.'";
			blog_url = "'.get_bloginfo("url").'";
			reusables_directory = "'.$this->directory.'";
		</script>';
	
	}
	
	
	
	/**
	
		deactivate()
		
		What to do when we deactivate the plugin.
	
	*/
	public function deactivate(){
		
		
		
	}
	
	
	
	
	
	/**
	
		run()
		
		What to do while the plugin is active.
	
	*/
	public function run(){
		

		$this->add_admin_page(null, "reus", "Reusables", "Reusables", "edit_reusables", null, "images/icon-sm.png", null, false);
		$this->add_admin_page("reus", "reus", "Reusables", "Reusables", "edit_reusables", "reus_edit_page");
		$this->add_admin_page("reus", "add", "Add New", "Add New Reusable", "edit_reusables", "reus_add_page");
		$this->add_admin_page("reus", "about", "About", "About Reusables", "edit_reusables", "reus_about_page");		

		// Hidden pages
		$this->add_admin_page(null, "hidden", "Insert Reusable", "Insert Reusable", "insert_reusables", null, "images/icon-sm.png", null, true);
		$this->add_admin_page("hidden", "insert", "Insert Reusable", "Insert Reusable", "insert_reusables", "reus_insert_page");
		$this->add_admin_page("hidden", "preview", "Preview Reusable", "Preview Reusable", "insert_reusables", "reus_preview_page");
		
		$this->add_widget("Reusables_Region");
		
		$this->add_meta_box("reusables", "Reusable Regions", "post", "reus_meta");
		$this->add_meta_box("reusables", "Reusable Regions", "page", "reus_meta");
		
		$this->add_shortcode("reus", "shortcode_reus", array("id" => null, "meta" => null));
		
		add_action("admin_head", array(&$this, "reset_regions"));
			
	}
	
	
	
	
	
	/**
	
		shortcode_reus()
		
		The "reus" shortcode callback
	
	*/
	public function shortcode_reus($atts){
		
		extract(shortcode_atts($this->shortcode["reus"], $atts));
		return $this->reus($id, $meta, false);
	
	}
	
	
	
	
	
	/* ------------------------------------------------------------
	
	
			The following is the meat and potatos of the plugin.
	
	
	   ------------------------------------------------------------ */
	
	
	
	
	
	/**
	
		get_reus()
	
		Return the reusable object based on the currently logged in 
		users role(s). Users can see ANY reusables which have 
		permission to see the reusable.
	
	*/
	public function get_reus($id){
		
		global $wpdb;
		$user_roles = get_currentuser_roles();
		
		if(!is_admin()){
				
			foreach($user_roles as $key => $role){
				
				$reus_id = $wpdb->get_row("SELECT reus_id FROM ".$wpdb->prefix."reus_roles					
					WHERE role = '$role'
					AND (reus_id = $id)");
					
				if($reus_id){
				
					$reus = get_posts(array(
						"include" => "$id",
						"post_type" => "reusable"
					));
										
					return $reus[0];
					
				}
				
			}
		
		} else {
		
			$reus = get_posts(array(
				"include" => $id,
				"post_type" => "reusable"
			));
			
			return $reus[0];
		
		}
		
		return null;
	
	}
	
	
	
	
	
	/**
	
		reus()
		
		Output a reusable
		
	*/
	public function reus($id, $meta = null, $echo = true, $before = null, $after = null){

		$reus = $this->get_reus($id);
			
		if(is_string($meta)){
			parse_str(htmlspecialchars_decode($meta), $meta);
		}
			
		if($echo == false){
		
			if($reus){
			
				$return = "<div class=\"reus-$id\">";
				$return .= $before;
				$content = str_ireplace(array("%name%","%description%","%url%","%wpurl%","%rdf_url%","%rss_url%","%rss2_url%","%atom_url%","%comments_rss2_url%","%pingback_url%","%stylesheet_url%","%stylesheet_directory%","%template_directory%","%template_url%","%admin_email%","%charset%","%version%","%html_type%"), array(get_bloginfo("name"),get_bloginfo("description"),get_bloginfo("url"),get_bloginfo("wpurl"),get_bloginfo("rdf_url"),get_bloginfo("rss_url"),get_bloginfo("rss2_url"),get_bloginfo("atom_url"),get_bloginfo("comments_rss2_url"),get_bloginfo("pingback_url"),get_bloginfo("stylesheet_url"),get_bloginfo("stylesheet_directory"),get_bloginfo("template_directory"),get_bloginfo("template_url"),get_bloginfo("admin_email"),get_bloginfo("charset"),get_bloginfo("version"),get_bloginfo("html_type")), $reus->post_content);
			
				if(!empty($meta)){
					foreach($meta as $name => $value){
						$content = str_ireplace("%$name%", $value, $content);
					}
				}
			
				$return .= do_shortcode($content);
				$return .= $after;
				$return .= "</div>";
				
				return $return;
				
			} else {
			
				return null;
			
			}
		
		} else {
		
			if($reus){
				
				$return = "<div class=\"reus-$id\">";
				$return .= $before;
				$content = str_ireplace(array("%name%","%description%","%url%","%wpurl%","%rdf_url%","%rss_url%","%rss2_url%","%atom_url%","%comments_rss2_url%","%pingback_url%","%stylesheet_url%","%stylesheet_directory%","%template_directory%","%template_url%","%admin_email%","%charset%","%version%","%html_type%"), array(get_bloginfo("name"),get_bloginfo("description"),get_bloginfo("url"),get_bloginfo("wpurl"),get_bloginfo("rdf_url"),get_bloginfo("rss_url"),get_bloginfo("rss2_url"),get_bloginfo("atom_url"),get_bloginfo("comments_rss2_url"),get_bloginfo("pingback_url"),get_bloginfo("stylesheet_url"),get_bloginfo("stylesheet_directory"),get_bloginfo("template_directory"),get_bloginfo("template_url"),get_bloginfo("admin_email"),get_bloginfo("charset"),get_bloginfo("version"),get_bloginfo("html_type")), $reus->post_content);
			
				if(!empty($meta)){
					foreach($meta as $name => $value){
						$content = str_ireplace("%$name%", $value, $content);
					}
				}
			
				$return .= do_shortcode($content);
				$return .= $after;
				$return .= "</div>";
				
				echo $return;
				
			} else {
			
				echo null;
			
			}
		
		}
	
	}
	
	
	
	
	
	/**
	
		add_reus()
		
		Create a new reusable.
	
	*/
	public function add_reus($name, $content, $roles = null, $author = null){
		
		global $user_ID;
		global $wpdb;
		
		$reus_id = wp_insert_post(array(
			"post_title" => $name,
			"post_content" => $content,
			"post_status" => "publish",
			"post_author" => (!$author)?$user_ID:$author,
			"post_type" => "reusable",
			"post_category" => array(0)
		));
		
		if($reus_id){
			
			$user_roles = (empty($roles)) ? get_roles() : $roles;			
			
			if(is_array($user_roles)){
				foreach($user_roles as $role){				
					$wpdb->insert($wpdb->prefix."reus_roles", array("reus_id" => $reus_id, "role" => $role), array("%d", "%s"));
				}
			} else {
				$wpdb->insert($wpdb->prefix."reus_roles", array("reus_id" => $reus_id, "role" => $user_roles), array("%d", "%s"));
			}
			
			return $reus_id;
			
		}
		
		return false;
		
	}
	
	
	
	
	
	/**
	
		reset_regions()
	
	*/
	public function reset_regions(){
	
		global $wpdb;
		
		$regions = get_option("widget_reusables_region");
				
		$wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."reus_regions");

		if(!empty($regions)){
			foreach($regions as $id => $data){
				if(!empty($data["title"]) and is_numeric($id)){
					$active_regions[$id] = $id;
					$wpdb->insert($wpdb->prefix."reus_regions", array("id" => $id, "name" => $data["title"]), array("%d","%s"));
				}
			}
		}
		
		if(!empty($active_regions)){
		
			foreach($active_regions as $active_region){
				$where[] = "region_id <> $active_region";
			}
			
			$wpdb->query("DELETE FROM ".$wpdb->prefix."post_reus WHERE ".implode(" AND ",$where));
					
		}
		
	}
	
	
	
	
	
	/**
	
		update_reus()
		
		Update an existing reusable or create it if it doesnt exist.
	
	*/
	public function update_reus($id, $name, $content, $roles = null){
	
		global $wpdb;
				
		if(get_reus($id)){
		
			$update = wp_update_post(array(
				"ID" => $id,
				"post_title" => $name,
				"post_content" => $content
			));
				
			if($update){
				
				$roles = (empty($roles)) ? get_roles() : $roles;
				$wpdb->query("DELETE FROM ".$wpdb->prefix."reus_roles WHERE reus_id = $id");
	
				if(is_array($roles)){
					foreach($roles as $role){
						$wpdb->insert($wpdb->prefix."reus_roles", array("reus_id" => $id, "role" => $role), array("%d", "%s"));
					}
				} else {
					$wpdb->insert($wpdb->prefix."reus_roles", array("reus_id" => $id, "role" => $roles), array("%d", "%s"));
				}
				
			}
		
		} else {
		
			$this->add_reus($name, $content, $roles = null);
		
		}
		
	}
	
	
	
	
	
	/**
	
		delete_reus()
		
		Delete a reusable.
	
	*/
	public function delete_reus($id){
	
		global $wpdb;
		
		wp_delete_post($id);
		$wpdb->query("DELETE FROM ".$wpdb->prefix."post_reus WHERE reus_id = $id");
		$wpdb->query("DELETE FROM ".$wpdb->prefix."reus_roles WHERE reus_id = $id");
		
		return $id;		
	
	}
	
}





/* Individual Admin Page Code */
class reus_edit_page extends admin_page {

	public function page(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-edit.php");
	}
	
	public function single(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-edit-single.php");
	}

	public function delete(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-edit.php");
	}

}

class reus_add_page extends admin_page {

	public function page(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-add.php");
	}

}

class reus_about_page extends admin_page {

	public function page(){
		global $reusables;
		include_once("screens/page-about.php");
	}

}

/**

	Reusables Insert Page

*/
class reus_insert_page extends admin_page {

	public function page(){
		global $wpdb;
		global $reusables;		
		include_once("screens/page-insert.php");
	}
	
	public function meta(){
		global $wpdb;
		global $reusables;		
		include_once("screens/page-insert-meta.php");
	}
	
	public function post(){
		global $wpdb;
		global $reusables;		
		include_once("screens/page-insert-post.php");
	}

}

/**

	Reusables Preview Page

*/
class reus_preview_page extends admin_page {

	public function page(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-preview.php");
	}
	
	public function meta(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-preview-meta.php");
	}
	
	public function post(){
		global $wpdb;
		global $reusables;
		include_once("screens/page-preview-post.php");
	}

}




/**

	Reusables Region Widget

*/
class Reusables_Region extends WP_Widget {

	function Reusables_Region() {
		parent::WP_Widget(false, $name = str_replace("_", " ", get_class($this)));
	}

	function form($instance) {
	
		foreach($instance as $name => $value){
			$$name = esc_attr($value);
		}
	
		?>
      
		<p><label for="<?php echo $this->get_field_id('title');?>"><?php _e('Name:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title;?>" /></label></p>
      
      <p class="widget-warning"><small>Deleting this region will permanently remove all reusables assigned to it.</small></p>
      
		<?
	
	}

	function update($new_instance, $old_instance) {
		
		global $wpdb;
									
		if(empty($new_instance["title"])){
			$new_instance["title"] = $old_instance["title"];
		}
							
		$region = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."reus_regions WHERE id = ".$this->number);
		
		if($region){
			$wpdb->update($wpdb->prefix."reus_regions", array("name" => $new_instance["title"]), array("id" => $this->number), array("%s"));
		} else {
			$wpdb->insert($wpdb->prefix."reus_regions", array("id" => $this->number, "name" => $new_instance["title"]), array("%d", "%s"));
		}
				
		$instance = array_merge($old_instance, $new_instance);

		return $instance;
		
	}

	function widget($args, $instance) {
				
		global $reusables;
		global $post;
		global $wpdb;
									
		foreach($instance as $name => $value){
			$$name = esc_attr($value);
		}
	
		if(!empty($post)){
			
			$post_reus = $wpdb->get_results("SELECT ".$wpdb->prefix."post_reus.* FROM ".$wpdb->prefix."post_reus WHERE ".$wpdb->prefix."post_reus.post_id = $post->ID AND ".$wpdb->prefix."post_reus.region_id = ".$this->number." ORDER BY weight ASC");
			
			if(!empty($post_reus)){
			
				$reus = null;
							
				foreach($post_reus as $post_reusable){
				
					$vars = unserialize($post_reusable->meta);
				
					if(!empty($vars)){
						foreach($vars as $variable){
							$meta[$variable->name] = $variable->value;
						}
					}	

					$reus .= $reusables->reus($post_reusable->reus_id, $meta, false, '<li id="'.$args["widget_id"].'-reus-'.$post_reusable->reus_id.'" class="reus reus-'.$post_reusable->reus_id.' '.$args["widget_id"].'">', '</li>');
								
				}
				
				if(!empty($reus)){
					echo $args["before_widget"];
					echo $args["before_title"].$title.$args["after_title"];
					echo "<ul>";
					echo $reus;
					echo "</ul>";
					echo $args["after_widget"];
				}
				
			}
			
		}
		
	}

}











/**

	Reusables Meta Box

*/
class reus_meta extends meta_box {

	public function form(){
		global $wpdb;
		global $reusables;		
		include_once("screens/meta-reusables.php");
	}
	
	public function update($post_id){
	
		global $wpdb;
		
		$regions = $_POST["region"];
						
		$wpdb->query("DELETE FROM ".$wpdb->prefix."post_reus WHERE post_id = $post_id");
	
								
		if(!empty($regions)){
			foreach($regions as $key => $region){
									
				if(!empty($region)){
					foreach($region as $reus_key => $reus){					
						$wpdb->insert($wpdb->prefix."post_reus", array("post_id" => $post_id, "reus_id" => $reus["id"], "region_id" => $key, "weight" => $reus["weight"], "meta" => serialize(json_decode(str_replace("\'","\"",$reus["meta"])))), array("%d","%d","%d","%d","%s"));
					}
				}
			
			}
		}
		
	}

}





/**

	TinyMCE Button

*/
add_action("init", "init_og_reusables_icon");

function init_og_reusables_icon() {

	global $user_level;

	if($user_level > 1 and !current_user_can("edit_posts") and ! current_user_can("edit_pages")) {
		return;
	}
	if(get_user_option("rich_editing") == "true") {
		add_filter("mce_external_plugins", "tinymc_add_reus_bttn");
		add_filter("mce_buttons", "tinymce_reg_og_reusables_bttn");
	}
}
function tinymce_reg_og_reusables_bttn($buttons) {
	array_push($buttons, "separator", "reus");
	return $buttons;
}
function tinymc_add_reus_bttn($plug_array) {
	
	global $reusables;
	global $mgr;

	$plug_array["reus"] = plugins_url($reusables->directory."/_include/tinymce.reus.js.php?directory=".$reusables->directory);
	return $plug_array;
}



$reusables = new Reusables(__FILE__);

?>