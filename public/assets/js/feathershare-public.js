(function( $ ) {
	'use strict';

	$(function() {
		// Handle subscription form submission
		$('#feathershare-subscription-form').on('submit', function(e) {
			e.preventDefault();
			
			var form = $(this);
			var messageContainer = form.siblings('.feathershare-subscription-message');
			var emailField = form.find('#feathershare_email');
			var nonceField = form.find('input[name="feathershare_subscribe_nonce"]');
			
			// Clear any previous messages
			messageContainer.empty();
			
			// Validate email
			if (!emailField.val() || !isValidEmail(emailField.val())) {
				messageContainer.html('<div class="feathershare-error">Please enter a valid email address.</div>');
				return;
			}
			
			// Disable submit button during processing
			var submitButton = form.find('input[type="submit"]');
			var originalText = submitButton.val();
			submitButton.val('Processing...').prop('disabled', true);
			
			// Send AJAX request
			$.ajax({
				url: feathershare_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'feathershare_subscribe',
					feathershare_subscribe_submit: '1',
					feathershare_subscribe_nonce: nonceField.val(),
					feathershare_email: emailField.val()
				},
				dataType: 'json',
				timeout: 10000, // 10 second timeout
				success: function(response) {
					if (response.success) {
						messageContainer.html('<div class="feathershare-success">' + response.data.message + '</div>');
						// Clear the form
						emailField.val('');
					} else {
						messageContainer.html('<div class="feathershare-error">' + response.data.message + '</div>');
					}
				},
				error: function(xhr, status, error) {
					// Handle different types of errors
					var errorMessage = 'An error occurred. Please try again.';
					
					if (status === 'timeout') {
						errorMessage = 'Request timed out. Please check your connection and try again.';
					} else if (xhr.status === 0) {
						errorMessage = 'Could not connect to server. Please check your connection.';
					} else if (xhr.status >= 500) {
						errorMessage = 'Server error. Please try again later.';
					}
					
					messageContainer.html('<div class="feathershare-error">' + errorMessage + '</div>');
				},
				complete: function() {
					// Re-enable submit button
					submitButton.val(originalText).prop('disabled', false);
				}
			});
		});
		
		// Email validation helper function
		function isValidEmail(email) {
			var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailRegex.test(email);
		}
	});

})( jQuery );