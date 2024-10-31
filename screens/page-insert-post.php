<?php 
$all_reus = get_posts(array(
	"post_type" => "reusable",
	"orderby" => "title",
	"order" => "ASC",
	"numberposts" => -1,
	"post_status" => "publish",
	"nopaging" => true
));

if(!empty($all_reus)){
	$total_reus = count($all_reus);
}

$_REQUEST["paged"] = (!empty($_REQUEST["paged"]))?$_REQUEST["paged"]:1;
$numberposts = (!empty($_REQUEST["n"]))?$_REQUEST["n"]:10;
$pages = ceil($total_reus/$numberposts);
$currentpage = (!empty($_REQUEST["paged"]))?$_REQUEST["paged"]-1:0;
$offset = $numberposts*$currentpage;
$showing_from = $offset+1;
$showing_to = ($showing_from+$numberposts <= $total_reus)?$showing_from+$numberposts-1:$total_reus;
$max_pages = 3;

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



$reuss = get_posts(array(
	"post_type" => "reusable",
	"orderby" => "title",
	"order" => "ASC",
	"numberposts" => $numberposts,
	"post_status" => "publish",
	"offset" => $offset
));
		
?>







<div class="tablenav">
<div class="tablenav-pages"><span class="displaying-num">Displaying <?php echo $showing_from;?>&ndash;<?php echo $showing_to;?> of <?php echo $total_reus;?></span>

<?php if($pages > 1){ ?>
<?php if($_REQUEST["paged"] != 1){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/insert&f=post&region_id=<?php echo $_GET["region_id"];?>&paged=<?php echo $_REQUEST["paged"]-1;?>&n=<?php echo $numberposts?>&n=<?php echo $numberposts?>" class="previous page-numbers">&laquo;</a>
<?php } ?>

<?php for($i = $start_pagination; $i <= $end_pagination; $i++){ ?>

<?php if($i-1 == $currentpage){ ?>
<span class="page-numbers current"><?php echo $i;?></span>
<?php } else { ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/insert&f=post&region_id=<?php echo $_GET["region_id"];?>&paged=<?php echo $i;?>&n=<?php echo $numberposts?>" class="page-numbers"><?php echo $i?></a>
<?php } ?>

<?php } ?>

<?php if($_REQUEST["paged"] != $pages){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/insert&f=post&region_id=<?php echo $_GET["region_id"];?>&paged=<?php echo $_REQUEST["paged"]+1;?>&n=<?php echo $numberposts?>" class="next page-numbers">&raquo;</a>
<?php } ?>
<?php } ?>

</div>

<br class="clear">
</div>









<table class="widefat post fixed" cellspacing="0">
<thead>
<tr>
<th id="title" class="manage-column column-title" scope="col">Name</th>
</tr>
</thead>
<tfoot>
<tr>
<th id="title" class="manage-column column-title" scope="col">Name</th>
</tr>
</tfoot>
<tbody><?php 
foreach($reuss as $reus){
      
   $meta = null;
   $variables = null;
   $metas = null;
	
	$reus->post_content = str_ireplace(array("%name%","%description%","%url%","%wpurl%","%rdf_url%","%rss_url%","%rss2_url%","%atom_url%","%comments_rss2_url%","%pingback_url%","%stylesheet_url%","%stylesheet_directory%","%template_directory%","%template_url%","%admin_email%","%charset%","%version%","%html_type%"), array(get_bloginfo("name"),get_bloginfo("description"),get_bloginfo("url"),get_bloginfo("wpurl"),get_bloginfo("rdf_url"),get_bloginfo("rss_url"),get_bloginfo("rss2_url"),get_bloginfo("atom_url"),get_bloginfo("comments_rss2_url"),get_bloginfo("pingback_url"),get_bloginfo("stylesheet_url"),get_bloginfo("stylesheet_directory"),get_bloginfo("template_directory"),get_bloginfo("template_url"),get_bloginfo("admin_email"),get_bloginfo("charset"),get_bloginfo("version"),get_bloginfo("html_type")), $reus->post_content);

   preg_match_all("/%((?:[a-z0-9_]*))%/is", $reus->post_content, $variables);
   
   if(!empty($variables[1])){
      foreach($variables[1] as $var){
         $metas[$var] = "'".$var."'";
      }
   }

   echo '<tr id="reus-'.$reus->ID.'"><td class="reus-title"><strong><a href="javascript:popWin(\'admin.php?page='.$reusables->directory.'/preview&id='.$reus->ID.'\',400,400,\'preview\')">'.$reus->post_title.'</a></strong>
   <div class="row-actions"><a href="javascript://" onclick="if(meta = set_meta(['.(!empty($metas)?implode(",",$metas):null).'])){ insert_reus('.$reus->ID.',meta);window.close(); }">Insert</a></div></td></tr>';
   
}

?></tbody>
</table>
      
      
      
      
      
      
      
      
      
      
<div class="tablenav">
<div class="tablenav-pages"><span class="displaying-num">Displaying <?php echo $showing_from;?>&ndash;<?php echo $showing_to;?> of <?php echo $total_reus;?></span>

<?php if($pages > 1){ ?>
<?php if($_REQUEST["paged"] != 1){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/insert&f=post&region_id=<?php echo $_GET["region_id"];?>&paged=<?php echo $_REQUEST["paged"]-1;?>&n=<?php echo $numberposts?>&n=<?php echo $numberposts?>" class="previous page-numbers">&laquo;</a>
<?php } ?>

<?php for($i = $start_pagination; $i <= $end_pagination; $i++){ ?>

<?php if($i-1 == $currentpage){ ?>
<span class="page-numbers current"><?php echo $i;?></span>
<?php } else { ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/insert&f=post&region_id=<?php echo $_GET["region_id"];?>&paged=<?php echo $i;?>&n=<?php echo $numberposts?>" class="page-numbers"><?php echo $i?></a>
<?php } ?>

<?php } ?>

<?php if($_REQUEST["paged"] != $pages){ ?>
<a href="admin.php?page=<?php echo $reusables->directory;?>/insert&f=post&region_id=<?php echo $_GET["region_id"];?>&paged=<?php echo $_REQUEST["paged"]+1;?>&n=<?php echo $numberposts?>" class="next page-numbers">&raquo;</a>
<?php } ?>
<?php } ?>

</div>

<br class="clear">
</div>







      

<style>

	html { height: auto; }
	#wphead { display: none; }
	#footer { display: none; }
	#adminmenu { display: none; }
	#wpbody { margin-left: 0px !important; }
	.wrap { margin: 0px; padding: 0 5px; }
	#screen-meta { display: none; }
	#wpcontent { padding-bottom: 0; }
	#wpbody-content { float: none; }
	body.wp-admin { min-width: 100%; }
	.wrap .icon32 { display: none; }
	.wrap h2 { display: none; }
	#wpwrap { height: auto; min-height: inherit; }

</style>
		