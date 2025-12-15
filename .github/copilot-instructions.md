# FeatherShare WordPress Plugin - AI Coding Guidelines

## Architecture Overview

FeatherShare is a modular WordPress plugin built with object-oriented PHP following WordPress coding standards. The plugin provides social sharing buttons and email subscription functionality.

### Core Components
- **Main Plugin Class** (`includes/class-feathershare.php`): Orchestrates all components using a custom loader pattern
- **Social Sharing** (`includes/class-social-sharing.php`): Handles sharing button generation and placement
- **Subscription Handler** (`includes/class-subscription-handler.php`): Manages email subscriptions via AJAX and custom post types
- **Admin Interface** (`admin/class-feathershare-admin.php`): Settings page and admin functionality
- **Public Interface** (`public/class-feathershare-public.php`): Frontend asset enqueuing

### Key Architectural Patterns

#### Hook Management
Use the custom `FeatherShare_Loader` class for all WordPress hooks instead of direct `add_action`/`add_filter` calls:
```php
$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
$this->loader->add_filter('the_content', $social_sharing, 'add_sharing_buttons');
```

#### Settings API Integration
Register settings in admin class with proper sanitization:
```php
register_setting($this->plugin_name, 'feathershare_button_style', [
    'type' => 'string',
    'sanitize_callback' => 'sanitize_key',
    'default' => 'circle'
]);
```

#### AJAX Endpoints
Handle AJAX requests with proper nonce verification and JSON responses:
```php
add_action('wp_ajax_feathershare_subscribe', [$this, 'process_subscription_form']);
add_action('wp_ajax_nopriv_feathershare_subscribe', [$this, 'process_subscription_form']);
```

## Development Workflow

### No Build Process
This is pure PHP/JS/CSS with no compilation step. Edit files directly and test in WordPress environment.

### Security First
- Always use `wp_nonce_field()` and `wp_verify_nonce()` for forms
- Sanitize all user inputs with appropriate WordPress functions (`sanitize_text_field`, `sanitize_email`, etc.)
- Escape all outputs with `esc_attr()`, `esc_html()`, `esc_url()`
- Use prepared statements for database queries

### Accessibility Standards
- Include ARIA labels for interactive elements
- Add screen reader text with `<span class="screen-reader-text">`
- Ensure keyboard navigation support
- Test with screen readers

### Frontend Patterns

#### jQuery Usage
Wrap all jQuery code in IIFE with `$` parameter:
```javascript
(function($) {
    'use strict';
    $(function() {
        // Code here
    });
})(jQuery);
```

#### AJAX Calls
Use WordPress AJAX API with proper localization:
```javascript
$.ajax({
    url: feathershare_ajax.ajax_url,
    type: 'POST',
    data: {
        action: 'feathershare_subscribe',
        security: nonce_value
    },
    dataType: 'json'
});
```

### Content Integration

#### Filter-Based Injection
Add content via WordPress filters with conditional checks:
```php
if (is_single() && in_the_loop() && is_main_query()) {
    return $content . $sharing_buttons;
}
```

#### Shortcode Implementation
Register shortcodes in constructor and handle attributes:
```php
add_shortcode('feathershare_subscribe', [$this, 'display_subscription_form']);
```

### Custom Post Types
Create CPTs for data storage with appropriate arguments:
```php
register_post_type('feathershare_subscription', [
    'public' => false,
    'show_ui' => true,
    'supports' => ['title'],
    'menu_icon' => 'dashicons-email'
]);
```

## Key Files Reference

- `includes/class-feathershare.php`: Main orchestrator, see `load_dependencies()` and hook definitions
- `includes/class-social-sharing.php`: Sharing button logic, examine `generate_sharing_buttons()` for HTML structure
- `includes/class-subscription-handler.php`: Subscription processing, check AJAX handling in `process_subscription_form()`
- `admin/class-feathershare-admin.php`: Settings registration and admin page rendering
- `public/js/social-sharing.js`: Copy link functionality with Clipboard API fallback
- `public/js/subscription-handler.js`: AJAX form submission with validation

## Common Patterns

- Class constructors take `$plugin_name` and `$version` parameters
- Use `FEATHERSHARE_DIR` and `FEATHERSHARE_URL` constants for paths
- Enqueue assets conditionally based on page type (`is_single()`, `is_admin()`)
- Store settings with `feathershare_` prefix
- Use WordPress translation functions (`__()`, `_e()`) with text domain 'feathershare'</content>
<parameter name="filePath">d:\dev\feathershare\.github\copilot-instructions.md