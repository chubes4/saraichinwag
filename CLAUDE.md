# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Environment

This is a WordPress theme for "Sarai Chinwag" with no build process. All files are directly edited and changes are immediately reflected. No package.json, composer.json, or build tools are configured.

### Admin Settings
The theme includes an admin settings page accessible via **Settings → Theme Settings** in the WordPress admin. This page allows configuration of:
- IndexNow API Key for automatic search engine indexing
- Google Fonts API Key for dynamic font loading from Google Fonts API
- Recipe Functionality Toggle: Completely disable all recipe-related features for universal theme usage

## Theme Architecture

### Core Structure
- **functions.php**: Main theme setup, enqueues styles/scripts with dynamic versioning using `filemtime()`, autoloads PHP modules from `/php` directory
- **style.css**: Primary stylesheet with custom font declarations and theme styles  
- **Template hierarchy**: Standard WordPress templates (single.php, archive.php, home.php, search.php, page.php, etc.)
- **page.php**: Standard page template using `template-parts/content-single.php` with sidebar support

### Custom Post Types
- **Recipe post type**: Registered via `php/recipes.php` with full labels and Schema.org markup support
- **Main query modification**: Includes both 'post' and 'recipe' post types in home, category, tag, and search queries

### PHP Module System
The theme uses an autoload system in functions.php that includes all PHP files from the `/php` directory:
- `admin-settings.php`: Theme settings page with API key management
- `bing-index-now.php`: Bing indexing integration
- `customizer.php`: Dynamic Google Fonts system with API integration and percentage-based scaling
- `filter-bar.php`: Advanced filter bar interface for home/archive pages
- `image-counts.php`: Image handling utilities
- `random-post.php` & `random-queries.php`: Random content functionality (anti-chronological design)
- `ratings.php`: AJAX-based recipe rating system with nonce security
- `recipes.php`: Custom post type registration
- `related-posts.php`: Random discovery functionality (replaces complex related logic)
- `schema-recipe.php`: Schema.org structured data for recipes
- `sorting-archives.php`: Advanced AJAX filtering and sorting system
- `view-counter.php`: Simple post view tracking for popularity sorting
- `yoast-stuff.php`: Yoast SEO customizations

### JavaScript Structure
- **js/nav.js**: Header search functionality with position adjustment for admin bar
- **js/customizer.js**: Live preview functionality for font changes and size scaling in WordPress Customizer
- **js/advanced-filters.js**: Advanced AJAX filtering system with sort options and Load More integration
- **js/filters.js**: Legacy filtering functionality (maintained for compatibility)
- **js/rating.js**: AJAX rating system with localStorage persistence, nonce security, and dual-state management (user + server average)

### Template Parts
- `template-parts/content-recipe.php`: Recipe display with Schema.org microdata
- `template-parts/content.php` & `template-parts/content-single.php`: Standard post templates

### Key Features
- **Advanced Filter System**: Full-width filter bar with sort options (Random, Most Popular, Recent, Oldest) and post type filtering
- **Full-Width Layout**: Home and archive pages display 4-column responsive grid (sidebar removed for maximum content visibility)
- **View Counter System**: Simple post meta tracking for popularity sorting (`_post_views` field)
- **Randomized content discovery**: Anti-chronological design with randomization as default behavior
- **Random access pages**: `/random-post`, `/random-recipe`, and `/random-all` pages for serendipitous browsing
- **Dynamic Google Fonts system**: API integration with category filtering (display fonts for headings, sans-serif + serif for body)
- **Percentage-based font scaling**: 1-100% size control with 50% = current theme baseline, maintains heading hierarchy
- **Universal theme design**: Can function as recipe site or standard blog via admin toggle
- **Recipe functionality**: Complete recipe post type with ratings, schema markup, and specialized templates (when enabled)
- **WordPress Customizer integration**: Live preview for font changes and size scaling with custom CSS properties
- **Pinterest integration**: Footer follow button + automatic Pinterest save buttons with `data-pin-url` attributes
- **Badge-breadcrumb system**: Category/tag badges on single posts for navigation, traditional breadcrumbs on archives
- **Random discovery section**: 3-post random grid replaces complex related posts logic
- **Dynamic asset versioning**: Uses `filemtime()` for cache busting on CSS and JS files
- **Rating system**: AJAX-powered recipe ratings with localStorage persistence, nonce security, and dual-state management
- **Schema.org markup**: Full structured data implementation for recipes
- **Performance optimizations**: Object caching with wp_cache_* functions, indefinite footer caching, cache groups, and limited query results
- **Security enhancements**: All output properly escaped, input sanitized, secure API key storage

## Image Optimization Strategy

**Custom Image Sizes:**
- **grid-thumb**: 450x450px cropped for post grid display (optimized for 412px mobile viewports)
- **Removed unused WordPress defaults**: thumbnail (150px), medium (300px), medium_large (768px) to reduce storage overhead

**Performance Benefits:**
- Single optimized image size reduces server storage and bandwidth usage
- 450px size provides crisp display on mobile devices while remaining lightweight
- Eliminates WordPress generation of unnecessary image variations
- Improves theme loading speed by serving appropriately sized images

## Dynamic Google Fonts Architecture

### Font System Components
- **API Integration**: `php/customizer.php` handles Google Fonts API calls with `sarai_chinwag_fetch_google_fonts_by_category()`
- **Customizer Controls**: Two font dropdowns (Header/Body) + two size sliders (1-100%) accessible via **Appearance → Customize → Typography**
- **CSS Scaling System**: Uses CSS custom properties (`--font-heading-scale`, `--font-body-scale`) for proportional scaling
- **Live Preview**: `js/customizer.js` provides real-time font and size changes in WordPress Customizer
- **Caching Strategy**: Object cache groups with `sarai_chinwag_fonts` group for all font-related data, 24-hour wp_cache for Google Fonts API responses using dynamic cache keys

### Font Organization
- **Header Fonts**: Display category fonts only (`category=display` API parameter)
- **Body Fonts**: Combined sans-serif + serif fonts (`category=sans-serif` + `category=serif`)
- **Fallback Strategy**: Google Font → Gluten (theme font) → System fonts

### Scaling System
- **50% = Baseline**: Current theme sizes (20px body, 1.75em h1, 1.38em h2)  
- **Fluid Responsive Scaling**: Uses `clamp()` functions for optimal display across all devices
- **CSS Implementation**: `calc()` functions with custom properties and clamp() for responsive breakpoints
- **Mobile Optimization**: Specific breakpoints at 768px, 600px, and 480px for enhanced mobile typography
- **Hierarchy Preservation**: All heading levels (h1-h6) scale proportionally while maintaining fluid responsiveness

## Randomization System Architecture

### Anti-Chronological Design
- **Main Query Modification**: `php/random-queries.php` modifies home and archive queries to use `orderby: rand`
- **Post Type Integration**: Includes both 'post' and 'recipe' post types in randomized queries
- **Performance Optimizations**: Cached random ID arrays replace expensive `orderby => 'rand'` queries with rotation system and limited datasets (max 500 posts)

### Random Access Pages  
- **Direct Random Posts**: `/random-post` page redirects to random post via `extra_chill_redirect_to_random_post()`
- **Direct Random Recipes**: `/random-recipe` page redirects to random recipe (respects recipe toggle)
- **Direct Random All**: `/random-all` page redirects to random post or recipe (mixed selection)
- **Fallback Logic**: When recipes disabled, random-recipe redirects to random-post instead
- **Implementation**: Uses `WP_Query` with `orderby: rand` and `posts_per_page: 1`

## Advanced Filter System Architecture

### Filter Bar Components
- **Sort Options**: Random (default) | Most Popular (by view count) | Most Recent | Oldest
- **Content Types**: All | Posts | Recipes (only shown when both post types exist)
- **AJAX Integration**: Real-time filtering without page reload, preserves filter state across Load More
- **Mobile Responsive**: Collapsible interface with touch-friendly buttons (44px minimum height)
- **Legacy Compatibility**: Old checkbox system deprecated in favor of cleaner button interface

### View Counter System
- **Storage**: Simple post meta (`_post_views`) incremented on each post view
- **Tracking**: Automatic on `wp_head` for singular posts/recipes only
- **Performance**: Efficient meta queries for popularity sorting
- **Functions**: `sarai_chinwag_track_post_view()`, `sarai_chinwag_get_post_views($post_id)`

### Layout Strategy
- **Full-Width Pages**: Home, archives, search (sidebars removed for 4-column grid)
- **Sidebar Pages**: Single posts, recipes, pages (maintains discovery widgets)
- **Responsive Grid**: 4 columns (desktop) → 3 (laptop) → 2 (tablet) → 1 (mobile)
- **Filter Persistence**: AJAX Load More preserves active sort and type filters

## Badge-Breadcrumb Navigation System

### Single Posts/Recipes
- **Badge Navigation**: Clickable category (blue) and tag (pink) badges above post title
- **No Home Link**: Random icon and underlined site title serve this purpose
- **Function**: `sarai_chinwag_post_badges()` displays primary category + up to 3 tags

### Archive Pages
- **Traditional Breadcrumbs**: Text-based hierarchy (Home > Category/Tag/Search)
- **Context Aware**: Different patterns for category, tag, search, author, date archives
- **Function**: `sarai_chinwag_archive_breadcrumbs()` generates appropriate breadcrumb trail

## Development Commands

No build process required - this is a direct-edit WordPress theme:
1. Edit PHP, CSS, or JS files directly
2. Changes are immediately reflected (CSS/JS have dynamic versioning via `filemtime()`)
3. For PHP changes, reload the page to see updates
4. No package.json, composer.json, or build tools configured

### Testing
- Test functionality directly in WordPress environment
- Use WordPress debugging (`WP_DEBUG`) for PHP errors
- Browser developer tools for JavaScript debugging

## Code Conventions

- Follow WordPress coding standards
- Use `wp_remote_get()` instead of cURL functions  
- All output should be run through escaping functions (`esc_url()`, `esc_html()`, `esc_attr()`, etc.)
- Nonce verification required for AJAX requests using `wp_verify_nonce()`
- Use `get_template_directory()` and `get_template_directory_uri()` for file paths
- Dynamic versioning for static assets using `filemtime()` for cache busting
- Always call `wp_reset_postdata()` after custom post queries using `setup_postdata()`
- Sanitize all `$_POST` data with appropriate WordPress functions (`sanitize_text_field()`, etc.)
- Use wp_cache_* functions with appropriate cache groups for expensive queries (1 hour for random posts, 24 hours for Google Fonts, indefinite for footer content)
- Use modular PHP file organization in `/php` directory with descriptive naming
- JavaScript should use WordPress i18n (`wp.i18n`) for user-facing messages

## Performance Notes

**Object Caching Architecture:**
- **wp_cache_* functions** replace transients throughout theme for better performance
- **Cache Groups**: `sarai_chinwag_footer`, `sarai_chinwag_random`, `sarai_chinwag_related`, `sarai_chinwag_fonts`, `sarai_chinwag_sidebar`
- **Dynamic cache versioning** using `wp_cache_get_last_changed()` for content-dependent caches
- **Indefinite caching** for footer content (cleared only when content changes via hooks)
- **Limited query results** (max 500 posts) to prevent memory issues in large sites

**Specific Optimizations:**
- Category/tag clouds use indefinite caching with automatic invalidation
- Random posts cached for 1 hour with rotation system to reduce `orderby => 'rand'` queries  
- Related content cached for 15 minutes per post
- Google Fonts API responses cached for 24 hours
- Sidebar widgets cached for 15 minutes
- CSS uses custom properties for dynamic styling instead of inline styles
- All queries use proper WordPress functions and avoid direct database access

## Universal Theme Usage

### Recipe Toggle Feature
The theme includes a "Disable Recipe Functionality" setting in **Settings → Theme Settings** that allows complete control over recipe features:

**When Recipes Enabled (Default):**
- Recipe post type registered and available
- Recipe-specific templates and widgets active
- Rating system functional
- Schema.org recipe markup applied
- Recipe filtering in archives
- Random recipe functionality

**When Recipes Disabled:**
- All recipe functionality completely hidden
- Theme operates as standard blog theme
- Existing recipe posts remain accessible via direct URL only
- Clean, blog-focused user experience
- No recipe-specific scripts or styles loaded

### Helper Function
Use `sarai_chinwag_recipes_disabled()` to check recipe status in custom code:
```php
if (!sarai_chinwag_recipes_disabled()) {
    // Recipe-specific code here
}
```

## Internationalization & Browser Compatibility

**Language Support:**
- **Content-Language HTTP header**: Automatically set to 'en' to help browsers determine when to offer translation services
- **Language attributes**: Dynamic language attributes in template parts using `get_locale()` for proper content marking
- **Translation ready**: Full i18n support with `load_theme_textdomain()` and translatable strings throughout
- **Browser translation detection**: Helps browsers like Chrome and Edge determine when to show translation options

**Implementation Details:**
- Header function `sarai_chinwag_set_content_language_header()` sets HTTP headers before content output
- Template parts include `lang` attributes on content containers
- All user-facing strings use WordPress translation functions with proper text domains

## Security Notes

- All user input is properly sanitized using WordPress functions
- All output is properly escaped with `esc_html()`, `esc_url()`, `esc_attr()`, etc.
- API keys stored in database options via `get_option()`, never hardcoded
- Rating system uses WordPress nonce verification for CSRF protection
- AJAX endpoints include proper nonce verification and validation
- Client-side localStorage provides graceful degradation if server requests fail

## Theme Information

**Current Version**: 2.1 (Advanced Filter & Layout System + Performance Optimization)  
**Live Demo**: [saraichinwag.com](https://saraichinwag.com)  
**Developer**: Chris Huber ([chubes.net](https://chubes.net))

### Recent Major Updates (v2.1)
- **Object Caching System**: Replaced transients with wp_cache_* functions for better performance
- **Image Optimization**: Custom 450x450 grid-thumb size, removed unused WordPress image sizes
- **Fluid Typography**: Implemented clamp() functions for responsive font scaling across all devices
- **Internationalization**: Content-Language HTTP headers and dynamic language attributes
- **Performance Caching**: Indefinite caching for footer, 1-hour rotation for random posts, cache groups
- **Advanced filter bar** with multiple sort options (Random, Popular, Recent, Oldest)
- **Full-width 4-column responsive grid** layout for maximum content visibility
- **Simple view counter system** for popularity tracking with `_post_views` meta field
- **Badge-breadcrumb navigation** system for single posts, traditional breadcrumbs for archives