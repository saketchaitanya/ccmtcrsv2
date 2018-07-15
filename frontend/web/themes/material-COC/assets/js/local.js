/**
* local jquery scripts added after loading all other scripts
*/
  $(document).ready(function(){
    $('.carousel').carousel();
});

/*  this is for alerting user for unsaved data in form before exit */
/* $(function() {
 $('form').dirtyForms({
	 dialog: { title: 'Wait!' }, 
		message: 'Please save the data by pressing SAVE button before further action.' 
 } );
}); */
$(function() {
				$('<div id="dirty-dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dirty-title">' +
					'<div class="modal-dialog" role="document">' +
						'<div class="modal-content panel-danger">' +
							'<div class="modal-header panel-heading">' +
								'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' + 
									'<span aria-hidden="true">&times;</span>' + 
								'</button>' +
								'<h3 class="modal-title" id="dirty-title"></h3>' +      
							'</div>' +
							'<div class="modal-body panel-body dirty-message"></div>' +
							'<div class="modal-body panel-body"><b>Please SAVE the data by clicking "Save" button before proceeding</b></div>' +
							'<div class="modal-footer panel-footer">' +
								'<button type="button" class="dirty-proceed btn btn-primary" data-dismiss="modal"></button>' +
								'<button type="button" class="dirty-stay btn btn-default" data-dismiss="modal"></button>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>').appendTo('body');
			$('form').dirtyForms({
				// Message will be shown both in the Bootstrap Modal dialog 
				// and in most browsers when attempting to navigate away 
				// using browser actions.
				message: 'Wait! Your form is not saved yet.',
				dialog: {
					title: 'Attention!!',
					dialogID: 'dirty-dialog', 
					titleID: 'dirty-title', 
					messageClass: 'dirty-message', 
					proceedButtonClass: 'dirty-proceed', 
					stayButtonClass: 'dirty-stay' 
				}
			});
});


	jQuery(function($){
		$('.fTab').on('click', function(){
			$('.footer').toggleClass('inactive');
			var span_text = $(this).text();
			$(this).toggleClass('active');
			if
			(span_text =='Close Footer')
			{
				span_text = 'Open Footer';
			}
			else
			{
				span_text ='Close Footer';
			}
			$(this).html(span_text);
			
		});
	})
	
	window.onscroll = function() {scrollFunction()};

	function scrollFunction() {
		  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			  document.getElementById("myBtn").style.display = "block";
		  } else {
			  document.getElementById("myBtn").style.display = "none";
		  }
	}

	// When the user clicks on the button, scroll to the top of the document
	 function topFunction() {
		  document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	 }

	 
   