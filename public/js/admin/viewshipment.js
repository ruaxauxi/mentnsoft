$(document).ready(function(){
	
	$(window).scroll(function(){		 
		setPosition();
	 });


	function setPosition(){
		if ($("#anchor").length){
			var anchor = $("#anchor").offset().top ;
			var top = ( $(window).scrollTop());
			 var top_pos = top - $(window).scrollTop() + 40 ;
			 var left_pos = $('#anchor').offset().left -  $(window).scrollLeft();
			 if  ( top >= anchor - 40 ){				 
				 if ($('#divTableHeader').html().length <= 0){
					 var table = $('#shipmentTable').clone();
					 $(table).find('a').removeClass('fancybox');
					 $(table).removeAttr('id');
					 $(table).find('tfoot').remove();
					 
					 $(table).find('input').each(function(i,e){
						$(e).removeAttr('name'); 
					 });					 
					 $('#divTableHeader').html(table);	 
				 } 				
				  
				 $('#divTableHeader').width($('#shipmentTable').width());
				 $('#divTableHeader').css('position','fixed').css('top',top_pos).css('left',left_pos);
				 $('#divTableHeader').show();
			 }else{
				 $('#searchSection').css('position','relative').css('top','').css('left','');
				 $('#divTableHeader').hide();
			 }
		}
	}
	 
	
	$('#note').autosize();
	
	  
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
	
	 
	$('.soNote').autosize();
	
	 
	 
	 
		
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
						//$('.soNote').autosize();
					}
					
			});// ajax
		 } // submitForm
	//##################################
	
});//ready