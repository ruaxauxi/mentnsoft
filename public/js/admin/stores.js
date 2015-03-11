$(document).ready(function(e){
	
	$('#search').autocomplete({
		source: data,
	});
	
	$(document).on('keyup','#name',function(e){
		if ($(this).val().trim().length == 0){
			$('#btnAddNew').attr('disabled','disabled');
		}else{
			$('#btnAddNew').removeAttr('disabled');
		}		
	});
	
	
	$(document).on('click','#btnAddNew',function(e){
		 e.preventDefault();
		
		 var url = $("#storesAddForm").attr('action');
		 var param = $("#storesAddForm").serialize();
		 var img = '<img src="../../images/loading.gif" width="26px;" height="22px" style="margin-right: 5px;" />';
		 var btn = $(this);
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
						var url = $('#storesForm').attr('action');
						$('#name').val('');
						 
						$('#btnAddNew').attr('disabled','disabled');
						submitForm(url);
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
		 var url = $("#xratesForm").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#storesForm").serialize();		
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
					$("#storesList").html(data);
					 
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