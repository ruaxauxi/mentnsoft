$(document).ready(function(){
	 
	$(document).on('click','.filter',function(e){
		e.preventDefault();
		$('#type').val($(this).data('type'));
		
		 var url = $("#frmQuestion").attr('action');		  
		 submitForm(url);
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
		 var url = $("#frmQuestion").attr('action');
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