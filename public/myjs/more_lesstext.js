$(document).ready(function(){
	$(document).on('click','.read-more-btn', function(){
		var $this = $(this);
		var $moretext = $this.siblings('.moreText');
		var $longtext = $this.siblings('.longText');
		if($moretext.is(':visible')){
              $this.text('see-more');
             $longtext.show();
             $moretext.slideToggle('slow');
		}else{
			$longtext.hide();
              $('.hidd').hide();
               $this.text('see-less');
               $moretext.slideToggle('slow');
            
		}

        });

});
 
