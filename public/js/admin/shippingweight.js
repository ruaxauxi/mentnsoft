$(document).ready(function(){
	 
	$(window).scroll(function(){		 
		setPosition();
	 });


	function setPosition(){
		if ($("#anchor").length){
			var anchor = $("#anchor").offset().top ;
			var top = ( $(window).scrollTop())+ 40;
			 var top_pos = top - $(window).scrollTop() ;
			 var left_pos = $('#anchor').offset().left -  $(window).scrollLeft();
			 if  ( top >= anchor ){			
				 $('#searchSection').css('position','fixed').css('top',top_pos).css('left',left_pos);		
				 
				 if ((top >= anchor + 5)){
					 if ($('#divTableHeader').html().length <= 0){
						 var table = $('#shippingWeightTable').clone();
						 $(table).find('a').removeClass('fancybox');
						 $(table).removeAttr('id');
						 $(table).find('tfoot').remove();
						 
						 $(table).find('input').each(function(i,e){
							$(e).removeAttr('name'); 
						 });					 
						 $('#divTableHeader').html(table);	
					 }					 			  
					 
					 var tbheader_top = $('#searchSection').height()+ top_pos ;
					 var tbheader_left = $('#shippingWeightTable').offset().left - $(window).scrollLeft();	
					 $('#divTableHeader').width($('#shippingWeightTable').width());
					 $('#divTableHeader').css('position','fixed').css('top',tbheader_top).css('left',tbheader_left);
					 $('#divTableHeader').show();
				 }else{
					 $('#divTableHeader').hide();
					 
				 }
				 
				 
			 }else{
				 $('#searchSection').css('position','relative').css('top','').css('left','');
				 $('#divTableHeader').hide();
			 }
		}
	}
	
	
	 
	
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
		$('#shippingWeightTable .weight').each(function(){
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
					$('#divShippingWeight').html(data);
					$('#orders').combobox({});
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
	
	$('#shipment_id').combobox({});
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