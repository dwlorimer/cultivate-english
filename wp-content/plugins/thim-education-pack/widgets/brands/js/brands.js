(function ($) {
	$(document).ready(function () {
		$(".thim-brands").owlCarousel({
			items: 				6,
			itemsDesktop: 		[1170,6],
			itemsDesktopSmall: 	[980,5],
			itemsTablet: 		[800,4],
			itemsTabletSmall: 	[650,3],
			itemsMobile: 		[450,2],
			slideSpeed: 		200,
			paginationSpeed: 	800,
			rewindSpeed: 		1000,
			autoPlay: 			true,
			stopOnHover: 		true,
			navigation: 		false,
			pagination: 		false,
			mousewheel: 		true,
			leftOffSet: 		-15,
		});
	});
})(jQuery);