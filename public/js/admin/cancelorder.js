$(document).ready(function(){
	$('#orders').combobox({});
	$('.soNote').autosize();
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	
	
	$(document).on('change','.chkEdit',function(){
		var checked = $(this).prop('checked');
		var tr = $(this).closest('.trItem');
		if (checked){			
			$(tr).find('.totalItems').prop('disabled',false);
			
			$(tr).find('.totalUpdate').prop('disabled',false);
			$(tr).find('.soNote').prop('disabled',false);
		}else{
			$(tr).find('.totalItems').prop('disabled',true);
			$(tr).find('.totalItems').val('');
			$(tr).find('.totalItems').removeClass('error');
			
			$(tr).find('.totalUpdate').prop('disabled',true);
			$(tr).find('.totalUpdate').val('');
			$(tr).find('.totalUpdate').removeClass('error');
			
			$(tr).find('.soNote').prop('disabled',true);
			$(tr).find('.soNote').val('');
			$(tr).find('.soNote').removeClass('error');
			
			calc();
		}
		
	});
	
	$(document).on('click','#btnSave',function(){
		 
		if ($('.trItem').find('.chkEdit:checked').length<=0){
			$("#notify").find('span').text('Chưa có item nào bị cancel. Check vào item và nhập vào thông tin để lưu.');
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
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}
		
		var url=$(this).data('url');
		var total_web1 = $('#total_web1').val();
		 
		total_web1 = $.parseNumber(total_web1,{format:"#,###.00", locale:"us"});
		
		var total_final = $('#total_final').val();
		total_final = $.parseNumber(total_final,{format:"#,###.00", locale:"us"});
		
		
		
		// validate total update and total item
		var error = false;
		$('.trItem').each(function(){
			if ($(this).find('.chkEdit').prop('checked')){
				 
				var totalItems = $(this).find('.totalItems').val();				
				totalItems = $.parseNumber(totalItems,{format:"#,###",locate:'us'});
				
				var totalUpdate = $(this).find('.totalUpdate').val();
				totalUpdate = $.parseNumber(totalUpdate,{format:"#,###.00",locate:'us'});
				
				var soNote = $(this).find('.soNote').val().trim();
				
				if (totalItems<=0){
					$(this).find('.totalItems').addClass('error');
					error = true;
				}else{
					$(this).find('.totalItems').removeClass('error');
				}
				
				if (totalUpdate<=0){
					$(this).find('.totalUpdate').addClass('error');		
					error = true;
				}else{
					$(this).find('.totalUpdate').removeClass('error');
				}
				
				if (!soNote){
					$(this).find('.soNote').addClass('error');
					error = true;
				}else{
					$(this).find('.soNote').removeClass('error');
				}
			}
		});
		
		// is error?
		if (error){
			$("#notify").find('span').text('Các giá trị không được bỏ trống. Vui lòng nhập vào để tiếp tục.');			
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
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}
		
		
		// check total web1
		if (total_web1<=0){
			$("#notify").find('span').text('Nhập lại Total web1 cho order.');
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
				    		$('#total_web1').focus();
				    		$('#total_web1').addClass('error');
				    		
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}else{
			$('#total_web1').removeClass('error');		 
		}
		
		// check total final
		if (total_final<=0){
			
			$("#notify").find('span').text('Nhập lại Total Final cho order.');
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
				    		$('#total_final').focus();
				    		$('#total_final').addClass('error');
				    		
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}else{
			$('#total_final').removeClass('error'); 
		}
		
		// OK
		
		var param = $('#frmCancelOrder').serialize();
		
		$.ajax({
			url:url,
			type: "POST",
			data: param,
			dataType: "HTML",			
			error: function(xhr,status,errmgs){},
			beforeSend: function(){
				$("#notify").find('span').text('Đang lưu dữ liệu, vui lòng chờ.');
				var divImg = '<div><img src="../../images/loading.gif" width="14px;" height="14px" /></div>';
				$("#notify").find('span').after(divImg);
				$("#notify").find('button').remove();
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  of: window,
					  modal: true,	
					  closeOnEscape: false
				});					  			
			},
			complete: function(){
				$("#notify").find('div').remove();
				$("#notify").dialog('close');				
			},
			success: function(data){
				$('#allInfo').html(data);
				$('#orders').combobox({});
				$('.soNote').autosize();
			}
			
		});// ajax
		
	}); // btnSave click
	
	
	// validate total web1
	$(document).on('blur','#total_web1',function(){
		var val = $.parseNumber($(this).val(),{format:"#,###.00", locale:"us"});
		var tag = $(this);
		if (val > 0){
			
			var current_val = $('#current_total_web1').val();			 
			current_val = $.parseNumber(current_val,{format:"#,###.00", locale:"us"});			
			
			if (val>current_val){
				$("#notify").find('span').text('Giá trị không được lớn hơn Total web1 hiện tại.');
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
					    		$(tag).val(0);
					    		$(tag).focus();
					    		$(tag).addClass('error');
					    		
					    	},
					      },
					      
					  ]		      
				});
				return false;
			}else{				 
				$(this).val($.formatNumber(val,{format:"#,###.00", locale:"us"}));
				$(this).removeClass('error');
			}
		}else{
			$(this).val('');
		}
		
	});
	
	
	// validate total final
	$(document).on('blur','#total_final',function(){
		var tag = $(this);
		var val = $.parseNumber($(this).val(),{format:"#,###.00", locale:"us"});
		if (val > 0){
			var current_val = $('#current_total_final').val();
			current_val = $.parseNumber(current_val,{format:"#,###.00", locale:"us"});
			
			if (val>current_val){
				$("#notify").find('span').text('Giá trị không được lớn hơn Total Final hiện tại.');
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
					    		$(tag).val(0);
					    		$(tag).focus();
					    		$(tag).addClass('error');
					    		
					    	},
					      },
					      
					  ]		      
				});
				
				return false;
			}else{
				$(this).removeClass('error');
				 
				$(this).val($.formatNumber(val,{format:"#,###.00", locale:"us"}));
			}
			
		}else{
			$(this).val('');
		}
		
	});
	
	
	// calcuates the total item and total update
	function calc(){
		var total = 0;
		$('.totalItems').each(function(i,e){
			var val = $.formatNumber($(this).val(),{format:"#,###", locale:"us"});
			total += $.parseNumber($(this).val(),{format:"#,###", locale:"us"});			
		});
		
		$('#totalCanelledItem').val(total);
		$('#spanTotalItem').text(total);
		
		var totalUpdate=0;
		
		$('.totalUpdate').each(function(i,e){
			var val = $.formatNumber($(this).val(),{format:"#,###.00", locale:"us"});
			totalUpdate += $.parseNumber($(this).val(),{format:"#,###.00", locale:"us"});			
		});
		
		$('#totalUpdated').val(totalUpdate);		
		$('#spanTotalUpdate').text($.formatNumber(totalUpdate,{format:"#,###.00", locale:"us"}));
		
	}
	
	$(document).on('keyup','.totalUpdate',function(){		 
		calc();
		
	});
	
	$(document).on('keyup','.totalItems',function(){
		$(this).parseNumber({format:"#,###", locale:"us"});
		$(this).formatNumber({format:"#,###", locale:"us"});		
		calc();
		
	});
	
	
	$(document).on('blur','.totalItems',function(){
		var val = $.parseNumber($(this).val(),{format:"#,###", locale:"us"});
		var item = $(this).closest('.trItem').find('.items_val').val();
		var tag = $(this);
		if (!(val >= 0 && val <= item)){
			$("#notify").find('span').text('Số item không hợp lệ.');
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
				    		$(tag).val(0);
				    		$(tag).focus();
				    		calc();
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}		
		if (val == 0){
			$(this).val(0);
		}
	 	
	});
	
	
	$(document).on('blur','.totalUpdate',function(){
		var val = $(this).val();
		var total = $(this).closest('.trItem').find('.totalFinal_val').val();
		val = $.parseNumber(val,{format:"#,###.00", locale:"us"});
		var tag = $(this);
		if (!(val >= 0 && val <= total)){
			$("#notify").find('span').text('Số $ không được lớn hơn Total Final.');
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
				    		$(tag).val(0);
				    		$(tag).focus();
				    		calc();
				    	},
				      },
				      
				  ]		      
			});
			return false;
		}		
		if (val == 0){
			$(this).val('0.00');
		}else{
			$(this).val(val);
		}		
	});
	
});