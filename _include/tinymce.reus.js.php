(function() {
		  
	//tinymce.PluginManager.requireLangPack('reus');
	
	tinymce.create('tinymce.plugins.ReusPlugin', {
				   
		init : function(ed, url) {
			
			ed.addCommand('mceReusablesInsert', function() {
				
				
				ed.windowManager.open({
					title : "Insert Reusable",
					url : 'post.php?page=<?php echo $_REQUEST["directory"]; ?>/insert&f=post',
					width : 350,
					height : 600
				});
								
			});
			
			ed.addButton('reus', {
				title : 'Insert Reusable',
				cmd : 'mceReusablesInsert',
				image : '../wp-content/plugins/<?php echo $_REQUEST["directory"]; ?>/images/icon-tinymce.png'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('reus', n.nodeName == 'IMG');
			});
			
		},

		createControl : function(n, cm) {
			return null;
		},
		
		getInfo : function() {
			return {
				longname : 'WordPress Reusables',
				author : 'Ian Whitcomb',
				authorurl : 'http://www.ooeygui.net',
				infourl : 'http://www.ooeygui.net',
				version : '2.5'
			};
		}
		
	});
	
	tinymce.PluginManager.add('reus', tinymce.plugins.ReusPlugin);

})(); 