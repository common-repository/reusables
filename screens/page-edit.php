<?php 

global $current_user;
get_currentuserinfo();

if(isset($_GET["f"])){ 

	switch($_GET["f"]){
	
		case "delete" :
			$reusables->delete_reus($_GET["id"]);
			$message = "Reusable deleted.";
			break;
		
	}
		
}

if(isset($_POST["action"]) or isset($_POST["action2"])){
	
	$_POST["action"] = ($_POST["action2"] != -1) ? $_POST["action2"] : $_POST["action"];
	
	switch($_POST["action"]){
	
		case "delete" :
		
			if(!empty($_POST["cb_reus"])){
				foreach($_POST["cb_reus"] as $reus_id){
					$reusables->delete_reus($reus_id);
				}
			}
			$message = "Reusables deleted.";
			break;
	
	}
	
}

$all_reus = get_posts(array(
	"post_type" => "reusable",
	"orderby" => "title",
	"order" => "ASC",
	"numberposts" => -1,
	"post_status" => "publish",
	"nopaging" => true
));

if(!current_user_can("edit_others_reusables")){

	$exclude = array();

	if(isset($all_reus)){
	
		foreach($all_reus as $reus){
			if($reus->post_author != $current_user->ID){
				$exclude[] = $reus->ID;
			}
		}
		
		$all_reus = get_posts(array(
			"post_type" => "reusable",
			"orderby" => "title",
			"order" => "ASC",
			"numberposts" => -1,
			"post_status" => "publish",
			"nopaging" => true,
			"exclude" => implode(",",$exclude)
		));
		
	}
	
}

if(!empty($all_reus)){
	$total_reus = count($all_reus);
}

$_REQUEST["paged"] = (!empty($_REQUEST["paged"]))?$_REQUEST["paged"]:1;
$numberposts = (!empty($_REQUEST["n"]))?$_REQUEST["n"]:20;
$pages = ceil($total_reus/$numberposts);
$currentpage = (!empty($_REQUEST["paged"]))?$_REQUEST["paged"]-1:0;
$offset = $numberposts*$currentpage;
$showing_from = $offset+1;
$showing_to = ($showing_from+$numberposts <= $total_reus)?$showing_from+$numberposts-1:$total_reus;
$max_pages = 4;

if($_REQUEST["paged"]-floor($max_pages/2) < 1){
	$start_pagination = 1;
} else {
	$start_pagination = $_REQUEST["paged"]-floor($max_pages/2);
}

if($start_pagination+$max_pages > $pages){
	$end_pagination = $pages;
} else {
	$end_pagination = $start_pagination+$max_pages-1;
}


if(current_user_can("edit_others_reusables")){
	$reuss = get_posts(array(
		"post_type" => "reusable",
		"orderby" => "title",
		"order" => "ASC",
		"numberposts" => $numberposts,
		"post_status" => "publish",
		"offset" => $offset
	));
} else {
	$reuss = get_posts(array(
		"post_type" => "reusable",
		"orderby" => "title",
		"order" => "ASC",
		"numberposts" => $numberposts,
		"post_status" => "publish",
		"offset" => $offset,
		"exclude" => implode(",",$exclude)
	));
}




if($message){ 
	echo "<div class=\"updated below-h2\" id=\"message\"><p>$message</p></div>";
}


?>
      
<div id="poststuff">








<div class="tablenav">
<div class="tablenav-pages"><span class="displaying-num">Displaying <?php echo $showing_from;?>&ndash;<?php echo $showing_to;?> of <?php echo $total_reus;?></span>

<?php if($pages > 1){ ?>
<?php if($_REQUEST["paged"] != 1){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/reus&paged=<?php echo $_REQUEST["paged"]-1;?>&n=<?php echo $numberposts?>&n=<?php echo $numberposts?>" class="previous page-numbers">&laquo;</a>
<?php } ?>

<?php for($i = $start_pagination; $i <= $end_pagination; $i++){ ?>

<?php if($i-1 == $currentpage){ ?>
<span class="page-numbers current"><?php echo $i;?></span>
<?php } else { ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/reus&paged=<?php echo $i;?>&n=<?php echo $numberposts?>" class="page-numbers"><?php echo $i?></a>
<?php } ?>

<?php } ?>

<?php if($_REQUEST["paged"] != $pages){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/reus&paged=<?php echo $_REQUEST["paged"]+1;?>&n=<?php echo $numberposts?>" class="next page-numbers">&raquo;</a>
<?php } ?>
<?php } ?>

</div>
<div class="alignleft actions">
<select name="action">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="delete">Delete</option>
</select>
<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">
<br class="clear">
</div>
<br class="clear">
</div>










<table class="widefat post fixed" cellspacing="0">
<thead>
<tr>
<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
<th id="title" class="manage-column column-title" scope="col">Name</th>
<th id="author" class="manage-column column-author" scope="col">Author</th>
<th id="roles" class="manage-column column-roles" scope="col">Roles</th>
<th id="date" class="manage-column column-date" scope="col">Date</th>
</tr>
</thead>
<tfoot>
<tr>
<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
<th id="title" class="manage-column column-title" scope="col">Name</th>
<th id="author" class="manage-column column-author" scope="col">Author</th>
<th id="roles" class="manage-column column-roles" scope="col">Roles</th>
<th id="date" class="manage-column column-date" scope="col">Date</th>
</tr>
</tfoot>
<tbody><?php 
if(!empty($reuss)){
	foreach($reuss as $reus){
	
	$reus_roles = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."reus_roles WHERE reus_id = ".$reus->ID);
	$roles = array();
	
	foreach($reus_roles as $role){
		
		$roles[$role->role] = $role->role;
		
	}
		
	setup_postdata($reus);
	$meta = null;
	$variables = null;
	$metas = null;
	
	preg_match_all("/%((?:[a-z][a-z0-9_]*))%/is", $reus->post_content, $variables);
	
	if(!empty($variables[1])){
		foreach($variables[1] as $var){
			$metas[$var] = "'".$var."'";
		}
	}
	
	?><tr class="reus">
   <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $reus->ID;?>" name="cb_reus[]"></th>
	<td class="reus-title"><strong><a href="admin.php?page=<?php echo $reusables->directory;?>/reus&f=single&id=<?php echo $reus->ID;?>" class="row-title"><?php echo $reus->post_title;?></a></strong>
	<div class="row-actions"><span class="edit"><a href="admin.php?page=<?php echo $reusables->directory;?>/reus&f=single&id=<?php echo $reus->ID;?>">Edit</a> | </span><span class="trash"><a href="javascript:delete_reus(<?php echo $reus->ID;?>,'<?php echo $reus->post_title;?>')">Delete</a> | </span><span class="view"><a href="javascript:popWin('admin.php?page=<?php echo $reusables->directory;?>/preview&id=<?php echo $reus->ID;?>',400,400,'preview')">Preview</a></span></div></td>
	<td class="reus-author"><?php the_author_link();?></td>
	<td class="reus-roles"><?php echo implode(", ",$roles);?></td>
	<td class="reus-date"><?php echo date("Y/m/d", strtotime($reus->post_date));?></td>
	</tr><?php 	
	}
}

?></tbody>
</table>








<div class="tablenav">
<div class="tablenav-pages"><span class="displaying-num">Displaying <?php echo $showing_from;?>&ndash;<?php echo $showing_to;?> of <?php echo $total_reus;?></span>

<?php if($pages > 1){ ?>
<?php if($_REQUEST["paged"] != 1){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/reus&paged=<?php echo $_REQUEST["paged"]-1;?>&n=<?php echo $numberposts?>&n=<?php echo $numberposts?>" class="previous page-numbers">&laquo;</a>
<?php } ?>

<?php for($i = $start_pagination; $i <= $end_pagination; $i++){ ?>

<?php if($i-1 == $currentpage){ ?>
<span class="page-numbers current"><?php echo $i;?></span>
<?php } else { ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/reus&paged=<?php echo $i;?>&n=<?php echo $numberposts?>" class="page-numbers"><?php echo $i?></a>
<?php } ?>

<?php } ?>

<?php if($_REQUEST["paged"] != $pages){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/reus&paged=<?php echo $_REQUEST["paged"]+1;?>&n=<?php echo $numberposts?>" class="next page-numbers">&raquo;</a>
<?php } ?>
<?php } ?>

</div>
<div class="alignleft actions">
<select name="action2">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="delete">Delete</option>
</select>
<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">
<br class="clear">
</div>
<br class="clear">
</div>









</div>