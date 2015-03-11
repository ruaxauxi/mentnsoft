$(document).ready(function(){
	
	$('#nicks').combobox(function(){});	
	
	function checkUsername(){
		var reg = /^[a-zA-Z0-9]*$/;
		var val = $('#username').val();
		return reg.test(val);
	}
	
	$(document).on('blur','#username',function(){		 
		var tag = $(this);
		if (!checkUsername()){
			$("#notify").find('span').text('Tên đăng nhập không được chứa khoảng trắng hay ký tự đặc biệt.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    		$(tag).val('');
				    		$(tag).focus();
				    	},
				      },				      
				  ]		      
			});
			return false;
		}
	});
	
	$(document).on('click','#search',function(){
		var nick = $('#divSearch .custom-combobox-input').val();
		$('#nicks').val(nick);
		
		 var url = $("#customerForm").attr('action');
		 var param = $("#customerForm").serialize();
		 var img = '<img src="../images/loading.gif" width="26px;" height="22px" style="margin-right: 5px;" />';
		 var btn = $(this);
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
					$(btn).next().remove();
					$(btn).show();
				},				
				success: function(data){					 
					$("#customerList").html(data);
					$('#nicks').combobox(function(){});	
				},
				
				
		});// ajax
	});
	
	$(document).on('blur','.service',function(){		
		var oldval = $(this).data('val');
		var newval = $(this).val();
		var tag = $(this);
		if (newval == ""){
			$(this).val(oldval);
			return true;
		}
		
		var service = $.parseNumber(newval,{format:"#,##0.00", locale:"us"});		
		 
		if ((service <= 0.00 || service > 100 ) && newval !== '0'){
			$("#notify").find('span').text('Giá trị % service không hợp lệ.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    		$(tag).val('');
				    		$(tag).focus();
				    	},
				      },				      
				  ]		      
			});
		}else{
			if (oldval != newval){
				 var nick = $(tag).prop('id');
				 
				 var url = $(tag).data('url');
				 var param =  {nick:nick, service:newval};
				 var img = '<img src="../images/loading.gif" width="22px;" height="14px" style="margin-right: 5px;" />';
				 var btn = $(tag);
				 $.ajax({
						url:url,
						type: "POST",
						data: param,
						dataType: "JSON",			
						error: function(xhr,status,errmgs){					
						},
						beforeSend: function(){
							$(btn).after(img);						
							$(btn).removeClass('servicechecked');
							$(btn).addClass('servicechecking');
						},
						complete: function(){
							$(btn).next().remove();
							$(btn).removeClass('servicechecking');
							$(btn).addClass('servicechecked');
						},				
						success: function(data){					 
							if (data.success == 1){
								 $(tag).removeClass('error');
								 $(tag).val($.formatNumber(newval,{format:"#,##0.00", locale:"us"}));
								 $(tag).data('val',$(tag).val());
							}else{
								$(tag).addClass('error');
							}
						},
						
				});// ajax
			}
		}
		
	});
	
	$(document).on('blur','.shipping',function(){		
		var oldval = $(this).data('val');
		var newval = $(this).val();
		
		var tag = $(this);
		
		if (newval == ""){
			$(this).val(oldval);
			return true;
		}
		
		var shipping = $.parseNumber(newval,{format:"#,##0.00", locale:"us"});		
		 
		if (shipping <= 0.00  && newval !== '0'){
			$("#notify").find('span').text('Giá trị shipping không hợp lệ.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    		$(tag).val('');
				    		$(tag).focus();
				    	},
				      },				      
				  ]		      
			});
		}else{
			if (oldval != newval){
				 var nick = $(tag).prop('id');
				 
				 var url = $(tag).data('url');
				 var param =  {nick:nick, shipping:newval};
				 var img = '<img src="../images/loading.gif" width="22px;" height="14px" style="margin-right: 5px;" />';
				 var btn = $(tag);
				 $.ajax({
						url:url,
						type: "POST",
						data: param,
						dataType: "JSON",			
						error: function(xhr,status,errmgs){					
						},
						beforeSend: function(){
							$(btn).after(img);						
							$(btn).removeClass('shippingchecked');
							$(btn).addClass('shippingchecking');
						},
						complete: function(){
							$(btn).next().remove();
							$(btn).removeClass('shippingchecking');
							$(btn).addClass('shippingchecked');
						},				
						success: function(data){					 
							if (data.success == 1){
								 $(tag).removeClass('error');
								 $(tag).val($.formatNumber(newval,{format:"#,##0.00", locale:"us"}));
								 $(tag).data('val',$(tag).val());
							}else{
								$(tag).addClass('error');
							}
						},
						
				});// ajax
			}
		}
	});
	
	$(document).on('focus','.service, .shipping',function(){
		$(this).val('');
	});
	
	$(document).on('blur','.passwd',function(){
		var oldval = $(this).data('val');
		var newval = $(this).val();
		var tag = $(this);
		if ($(this).val().length < 3){
				$("#notify").find('span').text('Mật khẩu không được ít hơn 3 ký tự.');
				$("#notify").dialog({
					  my: "center",
					  at: "center",
					  of: window,
					  buttons: [
					      {
					    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
					    	click: function(){
					    		$(this).dialog('close');
					    		$(tag).val(oldval);
					    		$(tag).focus();
					    	},
					      },
					      
					  ]		      
				});
				return false;
		}
		
		if (oldval != newval){
			 var nick = $(tag).prop('id');
			 
			 var url = $(tag).data('url');
			 var param =  {nick:nick, passwd:newval};
			 var img = '<img src="../images/loading.gif" width="22px;" height="14px" style="margin-right: 5px;" />';
			 var btn = $(tag);
			 $.ajax({
					url:url,
					type: "POST",
					data: param,
					dataType: "JSON",			
					error: function(xhr,status,errmgs){					
					},
					beforeSend: function(){
						$(btn).after(img);						
						$(btn).removeClass('checked');
						$(btn).addClass('checking');
					},
					complete: function(){
						$(btn).next().remove();
						$(btn).removeClass('checking');
						$(btn).addClass('checked');
					},				
					success: function(data){					 
						if (data.success == 1){
							 $(tag).removeClass('error');
							 $(tag).data('val',$(tag).val());
						}else{
							$(tag).addClass('error');
						}
					},
					
					
			});// ajax
		}
	});
	
	$(document).on('change','#chkall',function(){
		var checked = $(this).prop('checked');
		$('.chkdel').prop('checked',checked);
		$('.chkdel').each(function(i,e){
			if ($(this).prop('checked')){
				$(this).closest('tr').addClass('checked');
			}else{
				$(this).closest('tr').removeClass('checked');
			}
		});
	});
	
	$(document).on('change','.chkdel',function(){
		var checked = $(this).prop('checked');
		if (checked){
			$(this).closest('tr').addClass('checked');
		}else{
			$(this).closest('tr').removeClass('checked');
		}
		
	});
	
	
	$(document).on('keyup','#username, #password',function(e){
		 
		var valid = false;
		valid = ($('#username').val().trim().length >= 3 && $('#password').val().trim().length >= 3 && checkUsername());
		if (valid){
			$('#btnAddNew').removeAttr('disabled');
		}else{
			$('#btnAddNew').attr('disabled','disabled');
		}
	});
	
	$(document).on('click','#btnAddNew',function(e){
		e.preventDefault();
		$('#errormsg').html('');
		 var url = $("#customerAddForm").attr('action');
		 var param = $("#customerAddForm").serialize();
		 var img = '<img src="../images/loading.gif" width="26px;" height="22px" style="margin-right: 5px;" />';
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
						var url = $('#customerForm').attr('action');
						$('#username').val('');
						$('#password').val('');
						$('#btnAddNew').attr('disabled','disabled');
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
		 var url = $("#customerForm").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#customerForm").serialize();		
		 $.ajax({
				url:url,
				type: "POST",
				data: param,
				dataType: "HTML",			
				error: function(xhr,status,errmgs){},
				beforeSend: function(){
					$("#splash").find("#splashcontent").html("Đang tải dữ liệu, vui lòng chờ."); 
					$("#splash").fadeIn(200);					  			
				},
				complete: function(){
					$("#splash").hide();
				},
				success: function(data){					 
					$("#customerList").html(data);
					$('#nicks').combobox(function(){});	
					 
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
	 
	 
});