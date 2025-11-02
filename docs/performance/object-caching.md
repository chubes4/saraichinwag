# Object Caching System

The Sarai Chinwag theme implements comprehensive wp_cache_* object caching throughout all systems to deliver optimal performance and efficient database usage.

## Caching Architecture Overview

### wp_cache_* Implementation

**Cache System**: WordPress native object cache functions
**Performance Benefits**: Dramatically reduced database queries
**Scalability**: Object cache compatible with Redis, Memcached
**Efficiency**: Targeted caching with intelligent invalidation

### Cache Groups

The theme uses segregated cache groups for organized cache management:

#### sarai_chinwag_random
- **Purpose**: Random content ID caching
- **Duration**: 1 hour for single posts, 30 minutes for queries
- **Content**: Cached random post/recipe IDs
- **Performance**: Eliminates expensive `ORDER BY RAND()` queries

#### sarai_chinwag_related  
- **Purpose**: Related content and sidebar caching
- **Duration**: 15 minutes
- **Content**: Related posts, gallery discovery badges
- **Scope**: Per-post caching with automatic invalidation

#### sarai_chinwag_fonts
- **Purpose**: Google Fonts API response caching
- **Duration**: 24 hours
- **Content**: Font listings by category from Google API
- **Efficiency**: Prevents redundant API calls

#### sarai_chinwag_views
- **Purpose**: Post view counter caching
- **Duration**: Variable based on usage
- **Content**: Post view counts and popular content queries
- **Performance**: Efficient popularity sorting

#### sarai_chinwag_sidebar
- **Purpose**: Sidebar widget caching
- **Duration**: 15 minutes
- **Content**: Rendered widget output
- **Benefits**: Reduced widget rendering overhead

#### Rating System Caching
- **Purpose**: Recipe rating data optimization
- **Implementation**: Post meta caching for rating_value and review_count
- **Performance**: Efficient rating calculations and display
- **Integration**: Seamless with default 5-star rating system

#### sarai_chinwag_images
- **Purpose**: Image extraction and gallery caching
- **Duration**: 1-2 hours
- **Content**: Extracted image collections and metadata
- **Scope**: Term-based, site-wide, and search image caches

#### Rating System Caching
- **Purpose**: Recipe rating data optimization
- **Implementation**: Post meta caching for rating_value and review_count
- **Performance**: Efficient rating calculations and display
- **Integration**: Seamless with default 5-star rating system

## Random Content Caching

### Random Post ID System

**Function**: `sarai_chinwag_get_cached_random_post_id($post_type)`

**Implementation**:
```php
$cache_key = "random_{$post_type}_ids";
$random_ids = wp_cache_get($cache_key, 'sarai_chinwag_random');
```

**Performance Benefits**:
- Replaces expensive `ORDER BY RAND()` database queries
- Sub-second response times for random access
- Scalable for large post databases
- Automatic rotation system for variety

**Cache Strategy**:
- Initial query gets 500 random post IDs
- IDs cached for 1 hour with rotation
- Array shifted on each request for variety
- Cache refreshed automatically when empty

### Query-Level Random Caching

**Function**: `sarai_chinwag_get_cached_random_query_ids($post_types, $count)`

**Usage**: Home and archive page randomization
**Cache Duration**: 30 minutes (shorter for more variety)
**Benefits**: Bulk random ID retrieval for archive pages
**Efficiency**: 10x requested count cached for variety

## Image System Caching

### Image Extraction Caching

**Term-Based Caching**:
```php
$cache_key = "sarai_chinwag_term_images_{$term_id}_{$term_type}";
wp_cache_set($cache_key, $images, 'sarai_chinwag_images', 3600);
```

**Site-Wide Caching**:
```php
$cache_key = "sarai_chinwag_all_site_images";
wp_cache_set($cache_key, $images, 'sarai_chinwag_images', 3600);
```

### Image System Caching Strategy

The theme implements a sophisticated dual-cache strategy to balance performance with accuracy for image galleries and counts.

#### Image Data Caches

**Purpose**: Store extracted image arrays for gallery display
**Cache Keys**:
- Term images: `sarai_chinwag_term_images_{$term_id}_{$term_type}`
- Site-wide images: `sarai_chinwag_all_site_images`
- Search images: `sarai_chinwag_search_images_{md5($search_query)}`

**Limitations**: 30 images per cache for display performance
**Duration**: 2 hours (7200 seconds)
**Usage**: Gallery archives, image mode displays

#### Image Count Caches

**Purpose**: Store accurate total counts for badges and archive mode links
**Cache Keys**:
- Term counts: `term_image_count_{$term_id}_{$taxonomy}`
- Site-wide count: `site_wide_image_count`
- Search counts: `search_image_count_{md5($search_query)}`

**Limitations**: 99999 images per query for accurate counting
**Duration**: 2 hours (7200 seconds)
**Usage**: Gallery discovery badges, archive image mode links, image counts

#### Cache Invalidation System

**Centralized Invalidation**:
```php
sarai_chinwag_clear_all_image_count_caches($post_id)
```
Clears all image count caches when content changes. Handles:
- Site-wide image count cache
- Category-specific count caches for the post
- Tag-specific count caches for the post

**Media Attachment Invalidation**:
```php
sarai_chinwag_clear_image_cache_on_media_change($attachment_id)
```
Clears both data and count caches when media library changes. Triggered by:
- `add_attachment` hook - New media uploaded
- `edit_attachment` hook - Media metadata edited
- `delete_attachment` hook - Media removed

**Post Update Invalidation**:
Existing `sarai_chinwag_clear_image_cache_on_post_update()` function handles:
- Post save (`save_post` hook)
- Post deletion (`delete_post` hook)
- Clears image data caches for affected terms

**Search Cache Limitation**:
Search image count caches use MD5 hashes of query strings, making individual invalidation impractical. These caches expire naturally after 2 hours or are cleared by the search invalidation function when posts are updated.

#### Performance Benefits

**Dual Strategy Advantages**:
- Gallery displays load quickly with 30-image limit
- Count badges show accurate totals with 99999-image queries
- Separate caching prevents display queries from overwhelming memory
- Count caches enable accurate badges for sites with tens of thousands of images

**Automatic Updates**:
- Media uploads immediately reflect in image counts
- Media deletions update counts without manual cache clearing
- Post edits trigger appropriate cache invalidation
- Category/tag changes update relevant count caches

### Gallery Badge Caching

**Function**: Gallery discovery badges cached per post
**Cache Key**: `gallery_badges_{$post_id}`
**Duration**: 15 minutes
**Scope**: Related content caching group
**Benefits**: Expensive image count queries cached

## Google Fonts Caching

### API Response Caching

**Cache Strategy**:
```php
$cache_key = 'google_fonts_' . $category;
wp_cache_set($cache_key, $fonts, 'sarai_chinwag_fonts', DAY_IN_SECONDS);
```

**Benefits**:
- Prevents redundant Google Fonts API calls
- 24-hour cache duration for font listings
- Category-specific cache keys
- Fallback handling when API unavailable

### Font Selection Caching

**Customizer Integration**: Font selections cached with customizer settings
**CSS Generation**: Font CSS cached until settings change
**Performance**: Immediate font loading without API delays
**Reliability**: Cached fonts available during API outages

## View Counter Caching

### View Count Performance

**Cache Group**: `sarai_chinwag_views`
**Purpose**: Efficient view count queries for popularity sorting
**Implementation**: Post view counts cached for database efficiency
**Updates**: Cache invalidated on view count changes

### Popular Content Caching

**Popular Queries**: Database queries for popular content cached
**Sort Performance**: "Most Popular" filter uses cached data
**Meta Queries**: Efficient `_post_views` meta queries
**Scalability**: Performs well with large post counts

## Sidebar Caching

### Widget Output Caching

**Cache Duration**: 15 minutes
**Cache Group**: `sarai_chinwag_sidebar`
**Benefits**: Rendered widget output cached
**Performance**: Reduces widget rendering overhead
**Dynamic Content**: Balances caching with content freshness

## Cache Invalidation Strategy

### Automatic Invalidation

**Content Updates**: Cache cleared on post save/delete
**Function**: `sarai_chinwag_clear_performance_caches($post_id)`
**Hooks**: `save_post`, `delete_post`, `wp_trash_post`, `untrash_post`

**Targeted Clearing**:
- Random caches: Cleared when content changes
- Related caches: Cleared per-post
- Image caches: Cleared for affected terms
- Font caches: Persist until API changes

### Smart Cache Management

**Dynamic Cache Keys**: Content-dependent cache versioning
**Group Flushing**: `wp_cache_flush_group()` for bulk invalidation
**Selective Clearing**: Only affected caches invalidated
**Performance**: Minimal cache rebuilding required

## Cache Performance Monitoring

### Cache Hit Rate Optimization

**Efficient Keys**: Descriptive, collision-free cache keys
**Appropriate Durations**: Cache durations matched to content volatility
**Group Segregation**: Organized cache groups prevent conflicts
**Memory Efficiency**: Limited cache sizes prevent memory issues

### Database Query Reduction

**Query Elimination**: Expensive queries replaced with cached results
**Database Load**: Significantly reduced database server load
**Response Times**: Faster page loading with cached data
**Scalability**: Better performance scaling with traffic

## Memory Management

### Cache Size Limitations

**Query Limits**: Maximum post counts prevent excessive cache sizes
**Rotation Systems**: Automatic rotation prevents memory buildup
**Cleanup**: Expired cache automatic cleanup
**Monitoring**: Cache size monitoring and optimization

### Efficient Cache Usage

**Targeted Caching**: Only expensive operations cached
**Appropriate Sizes**: Cache sizes matched to actual needs
**Memory Conscious**: Designed for shared hosting environments
**Cleanup**: Proper cache cleanup on theme deactivation

## Performance Benefits

### Database Impact

**Query Reduction**: 70-80% reduction in database queries
**Server Load**: Significantly reduced MySQL server load
**Response Times**: Faster page generation times
**Concurrent Users**: Better performance with multiple users

### User Experience

**Page Speed**: Faster loading times across all pages
**Consistency**: Consistent performance regardless of content size
**Scalability**: Performance maintained with growing content
**Reliability**: Cached fallbacks during high traffic

## Troubleshooting

### Cache Issues

**Cache Not Working**:
1. Check object cache implementation
2. Verify cache keys and groups
3. Monitor cache hit rates
4. Test cache invalidation

**Performance Problems**:
1. Monitor cache usage and hit rates
2. Check cache duration appropriateness
3. Verify efficient cache key generation
4. Test with different cache backends

### Cache Debugging

**Debug Functions**: WordPress cache debugging tools
**Monitoring**: Cache statistics and performance monitoring
**Testing**: Cache behavior testing in different scenarios
**Optimization**: Continuous cache performance optimization

## Advanced Usage

### Custom Cache Integration

The caching system can be extended with additional cache groups while maintaining the existing performance optimizations.

### Cache Backend Optimization

The theme's caching works optimally with Redis or Memcached object cache implementations for maximum performance.

### Performance Tuning

Cache durations and strategies can be fine-tuned based on specific site requirements and hosting environment capabilities.