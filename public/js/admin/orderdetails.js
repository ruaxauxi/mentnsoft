$(document).ready(function(){	
	
	
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
					
					var refTr = $('#refTr').val();
					$(refTr).find('.images').val($('#imageList').val());
					
				
				}
			},
			
			
		});// ajax
		 
	 });
	
	$(document).on('click','.attachFile',function(e){
		e.preventDefault();
		
		//var delImg = $(this);
		var images = $(this).closest('.trItem').find('.images').val();
		
		$('#imageList').val(images);
		var trId = $(this).closest('.trItem').prop('id');
		$('#refTr').val("#" + trId);
		$("#imageDialog").dialog({
			  my: "center",
			  at: "center",
			  of: window,
			  width:700,
			  height: 600,
			  modal: true,	
			  closeText: "Close",
			  title: "Upload picture",
			  buttons: [
			      {
			    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
			    	click: function(){
			    		$(this).dialog('close');
			    	},
			      },
			      
			  ],
			  open: function(){
				  $('#OrderImages').html('');
				  $('#filelist').html('');
				  var jobj = jQuery.parseJSON(images);
				  var src;
				  for(var i in jobj){
					  src = jobj[i].image;
					  
					 var item = $('#imgitemHidden').clone();
					 $(item).removeAttr('id');
					 $(item).find('img').prop('src',src);
					 $(item).find('.delImg').prop('id',i);
					 $('#OrderImages').append(item);
						
				  }
				  
			  }
		});
		
	 
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

		var max_file = 500;
		uploader.bind('FilesAdded', function(up, files) {
			
			$count = $('.imgList').length + uploader.files.length - 1;
			
			if ( $count > max_file) {
				uploader.removeFile(files[0]);
	            alert('KhÃ´ng thá»ƒ upload quÃ¡ '+ max_file + ' áº£nh.');
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
			
			var refTr = $('#refTr').val();
			$(refTr).find('.images').val($('#imageList').val());
			
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
	
	$('.nicks').combobox({
			select: function (event, ui) {			
			  var nick = ui.item.value;
			  var item;	 
			 
	       	  for (i in users){
	       		   item = users[i];	        	   
		        	   if (nick == item.nick){
		        		   $(this).closest('.trItem').find('.service_val').val(item.service);
		        		    
		        	   }
	       	  }
	       	   
	       },
	       
	});
	
	$(document).on('blur','.divNicks .custom-combobox',function(e){
		var nick = $(this).closest('.divNicks').find('.custom-combobox-input').val();
		
		var item;	 
		  var found = false;
     	  for (i in users){
     		   item = users[i];	        	   
	        	   if (nick == item.nick){
	        		   found = true;
	        		   break;
	        	   }
     	  }
		
		if (!found){
			
    		$(this).closest('.trItem').find('.service_val').val(0);
		}
		var tr = $(this).closest('.trItem');
		calc(tr);
		 
	});
	
	$('.stores').combobox({		
	});
	
	$('.description').autosize();
	
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$(document).on('focus','.formatnumber',function(e){
		$(this).select();
	});
	
	$(document).on('click','.removeRow',function(e){
		 $(this).closest('.trItem').remove();
		 sum();
		 
	});
	
	$(document).on('click','#btnSet',function(e){
		var val = $('#discount_order').val();		
		if ($(this).val() != 'Update'){
			if ( $('#trans_type').val()== 'online' && $('#refno').val().length == 0 ){
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
			}else{
				$('#discount_all').val($('#discount_order').val());
				$('#divOrderdetails').slideDown();
				$(this).val('Update');
			}
		}else{
			if ($(this).val() == "Update"){
				$('.trItem').each(function(i,e){
					calc(this);
					 
				});
				
			} 
		}
		
		
	});
	
	$(document).on('keyup','#discount_order',function(){
		var val = $(this).val();
		if (val.length > 0){
			$('#btnSet').prop('disabled',false);
		}else{
			$('#btnSet').prop('disabled',true);
		}
	});
	
	
	$(document).on('blur','#discount_order',function(){	
		$(this).parseNumber({format:"#,##0.00", locale:"us"});
		$(this).formatNumber({format:"#,##0.00", locale:"us"}); 
		var val = $(this).val();
		
		if (!(val >= 0 && val <= 100)){			
			$("#notify").find('span').text('Giá trị % discount không hợp lệ.');
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
			$('#btnSet').prop('disabled',true);
			
		}else{
			$('#discount_all').val(val);
		}
	});
	
	$(document).on('focus','.formatnumber',function(){
		var val = $(this).val();
		val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
		if (val == 0){
			$(this).val('');
		}else{
			$(this).select();
		}
	});

	
	$(document).on('blur','.tax',function(e){
		var percent = $(this).closest('.divTax').find('.tax_type').prop('checked');
		var val = $(this).val();
		 val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});	
		 
		 if (percent){
			 if (val > 100){
				 $("#notify").find('span').text('Tỷ lệ % tax không hợp lệ.');
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
				$(this).val(0);
				$(this).focus();
			 }
		 }
		 
		 $(this).closest('.trItem').find('.tax_val').val(val);
		 var tr = $(this).closest('.trItem');
		calc(tr);
		 
		
	});
	
	$(document).on('change','.tax_type',function(e){
		var total_web = $(this).closest('.trItem').find('.total_web').val();
		 
		if (total_web.length > 0){
			total_web = $.parseNumber(total_web,{format:"#,##0.00", locale:"us"});			 
		}else{
			total_web = 0;
		}
		
		var tax = $(this).closest('.trItem').find('.tax').val();
		if (tax.length > 0){
			tax = $.parseNumber(tax,{format:"#,##0.00", locale:"us"});
		}else{
			tax = 0 ;
		}
		
		
		//discount
		var discount = $('#discount_all').val();
		discount = $.parseNumber(discount,{format:"#,##0.00", locale:"us"});
		var discount_changed = $(e).find('.discount_val').data('changed');
		
		var discount_final = 0; 
		if (!discount_changed){			
			discount_final = Math.round((total_web*discount/100)*100)/100;
			$(e).find('.discount_val').val($.formatNumber(discount_final,{format:"#,##0.00", locale:"us"}));
		}else{
			discount_final = discount;
		}
		
		
		var total_web1;
		total_web1 = total_web - discount_final;
		 
		var tax_val = tax;
		if ($(this).prop('checked')){
			tax_val = total_web1*tax/100;
			$(this).prev().text('%');
			 
		}else{
			$(this).prev().text('$');
			 
		}		
		
		
		$(this).closest('.trItem').find('.tax_val').val(tax_val);
		
		var tr = $(this).closest('.trItem');
		calc(tr);
		 
		 
	});
	
	$(document).on('change','.discount_val',function(e){
		$(this).data('changed',1);
	});
		
	$(document).on('blur','.total_web, .discount_val, .extra_fee, .items, .ship_us ',function(e){
		var val = $(this).val();
		if ($(this).hasClass('items')){
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			val = $.formatNumber(val,{format:"#,##0", locale:"us"});
		}else{
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
			val = $.formatNumber(val,{format:"#,##0.00", locale:"us"});
		}
		
		$(this).val(val);
		 
		var tr = $(this).closest('.trItem');
		calc(tr);	
		 
	});
	
	function finalize(){
		$('.trItem').each(function(i,e){
			calc(this);
			
		});
	}
	
	
	function calc(e){	
		 
		//total web
		var total_web = $(e).find('.total_web').val();
		total_web = $.parseNumber(total_web,{format:"#,##0.00", locale:"us"});
		total_web =  Math.round(total_web*100)/100;
		
		
		//discount
		var discount = $('#discount_all').val();
		discount = $.parseNumber(discount,{format:"#,##0.00", locale:"us"});
		var discount_changed = $(e).find('.discount_val').data('changed');
		
		var discount_final = 0; 
		if (!discount_changed){			
			discount_final = Math.round((total_web*discount/100)*100)/100;			
		}else{
			discount_final = $(e).find('.discount_val').val();
			discount_final = $.parseNumber(discount_final,{format:"#,##0.00", locale:"us"});
		}
		$(e).find('.discount_val').val($.formatNumber(discount_final,{format:"#,##0.00", locale:"us"}));
		
		
		// total web 1
		var total_web1;
		total_web1 = total_web - discount_final;		 
		total_web1 =  Math.round(total_web1*100)/100;
		
		
		//tax
		var tax = $(e).find('.tax').val();
		tax = $.parseNumber(tax,{format:"#,##0.00", locale:"us"});
		
		var tax_type = $(e).find('.tax_type:checked').val();
		var tax_val = tax;	 
		if (tax_type == 'percent'){
			tax_val = total_web1*tax/100;			 		
		} 
		tax_val =  Math.round(tax_val*100)/100;
		$(e).find('.tax_val').val(tax_val);
	 
		var service = $(e).find('.service_val').val();
		service = $.parseNumber(service,{format:"#,##0.00", locale:"us"});
		var service_final  = (service/100)*total_web1;
		service_final =  Math.round(service_final*100)/100;
		
		
		var ship_us = $(e).find('.ship_us').val();
		ship_us = $.parseNumber(ship_us,{format:"#,##0.00", locale:"us"});
		ship_us =  Math.round(ship_us*100)/100;
		
		var extra_fee = $(e).find('.extra_fee').val();
		extra_fee = $.parseNumber(extra_fee,{format:"#,##0.00", locale:"us"});		
		extra_fee =  Math.round(extra_fee*100)/100;
		
		var total = total_web1 +  ship_us +  tax_val;	
		total = Math.round(total*100)/100;
		 
		$(e).find('.total').val($.formatNumber(total,{format:"#,##0.00", locale:"us"}));
		
		var total_final ;
		total_final = total_web1 + service_final + ship_us + extra_fee + tax_val;
		
		total_web1 = $.formatNumber(total_web1,{format:"#,##0.00", locale:"us"});
		$(e).find('.total_web1').val(total_web1);
		
		 
		 
		$(e).find('.service_final').val(service_final);	
		 
		$(e).find('.total_final_sp').text( $.formatNumber(total_final,{format:"#,##0.00", locale:"us"}));
		$(e).find('.total_final_val').val(total_final);
		
		sum();
		
		
	}
	
	function validate(){
		var valid = true;
		var val;
		var tag;
		$('.trItem').each(function(i,e){
			tag = $(this).find('.custom-combobox-input');
			val = $(tag).val();
			if (val.length == 0){
				valid = false;
				$(tag).addClass('error');
			}else{
				$(tag).removeClass('error');
			}
			
			tag = $(this).find('.description');
			val = $(tag).val();
			if (val.length == 0){
				valid = false;
				$(tag).addClass('error');
			}else{
				$(tag).removeClass('error');
			}
			
			tag = $(this).find('.items');
			val = $(tag).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			if (val <= 0 && $(this).find('.custom-combobox-input').val() !== 'linhtinh'){
				valid = false;
				$(tag).addClass('error');
			}else{
				$(tag).removeClass('error');
			}
			
			tag = $(this).find('.total_web');
			val = $(tag).val();
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
			 
			if (val <= 0 ){
				valid = false;
				$(tag).addClass('error');
			}else{
				$(tag).removeClass('error');
			}
			
			tag = $(this).find('.ship_us');
			val = $(tag).val();
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
			if (val < 0){
				valid = false;
				$(tag).addClass('error');
			}else{
				$(tag).removeClass('error');
			}
			
			tag = $(this).find('.extra_fee');
			val = $(tag).val();
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});			
			$(tag).val($.formatNumber(val,{format:"#,##0.00", locale:"us"}));
			
			tag = $(this).find('.tax');
			val = $(tag).val();
			val = $.parseNumber(val,{format:"#,##0.00", locale:"us"});
			if (val < 0){
				valid = false;
				$(tag).addClass('error');
			}else{
				$(tag).removeClass('error');
			}
		});
		
		return valid;
	}
	
	function valid(){		
		 
		var total_web = $.parseNumber($('#totalWeb').val(),{format:"#,##0.00", locale:"us"});		
		var totalItem =$.parseNumber($('#totalItems').val(),{format:"#,##0", locale:"us"});
		var totalWeb1 =$.parseNumber($('#totalWeb1').val(),{format:"#,##0", locale:"us"});
		 
		
		var order_items =$.parseNumber($('#order_items').val(),{format:"#,##0", locale:"us"});
		var order_total_web =$.parseNumber($('#order_total_web').val(),{format:"#,##0.00", locale:"us"});
		var order_total_web1 =$.parseNumber($('#order_total_web1').val(),{format:"#,##0.00", locale:"us"});
		var order_total_final =$.parseNumber($('#order_total_final').val(),{format:"#,##0.00", locale:"us"});
		 
		var total_final =  0;
		$('.trItem').each(function(i,e){
			var discount = $(e).find('.discount_val').parseNumber({format:"#,##0.00", locale:"us"});		
			var total_web = $(e).find('.total_web').parseNumber({format:"#,##0.00", locale:"us"});
			var ship_us = $(e).find('.ship_us').parseNumber({format:"#,##0.00", locale:"us"});			 
			var tax = $(e).find('.tax_val').parseNumber({format:"#,##0.00", locale:"us"});
			
			 		
			var total_web1;
			total_web1 = total_web - discount;					
			 total_final += total_web1 +  ship_us +  tax;
			
		});
		total_final = Math.round(total_final*100)/100;
		  
		 
		if (parseInt(totalItem) !== parseInt(order_items)){
			$("#notify").find('span').text('Tổng số items không khớp.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  title: 'Lỗi',
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
		
		if (parseFloat(total_web) <  parseFloat(order_total_web)){
			$("#notify").find('span').text('Total Web của chi tiết nhỏ hơn Total Web của đơn hàng.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  title: 'Lá»—i',
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
		
		if (parseFloat(totalWeb1) < parseFloat(order_total_web1)){
			$("#notify").find('span').text('Total Web1 của chi tiết nhỏ hơn Total Web1 của đơn hàng.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  title: 'Lỗi',
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
		
		if (parseFloat(total_final) <  parseFloat(order_total_final)){
			$("#notify").find('span').text('Total final('+ $.formatNumber(total_final,{format:"#,##0.00", locale:"us"}) +') cá»§a chi tiáº¿t (khÃ´ng tÃ­nh service vÃ  extra fee ) nhá»� hÆ¡n Total final cá»§a Ä�Æ¡n hÃ ng.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  title: 'Lá»—i',
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
		
		return true;
		 
	}
	
	function sum(){
		
		var totalItem = 0;
		
		$('.items').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			totalItem += val;
		});		
		$('#totalItems').val( $.formatNumber(totalItem,{format:"#,##0", locale:"us"}));
		 
		
		var total = 0;
		var val;
		$('.total_final_val').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			total += val;
		});		
		$('#total').val( $.formatNumber(total,{format:"#,##0.00", locale:"us"}));
		
		var total_total = 0;
		var val;
		$('.total').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			total_total += val;
		});		
		$('#total_total').val( $.formatNumber(total_total,{format:"#,##0.00", locale:"us"}));
		
		var discount = 0;
		var val;
		$('.discount_val').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			discount += val;
		});		
		$('#totalDiscount').val( $.formatNumber(discount,{format:"#,##0.00", locale:"us"}));
		
		var total_web = 0;
		
		$('.total_web').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			total_web += val;
		});		
		$('#totalWeb').val( $.formatNumber(total_web,{format:"#,##0.00", locale:"us"}));
		
		var total_web1 = 0;
		$('.total_web1').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			total_web1 += val;
		});		
		$('#totalWeb1').val( $.formatNumber(total_web1,{format:"#,##0.00", locale:"us"}));
		
		
		var ship_us = 0;
		$('.ship_us').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			ship_us += val;
		});		
		$('#totalShip_us').val( $.formatNumber(ship_us,{format:"#,##0.00", locale:"us"}));
		
		
		var extra_fee = 0;
		$('.extra_fee').each(function(i,e){
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			extra_fee += val;
		});		
		$('#totalExtra_fee').val( $.formatNumber(extra_fee,{format:"#,##0.00", locale:"us"}));
		
		
		var tax = 0;
		$('.tax_val').each(function(i,e){			 
			val = $(e).val();
			val = $.parseNumber(val,{format:"#,##0", locale:"us"});
			tax += val;
		});		
		$('#totalTax').val( $.formatNumber(tax,{format:"#,##0.00", locale:"us"}));
		
		
		
	}
	
	/*$(document).on('blur','.ship_us, .extra_fee, .items',function(e){
		var tr = $(this).closest('.trItem');
		calc(tr);
		
	});*/
	
	
	$(document).on('click','#btnSave',function(e){
		e.preventDefault();
		finalize();
		validate();
		if (!validate()){
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
			return false;
		}
		
		if (valid()){
			 $('#frmOrderDetails').submit();
		}
	});
	
	$(document).on('click','#addRow',function(e){
		finalize();
		var newRow =  $('#hiddenRow').clone();
		$(newRow).removeAttr('id');
		$(newRow).addClass('trItem');
		var tax_type =  $(newRow).find('input[type=checkbox]').attr('name') + $('.trItem').find('.no').length + '[]';
		$(newRow).find('input[type=checkbox]').attr('name',tax_type);
		$(newRow).find('#hiddenNick').addClass('nicks');
		$(newRow).find('#hiddenNick').removeAttr('id');
		
		if ($('.trItem').length > 0){
			var id = $('.trItem:last-child').prop('id');
			
			id = id.substring(6);			 
			id = $.parseNumber(id,{format:"#,##0", locale:"us"}) + 1;
			
			$(newRow).prop('id', 'trItem' + id.toString());
			$('.trItem:last-child').after(newRow);
		}else{
			
			$(newRow).prop('id','.trItem0');
			$('#orderdetailTable tbody').append(newRow);
		}
		
		
		$('.trItem').find('.no').each(function(i,e){
			$(this).text(i+1);
		});
		 
		$('.nicks').combobox({	
			select: function (event, ui) {			
				  var nick = ui.item.value;
				  var item;	 
				  
		       	  for (i in users){
		       		   item = users[i];	        	   
			        	   if (nick == item.nick){			        		   
			        		  
			        		   $(this).closest('.trItem').find('.service_val').val(item.service);			        		    
			        	   }
		       	  }
		       },
		});
		$('.description').autosize();
		
	});
	
	
	$("#dialog").dialog({
		 autoOpen:false,
		 closeOnEscape: true,
		 closeText: "Ä�Ã³ng",
		 resizable: false,		 
		 show: {effect: "fade", duration: 200},
		 hide: {effect: "fade", duration: 200},
	 });
	
	  
	
});//ready