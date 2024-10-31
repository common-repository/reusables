<div id="poststuff"class="metabox-holder has-right-sidebar">
<div id="side-info-column" class="inner-sidebar">


<div class="postbox " id="tagsdiv-post_tag">
<h3 class="hndle"><span>Help Us Help You</span></h3>
<div class="inside">

	<a href="http://www.ooeygui.net" target="_blank"><img src="<?php echo plugins_url()."/".$reusables->directory."/images/logo-og.png"; ?>" class="alignright" style="margin:0 10px 10px 10px;" /></a>

    <p><b>Countless days and nights were spent perfecting this plugin just for YOU.</b> Help ensure the continued support of this plugin, and the development of new ones by donating now.</p>

   <p align="center"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=25WYW3FP76G2U" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!">
   <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></p>



</div>
</div>






<div class="postbox " id="tagsdiv-post_tag">
<h3 class="hndle"><span>Information</span></h3>
<div class="inside">

    <p><b>Version 3.0</b><br>
    <small>Release Date: June 12, 2010</small></p>
    
    <p><b>Minimum Requirements</b><br />
	<small>WordPress 3.0</small><br />
    <small>PHP 5.2.6</small><br />
    <small>MySQL 5.0.45</small><br /></p>
    
    <p><small>Developed and maintained by Ian Whitcomb<br /><a href="http://www.ooeygui.net/" target="_blank">http://www.ooeygui.net</a></small></p>


</div>
</div>









</div>
    
    
<div id="post-body">
<div id="post-body-content">    
    
    
    <p>WordPress Reusables was created to help developers manage their content more efficiently. By allowing content to be stored and edited in a single location on WordPress it is possible to update a single piece of content that will update retroactively anywhere that the reusable is being used. This plugin creates a more front-end approach to templatizing content and even makes it possible to create or modify an entire theme without touching the PHP code.</p>
    
    
    <h3>New in Version 3.0</h3>
    
    <p><b>Reusable regions</b> are widgets that admins can place into dynamic sidebars, reusables can then be assigned to regions from post/page edit screens.</p>
    <p><b>User roles</b> allow admins to display reusables based on a users role.</p>
    <p><b>WYSIWYG</b> the TinyMCE WYSIWYG editor has been added to the reusable edit screen.</p>
    <p><b>Reusable content type</b> is now available thanks to WordPress 3.0.</p>
    <p><b>Predefined meta variables</b> for easier access to WordPress <a href="http://codex.wordpress.org/Function_Reference/get_bloginfo" target="_blank">bloginfo()</a> variables.</p>
    <p><b>Completley overhauled code</b> for better performance.</p>
        
    <h3>Usage</h3>
        
    <h4>Inserting Reusables</h4>
    <p>You can use the <b>reus</b> short code to manually insert reusables into pages, posts, and other reusables using the following format.</p>
    <pre>[reus id[ meta]]</pre>
    <p>For example:</p>
    <pre>[reus id="123"]</pre>
    <pre>[reus id="123" meta="foo=bar&amp;name=value"]</pre>
    <p>This can also be done through the <b>insert</b> interface by clicking the <b>Insert Reusable</b> button (<img src="<?php echo plugins_url()."/".$reusables->directory."/images/icon-tinymce.png"; ?>" />) on the content editor.</p>
    
    <p><small>The <b>Insert Reusable</b> button is currently unavailable on the reusable edit screen, nesting reusables must be done manually. Please also note that there is a maximum number of nested reusables available which is dependant entirely on how your server is setup, it is recommended that you do not nest anymore than 2 or 3 levels deep as it could cause PHP memory errors. You should also not nest a reusable within its self as this will also cause memory errors.</small></p>
    
    <p>Reusables may also be used within the template files of a theme by using the <b>reus()</b> function. All of the same functionality still applies but is called using the following format.</p>
    
    <pre><b>reus</b>(string/int <i>$id</i> [, <i>$meta = null</i> [, <i>$echo = true</i> ]])</pre>
    
    <p>For example:</p>
    
    <pre>&lt;?PHP reus("My Reusable", "foo=bar&amp;name=value"); ?&gt;</pre>
    
    <h4>Meta Content</h4>
    <p>Meta variables can be added upon creating or editing reusables to allow users to interchange content. Simply surround the variable name with the percent(%) symbol to create a new variable.</p>
    <pre>Hello %variable%!</pre>
    <p>You can then use the <b>meta</b> attribute in the short code to define the content of the variable.>Multiple variables can be defined <b>meta</b> attribute of the <b>reus</b> short code simply by stringing them together using the ampersand(&amp;) symbol.</p>
    <pre>[reus name="My Reusable" meta="variable=World&variable2=Lorem&variable3=Ipsum"]</pre>
    <p>Meta variables can also be used to interchange entire reusables on the fly simply by creating a new variable where you would normally define the <b>id</b> attribute. This also implies nesting reusables so all of the same rules apply.</p> 


	 <h4>User Roles</h4>

	 <p>Upon the creation of a reusable, you can allow only certain user roles to be able to see the reusable. When inserted into posts, pages, regions, or other reusables it will only be viewable by users with one of the specified roles.</p>
    
    <h4>Regions</h4>

	 <p>Define <b>reusable regions</b> on the <a href="widgets.php">Widgets</a> page and assign page-specific, arrangable reusables to any region within any sidebar.</p>

</div>
</div>
</div>
</div>