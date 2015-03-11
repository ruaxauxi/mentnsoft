$(document).ready(function(){
	
	$(document).on('click','.del',function(e){
		e.preventDefault();
		var tag = $(this);
		
		$("#divDeleteForm").find('span').html('Có chắc bạn muốn xóa toàn bộ dữ liệu <span class="bigsize">' + $(this).data('my') + '</span>?');
		$("#divDeleteForm").dialog({
			  my: "center",
			  at: "center",
			  of: window,
			  width: 450,
			  height: 160,
			  modal: true,
			  closeText: "Đóng",
			  title: "Xóa dữ liệu Thông tin chuyển khoản",
			  resizable: false,		 
			  show: {effect: "fade", duration: 200},
			  hide: {effect: "fade", duration: 200},		
			  buttons: [
						      {
						    	html: "<span class='ui-icon ui-icon-check'>OK</span>",
						    	id: 'btnOK',
						    	click: function(){
						    		 
						    			var url = $('#shipmentForm').prop('action'); 
							    		var btn = $(this);
							    		var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
							    		var month = $(tag).data('month');
							    		var year = $(tag).data('year');
							    		var param = {month:month,year:year};
							    		$.ajax({
							    			url:url,
							    			type: "POST",			 
							    			dataType: "JSON",
							    			data:param,
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
							    					 
							    					$("#divDeleteForm").find('span').html('Đã xóa tổng cộng <b>'+data.total + ' thông tin ck</b>.');
							    					 $(tag).closest('tr').fadeOut(400,function(){
							    						 $(this).remove();
							    						 var k = $('#start').val();
							    						 $('.stt').each(function(i,e){
							    								$(e).text(k++); 
							    						 });
							    						 
							    						 $('#btnOK').hide();
							    					 });
							    					
							    				}else{
							    					$("#divDeleteForm").find('span').html('Không thể xóa vui lòng thử lại.');
							    				}
							    			},
							    			
							    			
							    		});// ajax
						    		  
						    	},
						      },
						      {
							    html: "<span class='ui-icon ui-icon-close'>Cancel</span>",
							    click: function(){
							    		$(this).dialog('close');
							    	},
							    },
						  ]	
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
			 var url = $("#frmDelTransfers").attr('action');
			 // reset current page 
			 $('#page').val(1);
			 submitForm(url);
		 });
		 
	//##################################
		 
	//########loads data##############
		 function submitForm(url){
			 var param = $("#frmDelTransfers").serialize();		
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
						$("#divList").html(data);
						 
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