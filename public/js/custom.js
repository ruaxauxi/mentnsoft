function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode;
   if (charCode != 46 && charCode > 31 
     && (charCode < 48 || charCode > 57))
      return false;

   return true;
}


$(document).ready(function(){
	
	 
	
	// set splash position
	setSplashPosition();
	
	
	$(window).resize(function(){
		setSplashPosition();
	});
	
	function setSplashPosition(){
		var w = $("#splash").width()/2;
		//var h = $("#splash").height()/2;
		var top = '80';
		$("#splash").css("left",$(window).width()/2 - w);
		$("#splash").css("top",top);
	}
	
});// ready

 