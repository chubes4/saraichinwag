# Sarai Chinwag WordPress Theme Documentation

This documentation provides comprehensive guidance for using the Sarai Chinwag WordPress theme, a versatile theme with advanced features for content discovery and visual browsing.

## Theme Overview

Sarai Chinwag is designed for both recipe sites and standard blogs, featuring:

- **Dynamic Google Fonts Integration** - Customizable typography with API-powered font selection
- **Advanced Image Gallery System** - Specialized image archives and discovery tools
- **Randomized Content Discovery** - Anti-chronological design with serendipitous browsing
- **Default 5-Star Rating System** - New recipes automatically receive 5.0 rating with 1 review count for immediate visibility
- **Recipe Functionality** - Complete recipe management with user ratings and embedded Schema.org markup
- **Performance Optimizations** - Cached content delivery and efficient queries
- **Universal Theme Design** - Toggle between recipe site and standard blog modes

## Documentation Structure

This documentation is organized by functional components:

### [Administration](administration/)
- **Theme Settings** - IndexNow API, Google Fonts API, Pinterest integration, and recipe toggle
- **WordPress Customizer** - Typography controls, color schemes, and live preview

### [Content Types](content-types/)  
- **Recipe Post Type** - Custom post type with default 5-star ratings and user rating system
- **Standard Posts** - Enhanced post display and features

### [Image Gallery System](image-gallery/)
- **Image Extraction** - Automated image discovery from posts and categories
- **Gallery Archives** - Category and tag-based image galleries
- **Gallery Navigation** - Lightbox functionality and responsive layouts
- **Archive Image Mode Links** - Intelligent "Try Image Mode" switching with accurate image counts

### [Navigation & Discovery](navigation/)
- **Filter System** - Advanced filtering with sort options and post type filtering
- **Random Access** - Direct random post and recipe discovery
- **Breadcrumb Navigation** - Context-aware navigation for different page types

### [Typography & Customization](typography/)
- **Google Fonts Integration** - API-powered font selection and loading
- **Font Scaling System** - Responsive typography with percentage-based scaling
- **Editor Integration** - Consistent fonts between editors and frontend

### [Contact Form System](https://github.com/chubes4/sarai-chinwag/tree/main/inc/contact)
- **Cloudflare Turnstile Integration** - Bot protection for contact forms
- **AJAX Form Processing** - Real-time form submission and validation
- **Email Notifications** - Automated admin and submitter email handling
- **Shortcode Integration** - Easy form embedding with `[sarai_contact_form]`

### [Performance & Caching](performance/)
- **Object Caching** - wp_cache_* implementation across all systems
- **Asset Optimization** - Dynamic versioning and efficient loading
- **Query Optimization** - Cached random queries and limited result sets

## Getting Started

1. **Theme Installation** - Install and activate the theme in WordPress
2. **Configure Settings** - Visit Settings → Theme Settings for API keys and functionality toggles
3. **Customize Typography** - Use Appearance → Customize → Typography for font selection
4. **Enable Features** - Configure recipe functionality, image galleries, and discovery tools

## Key Features at a Glance

**For Content Discovery:**
- Random post access at `/random-post`, `/random-recipe`, `/random-all`
- Image galleries for categories and tags at `/category/name/images/`
- Site-wide image gallery at `/images/`
- Advanced filtering with real-time AJAX updates

**For Content Management:**
- Recipe post type with automatic default 5-star ratings and user rating system
- Interactive rating interface with AJAX submissions and localStorage persistence
- Recipe ratings integrated with popularity sorting for enhanced discovery
- Automatic image extraction from posts
- Performance-optimized caching throughout
- Universal theme toggle for recipe/blog modes

**For Customization:**
- Dynamic Google Fonts with live preview
- Responsive typography scaling
- Color scheme customization
- Pinterest integration

Each section provides detailed documentation of functionality, configuration options, and usage examples based on the actual theme implementation.