<div id="custom_content" class="span12"></div>
<div class="span9 clearfix">
<span id="homepage-flag" style="display: none" />

<script>
$(function() {
	var p = window.location.pathname;

	//run the code only on index page
	//if( window.location.pathname=="/" || window.location.pathname=="/index.html"  ){

		//remove breadcrumb on homepage
	 	$('.breadcrumbs').remove();

		$('#custom_content').find('hr').attr('id', 'fullWidth');
		//add class to content before and after <hr>
		$('#fullWidth').nextAll().addClass('bottomcontent');
		$('#fullWidth').prevAll().addClass('topcontent');

		//remove empty paragraphs
		$('#custom_content p').each(function() {
	    	var $this = $(this);
	    	if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
	        $this.remove();
		});

		$('.topcontent').has('img').addClass('cat-thumbs');

		//remove spacing
		$('.cat-thumbs').each(function() {
		    var $this = $(this);
		    $this.html($this.html().replace(/&nbsp;/g, ''));
		});
		//count images inside each row
		$('.cat-thumbs').each(function(){
			var $this = $(this);
			$(this).addClass('pics' + ($this.find('img').length));
		});
		//append thumb titles using their relative img alt
		$('.cat-thumbs a img').each(function() {
			 $(this).parent().append('<div class=imgalt>' + $(this).attr('alt') + '</div>');
		});
		$('.imgalt').each(function() {
			$(this).width($(this).parent().find('img').width());
			$(this).css('marginLeft', -($(this).parent().find('img').width()));
		});
		//iframe resizing
		$('#custom_content iframe').each(
     		function(index, elem) {
         		elem.setAttribute('width','960');
         		elem.setAttribute('height','540');
     		}
 		);
 		if ( $(window).width() < 961) {
 			$('#custom_content iframe').each(
     			function(index, elem) {
	         		elem.setAttribute('width','320');
	         		elem.setAttribute('height','180');
     			}
     		)};
	//}
});
</script>
</div>
