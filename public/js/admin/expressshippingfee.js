$(document).ready(function(){
	function valid(){
		var isValid = true;
		$(".required").each(function(){
			var tag = $(this);
			if ($(tag).val().trim().length === 0){				
				$("#notify").find('span').text($(this).data("info"));
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  of: window,
					  buttons: [
					      {
					    	html: "<span class='ui-icon ui-icon-close'></span>OK",
					    	 
					    	click: function(){
					    		$(this).dialog('close');
					    		$(tag).focus();
					    	},
					      },
					      
					  ]		      
				});
				isValid = false;
				return false;
				
			}
		}); // end foreach
		
		return isValid;
	}
	
	$("#btnSave").click(function(){
	 
		if (valid()){
			
			var nick = $("#nick").val();			
			var cls =  "."+ nick;
			$(cls).closest('tr').addClass("warning").addClass('highlight');
			if ($(cls).length> 0){
				
				$("#divConfirmDialog").find('span').html('Bạn đã nhập phí CNP cho ' + nick + " trong đợt này rồi. <br/> Có chắc bạn muốn nhập tiếp?");
				$("#divConfirmDialog").dialog({
					  my: "center",
					  at: "center",
					  of: window,
					  width: 450,
					  height: 160,
					  modal: true,
					  closeText: "Đóng",
					  title: "Xác nhận nhập thêm Phí ",
					  resizable: false,		 
					  show: {effect: "fade", duration: 200},
					  hide: {effect: "fade", duration: 200},		
					  buttons: [
								      {
								    	html: "<span class='ui-icon ui-icon-check'></span>Yes",
								    	id: 'btnOK',
								    	class: 'btn btn-success',
								    	click: function(){
								    		saveExSFee();
								    		$(this).dialog('close');
								    	},
								      },
								      {
									    html: "<span class='ui-icon ui-icon-close'></span>No",
									    class: 'btn btn-default',
									    click: function(){
									    		$(this).dialog('close');
									    	},
									    },
								  ]	
				});
			}else{
				saveExSFee();
			}
		}// if valid
	});
	
	function saveExSFee(){
		
		var shipment_id = $('#hidden_shipment_id').val();
		var fee = $("#fee").val();
		var nick = $("#nick").val();
		var note = $("#note").val();
		var param = {"btnSave":true,"shipment_id":shipment_id,'nick':nick,'fee':fee,"note":note};
		var url = $('#frmExpressShipping').attr('action');
		 
		var btn = $("#btnSave");
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
				$('#divList').html(data);				 
				$('#note').val('');
				$('#fee').val('');
				
				$("#nick").val("");
				$("#nick").next().find(".custom-combobox-input").val('');
				 
			},
			
			
		});// ajax
	};
	 
	$("#note").autosize();
	 
	$(document).on('keyup','#fee',function(){
		$(this).parseNumber({format:"#,###", locale:"us"});
		$(this).formatNumber({format:"#,###", locale:"us"});
	});
	
	$(document).on('blur','#fee',function(){
		$(this).parseNumber({format:"#,###", locale:"us"});
		$(this).formatNumber({format:"#,###", locale:"us"});
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
					$("#splash").find("#splashcontent").html("Đang tải dữ liệu, vui lòng chờ"); 
					$("#splash").fadeIn(200);	
				},
				complete: function(){
					$(btn).show();
					$(btn).next().remove();
					$("#splash").hide();
				},						
				success: function(data){					
					$('#divShippingFeeList').html(data); 
				},
				
				
			});// ajax
	});
	
	
	$('#shipment_id').combobox({});
	
	$("#nick").combobox({});
	 
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