$(document).ready(function(){
	$('.description').autosize();
	
	$('.store').combobox({		
	});
	
	$(document).on('click','.orderdelete',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		 var btn = $(this); 
		 $.ajax({
				url:url,
				type: "GET",
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
						var url = $('#orderForm').attr('action');						
						submitForm(url);
					}else{
						var url = $('#orderForm').attr('action');
						$('#errormsg').html(data.errormsg);
						submitForm(url);
					}
				},
				
				
		});// ajax
		 
	});
	
	
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$(document).on('click','#btnSave',function(e){
		
		var valid = true;
		$('.orderRow').find('select').each(function(i,e){
			if(!$(e).val()){
				valid = false;
				$(e).next().find('input').addClass('error');
			}else{
				$(e).next().find('input').removeClass('error');
			}
		});
		
		if (!valid){
			e.preventDefault();
			$("#notify").find('span').text('Bạn chưa chọn Store.');
			$("#notify").dialog({
				 my: "center",
			     at: "center",
			     of: window,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    	},
				      },
				      
				  ]		      
			});
		}
		 
	});
	
	$(document).on('click','#addRow',function(e){
		var cols = $('#hiddenRow').find('td').clone();
		
		var newRow = $('<tr></tr>');
		$(newRow).addClass('orderRow');		
		$(newRow).append(cols);
		$(newRow).find('select').addClass('store');

		$(this).closest('tr').before(newRow);
		
		$('.orderRow').find('.no').each(function(i,e){
			$(this).text(i+1);
		});
		
		$('.description').autosize();
		
		$('.store').combobox({		
		});
		 
		
	});
	
	
	$(document).on('click','.delOrder',function(e){
		$(this).closest('tr').fadeOut(400,function(){
			$(this).remove();
		});
		
		$('.orderRow').find('.no').each(function(i,e){
			$(this).text(i+1);
		});
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
	
});// ready