jQuery(document).ready(function() {

	jQuery('.upload_image_button').click(function() {
	 formfield = jQuery(this).prev().attr('name');
	 destObj = jQuery(this).prev();
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 return false;
	});
	
	oldSendTo = window.send_to_editor;
	window.send_to_editor = function(html) {
		if (destObj != false) {
			 imgurl = jQuery('img',html).attr('src');
			 jQuery(destObj).val(imgurl);
			 jQuery(destObj).parent().find('img').attr('src', imgurl);
			 tb_remove();
			 destObj = false;
		} else {
			oldSendTo(html);
		}
	}	
	
	jQuery('.clear_field_button').click( function() {
		jQuery(this).prev().prev().val('');
	});
	
	
	

});

var destObj = false;
var oldSendTo;