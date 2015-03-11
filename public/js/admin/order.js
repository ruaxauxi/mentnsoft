$(document).ready(function(){
	
	$('#description').autosize();
	
	$(document).on('click','.delImg',function(e){
		e.preventDefault();
		
		var delImg = $(this);
		
		var image_id = $(delImg).attr('id');
		var param = {image_id: image_id};
		
		var url = $(delImg).find('span').data('url');
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
					$(delImg).parent().fadeOut(400,function(){
						$(this).closest('.imgList').remove();
					});
					
					var imageList = $('#imageList').val();
					var jobj = jQuery.parseJSON(imageList);
					 
					delete jobj[image_id];				
					$('#imageList').val(JSON.stringify(jobj));
				
				}
			},
			
			
		});// ajax
		 
	 });
	
	
	// Custom example logic
	$(function() {
		
		var url = $('#imageuploadForm').attr('action');
		var uploader = new plupload.Uploader({
			runtimes : 'gears,html5,flash,silverlight,browserplus',
			browse_button : 'pickfiles',
			container : 'container',
			max_file_size : '2mb',
			//file_data_name: 'image_name',
			url : url,
			flash_swf_url : '../plupload.flash.swf',
			silverlight_xap_url : '../plupload.silverlight.xap',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"},
				
			],
			resize : {width : 1024, height : 768, quality : 90},

			multi_selection: false,
			chunks : {
				size: '1mb',
				send_chunk_number: false // set this to true, to send chunk and total chunk numbers instead of offset and total bytes
			},
			 
		});
		
		//uploader.settings.multipart_params = { 'product_id': product_id };

		uploader.bind('Init', function(up, params) {
			
			//$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
		});

		$('#uploadfiles').click(function(e) {
			uploader.start();
			e.preventDefault();
		});

		uploader.init();
		var product_id = $('#frmProduct_id').val();
		uploader.bind('BeforeUpload', function(up, file) {
			up.settings.multipart_params = {
		            filename: file.name,
		            'product_id': product_id 
		        };
		});

		var max_file = 5;
		uploader.bind('FilesAdded', function(up, files) {
			
			$count = $('.imgList').length + uploader.files.length - 1;
			
			if ( $count > max_file) {
				uploader.removeFile(files[0]);
	            alert('Không thể upload quá '+ max_file + ' ảnh.');
	        }else{
	        	$.each(files, function(i, file) {
					$('#filelist').append(
						'<div id="' + file.id + '">' +
						file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
					'</div>');
	        	});
	        }
			
			console.log(files);
			

			up.refresh(); // Reposition Flash/Silverlight
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + " b").html(file.percent + "%");
		});

		uploader.bind('Error', function(up, err) {
			$('#filelist').append("<div>Error: " + err.code +
				", Message: " + err.message +
				(err.file ? ", File: " + err.file.name : "") +
				"</div>"
			);

			up.refresh(); // Reposition Flash/Silverlight
		});

		uploader.bind('FileUploaded', function(up, file,data) {
			$('#' + file.id + " b").html("100%");
			var result = jQuery.parseJSON(data.response);
			var imageList = $('#imageList').val();
			var i = imageList.length;
			if (i == 0){				 
				var obj = {};
				obj[result.image_id] = {image: result.image};
				$('#imageList').val(JSON.stringify(obj));
			}else{
				var jobj = jQuery.parseJSON(imageList);
				var img = {image: result.image};	
				jobj[result.image_id] = img;				
				$('#imageList').val(JSON.stringify(jobj));
				
			}
			
			var item = $('#imgitemHidden').clone();
			$(item).removeAttr('id');
			$(item).find('img').prop('src',result.image);
			$(item).find('.delImg').prop('id',result.image_id );
			$('#OrderImages').append(item);
			
			/*var divImg = $('<div></div>');
			$(divImg).addClass('imgList');
			
			var a = $('<a></a>');
			$(a).addClass('delImg')
				.prop('href','javascript:void(0)')
				.prop('id',result.image_id);
				
			
			var span = $('<span></span>');			
			$(span).addClass('icon-trash')
				   .data('url','/adminacp/upload/delete')
				   .appendTo(a);			
			$(divImg).append(a);
			
			var img = $('<img/>');
			$(img).prop('src',result.image).appendTo(divImg);			
			$('#productImages').append(divImg);
			*/
		 
			
		});
	});
	
	
	
	
	$('#store').combobox({		 
        change: function (event, ui) {        	        	
        	if (ui.item == null){
        		$('#store_name').val('');
        	}else{
        		$('#store_name').val(ui.item.text);
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
	        		   found = true;
	        		   $('#card_holder').val(item.holder);
	        		   $('#holder').val(item.holder);
	        	   }
        	  }		
        },
        change: function (event,ui){
        	if (ui.item == null){
        		$('#card_holder').val("");
      		    $('#holder').val("");
        	}
        }
		
	});
	 
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
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
	
	$('#orderdate').datepicker('option','maxDate','0');
	 
	$("#orderdate").datepicker('setDate', new Date());
	
	$("#orderdate").keydown(function (e)
			{e.preventDefault();
	});
	
	$(document).on('focus','#total_web, #discount, #tax, #ship_us, #items',function(e){
		$(this).select();
	});
	
	$(document).on('blur','#total_web, #discount, #tax, #ship_us',function(){		
		var total = $.parseNumber($('#total_web').val(),{format:"#,##0", locale:"us"});
		var discount = $.parseNumber($('#discount').val(),{format:"#,##0", locale:"us"});		
		var ship_us = $.parseNumber($('#ship_us').val(),{format:"#,##0.00", locale:"us"});
		var tax = $.parseNumber($('#tax').val(),{format:"#,##0", locale:"us"});		
		var total_web1 = total-discount;	
		var total_final = total + tax + ship_us - discount;		
		
		var val_web1 =  $.formatNumber(total_web1,{format:"#,##0.00", locale:"us"});		 
		var val_total_final = $.formatNumber(total_final,{format:"#,##0.00", locale:"us"});
		 $('#total_web1').val(val_web1);
		 $('#total_final').val(val_total_final);
	});
	
	$(document).on('keyup','#items',function(e){		 
			$(this).parseNumber({format:"#,##0", locale:"us"});
			$(this).formatNumber({format:"#,##0", locale:"us"});	
	});
	
	$(document).on('blur','#items',function(){
		$(this).parseNumber({format:"#,##0", locale:"us"});
		$(this).formatNumber({format:"#,##0", locale:"us"});
	});
	
	$(document).on('blur','.formatnumber',function(){		 
		var val = $(this).val();		 
		if (val.indexOf('.') != -1){
			$(this).parseNumber({format:"#,##0.00", locale:"us"});
			$(this).formatNumber({format:"#,##0.00", locale:"us"});
		}else{
			$(this).parseNumber({format:"#,##0", locale:"us"});
			$(this).formatNumber({format:"#,##0", locale:"us"});
		}
		
	});
	
	// check order no;
		 
	$(document).on('blur','#orderno',function(e){
		
		var orderno = $(this).val();
		// remove all special characters, excepts - . and _
		orderno = orderno.replace(/[^\w\.-]/gi, '');
        $(this).val(orderno);
		
		if (orderno != $(this).data('val')){
			 var url = $(this).data('url');
			 var img = '<img id="imgloading" src="../../images/loading.gif" width="22px;" height="18px" />';
			 var btn = $(this); 
			 
			 var param = {'orderno':orderno};
			 $.ajax({
					url:url,
					type: "POST",
					data: param,
					dataType: "JSON",			
					error: function(xhr,status,errmgs){					
					},
					beforeSend: function(){
						$(btn).after(img);
						$('#warrning-icon').remove();
						$('#check-icon').remove();
					},
					complete: function(){
						$('#imgloading').remove();							
					},				
					success: function(data){					 
						if (data.success == 1){
							$(btn).data('val',$(btn).val());
							var icon = '<span id="check-icon" class="icon-check"></span>';
							$(btn).after(icon);	
							$(btn).data('valid','1');
							$(btn).removeClass('error');
						}else{							
							var icon = '<span id="warrning-icon" class="icon-warning-sign" title="'+data.errmsg+'"></span>';
							$(btn).after(icon);	
							$(btn).data('valid','0');
							$(btn).addClass('error');
						}
					},
					
					
			});// ajax
		}
	});
	
	
	$(document).on('blur','#orderdate',function(e){
		var val =  $(this).val();
		if (val.length == 0){				
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}
	
	});
	
	$(document).on('blur','#items',function(e){		
			var val = $.parseNumber($(this).val(),{format:"#,##0", locale:"us"});
			if (val <= 0){				
				$(this).addClass('error');
			}else{
				$(this).removeClass('error');
			}
		
	});
	
	$(document).on('blur','#tax',function(e){		
		var val = $.parseNumber($(this).val(),{format:"#,##0.00", locale:"us"});
		if (val < 0){			
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}	
	});
	
	$(document).on('blur','#ship_us',function(e){		
		var val = $.parseNumber($(this).val(),{format:"#,##0.00", locale:"us"});
		if (val < 0){			
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}	
	});
	
	$(document).on('blur','#discount',function(e){		
		var val = $.parseNumber($(this).val(),{format:"#,##0", locale:"us"});
		if (val < 0){			
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}	
	});
	
	$(document).on('blur','#total_web',function(e){		
		var val = $.parseNumber($(this).val(),{format:"#,##0", locale:"us"});
		if (val < 0){			
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}	
	});
	
	$(document).on('blur','#total_final',function(e){		
		var val = $.parseNumber($(this).val(),{format:"#,##0", locale:"us"});
		if (val < 0){			
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}	
	});
	
	$(document).on('blur','.custom-combobox-input',function(e){		
		if ($(this).parent().prev().attr('id') == 'store'){
			var val = $('#store').val().length;
			if (val == 0){			
				$(this).addClass('error');
			}else{
				$(this).removeClass('error');
			}
		}
			
	});
	
	$(document).on('click','#btnNext',function(e){	
		var valid = validate();
		if (!valid){
			e.preventDefault();
			$("#notify").find('span').text('Bạn phải nhập thông tin đầy đủ.');
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
		} 
	});
	
	/*$(document).on('click','.orderdel',function(e){
		e.preventDefault();
		
		var url = $(this).attr('href');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		var btn = $(this); 	
		
		 $.ajax({
				url:url,
				type: "GET",
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
						$(btn).closest('tr').fadeOut(400,function(){
							$(this).remove();
							if ($('.no').length == 0){
								var url = $('#frmOrder').attr('action');
								$('#page').val(1);
								 submitForm(url);
							}else{
								$('.no').each(function(i,e){
									$(e).text(i+1);
								});
							}
						});		
					}else{
						
						$("#notify").find('span').text(data.errmsg);
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
				},
				
				
		});// ajax 
		
		
	});
	
	
	
		
		 
	});*/
	
	function validate(){
		var valid = true;
		$('.required').each(function(i,e){
			if ($(e).val().trim().length == 0){				
				$(this).addClass('error');
			}
		});
		
		//custom-combobox-input
		
		
		var val = $.parseNumber($('#items').val(),{format:"#,##0", locale:"us"});		
		if (val <= 0){			
			$('#items').addClass('error');
		}else{
			$('#items').removeClass('error');
		}
		
		val = $.parseNumber($('#tax').val(),{format:"#,##0.00", locale:"us"});
		
		if (val < 0 ){			
			$('#tax').addClass('error');
		}else{
			$('#tax').removeClass('error');
		}
		
		val = $.parseNumber($('#ship_us').val(),{format:"#,##0.00", locale:"us"});
		
		if (val < 0){			
			$('#ship_us').addClass('error');
		}else{
			$('#ship_us').removeClass('error');
		}
		
		
		val = $.parseNumber($('#total_web').val(),{format:"#,##0.00", locale:"us"});
		
		if (val <= 0){			
			$('#total_web').addClass('error');
		}else{
			$('#total_web').removeClass('error');
		}
		
		val = $.parseNumber($('#discount').val(),{format:"#,##0.00", locale:"us"});
		
		if (val < 0){			
			$('#discount').addClass('error');
		}else{
			$('#discount').removeClass('error');
		}
		
		val = $.parseNumber($('#total_web1').val(),{format:"#,##0.00", locale:"us"});
		
		if (val <= 0){			
			$('#total_web1').addClass('error');
		}else{
			$('#total_web1').removeClass('error');
		}
		
		
		val = $.parseNumber($('#total_final').val(),{format:"#,##0.00", locale:"us"});
		
		if (val <= 0){			
			$('#total_final').addClass('error');
		}else{
			$('#total_final').removeClass('error');
		}
		
		val = $('#orderdate').val();
		if (val.length == 0){				
			$('#orderdate').addClass('error');
		}else{
			$('#orderdate').removeClass('error');
		}
		
		val = $('#store').val();
		if (val.length == 0){
			$('#store').addClass('error');
			$('#store').next().find('.custom-combobox-input').addClass('error');
		}else{
			$('#store').removeClass('error');
			$('#store').next().find('.custom-combobox-input').removeClass('error');
		}
		
		val = $('#creditcard').val();
		if (val.length == 0){
			$('#creditcard').addClass('error');
			$('#creditcard').next().find('.custom-combobox-input').addClass('error');
		}else{
			$('#creditcard').removeClass('error');
			$('#creditcard').next().find('.custom-combobox-input').removeClass('error');
		}
		
		
		$('.required').each(function(i,e){
			if ($(e).hasClass('error')){				
				valid= false;
			}
		});
		
		return valid;
	}
	
	
/*
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
		 var url = $("#frmOrder").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#frmOrder").serialize();		
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
					$("#orderList").html(data);
					$('#store').combobox();
					
					$('.store').combobox({		
					});
					
					$('.nick').combobox({		
					});
					 
				}
				
		});// ajax
	 } // submitForm
//##################################
	 */
//dialog
	 $("#dialog").dialog({
		 autoOpen:false,
		 closeOnEscape: true,
		 closeText: "Đóng",
		 resizable: false,		 
		 show: {effect: "fade", duration: 200},
		 hide: {effect: "fade", duration: 200},
	 });
	
});//ready