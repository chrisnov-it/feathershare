(function ($) {
	'use strict';

	$(function () {
		// Copy Link button functionality
		$('.feathershare-social-buttons').on('click', '.feathershare-copy-link', function (e) {
			e.preventDefault();

			var button = $(this);
			var container = button.closest('.feathershare-social-buttons');
			var url = container.data('url') || window.location.href;
			var copiedText = button.data('copied-text') || 'Copied!';
			var copyLabel = button.find('.feathershare-copy-label');
			var originalLabelText = copyLabel.text();

			// Use modern Clipboard API if available, fallback to execCommand
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(function () {
					showCopiedFeedback(button, copyLabel, copiedText, originalLabelText);
				}).catch(function () {
					fallbackCopy(url, button, copyLabel, copiedText, originalLabelText);
				});
			} else {
				fallbackCopy(url, button, copyLabel, copiedText, originalLabelText);
			}
		});

		function fallbackCopy(text, button, copyLabel, copiedText, originalLabelText) {
			// Create a temporary textarea to copy the URL
			var tempInput = document.createElement('textarea');
			tempInput.value = text;
			tempInput.style.position = 'absolute';
			tempInput.style.left = '-9999px';
			document.body.appendChild(tempInput);
			tempInput.select();

			try {
				document.execCommand('copy');
				showCopiedFeedback(button, copyLabel, copiedText, originalLabelText);
			} catch (err) {
				console.error('Failed to copy URL:', err);
			}

			document.body.removeChild(tempInput);
		}

		function showCopiedFeedback(button, copyLabel, copiedText, originalLabelText) {
			// Add copied class for visual feedback
			button.addClass('copied');

			// Update label text if present
			if (copyLabel.length) {
				copyLabel.text(copiedText);
			}

			// Reset after 2 seconds
			setTimeout(function () {
				button.removeClass('copied');
				if (copyLabel.length) {
					copyLabel.text(originalLabelText);
				}
			}, 2000);
		}

		// Social share buttons click tracking (optional)
		$('.feathershare-social-buttons a').on('click', function (e) {
			// Example: Add tracking or custom behavior
			// console.log('Social button clicked: ' + $(this).attr('class'));
		});
	});

})(jQuery);