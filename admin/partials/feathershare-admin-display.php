<?php

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
    <h2>FeatherShare Settings</h2>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'feathershare' );
        do_settings_sections( 'feathershare' );
        submit_button();
        ?>
    </form>
</div>