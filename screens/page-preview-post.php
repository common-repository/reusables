<div id="preview">

<?php

$reus = $wpdb->get_row("SELECT ".$wpdb->prefix."post_reus.* FROM ".$wpdb->prefix."post_reus WHERE ".$wpdb->prefix."post_reus.id = ".$_GET["id"]);
				
if(!empty($reus)){
	$reusables->reus($reus->reus_id, (!empty($reus->meta)?unserialize($reus->meta):null));
}
	
?>

</div>

<style>
	
	#wphead { display: none; }
	#footer { display: none; }
	#adminmenu { display: none; }
	#wpbody { margin-left: 0px !important; }
	.wrap { margin: 0px; padding: 5px; }
	#screen-meta { display: none; }
	#wpcontent { padding-bottom: 0; }
	#wpbody-content { float: none; }
	body.wp-admin { min-width: 100%; }
	.wrap .icon32 { display: none; }
	.wrap h2 { display: none; }
	
</style>
