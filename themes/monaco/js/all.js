// $(document).ready(function () {
// 	$('.mobile-menu-btn').click(function () {
// 		$(".col1,.col2").toggleClass("show");
// 		$('.mobile-menu-btn').toggleClass('active');
// 		$("#container").toggleClass("noverflow");
// 	});
// });
window.onresize = function () {
	$(".col1,.col2").removeClass("show");
};



$('.widgetmenu').removeAttr('#nav_products');

