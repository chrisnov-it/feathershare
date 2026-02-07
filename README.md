# 🪶 FeatherShare

[![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-0073AA?style=for-the-badge&logo=wordpress&logoColor=white)](https://wordpress.org)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg?style=for-the-badge)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Pure Performance](https://img.shields.io/badge/Performance-99%2B-emerald?style=for-the-badge&logo=speedtest&logoColor=white)](#performance)

**FeatherShare** is an ultra-lightweight, high-performance social sharing and interaction framework for WordPress. Built with a "Zen-logic" approach, it eliminates the bloat of traditional social plugins by using inline SVGs, zero external dependencies, and optimized AJAX handlers.

---

## ⚡ Why FeatherShare?

Traditional sharing plugins often tank your PageSpeed score by loading heavy CSS libraries (like FontAwesome) and multiple JavaScript trackers. **FeatherShare** fixes this.

- **🚀 Zero Bloat**: No external font files, no tracking scripts, no heavy libraries.
- **🎨 Inline SVGs**: Scalable, lightning-fast icons that don't trigger extra HTTP requests.
- **🏗️ OOP Architecture**: Built following the WordPress Plugin Boilerplate (OO design) for maximum stability.
- **🛡️ Secure**: Fully sanitized, escaped, and nonced AJAX operations.
- **📱 Responsive**: Perfectly fluid on mobile, tablet, and desktop.

---

## 🛠️ Features

- **Extreme Performance**: Maintains 99+ PageSpeed scores.
- **Smart Placement**: Automatic insertion into post content or manual shortcode usage.
- **Modular Design**: Separate handlers for Social Sharing and Subscription Logic.
- **Dynamic CSS**: Styles are only loaded when needed.
- **Agnostic Logic**: Works with any theme without conflict.

---

## ⚙️ Installation

1. Create a folder named `feathershare` in your `/wp-content/plugins/` directory.
2. Upload the plugin files to the directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to **Settings > FeatherShare** to configure your buttons.

---

## 💻 Technical Details

FeatherShare is built on a modular OOP structure:

```text
feathershare/
├── admin/          # Admin-side settings and styles
├── includes/       # Core logic and orchestration
├── public/         # Public-facing components
└── languages/      # Internationalization
```

### Hooks & Integration
- Filter: `the_content` (for auto-insertion)
- Actions: `wp_enqueue_scripts`, `admin_init`, `admin_menu`

---

## 🛡️ WordPress Coding Standards (WPCS)

This plugin is developed with **WPCS compliance** in mind:
- [x] Yoda Conditions for secure comparisons.
- [x] Strict input sanitization (`sanitize_text_field`, `absint`).
- [x] Output escaping (`esc_html`, `esc_attr`).
- [x] Nonce verification for all state-changing operations.
- [x] Standardized file naming conventions (`class-xyz.php`).

---

## 👤 Author

**Reynov Christian**
- Website: [chrisnov.com](https://chrisnov.com)
- GitHub: [@chrisnov-it](https://github.com/chrisnov-it)

---

## 📄 License

This project is licensed under the GPL v2 or later. See the `LICENSE` file for details.

---

*Built with ❤️ for the performance-obsessed WordPress community.*
