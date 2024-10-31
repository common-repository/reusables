<div id="preview">
		
<?php echo preg_replace("/%((?:[a-z0-9_]*))%/is", '<span class="variable">$1</span>', $reusables->reus($_GET["id"], null, false));?>

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