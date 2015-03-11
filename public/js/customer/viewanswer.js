$(document).ready(function(){
	$('#reply').autosize();
	
	$(document).on('click','#btnSubmit',function(e){
		e.preventDefault();
		var content = $('#reply').val().trim();
		var question_id = $('#question_id').val();
		if (content.length == 0){
			$("#notify").find('span').text('Nhập vào nội dung.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  modal: true,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    		$('#reply').focus();
				    	},
				      },
				      
				  ]		      
			});
			
			return false;
		}
		
		
		var url = $('#frmAnswer').attr('action');
		var btn = $(this);
		var img = '<img src="../../images/loading.gif" width="22px;" height="18px" />';
		var param = {content:content,question_id:question_id,btnSubmit:true};
		$.ajax({
			url:url,
			type: "POST",
			data: param,
			dataType: "HTML",			
			error: function(xhr,status,errmgs){					
			},
			beforeSend: function(){	
				$(btn).after(img);
				$(btn).hide();
			},
			complete: function(){
				$(btn).show();
				$(btn).next().remove();
			},						
			success: function(data){				
				 $('#divAnswer').html(data);
				 $('#reply').val('');
				 $('#reply').autosize();
			},
			
			
		});// ajax
		
	});
	
});//ready