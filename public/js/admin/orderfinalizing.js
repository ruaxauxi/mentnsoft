$(document).ready(function(){
	
	$(document).on('click','.finalize',function(e){
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
						 $('.stt').each(function(i,e){
								$(e).text(i+1); 
						 });
					 });
					
				}
			},
			
			
		});// ajax
		
	});
	
	$(document).on('click','.changecreditcard',function(e){
		e.preventDefault();
		var orderno = $(this).closest('tr').find('.orderno').val();
		$('#current_creditcard').val($('#creditcard').val());
		var tag = $(this);
		$('#orderno_dialog').val(orderno);
		$("#divUpdateForm").dialog({
			  my: "center",
			  at: "center",
			  of: window,
			  width: 500,
			  height: 300,
			  modal: true,
			  closeText: "Đóng",
			  title: "Change Credit Card",
			  resizable: false,		 
			  show: {effect: "fade", duration: 200},
			  hide: {effect: "fade", duration: 200},		
			  buttons: [
						      {
						    	html: "<span class='ui-icon ui-icon-disk'>Update</span>",
						    	click: function(){
						    		
						    		if ($("#current_creditcard").val() != $('#creditcard_update').val() && $('#creditcard_update').val().length >0){
						    			var url = $(tag).data('url');
							    		var btn = $(this);
							    		var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
							    		var param = {orderno:$('#orderno_dialog').val(),creditcard:$('#creditcard_update').val(),holder:$('#card_holder').val()};
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
							    					$('#divUpdateForm').dialog('close');
							    					 $(tag).closest('.trItem').fadeOut(400,function(){
							    						 $(this).remove();
							    						 $('.stt').each(function(i,e){
							    								$(e).text(i+1); 
							    						 });
							    					 });
							    					
							    				}
							    			},
							    			
							    			
							    		});// ajax
						    		}else{
						    			$('#divUpdateForm').dialog('close');
						    		}
						    		
						    		
						    		
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
	
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	
	$('#creditcard_update').combobox({
		select: function (event, ui) {			
			 var x = ui.item.value;
			 var item;	  
        	  for (i in cards){
        		   item = cards[i];	        	   
	        	   if (x == item.creditcard){
	        		   found = true;
	        		   $('#card_holder').val(item.holder);
	        		    
	        	   }
        	  }		
        },
        change: function (event,ui){
        	if (ui.item == null){
        		$('#card_holder').val("");      		    
        	}
        }
		
	});
	
	
	
	$('#creditcard').combobox({
		select: function (event, ui) {			
			 var x = ui.item.value;
			 var item;	  
       	  for (i in cards){
       		   item = cards[i];	        	   
	        	   if (x == item.creditcard){
	        		   
	        		   $('#holder').val(item.holder);
	        		   break; 
	        	   }
       	  }		
       },
       change: function (event,ui){
       	if (ui.item == null){
       		$('#holder').val("");      		    
       	}
       }
	});
	
	 
	  
});//ready