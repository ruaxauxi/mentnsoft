$(document).ready(function(){
	$('#address').autosize();
	
	$(document).on('submit','#frmUpdateAddress',function(e){
		var city = parseInt($('#city').val());
		if (city == 0){
			e.preventDefault();
			$("#notify").find('span').text('Bạn vui lòng chọn một Tỉnh/Thành phố.');
			$("#notify").dialog({
				  my: "center",
				  at: "center",
				  of: window,
				  buttons: [
				      {
				    	html: "<span class='ui-icon ui-icon-close'>OK</span>",
				    	click: function(){
				    		$(this).dialog('close');
				    		$('#city').focus();
				    	},
				      },
				      
				  ]		      
			});
			return false;
			
		}
	});
	
	
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