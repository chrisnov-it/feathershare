<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    FeatherShare
 * @subpackage FeatherShare/admin/partials
 */
?>

<div class="wrap">
	<h1><?php esc_html_e( 'FeatherShare Settings', 'feathershare' ); ?></h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'feathershare' );
		do_settings_sections( 'feathershare' );
		submit_button();
		?>
	</form>
</div>
