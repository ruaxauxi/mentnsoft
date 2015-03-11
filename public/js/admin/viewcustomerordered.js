$(document).ready(function(){

	$(document).on('focus','#divNicks .custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
	});
	
	$('#nicks').combobox({
		 
	});
	
	$('#shipment_id').combobox({});	 
	$('#status').combobox({});
	
	$(document).on('focus','.custom-combobox-input',function(e){		 
		 $(this).autocomplete("search", "");
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
		 var url = $("#frmViewOrder").attr('action');
		 // reset current page 
		 $('#page').val(1);
		 submitForm(url);
	 });
	 
//##################################
	 
//########loads data##############
	 function submitForm(url){
		 var param = $("#frmViewOrder").serialize();		
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
					$("#divOrderdetails").html(data);
					 
				}
				
		});// ajax
	 } // submitForm
//##################################
});//ready