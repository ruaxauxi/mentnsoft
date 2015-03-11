$(document).ready(function(){
	
	
	$(document).on('click','.addOrder',function(){
		var orderno = $(this).data('orderno');
		$('#hiddenOrdernoAdd').val(orderno);
		 
		var tag = $(this);
		var param = $('#frmShipment').serialize();
		param += "&btnOrderAdd=true";
		var url = $('#frmShipment').attr('action');
		var btn = $(this);
		var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
		
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
				$('#divShipingOrder').html(data);
				$('#orders').combobox({});
				$('.autosizejs').remove();
				$('.soNote').autosize();
				setPosition();
				$(tag).closest('.trItem').fadeOut(300,function(){
					$(this).remove();
				});
				 
			},
			
			
		});// ajax
	});
	
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
						 var table = $('#shipmentTable').clone();
						 
						 $(table).find('tr').each(function(i,e){
							 if (i> 1){
								 $(this).remove();
							 }
						 });
						 
						 $(table).find('tfoot').remove();
						 $(table).find('input').removeAttr('name');
						 $(table).find('textarea').removeAttr('name');
						 
						 $(table).find('a').removeClass('fancybox');
						 $(table).removeAttr('id');
						 $(table).find('tfoot').remove();
						 
						/* $(table).find('input').each(function(i,e){
							$(e).removeAttr('name'); 
						 });*/					 
						 $('#divTableHeader').html(table);	
					 }					 			  
					 
					 var tbheader_top = $('#searchSection').height()+ top_pos + 5;
					 var tbheader_left = $('#shipmentTable').offset().left - $(window).scrollLeft();	
					 $('#divTableHeader').width($('#shipmentTable').width());
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
	
	
	$(document).on('click','#search',function(){
		$("#searchDialog").css('zIndex',1000);
		$("#searchResults").html('');
		
		var orderNo = $('#orderList').find('.custom-combobox-input').val();
		if (orderNo.length == 0){
			$('#orders').val('');
		}
		var table = $('#searchOrderTable').clone();
		$(table).removeAttr('id');
		$(table).find('a').removeClass('fancybox');
		$('#divResultHeader').html(table);
		
		
		$("#searchDialog").dialog({			  
			  position: {
				  my: "center",
				  at: "center",
				  of: window,
			  },
			  width: 1200,
			  height: 690,
			  modal: true,		
			  closeText: "Close",
			  title: "Search Order",
			  buttons: [
			      {
			    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
			    	click: function(){
			    		$(this).dialog('close');
			    		$("#searchResults").html('');
			    	},
			      },
			      
			  ]		      
		});
	});
	
	$(document).on('click','#submitSearch',function(){
		
			var val = $('#divSearchStore').find('.custom-combobox-input').val();
			
			if (val.length == 0){
				$('#searchStore').val('');
			}
			
			var tag = $(this);
			var storeid = $('#searchStore').val();
			var description = $('#searchDescription').val();
			var param = {store_id:storeid,description:description};			
			var url = $(tag).data('url');
			var btn = $(this);
			var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
			
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
					 $('#searchResults').html(data);	
					 
					 $('#divResultHeader').html('');
					 var table = $('#searchOrderTable').clone();
						$(table).removeAttr('id');
						$(table).find('a').removeClass('fancybox');
						 
						$('#divResultHeader').html(table);
						$('#divResultBody').slimscroll({		
							width: "100%",
							height: 520 ,							
							wheelStep: 15 
						});	
				},
				
				
			});// ajax
	});
	
	
	$('#note').autosize();
	
	$(document).on('blur','#weight',function(){
		var val = $(this).val();
		if (val){
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});	
			if (val > 0){
				val = $.formatNumber(val,{format:"#,##0.00", locale:"us"}); 
				$(this).val(val);
			}else{
				$(this).val('');
			}
		}
	});
	
	
	$(document).on('blur','.packageno, .soNote',function(){
		var initval = $(this).data('initval');
		var val = $(this).val();
		var tr = $(this).closest('.trItem');
		if (val != initval){			
			$(tr).data('isSaved',true);			
		}	
		 
	});
	
 
	$(document).on('blur','.total_web, .total_web1, .total_final',function(){
		var val = $(this).val();
		if (val){
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});	
			if (val > 0){
				val = $.formatNumber(val,{format:"#,##0.00", locale:"us"}); 
				$(this).val(val);
			}else{
				$(this).val('');
			}
		}
		
		var initval = $(this).data('initval');
		var tr = $(this).closest('.trItem');
		if (val != initval){
			$(tr).data('isSaved',true);
		}
		
	});
	
	
	
	$(document).on('blur','.items',function(){
		var val = $(this).val();
		if (val){
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});	
			if (val > 0){
				val = $.formatNumber(val,{format:"#,##0", locale:"us"}); 
				$(this).val(val);
			}else{
				$(this).val('');
			}
		}
		
		var initval = $(this).data('initval');
		var tr = $(this).closest('.trItem');
		if (val != initval){
			$(tr).data('isSaved','true');
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
			var orderno = $('#orders').val();
			var param = {'id':$('#ship_id').val(),'btnOrderAdd':true,'orders':orderno};
			//param += "&btnOrderAdd=true";
			var url = $(this).data('url');
			 
			var btn = $(this);
			var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
			
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
					if (data.success){
						var found = false;
						$('#shipmentTable>tbody').find('tr').each(function(i,e){
							if (data.stt==i){		
								found = true;
								var tr = $(this).clone();								
								$(tr).find('.soNo').text(data.stt);
								$(tr).find('.divDescription').html(data.description);
								$(tr).find('.soOrderdate').text(data.orderdate);
								$(tr).find('.soStoreName').text(data.storename);
								$(tr).find('.soHolder').text(data.holder);
								$(tr).find('.soItems').text(data.items_o);
								$(tr).find('.soDiscount').text(data.discount);
								$(tr).find('.soTotal_web').text(data.total_web_o);
								$(tr).find('.soTotal_web1').text(data.total_web1_o);
								$(tr).find('.soShipUS').text(data.ship_us);
								$(tr).find('.soTax').text(data.tax);
								$(tr).find('.soTotalFinal').text(data.total_final_o);
								
								$(tr).find('.packageno').attr('name',data.orderno+ '_package');
								$(tr).find('.packageno').val('');
								$(tr).find('.packageno').data('initval','');
								
								$(tr).find('.soNote').attr('name',data.orderno+ '_note');
								$(tr).find('.soNote').val('');
								$(tr).find('.soNote').data('initval','');
								
								$(tr).find('.total_web').attr('name',data.orderno+ '_total_web');
								$(tr).find('.total_web').val('');
								$(tr).find('.total_web').data('initval','');
								
								$(tr).find('.total_web1').attr('name',data.orderno+ '_total_web1');
								$(tr).find('.total_web1').val('');
								$(tr).find('.total_web1').data('initval','');
								
								$(tr).find('.items').attr('name',data.orderno+ '_items');
								$(tr).find('.items').val('');
								$(tr).find('.items').data('initval','');
								
								$(tr).find('.total_final').attr('name',data.orderno+ '_total_final');
								$(tr).find('.total_final').val('');
								$(tr).find('.total_final').data('initval','');
								
								$(tr).find('.delShip').data('orderno',data.orderno);
								 
								 
								$(tr).find('.hiddenOrderno').val(data.orderno);
								$(tr).find('.orderno').val(data.orderno);
								
								$('#shipmentTable>tbody').find('tr').removeClass('highlight2');
								$(tr).addClass('highlight2');
								
								$(this).before(tr);								 
							} 
						});
						
						if (!found){
							var tr = $('#shipmentTable>tbody').find('tr').last().clone();
							 
							$(tr).data('isSaved','false');							 
							$(tr).find('.soNo').text(data.stt);
							$(tr).find('.divDescription').html(data.description);
							$(tr).find('.soOrderdate').text(data.orderdate);
							$(tr).find('.soStoreName').text(data.storename);
							$(tr).find('.soHolder').text(data.holder);
							$(tr).find('.soItems').text(data.items_o);
							$(tr).find('.soDiscount').text(data.discount);
							$(tr).find('.soTotal_web').text(data.total_web_o);
							$(tr).find('.soTotal_web1').text(data.total_web1_o);
							$(tr).find('.soShipUS').text(data.ship_us);
							$(tr).find('.soTax').text(data.tax);
							$(tr).find('.soTotalFinal').text(data.total_final_o);
							
							$(tr).find('.packageno').attr('name',data.orderno+ '_package');
							$(tr).find('.packageno').val('');
							
							$(tr).find('.soNote').attr('name',data.orderno+ '_note');
							$(tr).find('.soNote').val('');
							
							$(tr).find('.total_web').attr('name',data.orderno+ '_total_web');
							$(tr).find('.total_web').val('');
							
							$(tr).find('.total_web1').attr('name',data.orderno+ '_total_web1');
							$(tr).find('.total_web1').val('');
							
							$(tr).find('.items').attr('name',data.orderno+ '_items');
							$(tr).find('.items').val('');
							
							$(tr).find('.total_final').attr('name',data.orderno+ '_total_final');
							$(tr).find('.total_final').val('');
							
							$(tr).find('.delShip').data('orderno',data.orderno);
							 
							 
							$(tr).find('.hiddenOrderno').val(data.orderno);
							$(tr).find('.orderno').val(data.orderno);
							
							$('#shipmentTable>tbody').find('tr').removeClass('highlight2');
							$(tr).addClass('highlight2');
							$('#shipmentTable>tbody').append(tr);
						}
						
						if ($('#shipmentTable>tbody').find('tr').length > 0){
							$('#shipmentTable>tbody').find('tr').each(function(i,e){
								$(this).find('.soNo').text(i+1);
							});
							$("#orders option[value='"+orderno+"']").remove();		
							$('#orderList').find('.custom-combobox-input').val('');
							$('.autosizejs').remove();
							$('.soNote').autosize();
							
							$('#total_items_tt').text(data.total_items);
							$('#total_web_tt').text(data.total_web);
							$('#total_web1_tt').text(data.total_web1);
							$('#total_final_tt').text(data.total_final);						
							$('#trSumarize').html(data.trSumzarize);
						}else{
							var url = $('#frmShipment').attr('action');
							submitForm(url);
							$('#orders').combobox({});
							$('.autosizejs').remove();
							$('.soNote').autosize();
						}
						
						
					}
					/*$('#divShipingOrder').html(data);
					$('#orders').combobox({});
					$('.autosizejs').remove();
					$('.soNote').autosize();*/
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
	
	$('#orders').combobox({});
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
	
	
	/*$(document).on('blur','#shipmentTable>tbody>tr',function(){
		var tr = $(this);
		$(tr).find('.changed').each(function(i,e){
			var initval = $(e).data('initval');
			var val = $(e).val();
			if (val != initval){
				$(tr).data('isSaved',true);
			}
		});
	});*/
	
	$(document).on('click','#btnShipeOrderSave',function(e){
		e.preventDefault();
		var btn = $(this); 
		saveShipmentOrders(btn,0);
 	 
	 });
	
	$(document).on('click','#btnFinish',function(e){
		e.preventDefault();
		var btn = $(this); 
		saveShipmentOrders(btn,1);
 	 
	 });
	function saveShipmentOrders(btn,finish){
		$('#shipmentTable>tbody>tr').removeClass('saved'); 
		$('#shipmentTable>tbody>tr').removeClass('unsaved'); 
		$('#shipmentTable>tbody>tr').removeClass('highlight2');
		
		var count = 1;
		var started = false;
		//var total = $('#shipmentTable>tbody').find('tr').length;
		var total = 0;
		$('#shipmentTable>tbody').find('tr').each(function(i,e){
			if ($(e).data('isSaved')){
				total++;
			}
		});
		
		if (total>0){
			$('#shipmentTable>tbody').find('tr').each(function(i,e){
				var tr = $(this);
				
				if ($(tr).data('isSaved')){
					var orderno = $(this).find('.orderno').val();
					var shipment_id = $('#ship_id').val();
					var note  = $(this).find('.soNote').val();
					var packageno = $(this).find('.packageno').val();
					var total_web = $(this).find('.total_web').val();
					var total_web1 = $(this).find('.total_web1').val();
					var items = $(this).find('.items').val();
					var total_final = $(this).find('.total_final').val();
					var param = {saveRow:'true',orderno:orderno,shipment_id:shipment_id,
							note:note,packageno:packageno,total_web:total_web,total_web1:total_web1,items:items,total_final:total_final};
					var url = $('#frmShipment').attr('action');
					
					var img = '<img src="../../images/loading.gif" width="32px;" height="24px" />';
					var loading = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
					var error = '<img src="../../images/del.png" width="18px;" height="14px" />';
					var success = '<img src="../../images/check.png" width="18px;" height="14px" />';
					var stt = $(tr).find('.soNo').html();
					
					$.ajax({
						url:url,
						type: "POST",
						data: param,
						dataType: "JSON",			
						error: function(xhr,status,errmgs){	
							$(tr).find('.soNo').html(stt);
						},
						beforeSend: function(){						 
							$(tr).find('.soNo').html(loading);
							if (started == false){
								$('#savingProcess').show();
								$( "#progressbar" ).progressbar({
								      value: false
								});
								
								$(btn).after(img);
								$(btn).hide();
								started = true;
								$('#saveInfo').html('');
							}
							 
						},
						complete: function(){
							if (count == total){
								$(btn).show();
								$(btn).next().remove();
								$('#saveInfo').html('<br/><span class="success">Thông tin đã được lưu.</span>');
								$('#percent').text(0 + '%');
								$('#savingProcess').hide();
								if (finish){
									// recdirect;
									$('#emptyForm').submit();
								}
							}else{						
								val = Math.floor((count/total)*100);
								$('#percent').text(val + '%');
								$( "#progressbar" ).progressbar( "option", {
							          value: val
						        });
								 
							}
							
							
							count++;
						},						
						success: function(data){
							$(tr).find('.soNo').html(stt); 
							if (data.success == 1){
								$(tr).addClass('saved');
								$(tr).removeClass('unsaved');
								$(tr).data('isSaved',false);
								$(tr).find('.changed').each(function(i,e){							 
									var val = $(e).val();							 
										$(this).data('initval',val);							 
								});
								
							}else{
								$(tr).addClass('unsaved');
								$(tr).removeClass('saved');
							}	 
						},				
						
					});// ajax
				}
				 
			});//each
		}else if(finish){
			$('#emptyForm').submit();
		}else{
			 
			$('#saveInfo').html('<br/><span class="success">Thông tin đã được lưu.</span>');
		}
		
		
	};
	
	$(document).on('click','.delShip',function(e){
		e.preventDefault();
		
		var tag = $(this);
		
		var shipment_id = $(tag).data('shipment_id');
		var orderno = $(tag).data('orderno');
		var param = {shipment_id: shipment_id,orderno: orderno};
		
		var url = $(tag).attr('href');
		var btn = $(this);
		var img = '<img src="../../images/loading.gif" width="18px;" height="14px" />';
		
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
						
						$(tag).closest('.trItem').fadeOut(300,function(){
							$(this).remove();
							
							if (data.success){
								$('#shipmentTable>tbody').find('tr').each(function(i,e){ 
									
								});
								
								$('#shipmentTable>tbody').find('tr').each(function(i,e){
									$(this).find('.soNo').text(i+1);
								});
								
								 
								$('.autosizejs').remove();
								$('.soNote').autosize();
								
								$('#total_items_tt').text(data.total_items);
								$('#total_web_tt').text(data.total_web);
								$('#total_web1_tt').text(data.total_web1);
								$('#total_final_tt').text(data.total_final);
								
								$('#total_web_dung_tt').text(data.total_web_dung);
								$('#total_web1_dung_tt').text(data.total_web1_dung);
								$('#total_items_dung_tt').text(data.total_items_dung);
								$('#total_final_dung_tt').text(data.total_final_dung);
								 
								
								$('#trSumarize').html(data.trSumzarize);
								$('#orders').append(item);
							}
							
							 
							 
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
						$("#divShipingOrder").html(data);
						$('#orders').combobox({});
						$('.autosizejs').remove();
						$('.soNote').autosize();
					}
					
			});// ajax
		 } // submitForm
	//##################################
	
});//ready