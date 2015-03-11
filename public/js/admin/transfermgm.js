$(document).ready(function(){
	$('#search_nick').autocomplete({
		source: data,
	});
	
	$(document).on('click','.delTrans',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		 var btn = $(this); 	 
		 
		 $.ajax({
				url:url,
				type: "POST",
				//data: param,
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
						var url = $('#transferMgmForm').attr('action');						
						submitForm(url);
					}else{
						var url = $('#transferMgmForm').attr('action');
						$('#errormsg').html(data.errormsg);
						submitForm(url);
					}
				},
				
				
		});// ajax
		 
	});
	
	$(document).on('click','.nicks',function(e){
		$('.nicks').removeClass('selected');
		$(this).addClass('selected');
		$nick = $(this).attr('id');
		$('#search_nick').val($nick);
		
		var img = '<img src="../../images/loading.gif" width="20px;" height="18px" />';
		var url = $('#transferMgmForm').attr('action');
		var param = $("#transferMgmForm").serialize();	
		var btn = $('#search'); 	
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
					$(btn).next().remove();
					$(btn).show();
				},				
				success: function(data){					 
					$("#transList").html(data);
					
				},
			
			
		});// ajax 
		
	});
	$(document).on('click','#search',function(e){
		 
		e.preventDefault();
		var url = $('#transferMgmForm').attr('action');
		var img = '<img src="../../images/loading.gif" width="20px;" height="18px" />';
		var btn = $(this); 	
		 var param = $("#transferMgmForm").serialize();	
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
					$(btn).next().remove();
					$(btn).show();
				},				
				success: function(data){					 
					$("#transList").html(data);
					
				},
				
				
		});// ajax 
	});
	
	$(document).on('click','.confirmTrans',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		var btn = $(this); 	
		
		 $.ajax({
				url:url,
				type: "POST",
				//data: param,
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
						$(btn).closest('tr').fadeOut(400,function(){
							$(this).remove();
						});						
						if ($('.no').length == 0){
							var url = $('#transferMgmForm').attr('action');
							$('#page').val(1);
							 submitForm(url);
						}else{
							$('.no').each(function(i,e){
								$(e).text(i+1);
							});
						}						
					}else{
						$('#errormsg').html(data.errormsg);
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
		 var url = $("#transferForm").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#transferMgmForm").serialize();		
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
					$("#transList").html(data);
					 
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
});