<div id="preview">

<?php 		
foreach(json_decode(str_replace("\'","\"",$_GET["meta"])) as $var){
	$meta[$var->name] = $var->value;
}

$reusables->reus($_GET["id"], (!empty($_GET["meta"])?$meta:null));

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
