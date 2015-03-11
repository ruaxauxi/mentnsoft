

$(document).ready(function(){
	

	$(document).on('focus','#divNicks .custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$('#nicks').combobox({
		 
	});
	
	
	 $("#divTransfer #transferForm input:text, #divTransfer #transferForm textarea, #transferForm select").focusin(function(){
		 var data = $(this).data("info");
		 var top = $(this).offset().top;
		 var left = $(this).offset().left;		 
		 left = left + $(this).width() + 25;
		 
		 
		 top = top - parseInt($('#divInfo').height()/2) - $(window).scrollTop();
		 
		 
		 $('#divInfo').find('#info').html(data);
		 $('#divInfo').css('top',top).css('left',left).show();
		
		
	 });
	 
	 $("#divTransfer #transferForm :input").focusout(function(){
		// $('.info').remove();
		 $('#divInfo').hide();
	 });
	
	
	$(document).on('change','#trans_type',function(e){
		if ($(this).val() == 'others'){
			$('#refno').val('');
			$('#refno').removeClass('required');
			$('#divRefno').slideUp(400);
			//valid();
		}else{
			$('#refno').addClass('required');
			$('#divRefno').slideDown(400);
			//valid();
		}
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
	
	$("#trans_date").keydown(function (e)
			{e.preventDefault();});
	
	$(document).on('keyup','#vnd',function(){
		$(this).parseNumber({format:"#,###", locale:"us"});
		$(this).formatNumber({format:"#,###", locale:"us"});
	});
	
	$(document).on('blur','#vnd',function(){
		$(this).parseNumber({format:"#,###", locale:"us"});
		$(this).formatNumber({format:"#,###", locale:"us"});
	});
	
	$(document).on('keyup','.required',function(e){
		//valid();
	});
	
	function valid(){
		 
		var val = $('#trans_date').val();
		if (val.length <=0){			
			$("#notify").find('span').text('Bạn vui lòng chọn Ngày chuyển.');
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
			$('#trans_date').addClass('error');
			return false;
		}else{
			$('#trans_date').removeClass('error');
		}
		
		
		 val = $('#refno').val().trim();
		
		if ($('#trans_type').val()== 'online' && val.length == 0 ){
			
			$("#notify").find('span').text('Bạn vui lòng nhập vào RefNo.');
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
			$('#refno').addClass('error');
			return false;
		}else{
			$('#refno').removeClass('error');
		}
		
		 val = $('#vnd').val();
		
		val = $.parseNumber(val,{format:"#,###", locale:"us"});
		if (val <= 0 ){			
			$("#notify").find('span').text('Số tiền không hợp lệ.');
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
			$('#vnd').addClass('error');
			return false;
		}else{
			$('#refno').removeClass('error');
		}
		
		return true;
	}
	
	$(document).on('click','.delTrans',function(e){
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
	
	$(document).on('click','#btnAddNew',function(e){
		 e.preventDefault();
		
		 if (!valid()){
				return false;
		 }
		 
		 var url = $("#transferForm").attr('action');
		 var param = $("#transferForm").serialize();
		 param += "&btnAdd=true";
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
						var url = $('#transferInfoForm').attr('action');
						$('.required').val('');
						$('.required').removeClass('error');
						$('#note').val('');
						 
						submitForm(url);
					}else{
						$('#errormsg').html(data.errormsg);
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
		 var url = $("#transferForm").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#transferInfoForm").serialize();		
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