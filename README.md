# Sarai Chinwag WordPress Theme

A versatile WordPress theme with dynamic Google Fonts integration and universal customization capabilities. See it in action at [saraichinwag.com](https://saraichinwag.com).

## Features

### Universal Theme Design
- **Recipe Site Mode**: Full recipe post type with ratings, schema markup, and specialized templates
- **Standard Blog Mode**: Clean blog functionality via admin toggle
- **White Label Ready**: Customizable for any site type

### Dynamic Typography System
- **Google Fonts Integration**: Access to all Google Fonts via API
- **Smart Font Organization**: Display fonts for headings, sans-serif + serif for body text
- **Percentage-Based Scaling**: 1-100% size control with proportional heading hierarchy
- **Responsive Design**: All font sizes scale across breakpoints

### Performance & Security
- **Transient Caching**: 24-hour font list caching for optimal performance
- **Dynamic Asset Versioning**: Automatic cache busting using `filemtime()`
- **Secure API Integration**: Proper sanitization and escaping throughout
- **Optimized Loading**: Only loads selected Google Fonts with `font-display: swap`

### Admin Features
- **Theme Settings Panel**: Configure API keys and toggle functionality
- **WordPress Customizer**: Live preview font and size changes
- **Recipe Toggle**: Completely disable recipe features for universal use

## Installation

1. Upload theme files to `/wp-content/themes/saraichinwag/`
2. Activate the theme in WordPress admin
3. Go to **Settings → Theme Settings** to configure Google Fonts API key
4. Customize fonts and sizing via **Appearance → Customize → Typography**

## Configuration

### Google Fonts API Key
1. Get your API key from [Google Fonts Developer API](https://developers.google.com/fonts/docs/developer_api)
2. Add it in **Settings → Theme Settings → Google Fonts API Key**
3. All Google Fonts will then be available in the customizer

### Universal Theme Usage
Toggle recipe functionality in **Settings → Theme Settings**:
- **Recipes Enabled**: Full recipe site with ratings and schema
- **Recipes Disabled**: Clean blog theme for any content type

## Development

### File Structure
```
saraichinwag/
├── php/                    # Modular PHP components
│   ├── customizer.php     # Font customization system
│   ├── recipes.php        # Recipe post type
│   ├── ratings.php        # AJAX rating system
│   └── admin-settings.php # Theme settings panel
├── js/                    # JavaScript files
│   ├── customizer.js      # Live preview functionality
│   └── nav.js            # Navigation enhancements
├── fonts/                 # Local theme fonts
└── template-parts/        # Reusable template components
```

### Coding Standards
- WordPress coding standards throughout
- All output properly escaped with `esc_html()`, `esc_url()`, etc.
- Input sanitization with WordPress functions
- Uses `wp_remote_get()` instead of cURL
- Transient caching for expensive operations

## Customization

### Font System
- **Default**: 50% = current theme appearance
- **Scaling**: 100% = 2x larger, 1% = minimal
- **Fallbacks**: Gluten (theme font) → System fonts
- **Categories**: Display (headings) + Sans-serif/Serif (body)

### CSS Custom Properties
```css
:root {
  --font-heading: 'Font Name', fallbacks;
  --font-body: 'Font Name', fallbacks;
  --font-heading-scale: 1.0;
  --font-body-scale: 1.0;
}
```

## Live Demo

See the theme in action at [saraichinwag.com](https://saraichinwag.com).

## Support

For technical support, please create an issue in this repository or contact the developer at [chubes.net](https://chubes.net).

## License

This theme is designed for personal use and white-labeling. Commercial distribution requires permission.

---

**Version**: 2.0  
**Author**: Chris Huber  
**Website**: [chubes.net](https://chubes.net)  
**Theme URI**: [saraichinwag.com](https://saraichinwag.com)
