$(document).ready(function(){
	 
	$(document).on('change','#unshipped',function(){
		if ($(this).prop('checked')){
			$('#orderDetailTable>tbody').hide();
		}else{
			$('#orderDetailTable>tbody').show();
		}
	});
	
	/*$(document).on('change','#cancelall',function(){
		if ($(this).prop('checked')){
			$('#orderDetailTable .trItem').each(function(){
				var tr = $(this);
				var items = $(tr).find('.items_val').val();
				var totalFinal = $(tr).find('.totalFinal_val').val();				
				$(tr).addClass('itemcancelled');
				$(tr).removeClass('itembackorder');
				$(tr).find('.totalItems').val(items);
				$(tr).find('.totalUpdate').val(totalFinal);
				$(tr).find('input:radio[class="rdoCommand"][value="cancel"]').prop('checked',true);
				$(tr).find('input:radio[class="rdoCommand"]').prop('readonly',false);
				$(tr).find('.totalItems').prop('readonly',true);
				$(tr).find('.totalUpdate').prop('readonly',true);	
			});
		}else{
			$('#orderDetailTable .trItem').each(function(){
				var tr = $(this);
				$(tr).removeClass('itemcancelled');
				$(tr).removeClass('itembackorder');
				$(tr).find('.totalItems').val('0');
				$(tr).find('.totalUpdate').val('0.00');
				$(tr).find('input:radio[class="rdoCommand"][value="ok"]').prop('checked',true);
				$(tr).find('input:radio[class="rdoCommand"]').prop('readonly',false);
				$(tr).find('.totalItems').prop('readonly',false);
				$(tr).find('.totalUpdate').prop('readonly',false);	
			});
		}
	});*/
	
	$(document).on('keyup','.totalItems',function(){
		$(this).parseNumber({format:"#,###", locale:"us"});
		$(this).formatNumber({format:"#,###", locale:"us"});
	});
	
	$(document).on('blur','#totalCancel, #totalCancel_web1',function(){
		var val = $.parseNumber($(this).val(),{format:"#,###.00", locale:"us"});
		if (val > 0){
			val = $.formatNumber(val,{format:"#,###.00", locale:"us"});			
			$(this).val(val);
		}else{
			$(this).val('');
		}
		
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
				    		return false;
				    	},
				      },
				      
				  ]		      
			});
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
				    		return false;
				    	},
				      },
				      
				  ]		      
			});
		}		
		if (val == 0){
			$(this).val('0.00');
		}else{
			$(this).val(val);
		}
		
	});
	
	
	$(document).on('click','#btnShow',function(e){
		e.preventDefault();
		var param = $('#frmShipment').serialize();
		param += "&btnShow=true";
		var url = $('#frmShipment').attr('action');
		var btn = $(this);
		var img = '<img src="../../images/loading.gif" width="24px;" height="18px" />';			
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
					$('#divOrderDetails').html(data);
					$('#orderno').combobox({});
					 
					$('.soNote').autosize();
			},				
			
		});// ajax
	});
	
	
	$(document).on('click','#btnFinish',function(e){
		e.preventDefault();
		var error = false;
		var cancelled = false;
		if (!$('#unshipped').prop('checked')){
			$('#orderDetailTable .trItem').each(function(){
				var item;
				var total;
				var command = $(this).find('input[class=rdoCommand]:checked').val();
				
				if (command == "cancel"){
					cancelled = true;
					item = $(this).find('.totalItems').val();			
					total = $(this).find('.totalUpdate').val();
					 
					if (item <= 0 || total <= 0){
						$(this).find('.totalItems').addClass('error');
						$(this).find('.totalUpdate').addClass('error');
						error = true;
					}else{
						$(this).find('.totalItems').removeClass('error');
						$(this).find('.totalUpdate').removeClass('error');
					}
				}
				
				if (command == "backordered"){
					item = $(this).find('.totalItems').val();			
					total = $(this).find('.totalUpdate').val();
					 
					if (!(item > 0)){
						$(this).find('.totalItems').addClass('error');	
						$(this).find('.totalUpdate').removeClass('error');
						error = true;
					}else{
						$(this).find('.totalItems').removeClass('error');
						$(this).find('.totalUpdate').removeClass('error');
					}
				}
				
			});
			
			var total_cancel = $('#totalCancel').val();
			total_cancel = $.parseNumber(total_cancel,{format:"#,###.00", locale:"us"});
			
			var total_cancel_web1 = $('#totalCancel_web1').val();
			total_cancel_web1 = $.parseNumber(total_cancel_web1,{format:"#,###.00", locale:"us"});
			
			if (cancelled){
				if (total_cancel_web1 <= 0){
					$("#notify").find('span').text('Nhập vào Total cancel (Web1) cho các item bị cancel.');
					$("#notify").dialog({
						  my: "center",
						  at: "center",
						  modal: true,
						  of: window,
						  buttons: [
						      {
						    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
						    	click: function(){
						    		$(this).dialog('close');					    									 
						    		$('#totalCancel_web1').focus();	
						    	},
						      },
						      
						  ]		      
					});
					
					return false;
				}
				if (total_cancel <= 0){
					$("#notify").find('span').text('Nhập vào Total cancel (final) cho các item bị cancel.');
					$("#notify").dialog({
						  my: "center",
						  at: "center",
						  modal: true,
						  of: window,
						  buttons: [
						      {
						    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
						    	click: function(){
						    		$(this).dialog('close');					    									 
						    		$('#totalCancel').focus();	
						    	},
						      },
						      
						  ]		      
					});
					
					return false;
				}
				
				
			}else if (!cancelled &&  (total_cancel > 0 || total_cancel_web1 > 0)){
				
				$("#notify").find('span').text('Chưa có Item nào bị cancel, tổng $ các item bị cancel không hợp lệ.');
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  modal: true,
					  of: window,
					  buttons: [
					      {
					    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
					    	click: function(){
					    		$(this).dialog('close');
					    		$('#totalCancel').focus();								 
								return false;
					    	},
					      },
					      
					  ]		      
				});
				return false;
			}
		}
		
		if (!error || $('#unshipped').prop('checked')){				 
			var param = $('#frmShipment').serialize();
			param += "&btnFinish=true";
			var url = $('#frmShipment').attr('action');
			var btn = $(this);
			var img = '<img src="../../images/loading.gif" width="24px;" height="18px" />';			
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
						$('#divOrderDetails').html(data);
						$('#orderno').combobox({});
						 
				},				
				
			});// ajax
		}else{
			$("#notify").find('span').text('Giá trị nhập chưa hợp lệ, vui lòng kiểm tra lại.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  modal: true,
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
			return false;
		}
	});
	
	$(document).on('focus','.totalItems, .totalUpdate',function(){
		var val = $(this).val();
		var enabled = !$(this).prop('readonly');
		val = $.parseNumber($(this).val(),{format:"#,###.00", locale:"us"});
		if (val == 0 && enabled){
			$(this).val('');
		}
	});
	
	 
	
	$(document).on('change','.rdoCommand',function(){
		/*if ($('#cancelall').prop('checked')){
			if ($(this).val() != 'cancel'){
				this.checked = false;
				$(this).closest('tr').find('input:radio[class="rdoCommand"][value="cancel"]').prop('checked',true);
			} 
			return true;
			
		}*/
		
		var val = $(this).val();
		var tr = $(this).closest('tr');
		if (val == "cancel"){
			var items = $(tr).find('.items_val').val();
			var totalFinal = $(tr).find('.totalFinal_val').val();
			$(tr).addClass('itemcancelled');
			$(tr).removeClass('itembackorder');
			/*$(tr).find('.totalItems').val(items);
			$(tr).find('.totalUpdate').val(totalFinal);*/
			
			$(tr).find('.totalItems').val('0');
			$(tr).find('.totalUpdate').val('0');
			$(tr).find('.totalItems').prop('readonly',false);
			$(tr).find('.totalUpdate').prop('readonly',false);	
			
			return true;
		}
		if (val == "ok"){
			$(tr).removeClass('checked');
			$(tr).removeClass('itembackorder');
			$(tr).find('.totalItems').val('0');
			$(tr).find('.totalUpdate').val('0.00');
			$(tr).find('.totalItems').prop('readonly',true);
			$(tr).find('.totalUpdate').prop('readonly',true);	
			
			return true;
		}
		if (val == "backordered"){
			var items = $(tr).find('.items_val').val();
			var totalFinal = $(tr).find('.totalFinal_val').val();
			$(tr).removeClass('itemcancelled');
			$(tr).addClass('itembackorder');
			$(tr).find('.totalItems').val('0');
			$(tr).find('.totalUpdate').val('0');
			$(tr).find('.totalItems').prop('readonly',false);
			$(tr).find('.totalUpdate').prop('readonly',true);				
			return true;
		}
		
	});

	  
	$('.fancybox').fancybox({
		prevEffect : 'none',
		nextEffect : 'none',
		openEffect  : 'none',
		closeEffect : 'none',
		
		closeBtn  : true,
		arrows    : true,
		nextClick : true,

		helpers : {
			thumbs : {
				width  : 50,
				height : 50
			}
		}
	});
	
	
	$(document).on('mouseenter','.divDescription',function(){
		$(this).find('.photo').show();
		 
	});
	$(document).on('mouseleave','.divDescription',function(){
		$(this).find('.photo').hide();
	});
	
	 
	$('.soNote').autosize();
	$('#note').autosize();
	
	$(document).on('blur','.weight',function(){
		var val = $(this).val().trim();
		if (val){
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});	
			var tag = $(this);
			if (val < 0){
				$("#notify").find('span').text('Shipping weight không được < 0.');
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  modal: true,
					  of: window,
					  buttons: [
					      {
					    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
					    	click: function(){
					    		$(this).dialog('close');
					    		$(tag).focus();
								$(tag).val('');
								return false;
					    	},
					      },
					      
					  ]		      
				});
				
			}
			val = $.formatNumber(val,{format:"#,##0.00", locale:"us"});	
			if (val > 0){
				$(this).val(val);
			}else{
				$(this).val('');
			}			
		}
		calc();
	});
	
	function calc(){
		var total = 0;
		var val;
		$('#orderDetailTable .weight').each(function(){
			val = $(this).val();
			
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});	
			total += val;
		});
		total = $.formatNumber(total,{format:"#,##0.00", locale:"us"});	
		$('#total_weight').text(total);
	}
	
	$(document).on('focus','.weight',function(){
		var val = $(this).val();		 
		if (val){
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});	
			if (val == 0){
				$(this).val('');
			}
		}
	});
	
	$('.fancybox').fancybox({
		prevEffect : 'none',
		nextEffect : 'none',
		openEffect  : 'none',
		closeEffect : 'none',
		
		closeBtn  : true,
		arrows    : true,
		nextClick : true,

		helpers : {
			thumbs : {
				width  : 50,
				height : 50
			}
		}
	});
	
	
	$(document).on('mouseenter','.divDescription',function(){
		$(this).find('.photo').show();
		 
	});
	$(document).on('mouseleave','.divDescription',function(){
		$(this).find('.photo').hide();
	});
	
	$(document).on('click','.viewmore',function(){
		$(this).find('span').toggleClass('ui-icon-minus','ui-icon-plus');
		if (!$(this).data('show')){
			$(this).closest('tr').next().find('.extraInfo').slideDown(300);
			$(this).data('show',1);
		}else{
			$(this).closest('tr').next().find('.extraInfo').slideUp(300);
			$(this).data('show',0);
		}
		 
		 
	});
	
	$(document).on('click','#btnOrderAdd',function(e){
		e.preventDefault();
			if ($('#orderList .custom-combobox-input').val().length <= 0){
				$("#notify").find('span').text('Bạn phải chọn OrderNo.');
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
				
				return false;
			}
			
			var tag = $(this);
			var param = $('#frmShipment').serialize();
			param += "&btnOrderAdd=true";
			var url = $('#frmShipment').attr('action');
			 
			var btn = $(this);
			var img = '<img src="../images/loading.gif" width="18px;" height="14px" />';
			
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
					$('#divOrderDetails').html(data);
					$('#orderno').combobox({});
					$('.autosizejs').remove();
					$('.soNote').autosize();
					setPosition();
					 
				},
				
				
			});// ajax
	});
	
	$( ".datepicker" ).datepicker({
		 showButtonPanel: true,
		 changeMonth: true,
	     changeYear: true,
		 constrainInput: true,
		// minDate: '-60Y',
		// maxDate: '-10Y',
		 //yearRange: '1970:' + year,
		// showOn: "button",
	 });
	
	$('#ship_date').datepicker('option','maxDate','0');
	 
	if (!$("#ship_date").val()){
		$("#ship_date").datepicker('setDate', new Date());
	}
	
	
	$("#ship_date").keydown(function (e)
			{e.preventDefault();
	});
	
	$('#orderno').combobox({});
	$('#searchStore').combobox({});
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$('.soNote').autosize();
	
	$(document).on('focus','.soNote',function(e){
	 
		//$(this).height(60);
	});
	$(document).on('blur','.soNote',function(e){
		//$(this).height(20);
	});
	
	
	
	
	/*$(document).on('click','.delShip',function(e){
		e.preventDefault();
		
		var tag = $(this);
		
		var shipment_id = $(tag).data('shipment_id');
		var orderno = $(tag).data('orderno');
		var param = {shipment_id: shipment_id,orderno: orderno};
		
		var url = $(tag).attr('href');
		var btn = $(this);
		var img = '<img src="../images/loading.gif" width="18px;" height="14px" />';
		
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
				$(btn).show();
				$(btn).next().remove();
			},						
			success: function(data){				
				if (data.success.toString() === 'yes'){				
						var item = "<option value='" + orderno + "'>" + orderno + "</option>";
						$('#orders').append(item);
						$(tag).closest('.trItem').fadeOut(300,function(){
							$(this).remove();
							var url = $('#frmShipment').attr('action');
							submitForm(url);
							 
						});
				}
			},
			
			
		});// ajax
		 
	 });
	*/
		
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
			 var param = $("#frmShipment").serialize();		
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
						$("#divOrderDetails").html(data);
						$('#orderno').combobox({});
						$('.autosizejs').remove();
						$('.soNote').autosize();
					}
					
			});// ajax
		 } // submitForm
	//##################################
	
});//ready