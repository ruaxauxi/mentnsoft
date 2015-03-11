$(document).ready(function(){
	$(document).on('keyup','.required',function(e){
		valid();
	});
	
	function valid(){
		var valid = true;
		$('.required').each(function(i,e){
			if ($(e).val().trim().length < 3){
				valid = false;
				 
			}else{
				 
			}
		});
		
		if (valid){
			$('#submitbutton').removeAttr('disabled');
		}else{
			$('#submitbutton').attr('disabled','disabled');
		}
	}
	
	$(document).on('submit','#changepasswdForm',function(e){
		e.preventDefault();
		$('#errmsg').html(''); 
		 $('#msg').html('');
		 
		if ($('#passwd').val() != $('#passwd2').val()){
			$("#notify").find('span').text('Mật khẩu mới nhập không khớp.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    		$('#passwd').val('');
				    		$('#passwd2').val('');
				    		$('#passwd').focus();
				    		$('#submitbutton').attr('disabled','disabled');
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}
		
		 var url = $("#changepasswdForm").attr('action');
		 var param = $("#changepasswdForm").serialize();
		 var img = '<img src="../images/loading.gif" width="26px;" height="22px" style="margin-right: 5px;" />';
		 var btn = $('#submitbutton');
		 $.ajax({
				url:url,
				type: "POST",
				data: param,
				dataType: "JSON",			
				error: function(xhr,status,errmgs){					
				},
				beforeSend: function(){
					$(btn).after(img);
					$(btn).hide();
				},
				complete: function(){
					$(btn).next().remove();
					$(btn).show();
				},				
				success: function(data){					 
					if (data.success == 1){
						 $('#msg').html(data.msg);
						 $('#errmsg').html('');
						 $('.required').val('');
					}else{
						 $('#errmsg').html(data.msg); 
						 $('#msg').html('');
					}
				},
				
				
		});// ajax
		
	});
	
	//dialog
	 $("#dialog").dialog({
		 autoOpen:false,
		 closeOnEscape: true,
		 closeText: "Đóng",
		 resizable: false,		 
		 show: {effect: "fade", duration: 200},
		 hide: {effect: "fade", duration: 200},
	 });
	 
	
});// ready