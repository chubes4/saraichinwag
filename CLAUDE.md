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
- **Template hierarchy**: Standard WordPress templates (single.php, archive.php, home.php, search.php, etc.)

### Custom Post Types
- **Recipe post type**: Registered via `php/recipes.php` with full labels and Schema.org markup support
- **Main query modification**: Includes both 'post' and 'recipe' post types in home, category, tag, and search queries

### PHP Module System
The theme uses an autoload system in functions.php that includes all PHP files from the `/php` directory:
- `admin-settings.php`: Theme settings page with API key management
- `bing-index-now.php`: Bing indexing integration
- `customizer.php`: Dynamic Google Fonts system with API integration and percentage-based scaling
- `image-counts.php`: Image handling utilities
- `random-post.php` & `random-queries.php`: Random content functionality (anti-chronological design)
- `ratings.php`: AJAX-based recipe rating system with nonce security
- `recipes.php`: Custom post type registration
- `related-posts.php`: Related content functionality
- `schema-recipe.php`: Schema.org structured data for recipes
- `sorting-archives.php`: Archive sorting functionality
- `yoast-stuff.php`: Yoast SEO customizations

### JavaScript Structure
- **js/nav.js**: Header search functionality with position adjustment for admin bar
- **js/customizer.js**: Live preview functionality for font changes and size scaling in WordPress Customizer
- **js/filters.js**: Filtering functionality
- **js/rating.js**: Client-side rating interactions

### Template Parts
- `template-parts/content-recipe.php`: Recipe display with Schema.org microdata
- `template-parts/content.php` & `template-parts/content-single.php`: Standard post templates

### Key Features
- **Randomized content discovery**: Anti-chronological design with home page and archives displaying posts in random order
- **Random access pages**: `/random-post` and `/random-recipe` pages for serendipitous browsing
- **Dynamic Google Fonts system**: API integration with category filtering (display fonts for headings, sans-serif + serif for body)
- **Percentage-based font scaling**: 1-100% size control with 50% = current theme baseline, maintains heading hierarchy
- **Universal theme design**: Can function as recipe site or standard blog via admin toggle
- **Recipe functionality**: Complete recipe post type with ratings, schema markup, and specialized templates (when enabled)
- **WordPress Customizer integration**: Live preview for font changes and size scaling with custom CSS properties
- **Pinterest integration**: Automatic Pinterest save buttons with `data-pin-url` attributes on featured images
- **Dynamic asset versioning**: Uses `filemtime()` for cache busting on CSS and JS files
- **Rating system**: AJAX-powered recipe ratings with security nonces and validation (1-5 range)
- **Schema.org markup**: Full structured data implementation for recipes
- **Performance optimizations**: Transient caching for Google Fonts API calls, category/tag clouds and random posts
- **Security enhancements**: All output properly escaped, input sanitized, secure API key storage

## Dynamic Google Fonts Architecture

### Font System Components
- **API Integration**: `php/customizer.php` handles Google Fonts API calls with `sarai_chinwag_fetch_google_fonts_by_category()`
- **Customizer Controls**: Two font dropdowns (Header/Body) + two size sliders (1-100%) accessible via **Appearance → Customize → Typography**
- **CSS Scaling System**: Uses CSS custom properties (`--font-heading-scale`, `--font-body-scale`) for proportional scaling
- **Live Preview**: `js/customizer.js` provides real-time font and size changes in WordPress Customizer
- **Caching Strategy**: 24-hour transients for API responses using keys like `sarai_chinwag_google_fonts_display`

### Font Organization
- **Header Fonts**: Display category fonts only (`category=display` API parameter)
- **Body Fonts**: Combined sans-serif + serif fonts (`category=sans-serif` + `category=serif`)
- **Fallback Strategy**: Google Font → Gluten (theme font) → System fonts

### Scaling System
- **50% = Baseline**: Current theme sizes (20px body, 1.75em h1, 1.38em h2)  
- **CSS Implementation**: `calc()` functions with custom properties maintain responsive breakpoints
- **Hierarchy Preservation**: All heading levels (h1-h6) scale proportionally

## Randomization System Architecture

### Anti-Chronological Design
- **Main Query Modification**: `php/random-queries.php` modifies home and archive queries to use `orderby: rand`
- **Post Type Integration**: Includes both 'post' and 'recipe' post types in randomized queries
- **Performance Consideration**: Random queries can be expensive; uses proper caching strategies

### Random Access Pages  
- **Direct Random Posts**: `/random-post` page redirects to random post via `extra_chill_redirect_to_random_post()`
- **Direct Random Recipes**: `/random-recipe` page redirects to random recipe (respects recipe toggle)
- **Fallback Logic**: When recipes disabled, random-recipe redirects to random-post instead
- **Implementation**: Uses `WP_Query` with `orderby: rand` and `posts_per_page: 1`

## Development Commands

No build commands are available. Direct file editing workflow:
1. Edit PHP, CSS, or JS files directly
2. Changes are immediately reflected (CSS/JS have dynamic versioning)
3. For PHP changes, reload the page to see updates

## Code Conventions

- Follow WordPress coding standards
- Use `wp_remote_get()` instead of cURL functions
- All output should be run through escaping functions (`esc_url()`, `esc_html()`, etc.)
- Nonce verification required for AJAX requests
- Use `get_template_directory()` and `get_template_directory_uri()` for file paths
- Dynamic versioning for static assets using `filemtime()`
- Always call `wp_reset_postdata()` after custom post queries using `setup_postdata()`
- Sanitize all `$_POST` data with appropriate functions
- Use transient caching for expensive queries (set to 1 hour or 15 minutes)

## Performance Notes

- Category and tag clouds use transient caching to avoid N+1 queries
- Random posts/recipes are cached for 15 minutes to reduce database load
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

## Security Notes

- All user input is properly sanitized
- All output is properly escaped
- API keys stored in database options, not hardcoded
- Rating validation prevents invalid data submission
- AJAX endpoints include proper nonce verification