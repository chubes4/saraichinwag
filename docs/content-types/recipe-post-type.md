# Recipe Post Type

The Sarai Chinwag theme includes a complete recipe management system with a custom post type, ratings functionality, and Schema.org structured data markup.

## Recipe Post Type Features

### Post Type Configuration

**Custom Post Type**: `recipe`
**URL Slug**: `/recipe/[recipe-name]`
**Archive URL**: `/recipe/` (when enabled)
**Admin Menu**: Located in WordPress admin sidebar

### Supported Features

**Content Features**:
- Title and content editor (Block Editor and Classic Editor)
- Featured image (labeled as "Recipe Cover Image")
- Excerpt for recipe summaries
- Author attribution
- Comments and discussion

**Taxonomy Support**:
- Categories (shared with regular posts)
- Tags (shared with regular posts)
- Hierarchical organization capability

**WordPress Integration**:
- REST API support for headless applications
- Search integration (recipes appear in site search)
- RSS feed inclusion (recipes included in site feeds)

## Recipe Management

### Creating Recipes

**Admin Access**: WordPress Admin → Recipes → Add New

**Required Fields**:
- Recipe title
- Recipe content (instructions, ingredients, etc.)
- Featured image recommended for visual appeal

**Optional Fields**:
- Recipe excerpt (summary)
- Categories and tags for organization
- Comments settings

### Recipe Display Templates

**Single Recipe Template**: `single-recipe.php`
- Specialized layout for recipe display
- Recipe-specific styling and formatting
- Rating widget integration
- Embedded Schema.org markup inclusion

**Recipe Archive Template**: Uses standard archive templates
- Grid layout for recipe browsing
- Featured image prominence
- Category and tag filtering
- Recipe-specific styling

## Rating System

### User Ratings

**Rating Widget**: 5-star rating system on single recipe pages
**User Experience**:
- Click stars to rate (1-5 stars)
- One rating per recipe per user
- Ratings saved to localStorage for immediate feedback
- Server-side rating storage for persistence

### Rating Functionality

**AJAX Implementation**:
- Real-time rating submission without page reload
- Nonce security verification
- Error handling with user feedback
- Loading states during submission

**Rating Storage**:
- Individual user ratings stored in database
- Average rating calculation
- Review count tracking
- Cache optimization for performance

### Rating Display

**Average Rating**: Displayed as "(4.2/5 based on 15 reviews)"
**User Rating**: Shows "You rated this 5 stars" after rating
**Star Visual**: Visual star representation of ratings
**Rating Widget**: `/js/rating.js` handles all interactions

## Schema.org Structured Data

### Recipe Schema Implementation

**Automatic Markup**: All recipes include structured data
**Schema Type**: Recipe schema with full property support
**SEO Benefits**: Enhanced search engine display with rich snippets

**Schema Properties**:
- Recipe name and description
- Author information
- Rating and review data
- Published date
- Featured image
- Categories and keywords

### Implementation Details

**Implementation**: Embedded directly in recipe templates
**Output**: Schema.org microdata attributes in HTML markup
**Location**: `single-recipe.php` and `template-parts/content-recipe.php`
**Validation**: Schema.org compliant markup
**Testing**: Use Google's Rich Results Test tool

## Recipe Toggle System

### Universal Theme Functionality

**Admin Control**: Settings → Theme Settings → Disable Recipe Functionality
**Purpose**: Convert theme from recipe-focused to universal blog theme

### When Recipes Enabled (Default)

**Full Functionality**:
- Recipe post type available in admin
- Recipe templates and styling active
- Rating system functional
- Recipe filtering in archives
- Recipe-specific widgets and features
- Embedded Schema.org recipe markup

### When Recipes Disabled

**Disabled Features**:
- Recipe post type hidden from admin
- Recipe creation disabled
- Rating system inactive
- Recipe-specific templates unused
- Recipe filtering removed from archives

**Preserved Access**:
- Existing recipes remain accessible via direct URL
- Recipe content preserved in database
- Re-enabling restores full functionality

### Helper Function

```php
sarai_chinwag_recipes_disabled()
```
**Returns**: `true` if recipes disabled, `false` if enabled
**Usage**: Conditional feature display in custom code

## Recipe Integration

### Archive Integration

**Home Page**: Recipes included in randomized home page display
**Category Archives**: Recipes appear alongside posts in category pages
**Tag Archives**: Recipes included in tag-based browsing
**Search Results**: Recipes appear in site search results

### Filter System Integration

**Post Type Filtering**: Filter archives by "All", "Posts", or "Recipes"
**Sort Options**: Recipes respect all sorting options (Random, Popular, Recent, Oldest)
**AJAX Filtering**: Real-time filtering includes recipe content

### Random Access

**Random Recipe URL**: `/random-recipe` redirects to random recipe
**Random All URL**: `/random-all` includes both posts and recipes
**Fallback Behavior**: When recipes disabled, redirects to random posts

## Performance Optimization

### Caching Strategy

**Recipe Data**: wp_cache_* implementation for recipe queries
**Rating Cache**: Cached rating calculations and counts
**Template Cache**: Recipe template output cached for performance
**Template Cache**: Recipe template fragments cached

### Database Optimization

**Efficient Queries**: Optimized database queries for recipe retrieval
**Index Optimization**: Proper database indexing for recipe metadata
**Limited Results**: Query limits prevent memory issues on large sites

## RSS Feed Integration

### Feed Inclusion

**Main RSS Feed**: Recipes automatically included
**Category Feeds**: Recipes appear in category-specific feeds
**Tag Feeds**: Recipes included in tag-based feeds
**Feed Validation**: Proper RSS markup for recipe content

### Feed Implementation

**Function**: `sarai_chinwag_add_recipe_to_rss_feed()`
**Hook**: `pre_get_posts` for feed queries
**Behavior**: Adds 'recipe' to post_type array in feed queries

## Troubleshooting

### Recipe Display Issues

**Template Problems**:
1. Verify `single-recipe.php` exists in theme
2. Check for template hierarchy conflicts
3. Clear any caching plugins

**Rating System Issues**:
1. Check JavaScript console for errors
2. Verify AJAX endpoints responding
3. Confirm nonce generation working

### Recipe Toggle Problems

**Recipes Not Hiding**:
1. Verify setting saved correctly
2. Check `sarai_chinwag_recipes_disabled()` function
3. Clear object cache if used

**Recipes Not Appearing**:
1. Confirm recipe toggle is disabled
2. Check recipe post type registration
3. Verify user permissions for recipe access

## Advanced Customization

### Custom Recipe Fields

The recipe system can be extended with additional custom fields using WordPress meta boxes or custom field plugins.

### Recipe Template Customization

Recipe templates can be customized by editing `single-recipe.php` and related template files while maintaining Schema.org markup integrity.