var popup = new Array();

function popWin(url,w,h,name){
	popup[name] = window.open(url, "popup-"+name, "scrollbars=1,width="+w+",height="+h);
}

function serialize(obj){
		
  var t = typeof (obj); 
  
  if (t != "object" || obj === null) {  
		if (t == "string") obj = '"'+obj+'"';  
		return String(obj);  
  }  
  
  else {  
		var n, v, json = [], arr = (obj && obj.constructor == Array);  
		for (n in obj) {  
			 v = obj[n]; t = typeof(v);  
			 if (t == "string") v = '"'+v+'"';  
			 else if (t == "object" && v !== null) v = JSON.stringify(v);  
			 json.push((arr ? "" : '"' + n + '":') + String(v));  
		}  
		return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");  
  }
  
}

function insert_reus(id, meta){

	if(meta || meta.length > 0){
		window.opener.tinyMCE.execCommand('mceInsertContent', 0, '[reus id="'+id+'" meta="'+meta_query(meta)+'"]');
	} else {
		window.opener.tinyMCE.execCommand('mceInsertContent', 0, '[reus id="'+id+'"]');
	}
	
	window.close();
}

function remove_reus(region,n){

	name = jQuery("ul#region-"+region+" li.n-"+n).find("input.name").val();
	obj = jQuery("ul#region-"+region+" li.n-"+n);
	conf = window.confirm("Are you sure you want to remove this reusable?\n\n"+name);
	
	if(conf){
		obj.remove();
	}
	
}

function delete_reus(id, name){

	conf = window.confirm("Are you sure you want to delete this reusable?\n\n"+name);
	
	if(conf){
		window.location='admin.php?page='+reusables_directory+'/reus&f=single&id='+id+'&f=delete';	
	}

}

function edit_meta(region,n){
	
	meta = eval("("+jQuery("ul#region-"+region+" li.n-"+n).find("input.meta").val()+")");
	obj = jQuery("ul#region-"+region+" li.n-"+n);
	
	new_meta = new Array();
		
	for(i in meta){
		
		new_meta[i] = {name:meta[i].name,value:meta[i].value}
		new_meta[i].value = window.prompt("Set the %"+meta[i].name+"% variable.",meta[i].value);
		
		if(new_meta[i].value == null){
			new_meta = new Array();
			break;
		}
			
	}
		
	for(i in new_meta){
		
		meta[i].name = new_meta[i].name;
		meta[i].value = new_meta[i].value;
		
	}
	
	jQuery("ul#region-"+region+" li.n-"+n+" input.meta").val(serialize_meta(meta));
	
}

function add_reus(region,id,name,meta){
	
	var weight = jQuery("ul#region-"+region+" li.reus").length;
	
	jQuery("ul#region-"+region).append("<li class=\"reus reus-"+id+" n-"+weight+"\">" +
		"<input class=\"weight\" type=\"hidden\" name=\"region["+region+"]["+weight+"][weight]\" value=\""+weight+"\" />" +
		"<input class=\"name\" type=\"hidden\" name=\"region["+region+"]["+weight+"][name]\" value=\""+name+"\" />" +
		"<input class=\"id\" type=\"hidden\" name=\"region["+region+"]["+weight+"][id]\" value=\""+id+"\" />" +
		"<input class=\"meta\" type=\"hidden\" name=\"region["+region+"]["+weight+"][meta]\" value=\""+serialize_meta(meta)+"\" />" +
		"<span class=\"controls\"><a href=\"javascript:edit_meta("+region+","+weight+")\">Edit</a> | <a href=\"javascript:remove_reus("+region+","+weight+")\">Remove</a></span>" +
		"<b><a href=\"javascript:popWin(blog_url+'/wp-admin/admin.php?page='+reusables_directory+'/preview&id="+id+"&f=meta&meta='+jQuery('ul#region-"+region+" li.n-"+weight+" input.meta').val(),400,400,'preview')\">"+name+"</a></b>" +
		"</li>");
	
	set_drag();
	
}

function set_meta(vars){
	
	var meta = [];

	if(vars.length > 0){
		for(i in vars){
			
			variable = window.prompt("Set the %"+vars[i]+"% variable.","");
										
			if(variable == null){
				return false;	
			} else {
				meta[i] = {name:vars[i],value:variable};
			}
			
			variable = null;
		
		}
	}
	
	return meta;
	
}

function set_weight(){

	jQuery("ul.region").each(function(){
												 
		var i = 0;
		
		jQuery(this).find("li.reus").each(function($){
		
			jQuery(this).find("input.weight").val(i);
			i++;
		
		});
		
	});

}

function set_drag(){

	jQuery("ul.region").each(function(){
	
		var offset;
		var drag_weight;
		var drop_weight;
		
		jQuery(this).find("li.reus")
			.bind("mousedown", function(){
				offset = jQuery(this).offset();
				drag_weight = jQuery(this).find("input.weight").val();
				
				jQuery(this).parents("ul.region").find("li.reus:not(.active)")
					.bind("dropstart", function(){
						drop_weight = jQuery(this).find("input.weight").val();
						
						if(!jQuery(this).hasClass("active")){
						
							if(drop_weight > drag_weight){
								jQuery(this).addClass("drop-after");
							}
							if(drop_weight < drag_weight){
								jQuery(this).addClass("drop-before");
							}
						
						}
						
					})
					.bind("drop", function(){
												  
						//if(!jQuery(this).hasClass("active")){
							
							reus = jQuery("ul.region li.reus.active");
							reus.removeClass("active");
							reus.css({top:0,left:0});
							
							if(drop_weight > drag_weight){
								jQuery(this).after(reus);
							}
							if(drop_weight < drag_weight){
								jQuery(this).before(reus);
							}
							
							jQuery("ul.region li.reus.active").remove();
							set_weight();
						
						//}
						
					})
					.bind("dropend", function(){
						jQuery(this).removeClass("drop-before").removeClass("drop-after");
					});
				
			})
			.bind("dragstart",function(event){
				if(jQuery(this).parents("ul.region").find("li.reus").length > 1){
					jQuery(this).addClass("active");
					jQuery.dropManage({ mode:'mouse', filter:'.reus' });
				}
			})
			.bind("drag",function(event){
				if(jQuery(this).parents("ul.region").find("li.reus").length > 1){
					jQuery(this).css({top:event.offsetY-offset.top,left:event.offsetX-offset.left});
				}
			})
			.bind("dragend",function(event){
				if(jQuery(this).parents("ul.region").find("li.reus").length > 1){
					jQuery(this).removeClass("active");
					jQuery(this).css({top:0,left:0});
					
					jQuery(this).parents("ul.region").find("li.reus:not(.active)")
						.unbind("dropstart")
						.unbind("drop")
						.unbind("dropend");
				}
			});
	
	});

}
function meta_query(meta){
	
	var query = new Array();
	
	for(i in meta){
		
		query[query.length] = meta[i].name+"="+meta[i].value;
		
	}
	
	return query.join("&");
	
}
function serialize_meta(meta){
	
	var query = new Array();
	
	for(i in meta){
		
		query[query.length] = "{'name':'"+meta[i].name+"','value':'"+meta[i].value+"'}";
		
	}
	
	return '['+query.join(",")+']';
	
}