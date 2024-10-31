<?php if($regions = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."reus_regions")){ ?>

<input type="hidden" name="txt-reusables_post" id="txt-reusables_post" value="<?php echo $_REQUEST["post"];?>" />

<script>

jQuery(document).ready(function(jQuery){
	
<?php foreach($regions as $region){ ?>

<?php $items = $wpdb->get_results("SELECT ".$wpdb->prefix."post_reus.*, ".$wpdb->prefix."posts.ID as reus_id, ".$wpdb->prefix."posts.post_title as reus_name FROM ".$wpdb->prefix."post_reus INNER JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."post_reus.reus_id WHERE region_id = $region->id AND post_id = ".$_REQUEST["post"]." ORDER BY weight ASC"); ?>

<?php $i=0; foreach($items as $reus){ 

	$reus_meta = array();
	$meta = unserialize($reus->meta); 
	
	if(!empty($meta)){	
	foreach($meta as $key => $variable){ 
	
		$reus_meta[] = "{name:\"".$variable->name."\",value:\"".$variable->value."\"}";?>
		
add_reus(<?php echo $region->id;?>, <?php echo $reus->reus_id;?>, "<?php echo $reus->reus_name;?>", [<?php echo implode(",",$reus_meta);?>]);

<?php }} else { ?>

add_reus(<?php echo $region->id;?>, <?php echo $reus->reus_id;?>, "<?php echo $reus->reus_name;?>", []);

<?php } $i++; } ?>

<?php } ?>

});

</script>

<div id="postcustomstuff">
  <p><strong>Assign reusables:</strong></p>
  
  <table id="reusables">
    <thead>
      <tr>
        <th class="left">Region</th>
        <th>Reusables</th>
      </tr>
    </thead>
    <tbody>
    
    <?php foreach($regions as $region){ ?>
    
    <tr>
      <td class="left" id="newmetaleft"><span class="name"><?php echo $region->name;?></span></td>
      <td>
      	<ul id="region-<?php echo $region->id;?>" class="region">
         </ul>
        </td>
    </tr>
    <tr>
    	<td></td>
      <td><div class="submit"><input type="button" name="btn-insert_reus" value="Insert Reusable" class="button" onclick="popWin('admin.php?page=<?php echo $reusables->directory; ?>/insert&f=meta&region_id=<?php echo $region->id;?>',325,600,'insert')" /></div></td>
    </tr>
    <?php } ?>
    
  </table>
  
</div>

<?php } else { ?>

<div id="postcustomstuff">
<p><strong>You do not have any reusable regions setup</strong></p>
</div>
               
<?php } ?>