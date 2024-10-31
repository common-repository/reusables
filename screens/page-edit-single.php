<?php

include_once("./admin-header.php");


if(isset($_POST["_wpnonce"])){

	$id = $_POST["id"];
	$title = (!empty($_POST["post_title"])) ? $_POST["post_title"] : "(blank)";

	foreach($_POST["user_role"] as $role => $name){
		$the_roles[$role] = $role;
	}

	$reusables->update_reus($id, $title, $_POST["content"], $the_roles);
	
	$message = "Reusable updated. <a href=\"javascript:popWin('admin.php?page=".$reusables->directory."/preview&id=$id',400,400,'preview')\">Preview reusable</a>";

}


if($message){
	echo "<div class=\"updated below-h2\" id=\"message\"><p>$message</p></div>";
}



$reus = $reusables->get_reus($_GET["id"]);





wp_tiny_mce( false , // true makes the editor "teeny"
	array(
		"editor_selector" => "content",
		"height" => 350,
		"mode" => "none",
		//"onpageload" => "switchEditors.edInit",
		"width" => "100%",
		"theme" => "advanced",
		//"skin" => "simple",
		//"theme_advanced_buttons1" => "$mce_buttons",
		//"theme_advanced_buttons2" => "$mce_buttons_2",
		//"theme_advanced_buttons3" => "$mce_buttons_3",
		//"theme_advanced_buttons4" => "$mce_buttons_4",
		//"language" => "$mce_locale",
		//"spellchecker_languages" => "$mce_spellchecker_languages",
		"theme_advanced_toolbar_location" => "top",
		"theme_advanced_toolbar_align" => "left",
		"theme_advanced_statusbar_location" => "bottom",
		"theme_advanced_resizing" => false,
		"theme_advanced_resize_horizontal" => false,
		"dialog_type" => "modal",
		//'relative_urls' => false,
		//'remove_script_host' => false,
		//'convert_urls' => false,
		//'apply_source_formatting' => true,
		//'remove_linebreaks' => true,
		//'paste_convert_middot_lists' => true,
		//'paste_remove_spans' => true,
		//'paste_remove_styles' => true,
		//'gecko_spellcheck' => true,
		//'entities' => '38,amp,60,lt,62,gt',
		//'accessibility_focus' => true,
		//'tab_focus' => ':prev,:next',
		//'content_css' => "$mce_css",
		//'save_callback' => 'switchEditors.saveCallback',
		//'wpeditimage_disable_captions' => $no_captions,
		"plugins" => "$plugins"
	)
);

 


?>

<script>

jQuery(document).ready(function($) {	
 	

	var id = 'content';
	tinyMCE.execCommand('mceAddControl', false, id);

	$('a.toggleVisual').click(
		function() {
			tinyMCE.execCommand('mceAddControl', false, id);
			
			$(this).addClass("active");
			$("a.toggleHTML").removeClass("active");
		
		}
	);
	
	$('a.toggleHTML').click(
		function() {
			tinyMCE.execCommand('mceRemoveControl', false, id);
			
			$(this).addClass("active");
			$("a.toggleVisual").removeClass("active");
			
		}
	);
	
	
	$("#title").focus(function(){
		
		$("#title-prompt-text").css({visibility:"hidden"});
		
	});
	$("#title").blur(function(){
		
		if(!$(this).val()){
			$("#title-prompt-text").css({visibility:"visible"});
		}
		
	});
	
	
});

</script>

<input type="hidden" name="id" value="<?php echo $_REQUEST["id"];?>" />

<div id="poststuff" class="metabox-holder has-right-sidebar">


   <div id="side-info-column" class="inner-sidebar">
      <div id="side-sortables" class="meta-box-sortables ui-sortable">
      
      
      
      
      
      
      
      <div class="postbox " id="categorydiv">
      <div title="Click to toggle" class="handlediv"><br/></div><h3 class="hndle"><span>Publish</span></h3>
      <div class="inside">
         <div class="categorydiv" id="taxonomy-category">
            
            <p><b>Make this reusable visible to:</b></p>
      
            <div class="tabs-panel" id="category-pop">
               <ul class="categorychecklist form-no-clear" id="categorychecklist-pop">
                  
               <?php $the_roles = array(); $reus_roles = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."reus_roles WHERE reus_id = ".$_GET["id"]); foreach($reus_roles as $role){ $the_roles[] = $role->role; } ?>
                                 
               <?php foreach(get_roles() as $role => $name){ ?>
               <li class="popular-category"><label class="selectit" for="role-<?php echo $role;?>"><input type="checkbox" name="user_role[<?php echo $role;?>]" id="role-<?php echo $role;?>" value="<?php echo $role;?>" <?php echo ((array_search($role,$the_roles) !== false)?"checked":null)?> > <?php echo $name;?></label></li>
               <?php } ?>
      
               </ul>
            </div>
            
            
            

            
            
         </div>
         
         
      
         
         
      </div>
      
      <div id="major-publishing-actions">
            
         <div id="delete-action"><a class="submitdelete deletion" href="javascript:delete_reus(<?php echo $_REQUEST["id"];?>,'<?php echo $reus->post_title;?>')">Delete</a></div>

         <div id="publishing-action">
            <input name="publish" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="Publish" /></div>
            
         <div class="clear"></div>
         
      </div>
         
         
      </div>
            
      
  
      
      
      </div>
   </div>
   
   
   
   
   <div id="post-body">
   <div id="post-body-content">
   
   
   <div id="titlediv">
   	<label class="hide-if-no-js" id="title-prompt-text" for="title" style="visibility:hidden">Enter name here</label>
      <div id="titlewrap">
         <input type="text" autocomplete="off" id="title" value="<?php echo $reus->post_title;?>" tabindex="1" size="30" name="post_title" />
      </div>
   </div>
   
   
   <div id="postdivrich" class="postarea">
      
      <div id="editor-toolbar">
            <a class="toggleHTML" id="edButtonHTML">HTML</a>
            <a class="toggleVisual active" id="edButtonPreview">Visual</a>
      </div>
      
      <div id="editorcontainer">
      <textarea class="content"  id="content" name="content"><?php echo $reus->post_content;?></textarea>
      </div>
   
   </div>
   

   
   </div>
   </div>


</div>