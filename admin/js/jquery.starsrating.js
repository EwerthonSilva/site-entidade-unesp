/* controle das estrelas */
$(document).ready(function(){
	/* 1 estrela */
	$(document).on({
		mouseenter: function() {
			$(this).closest('.star-rating')
				.removeClass('hover-2')
				.removeClass('hover-3')
				.removeClass('hover-4')
				.removeClass('hover-5')
				.addClass('hover-1')
		},
		click: function(){
			$(this).closest('.star-rating')
				.removeClass('rating-2')
				.removeClass('rating-3')
				.removeClass('rating-4')
				.removeClass('rating-5')
				.addClass('rating-1')
				.find('input').val(1);
		}
	}, '.star-1');

	/* 2 estrelas */
	$(document).on({
		mouseenter: function() {
			$(this).closest('.star-rating')
				.removeClass('hover-3')
				.removeClass('hover-4')
				.removeClass('hover-5')
				.addClass('hover-2')
		},
		click: function(){
			$(this).closest('.star-rating')
				.removeClass('rating-1')
				.removeClass('rating-3')
				.removeClass('rating-4')
				.removeClass('rating-5')
				.addClass('rating-2')
				.find('input').val(2);
		}
	}, '.star-2');

	/* 3 estrelas */
	$(document).on({
		mouseenter: function() {
			$(this).closest('.star-rating')
				.removeClass('hover-4')
				.removeClass('hover-5')
				.addClass('hover-3')
		},
		click: function(){
			$(this).closest('.star-rating')
				.removeClass('rating-1')
				.removeClass('rating-2')
				.removeClass('rating-4')
				.removeClass('rating-5')
				.addClass('rating-3')
				.find('input').val(3);
		}
	}, '.star-3');

	/* 4 estrelas */
	$(document).on({
		mouseenter: function() {
			$(this).closest('.star-rating')
				.removeClass('hover-5')
				.addClass('hover-4')
		},
		click: function(){
			$(this).closest('.star-rating')
				.removeClass('rating-1')
				.removeClass('rating-2')
				.removeClass('rating-3')
				.removeClass('rating-5')
				.addClass('rating-4')
				.find('input').val(4);
		}
	}, '.star-4');

	/* 5 estrelas */
	$(document).on({
		mouseenter: function() {
			$(this).closest('.star-rating')
				.addClass('hover-5')
		},
		click: function(){
			$(this).closest('.star-rating')
				.removeClass('rating-1')
				.removeClass('rating-2')
				.removeClass('rating-3')
				.removeClass('rating-4')
				.addClass('rating-5')
				.find('input').val(5);
		}
	}, '.star-5');

	$(document).on('mouseleave', '.star-rating', function(){
		$(this)
			.removeClass('hover-1')
			.removeClass('hover-2')
			.removeClass('hover-3')
			.removeClass('hover-4')
			.removeClass('hover-5')
	});
}) //doc.ready