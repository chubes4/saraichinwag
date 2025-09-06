# Query Optimization

The Sarai Chinwag theme implements comprehensive database query optimization strategies to ensure efficient performance even on large sites with extensive content.

## Query Optimization Strategy

### Core Principles

**Limited Result Sets**: All queries use configurable limits to prevent memory exhaustion
**Efficient Indexing**: Queries designed to use WordPress database indexes
**Caching Integration**: Query results cached using wp_cache_* functions
**Smart Pagination**: Efficient pagination that maintains performance

### Performance Limits

**Post Queries**: Maximum 500 posts per query
**Image Queries**: Limited to 30-50 images per request
**Random Queries**: Cached random ID arrays replace expensive ORDER BY RAND()
**Memory Protection**: Query limits prevent PHP memory exhaustion

## Random Query Optimization

### ORDER BY RAND() Elimination

**Problem**: `ORDER BY RAND()` queries are expensive on large databases
**Solution**: Cached random ID system with rotation

**Traditional Approach** (Expensive):
```sql
SELECT * FROM wp_posts WHERE post_status='publish' ORDER BY RAND() LIMIT 10;
```

**Optimized Approach** (Fast):
```php
// Get cached random IDs
$random_ids = wp_cache_get('random_post_ids', 'sarai_chinwag_random');
// Use specific post IDs
$posts = get_posts(array('post__in' => array_slice($random_ids, 0, 10)));
```

### Random ID Caching System

**Cache Strategy**: Pre-generate random ID arrays and cache for reuse
**Rotation System**: IDs rotated on each request for variety
**Cache Duration**: 1 hour for single posts, 30 minutes for bulk queries
**Performance**: Sub-second response times for random content

**Implementation**:
```php
function sarai_chinwag_get_cached_random_post_id($post_type = 'post') {
    $cache_key = "random_{$post_type}_ids";
    $random_ids = wp_cache_get($cache_key, 'sarai_chinwag_random');
    
    if (false === $random_ids || empty($random_ids)) {
        // Get 500 random IDs once, cache for reuse
        $all_posts = get_posts(array(
            'post_type' => $post_type,
            'fields' => 'ids',
            'numberposts' => 500,
            'orderby' => 'rand'
        ));
        wp_cache_set($cache_key, $all_posts, 'sarai_chinwag_random', HOUR_IN_SECONDS);
        $random_ids = $all_posts;
    }
    
    // Return first ID and rotate array
    $post_id = array_shift($random_ids);
    wp_cache_set($cache_key, $random_ids, 'sarai_chinwag_random', HOUR_IN_SECONDS);
    return $post_id;
}
```

## Image Query Optimization

### Attachment Query Efficiency

**Direct Media Queries**: Query attachment post type directly for images
**Parent Post Filtering**: Efficient filtering by parent post status
**Index Usage**: Queries designed to use WordPress database indexes

**Optimized Image Query**:
```php
$args = array(
    'post_type' => 'attachment',
    'post_status' => 'inherit',
    'post_mime_type' => 'image',
    'posts_per_page' => 60, // 2x needed for filtering
    'post__not_in' => $loaded_images,
    'meta_query' => array(
        array(
            'key' => '_wp_attached_file',
            'compare' => 'EXISTS'
        )
    )
);
```

### Image Extraction Performance

**Chunked Processing**: Large image collections processed in chunks
**Early Termination**: Processing stops when sufficient images found
**Duplicate Prevention**: Efficient duplicate detection with arrays
**Memory Management**: Processing limits prevent memory issues

## Meta Query Optimization

### Popular Content Queries

**View Counter Optimization**: Efficient queries for post view counts
**Index Usage**: `_post_views` meta key optimized for sorting
**Existence Checks**: Meta queries with EXISTS comparisons

**Popular Posts Query**:
```php
$args = array(
    'meta_key' => '_post_views',
    'orderby' => 'meta_value_num date',
    'order' => 'DESC',
    'meta_query' => array(
        array(
            'key' => '_post_views',
            'compare' => 'EXISTS'
        )
    )
);
```

### Rating System Queries

**Efficient Rating Queries**: Optimized queries for recipe ratings
**Aggregate Calculations**: Efficient average rating calculations
**Index Optimization**: Rating queries use proper database indexes

## Taxonomy Query Optimization

### Term-Based Queries

**Taxonomy Performance**: Efficient category and tag queries
**Term Relationship**: Optimized term relationship queries
**Hierarchical Support**: Efficient parent/child term queries

**Category Image Query**:
```php
$posts = get_posts(array(
    'post_type' => array('post', 'recipe'),
    'numberposts' => 500,
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $term_id,
        )
    )
));
```

### Search Query Optimization

**Search Performance**: Optimized search queries with proper indexes
**Content Matching**: Efficient full-text search implementation
**Result Limiting**: Search results limited for performance

## AJAX Query Optimization

### Real-Time Filtering

**Efficient AJAX Queries**: Optimized database queries for filter requests
**State Management**: Efficient filter state processing
**Pagination**: Optimized AJAX pagination queries

**Filter Query Optimization**:
```php
// Avoid loading full post objects for counting
$count_query = new WP_Query(array(
    'fields' => 'ids',
    'posts_per_page' => -1,
    'no_found_rows' => true,
    // ... filter parameters
));
```

### Load More Optimization

**Incremental Loading**: Efficient incremental content loading
**Duplicate Prevention**: Loaded content tracking prevents duplicates
**Memory Efficiency**: Load more queries optimized for memory usage

## Database Index Utilization

### WordPress Index Usage

**Core Indexes**: Queries designed to use WordPress core indexes
**Custom Indexes**: Recommendations for additional indexes when needed
**Composite Indexes**: Multi-column index usage for complex queries

### Query Analysis

**EXPLAIN Queries**: All complex queries analyzed with EXPLAIN
**Index Coverage**: Queries designed for full index coverage
**Performance Monitoring**: Regular query performance analysis

## Caching Integration

### Query Result Caching

**wp_cache_* Integration**: All expensive queries cached
**Cache Keys**: Descriptive, collision-free cache keys
**Cache Groups**: Organized caching with appropriate groups
**Invalidation**: Smart cache invalidation on content changes

**Query Cache Example**:
```php
$cache_key = "filtered_images_{$term_id}_{$sort_by}_{$filter}";
$cached_images = wp_cache_get($cache_key, 'sarai_chinwag_images');

if (false === $cached_images) {
    // Perform expensive query
    $images = perform_expensive_query($args);
    wp_cache_set($cache_key, $images, 'sarai_chinwag_images', HOUR_IN_SECONDS);
    return $images;
}
return $cached_images;
```

## Memory Management

### Query Memory Optimization

**Result Set Limits**: All queries have appropriate limits
**Field Selection**: Queries select only needed fields when possible
**Object Cleanup**: Proper WordPress post data cleanup
**Memory Monitoring**: Query memory usage monitored and optimized

### Batch Processing

**Chunked Operations**: Large operations processed in batches
**Progress Tracking**: Batch processing with progress tracking
**Resource Limits**: Respects PHP resource limits
**Error Recovery**: Graceful handling of memory/timeout issues

## Performance Monitoring

### Query Performance Metrics

**Query Timing**: Database query execution times monitored
**Cache Hit Rates**: Query cache effectiveness measured
**Memory Usage**: Query memory consumption tracked
**Slow Query Detection**: Identification of performance bottlenecks

### Database Load Analysis

**Query Frequency**: Analysis of most frequent queries
**Resource Consumption**: Database resource usage monitoring
**Optimization Opportunities**: Continuous optimization identification
**Performance Trends**: Long-term performance trend analysis

## Troubleshooting

### Query Performance Issues

**Slow Queries**:
1. Check query cache hit rates
2. Analyze query execution plans
3. Verify proper index usage
4. Review query complexity

**Memory Issues**:
1. Check query result set sizes
2. Verify query limits are appropriate
3. Monitor PHP memory usage
4. Review caching effectiveness

**Database Load**:
1. Identify expensive queries
2. Check for missing indexes
3. Review query frequency
4. Optimize cache strategies

### Cache Issues

**Poor Cache Performance**:
1. Verify cache key generation
2. Check cache invalidation logic
3. Monitor cache hit rates
4. Review cache duration settings

## Advanced Optimization

### Custom Query Optimization

Complex custom queries can be optimized using the same principles while maintaining the theme's performance standards.

### Database Tuning

Query optimization works best with properly configured database settings and appropriate hardware resources.

### Performance Scaling

Query optimization strategies scale effectively from small personal sites to large high-traffic installations.