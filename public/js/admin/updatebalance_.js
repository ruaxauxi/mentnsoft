function checkIsNumberKey(evt)
	{
	   var charCode = (evt.which) ? evt.which : event.keyCode;
	   if (charCode != 45 && charCode != 46 && charCode > 31 
	     && (charCode < 48 || charCode > 57))
	      return false;

	   return true;
	}

$(document).ready(function(){
		
	$(document).on('click','#btnFilter',function(e){	
		e.preventDefault();
		var url = $('#updatebalanceInfoForm').attr('action');		
		 var param = $("#updatebalanceInfoForm").serialize();		
		 var btn = $(this);
		 var img = '<img src="../images/loading.gif" width="18px;" height="14px" />';
		 $.ajax({
				url:url,
				type: "POST",
				data: param,
				dataType: "HTML",			
				error: function(xhr,status,errmgs){},
				beforeSend: function(){	
					$(btn).after(img);
					$(btn).hide();
				},
				complete: function(){
					$(btn).show();
					$(btn).next().remove();
				},				
				success: function(data){					 
					$("#transList").html(data);
					$('#admin_filter').combobox({});
					$('#nick_filter').combobox({});
					
					$('#date_filter').val('');
					
					 
				}
				
		});// ajax
	});
	
	$(document).on('click','#btnSubmit',function(e){	
		e.preventDefault();
		if (!valid()){			
			$("#notify").find('span').text('Thông tin nhập chưa đầy đủ.');
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
		
		 var url = $("#updatebalanceForm").attr('action');
		 var param = $("#updatebalanceForm").serialize();
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
						var url = $('#updatebalanceInfoForm').attr('action');
						$('.required').val('');
						$('#note').val('');
						$('#btnSubmit').attr('disabled','disabled');
						$('#divNicks .custom-combobox-input').val('');
						$('#divShipments .custom-combobox-input').val('');
						$('#shipment').val('');
						$('#divOrderNo .custom-combobox-input').val('');
						$('#orderno').val('');
						
						$("#trans_date").datepicker('setDate', new Date());
						$('#ui-datepicker-div').remove();
						submitForm(url);
					}else{
						$('#errormsg').html(data.msg);
					}
				},
				
				
		});// ajax
	});
	
	
	$('#nicks').combobox({
		select: function(event,ui){
			validate();
		},
	});
	
	$('#orderno').combobox({
		select: function(event,ui){			 
			validate();
		},
	});
	
	$('#shipment').combobox({
		select: function(event,ui){			 
			validate();
		},
	});
	
	$('#orderno').next().find('input').blur(function(){
		validate();
	});
	
	$('#shipment').next().find('input').blur(function(){
		validate();
	});
	
	$('#orderno').next().find('input').focus(function(){	
		$('#shipment').val('');		 
		$('#divShipments .custom-combobox-input').val('');
	});
	
	$('#shipment').next().find('input').focus(function(){		 
		$('#orderno').val('');
		$('#divOrderNo .custom-combobox-input').val('');
	});
	
	
	
	$('#admin_filter').combobox({});
	$('#nick_filter').combobox({});
	
	$('#date_filter').datepicker('option','maxDate','0');
	$("#date_filter").datepicker('setDate', new Date());
	$("#date_filter").keydown(function (e){
		if (e.keyCode != 127){
			e.preventDefault();
		}
			
	});
	
	
	$(document).on('focus','#divNicks .custom-combobox-input, #divShipments .custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$('#note').autosize();
	
	
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
	
	$('#trans_date').datepicker('option','maxDate','0');
	$("#trans_date").datepicker('setDate', new Date());
	$("#trans_date").keydown(function (e)
			{e.preventDefault();});
	
	$(document).on('focus','#usd',function(){
		var val = $(this).val();
		val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
		if (val == 0){
			$(this).val('');
		}
	});
	
	$(document).on('blur','#usd',function(){
		$(this).parseNumber({format:"#,##0.00", locale:"us"});
		$(this).formatNumber({format:"#,##0.00", locale:"us"});
		validate();
	});
	
	$(document).on('blur','#trans_date ',function(){
		validate();
	});
	
	$(document).on('keyup','#note ',function(){
		validate();
	});
	
	 
	
	function validate(){		
		if (valid()){
			$('#btnSubmit').removeAttr('disabled');
		}else{
			$('#btnSubmit').attr('disabled','disabled');
		}
	}
	
	function valid(){
	 
		var val;
		
		val = $('#trans_date').val();
		
		if (val.length <= 0){
			return false;
		}
		
		val = $('#nicks').val();
		if (val.length <= 0){
			return false;
		}
		
		val = $('#divNicks .custom-combobox-input').val();
		if (val.length <= 0){
			return false;
		}
		
		 val = $('#divOrderNo .custom-combobox-input').val();
		 var val1 = $('#divShipments .custom-combobox-input').val();
		if (val.length <= 0 && val1.length <= 0){
			return false;
		}
		 
		val = $('#usd').val();
		val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
		if (val  == 0){
			return false;
		}
		
		val = $('#note').val();
		if (val.length <= 0){
			return false;
		}
		
		return true;
		
	}
	
/*	$(document).on('click','.delTrans',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		 var btn = $(this); 	 
		 
		 $.ajax({
				url:url,
				type: "POST",
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
						var url = $('#transferInfoForm').attr('action');						
						submitForm(url);
					}else{
						var url = $('#transferInfoForm').attr('action');
						$('#errormsg').html(data.errormsg);
						submitForm(url);
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
		 var url = $("#updatebalanceInfoForm").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#updatebalanceInfoForm").serialize();		
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
					$("#transList").html(data);
					 
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