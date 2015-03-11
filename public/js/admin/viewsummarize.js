$(document).ready(function(){
	
	$(document).on('click',".paid",function(e){
		e.preventDefault();
		var url = $(this).data('url');
		var img = '<img src="../../images/loading.gif" width="14px;" height="14px" />';
		 var btn = $(this); 	 
		 
		 var shipment_id = $(this).closest('tr').find('.shipment_id').val();
		 var tongthukh = $(this).closest('tr').find('.tongthukh').val();
		 var tongthuweight = $(this).closest('tr').find('.tongthuweight').val();
		 var tongthushipping = $(this).closest('tr').find('.tongthushipping').val();
		 var lotamtinh = $(this).closest('tr').find('.lotamtinh').val();
		 var tongtienhang = $(this).closest('tr').find('.tongtienhang').val();
		 var tongshippingweigh = $(this).closest('tr').find('.tongshippingweigh').val();
		 var tongshipping = $(this).closest('tr').find('.tongshipping').val();
		 var tongchiphi = $(this).closest('tr').find('.tongchiphi').val();
		 var tonggiaodichkhac = $(this).closest('tr').find('.tonggiaodichkhac').val();
		 var tongdung = $(this).closest('tr').find('.tongdung').val();
		 var sum = $(this).closest('tr').find('.sum').val();
		 
		 var param = {
				 shipment_id:shipment_id,
				 tongthukh:tongthukh,
				 tongthuweight:tongthuweight,
				 tongthushipping:tongthushipping,
				 lotamtinh:lotamtinh,
				 tongtienhang:tongtienhang,
				 tongshippingweigh:tongshippingweigh,
				 tongshipping:tongshipping,
				 tongchiphi:tongchiphi,
				 tonggiaodichkhac:tonggiaodichkhac,
				 tongdung:tongdung,
				 sum:sum
		 };
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
						$(btn).next().remove();
						$(btn).after('<span class="icon-check" title="Đã thanh toán"></span>');
						$(btn).remove();
					}else{
						$("#notify").find('span').text('Có lỗi xảy ra, không thể cập nhật thông tin.');
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
		 var url = $("#frmViewsummarize").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#frmViewsummarize").serialize();		
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
					$("#divViewsummarize").html(data);
					 
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