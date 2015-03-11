$(document).ready(function(){
	$(document).on('click','.viewmore',function(){
		$(this).find('span').toggleClass('ui-icon-minus','ui-icon-plus');
		if (!$(this).data('show')){
			$(this).closest('tr').next().find('.details').slideDown(300);
			$(this).data('show',1);
		}else{
			$(this).closest('tr').next().find('.details').slideUp(300);
			$(this).data('show',0);
		}
		 
		 
	});
	
	
	$(window).scroll(function(){		 
		loadMore();
		 
	 });
	
	function loadMore(){
		 var lastPos = 0;
		 var isLoaded = false;
		 
		 try{
			 lastPos = $("#loadmore").offset().top ;
			 isLoaded = true;
			 
		 }catch(e){
			 isLoaded = false; 
		 }
		 
		 var finish = 0;
		 var isLoading = 0;
		 try{
			 finish = parseInt($("#btnLast").data("finish"));
			 isLoading = parseInt($("#btnLast").data("isloading"));
		 }catch(e){
			 finish = 0;
			 
		 }
		 		 
		 var top = ( $(window).scrollTop()  +  $(window).height());		 
		 
		 if  ( isLoaded && top >= lastPos && !finish && !isLoading){
		 
		 	var url = $('#transListForm').attr('action');
			 	 
			var last = $("#btnLast").val().toString();	
			
			var param = {"page" : last};
			var img = "<img id='imgLoading' src='../images/loading.gif' width='22' height='22'/>";
			
			$("#btnLast").data('isloading',1); 
			$.ajax({
				url:url,
				type: "POST",
				data: param,
				dataType: "HTML",			
				error: function(xhr,status,errmgs){					
				},
				beforeSend: function(){
					$('#btnLast').after(img);
				},
				complete: function(){
					$('#btnLast').next().remove();	
				},
				success: function(data){
					 var page = $('#btnLast').val();
					 page = parseInt(page);
					 page++;
					 $('#btnLast').val(page);
					 if (data){
						 $('#transList>tbody').append(data);
					 }else{
						 $('#btnLast').data('finish',1);
						 $('#loadmore').hide();
					 }
					 $("#btnLast").data('isloading',0); 
				}
				
			});
		 }
		 
	 }
	
});//ready