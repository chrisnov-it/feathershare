(function ($) {
    'use strict';

    $(function () {
        /**
         * Social Sharing: Copy Link Functionality
         */
        $('.feathershare-social-buttons').on('click', '.feathershare-copy-link', function (e) {
            e.preventDefault();

            var button = $(this);
            var container = button.closest('.feathershare-social-buttons');
            var url = container.data('url') || window.location.href;
            var copiedText = button.data('copied-text') || 'Copied!';
            var copyLabel = button.find('.feathershare-copy-label');
            var originalLabelText = copyLabel.text();

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
            button.addClass('copied');
            if (copyLabel.length) {
                copyLabel.text(copiedText);
            }

            setTimeout(function () {
                button.removeClass('copied');
                if (copyLabel.length) {
                    copyLabel.text(originalLabelText);
                }
            }, 2000);
        }

        /**
         * Subscription: AJAX Form Submission
         */
        $('#feathershare-subscription-form').on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var messageContainer = form.siblings('.feathershare-subscription-message');
            var emailField = form.find('#feathershare_email');
            var nonceField = form.find('input[name="feathershare_subscribe_nonce"]');

            messageContainer.empty();

            var i18n = (window.feathershareSubscribe && window.feathershareSubscribe.i18n) ? window.feathershareSubscribe.i18n : {};
            var strings = {
                invalidEmail: i18n.invalidEmail || 'Please enter a valid email address.',
                processing: i18n.processing || 'Processing...',
                genericError: i18n.genericError || 'An error occurred. Please try again.'
            };

            if (!emailField.val() || !isValidEmail(emailField.val())) {
                messageContainer.html('<div class="feathershare-error">' + strings.invalidEmail + '</div>');
                return;
            }

            var submitButton = form.find('input[type="submit"]');
            var originalText = submitButton.val();
            submitButton.val(strings.processing).prop('disabled', true);

            $.ajax({
                url: window.feathershareSubscribe ? window.feathershareSubscribe.ajaxUrl : '',
                type: 'POST',
                data: {
                    action: window.feathershareSubscribe ? window.feathershareSubscribe.action : 'feathershare_subscribe',
                    feathershare_subscribe_submit: '1',
                    feathershare_subscribe_nonce: nonceField.val(),
                    feathershare_email: emailField.val()
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        messageContainer.html('<div class="feathershare-success">' + response.data.message + '</div>');
                        emailField.val('');
                    } else {
                        messageContainer.html('<div class="feathershare-error">' + response.data.message + '</div>');
                    }
                },
                error: function () {
                    messageContainer.html('<div class="feathershare-error">' + strings.genericError + '</div>');
                },
                complete: function () {
                    submitButton.val(originalText).prop('disabled', false);
                }
            });
        });

        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });

})(jQuery);
