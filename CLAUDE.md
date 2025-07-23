# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Environment

This is a WordPress theme for "Sarai Chinwag" with no build process. All files are directly edited and changes are immediately reflected. No package.json, composer.json, or build tools are configured.

### Admin Settings
The theme includes an admin settings page accessible via **Settings → Sarai Chinwag** in the WordPress admin. This page allows configuration of:
- IndexNow API Key for automatic search engine indexing

## Theme Architecture

### Core Structure
- **functions.php**: Main theme setup, enqueues styles/scripts with dynamic versioning using `filemtime()`, autoloads PHP modules from `/php` directory
- **style.css**: Primary stylesheet with custom font declarations and theme styles
- **Template hierarchy**: Standard WordPress templates (single.php, archive.php, home.php, search.php, etc.)

### Custom Post Types
- **Recipe post type**: Registered via `php/recipes.php` with full labels and Schema.org markup support
- **Main query modification**: Includes both 'post' and 'recipe' post types in home, category, tag, and search queries

### PHP Module System
The theme uses an autoload system in functions.php:712 that includes all PHP files from the `/php` directory:
- `bing-index-now.php`: Bing indexing integration
- `image-counts.php`: Image handling utilities
- `random-post.php` & `random-queries.php`: Random content functionality
- `ratings.php`: AJAX-based recipe rating system with nonce security
- `recipes.php`: Custom post type registration
- `related-posts.php`: Related content functionality
- `schema-recipe.php`: Schema.org structured data for recipes
- `sorting-archives.php`: Archive sorting functionality
- `yoast-stuff.php`: Yoast SEO customizations

### JavaScript Structure
- **js/nav.js**: Header search functionality with position adjustment for admin bar
- **js/filters.js**: Filtering functionality (not examined in detail)
- **js/rating.js**: Client-side rating interactions (not examined in detail)

### Template Parts
- `template-parts/content-recipe.php`: Recipe display with Schema.org microdata
- `template-parts/content.php` & `template-parts/content-single.php`: Standard post templates

### Key Features
- **Pinterest integration**: Automatic Pinterest save buttons with `data-pin-url` attributes on featured images
- **Dynamic asset versioning**: Uses `filemtime()` for cache busting on CSS and JS files
- **Rating system**: AJAX-powered recipe ratings with security nonces and validation (1-5 range)
- **Schema.org markup**: Full structured data implementation for recipes
- **Custom sidebar**: Widget-ready sidebar registration
- **Performance optimizations**: Transient caching for category/tag clouds and random posts
- **Security enhancements**: All output properly escaped, input sanitized, secure API key storage

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

## Security Notes

- All user input is properly sanitized
- All output is properly escaped
- API keys stored in database options, not hardcoded
- Rating validation prevents invalid data submission
- AJAX endpoints include proper nonce verification