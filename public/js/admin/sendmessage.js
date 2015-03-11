$(document).ready(function(){
	$('#content').autosize();
	
	$(document).on('focus','#divNicks .custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$('#nicks').combobox({
		 
	});
	
	
	$(document).on('click','#btnSubmit',function(e){
		e.preventDefault();
		var content = $('#content').val().trim();
		var subject = $('#subject').val().trim();
		
		if (subject.length == 0){
			$("#notify").find('span').text('Nhập vào tiêu đề.');
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
				    		$('#subject').focus();
				    	},
				      },
				      
				  ]		      
			});
			
			return false;
		}
		
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
				    		$('#content').focus();
				    	},
				      },
				      
				  ]		      
			});
			
			return false;
		}
		
		
		var url = $('#frmQuestion').attr('action');
		var btn = $(this);
		var img = '<img src="../../images/loading.gif" width="22px;" height="18px" />';
		var nick = $('#nicks').val();
		var param = {nick:nick,subject:subject,content:content,btnSubmit:true};
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
				 $('#divQuestion').html(data);
				 $('#subject').val('');
				 $('#content').val('');
			},
			
			
		});// ajax
		
	});
	
	$(document).on('click','.delquestion',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var btn = $(this);
		var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
		
		$.ajax({
			url:url,
			type: "GET",			 
			dataType: "JSON",			
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
				if (data.success){					 
					 $(btn).closest('.trItem').fadeOut(400,function(){
						 $(this).remove();
						  
					 });
					
				}
			},
			
			
		});// ajax
	});
 
	
 
	// ##############for paging######################
	 $(document).on("click",".pagination ul>li>a",function(e){
		 e.preventDefault();
		 var url = $(this).attr('href');	
		 if (!$(this).parent().hasClass('disabled')){
			 submitForm(url);
		 }
		
	 });
	 
	// show number of items per page
	 $(document).on('change','#rowPaginator',function(){
		 var url = $("#frmOrder").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#frmQuestion").serialize();		
		 $.ajax({
				url:url,
				type: "POST",
				data: param,
				dataType: "HTML",			
				error: function(xhr,status,errmgs){},
				beforeSend: function(){
					$("#splash").find("#splashcontent").html("Đang tải dữ liệu, vui lòng chờ"); 
					$("#splash").fadeIn(200);					  			
				},
				complete: function(){
					$("#splash").hide();
				},
				success: function(data){					 
					$("#divQuestion").html(data);
					 
					 
				}
				
		});// ajax
	 } // submitForm
//##################################
	
	
//dialog
	 $("#dialog").dialog({
		 autoOpen:false,
		 closeOnEscape: true,
		 closeText: "Đóng",
		 resizable: false,		 
		 show: {effect: "fade", duration: 200},
		 hide: {effect: "fade", duration: 200},
	 });
	
});//ready