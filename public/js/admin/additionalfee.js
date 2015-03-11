$(document).ready(function(){
	 
	 
	 
	
	$('#note').autosize();
	
	$(document).on('blur','#total',function(){		
		var total = $.parseNumber($('#total').val(),{format:"#,##0", locale:"us"});	
		total =  $.formatNumber(total,{format:"#,##0.00", locale:"us"});
		 $('#total').val(total);
		 
	});
	
	$(document).on('focus','#total',function(e){
		var total = $.parseNumber($('#total').val(),{format:"#,##0", locale:"us"});	
		if (total <= 0){
			$('#total').val('');
		}
	});
	 
	$(document).on('click','#btnSave',function(e){
		e.preventDefault();
			var total = $.parseNumber($('#total').val(),{format:"#,##0", locale:"us"});	
			if (total <= 0){
				$("#notify").find('span').text('Vui lòng nhập vào số tiền.');
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  of: window,
					  buttons: [
					      {
					    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
					    	click: function(){
					    		$(this).dialog('close');
					    		$('#total').focus();
					    	},
					      },
					      
					  ]		      
				});
				
				return false;
			}
			
			var note = $('#note').val().trim();
			
			if (note.length == 0){
				$("#notify").find('span').text('Vui lòng nhập Note cho số tiền này.');
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  of: window,
					  buttons: [
					      {
					    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
					    	click: function(){
					    		$(this).dialog('close');
					    		$('#note').focus();
					    	},
					      },
					      
					  ]		      
				});
				
				return false;
			}
						
			var shipment_id = $('#hidden_shipment_id').val();
			var date = $('#ship_date').val();
			var param = {"btnSave":true,"shipment_id":shipment_id,'total':total,"note":note,"date":date};
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
					$('#divShippingFeeList').html(data);
					 
					$('#note').val('');
					$('#total').val('');
					/*$('.autosizejs').remove();
					$('#note').autosize();
					
					$('#ship_date').datepicker('option','maxDate','0');
					$("#ship_date").datepicker('setDate', new Date());
					$(document).on('keydown','#ship_date',function(e){
						e.preventDefault();
					});*/
				},
				
				
			});// ajax
	});
	
	// del
	
	$(document).on('click','.del',function(e){
			e.preventDefault();
			 	
			var shipment_id = $('#hidden_shipment_id').val();
			var id = $(this).data('id');
			var url = $(this).data('url');
			var param = {"btnDel":true,"shipment_id":shipment_id,'id':id}; 
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
					$('#divShippingFeeList').html(data); 
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
	
	
	 
	if (!$("#ship_date").val()){
		$("#ship_date").datepicker('setDate', new Date());
	}
	
	
	$("#ship_date").keydown(function (e)
			{e.preventDefault();
	});
	
	$('#shipment_id').combobox({});
	 
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	 
	 
	
	
	$(document).on('click','#btnShipeOrderSave',function(e){
		e.preventDefault();
		
		var tag = $(this);
		
		 
		var param = $('#frmShipment').serialize();
		param += "&btnShipeOrderSave=true";
		var url = $(tag).attr('action');
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
				$('.success').remove();
				$(btn).after(img);
				$(btn).hide();
			},
			complete: function(){
				$(btn).show();
				$(btn).next().remove();
			},						
			success: function(data){				
					$('#divShippingWeight').html(data);
					$('#orders').combobox({});
					setPosition();
			},
			
			
		});// ajax
		 
	 });
	
	$(document).on('click','.delShip',function(e){
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
						$("#divShippingWeight").html(data);
						$('#orders').combobox({});
						$('.autosizejs').remove();
						$('.soNote').autosize();
					}
					
			});// ajax
		 } // submitForm
	//##################################
	
});//ready