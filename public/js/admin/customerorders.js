$(document).ready(function(e){
	$('.note').autosize();
	
	
	$('.store').combobox({		
	});
	
	$('.nick').combobox({		
	});
	
	$(document).on('blur','.note',function(){
		var current = $(this).data('current');
		var val = $(this).val();
		var tag = $(this);
		if (val != current){
			var url = $('#orderForm').prop('action');
			var orderid = $(tag).closest('td').find('.orderid').val();
			var param = {orderid:orderid,note:val,updatenote:true};
			var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
			
			$.ajax({
				url:url,
				type: "POST",
				data: param,
				dataType: "JSON",			
				error: function(xhr,status,errmgs){					
				},
				beforeSend: function(){
					$(tag).after(img);					
					$(tag).addClass('saving');
				},
				complete: function(){
					$(tag).next().remove();
					$(tag).removeClass('saving');
				},				
				success: function(data){
					if (data.success){
						$(tag).data('current',val);
					}
					
				},
				
				
		});// ajax 
		}
	});
	
	$('.divDescription').each(function(i,e){
		var height = $(this).height();	
			$(this).slimscroll({		
				width: 560,
				height: height ,
				"min-height": 250,
				wheelStep: 15 
			});			
		 
	});
	
	$(document).on('click','.orderchecked',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		var btn = $(this); 	
		var id = '#note_' + $(this).data('id');
		
		var note = $(id).val();
		var param = {note:note};
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
						$(btn).closest('tr').fadeOut(400,function(){
							$(this).remove();
							if ($('.no').length == 0){
								var url = $('#orderForm').attr('action');
								$('#page').val(1);
								 submitForm(url);
							}else{
								$('.no').each(function(i,e){
									$(e).text(i+1);
								});
							}
						});		
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
		 var url = $("#orderForm").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#orderForm").serialize();		
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
					$("#orderList").html(data);
					$('.divDescription').each(function(i,e){
						var height = $(this).height();					 
							$(this).slimscroll({		
								width: 560,
								height: height ,
								"min-height": 250,
								wheelStep: 15 
							});			
						 
					});
					 
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