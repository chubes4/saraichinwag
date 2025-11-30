# Sarai Chinwag WordPress Theme

A versatile WordPress theme with advanced filtering, randomized content discovery, and full-width layout optimization. Features anti-chronological design that encourages serendipitous browsing through intelligent content presentation. See it in action at [saraichinwag.com](https://saraichinwag.com).

## Features

### Advanced Filter System
- **Multiple Sort Options**: Random (default), Most Popular (by view count), Most Recent, Oldest
- **Content Type Filtering**: Filter between Posts, Recipes, or All content seamlessly
- **AJAX-Powered**: Real-time filtering without page reloads, preserves state across Load More
- **Load More Integration**: Seamless infinite scroll that maintains filter state
- **Mobile Optimized**: Touch-friendly interface with collapsible design

### Full-Width Layout System
- **Maximized Content Discovery**: 4-column responsive grid on home and archive pages (vs traditional 2-3)
- **Strategic Sidebar Placement**: Removed from browse pages, maintained on single content for discovery widgets
- **Responsive Grid**: 4 columns (desktop) → 3 (laptop) → 2 (tablet) → 1 (mobile)
- **Performance Focused**: Simple view counter system tracks popularity without complex analytics

### Default 5-Star Rating System
- **Automatic Assignment**: New recipes receive 5.0 rating with 1 review count upon publication
- **Immediate Visibility**: Ensures new recipes appear properly in popularity sorting from day one
- **User Interaction**: Visitors can submit their own ratings through AJAX-powered interface
- **Smart Integration**: User ratings update the average calculation while preserving the initial boost
- **Security First**: AJAX submissions use WordPress nonce verification for CSRF protection
- **Local Storage**: Immediate visual feedback with localStorage persistence

### Universal Theme Design
- **Recipe Site Mode**: Full recipe post type with automatic default 5-star ratings, user rating system, embedded Schema.org markup, and specialized templates
- **Standard Blog Mode**: Clean blog functionality via admin toggle
- **White Label Ready**: Customizable for any site type

### Enhanced Navigation
- **Badge-Breadcrumb System**: Clickable category/tag badges on single posts for intuitive navigation
- **Smart Breadcrumbs**: Context-aware hierarchical navigation on archive pages
- **Random Discovery**: Multiple random access points including `/random-all` for mixed content

### Randomization Focus
- **Anti-Chronological Design**: Randomization as default behavior breaks traditional blog patterns
- **Random Content Discovery**: Home and archive pages encourage serendipitous browsing
- **Multiple Random Endpoints**: `/random-post`, `/random-recipe`, and `/random-all` for instant discovery
- **Performance Optimized**: Cached random queries for fast randomization without database strain

### Dynamic Typography System
- **Google Fonts Integration**: Access to all Google Fonts via API
- **Smart Font Organization**: Display fonts for headings, sans-serif + serif for body text
- **Percentage-Based Scaling**: 1-100% size control with proportional heading hierarchy
- **Responsive Design**: All font sizes scale across breakpoints

### Performance & Security
- **Object Caching**: wp_cache_* functions with specialized cache groups for optimal performance
- **Dynamic Asset Versioning**: Automatic cache busting using `filemtime()`
- **Secure API Integration**: Proper sanitization and escaping throughout
- **Optimized Loading**: Only loads selected Google Fonts with `font-display: swap`
- **Complete Image Gallery System**: Advanced image extraction, gallery archives, and specialized display templates
- **Archive Image Mode Links**: Intelligent "Try Image Mode" links for seamless switching between standard and gallery views

### Admin Features
- **Theme Settings Panel**: Configure API keys, contact form settings, and toggle functionality
- **WordPress Customizer**: Live preview font and size changes
- **Contact Form System**: AJAX-powered forms with Cloudflare Turnstile bot protection
- **Recipe Toggle**: Completely disable recipe features for universal use

## Installation

### Production Installation (Recommended)
1. Download or build the production package: `saraichinwag.zip`
2. Upload via WordPress admin: **Appearance → Themes → Add New → Upload Theme**
3. Activate the theme
4. Configure in **Settings → Theme Settings** (Google Fonts API key, recipe toggle)
5. Customize fonts via **Appearance → Customize → Typography**

### Development Installation
1. Clone or download the repository to `/wp-content/themes/saraichinwag/`
2. Activate the theme in WordPress admin
3. Edit files directly - changes reflect immediately
4. Run `./build.sh` when ready for production deployment

## Configuration

### Google Fonts API Key
1. Get your API key from [Google Fonts Developer API](https://developers.google.com/fonts/docs/developer_api)
2. Add it in **Settings → Theme Settings → Google Fonts API Key**
3. All Google Fonts will then be available in the customizer

### Contact Form Configuration
Configure contact forms in **Settings → Theme Settings → Contact Form Settings**:
- **Cloudflare Turnstile Keys**: Get keys from Cloudflare Turnstile dashboard for bot protection
- **Recipient Email**: Set email address for form submissions
- **Submitter Copy**: Optionally send confirmation emails to form submitters
- **Shortcode**: Use `[sarai_contact_form]` to embed forms in pages/posts

### Universal Theme Usage
Toggle recipe functionality in **Settings → Theme Settings**:
- **Recipes Enabled**: Full recipe site with automatic default 5-star ratings, user rating system, and embedded Schema.org markup
- **Recipes Disabled**: Clean blog theme for any content type

## Build System

### Production Build
```bash
# Create optimized production package
./build.sh

# Output: dist/saraichinwag.zip (ready for WordPress installation)
```

**Build Features:**
- **Automatic Versioning**: Extracts version from style.css header
- **Clean Production Package**: Excludes development files (/docs, .git, .md files)
- **VSCode Integration**: Build commands available via Command Palette
- **WordPress Ready**: Creates properly named ZIP for theme installation

**VSCode Build Tasks:**
- `Ctrl/Cmd+Shift+P` → "Tasks: Run Task" → "Build Theme"
- Default build: `Ctrl/Cmd+Shift+B`

## Development

### File Structure
```
saraichinwag/
├── inc/                          # PHP module directory (organized functionality)
│   ├── admin/                    # Admin interface components
│   ├── contact/                  # Contact form system
│   ├── core/
│   │   └── assets.php           # Centralized asset management
│   ├── queries/                  # Query modification systems
│   │   └── image-mode/          # Image gallery system
│   └── assets/                   # Organized CSS and JavaScript
│       ├── css/                  # Conditional CSS files
│       │   ├── root.css         # CSS custom properties (loaded first)
│       │   ├── archive.css      # Archive-specific styles
│       │   ├── contact.css      # Contact form styles
│       │   ├── customizer.css   # Live preview styling (admin)
│       │   ├── editor.css       # Editor font integration
│       │   ├── image-mode.css   # Image gallery styles
│       │   ├── recipes.css      # Recipe-specific styles
│       │   ├── sidebar.css      # Sidebar styles
│       │   └── single.css       # Single post/page styles
│       └── js/                   # Core JavaScript modules
│           ├── nav.js           # Navigation enhancements
│           ├── gallery-utils.js # Image gallery functionality
│           └── pinterest.js     # Pinterest integration
├── js/                           # Root-level JavaScript (specialized)
│   ├── filter-bar.js            # Advanced filter system
│   ├── load-more.js             # AJAX Load More functionality
│   ├── customizer.js            # Live preview functionality
│   ├── rating.js                # Recipe rating interactions
│   └── contact-form.js          # Contact form handling
├── template-parts/              # Reusable template components
│   ├── content-recipe.php       # Recipe display templates
│   ├── content-single.php       # Single post template
│   ├── content.php              # Standard post template
│   ├── content-image-gallery.php     # Image gallery post template
│   ├── filter-bar.php               # Filter interface component
│   ├── gallery-item.php             # Individual gallery item
│   └── archive-image-mode-link.php  # "Try Image Mode" switcher link
├── style.css                     # Primary stylesheet
└── fonts/                       # Local theme fonts
```

### Coding Standards
- WordPress coding standards throughout
- All output properly escaped with `esc_html()`, `esc_url()`, etc.
- Input sanitization with WordPress functions
- Uses `wp_remote_get()` instead of cURL
- Object caching with wp_cache_* functions for expensive operations

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

**Version**: 2.2.2  
**Author**: Chris Huber  
**Website**: [chubes.net](https://chubes.net)  
**Theme URI**: [saraichinwag.com](https://saraichinwag.com)

## Changelog

### v2.2.2 - Async View Counter & Related Posts Improvements
- **NEW**: Async view counter system using REST API to eliminate blocking page loads and improve performance
- **NEW**: SessionStorage-based duplicate prevention for view tracking within same browser session
- **IMPROVED**: Related posts system now prevents duplicate posts by accumulating excluded IDs across taxonomy queries
- **PERFORMANCE**: View tracking moved from wp_head to async JavaScript for better page load performance

### v2.2.1 - Image Count Cache Invalidation System
- **NEW**: Centralized cache invalidation system for image counts with `sarai_chinwag_clear_all_image_count_caches()` function
- **NEW**: Automatic cache clearing on media attachment add/edit/delete via WordPress hooks
- **NEW**: `sarai_chinwag_clear_image_cache_on_media_change()` function for attachment-specific invalidation
- **IMPROVED**: Dual cache strategy - image data caches (30 limit) separate from image count caches (99999 limit)
- **IMPROVED**: Archive image mode links now accurately count tens of thousands of images while maintaining performance
- **PERFORMANCE**: Immediate cache updates ensure image counts reflect media library changes instantly

### v2.2 - WordPress Editor Integration & Contact Form System
- **NEW**: Default 5-Star Rating System - new recipes automatically receive 5.0 rating with 1 review count for immediate visibility in popularity sorting
- **NEW**: WordPress Editor Font Integration - consistent fonts between editors and frontend
- **NEW**: Complete Contact Form System - AJAX-powered forms with Cloudflare Turnstile bot protection and email notifications
- **NEW**: Gallery Discovery Badges - intelligent gallery links on single posts with accurate image counts
- **NEW**: Image Anchoring - automatic anchorable spans on images for deep linking
- **NEW**: Complete Image Gallery System - advanced image extraction, gallery archives, and specialized display templates
- **NEW**: Archive Image Mode Links - intelligent "Try Image Mode" links with accurate image counts for seamless gallery switching
- **NEW**: Image Search & Discovery - comprehensive image search with category/tag-based filtering
- **NEW**: Load More Integration - AJAX infinite scroll that preserves filter state
- **NEW**: Enhanced Pinterest Integration - improved social functionality with js/pinterest.js
- **IMPROVED**: Footer Architecture Simplification - removed category/tag clouds for better UX/SEO
- **IMPROVED**: Object Caching Evolution - wp_cache_* functions with specialized cache groups including images
- **PERFORMANCE**: Enhanced caching strategies with proper cache group segregation

### v2.1 - Advanced Filter & Layout System
- **NEW**: Advanced filter bar with multiple sort options (Random, Popular, Recent, Oldest)
- **NEW**: Full-width 4-column responsive grid layout for maximum content discovery
- **NEW**: Simple view counter system for popularity tracking
- **NEW**: Badge-breadcrumb navigation system for enhanced UX
- **NEW**: Pinterest follow button relocated to footer
- **NEW**: Random discovery section replaces complex related posts logic
- **IMPROVED**: Mobile-optimized responsive design with touch-friendly controls
- **IMPROVED**: AJAX filtering with seamless Load More integration
- **PERFORMANCE**: Optimized queries and smart caching for all new features
