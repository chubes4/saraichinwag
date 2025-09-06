# Image Extraction System

The Sarai Chinwag theme includes a comprehensive image extraction system that automatically discovers and catalogs all images from posts for display in specialized gallery archives.

## Image Extraction Overview

### Automatic Image Discovery

**Sources Processed**:
- Featured images (highest priority)
- Gutenberg block images (core/image, core/gallery, core/media-text)
- Content images from all published posts
- Images from both posts and recipes (when enabled)

**Processing Scope**:
- All published posts and recipes
- All categories and tags with associated posts
- Site-wide image collection for global galleries

### Extraction Functions

#### Extract Images from Term
```php
sarai_chinwag_extract_images_from_term($term_id, $term_type, $limit = 30)
```

**Purpose**: Extract all images from posts within a specific category or tag
**Parameters**:
- `$term_id`: The term ID (category or tag ID)
- `$term_type`: Taxonomy type ('category' or 'post_tag')
- `$limit`: Maximum number of images to return

**Returns**: Array of image data objects with complete metadata

#### Extract Images from Post
```php
sarai_chinwag_extract_images_from_post($post_id)
```

**Purpose**: Extract all images from a single post
**Parameters**: `$post_id` - The WordPress post ID
**Returns**: Array of image data from all sources in the post

#### Site-Wide Image Extraction
```php
sarai_chinwag_get_all_site_images($limit = 30)
```

**Purpose**: Extract images from across the entire site
**Parameters**: `$limit` - Maximum number of images to return
**Returns**: Array of image data from all published posts

## Image Data Structure

### Image Metadata Collection

Each extracted image includes comprehensive metadata:

**Core Data**:
- `attachment_id`: WordPress attachment ID
- `post_id`: Source post ID where image was found
- `source`: Where image was discovered (featured, content, gallery, etc.)

**Image Information**:
- `title`: Image title or source post title
- `alt`: Alt text for accessibility
- `caption`: Image caption if available
- `description`: Image description

**URL Variations**:
- `url_full`: Full-size image URL
- `url_thumb`: Thumbnail URL (grid-thumb size)
- `url_medium`: Medium-large size URL

**Dimensions**:
- `width`: Image width in pixels
- `height`: Image height in pixels

**Source Context**:
- `source_post_title`: Title of post containing image
- `source_post_url`: Permalink to source post
- `source_post_date`: Publication date of source post

### Image Size Optimization

**Optimized Sizes**:
- `grid-thumb`: 450x450px cropped for gallery display
- Responsive sizing for different viewport requirements
- Lazy loading for images beyond initial viewport

**Performance Benefits**:
- Single optimized size reduces storage overhead
- Efficient loading for mobile devices
- Eliminates unnecessary image size generation

## Block Editor Integration

### Gutenberg Block Support

**Supported Block Types**:
- `core/image`: Individual image blocks
- `core/gallery`: Gallery blocks with multiple images
- `core/media-text`: Media and text combination blocks

**Nested Block Handling**:
- Recursive processing of inner blocks
- Complete block structure analysis
- Support for complex page layouts

### Block Parsing Process

**Image Block Processing**:
```php
sarai_chinwag_extract_images_from_blocks($blocks, $post_id)
```

**Functionality**:
- Parses block structure from post content
- Extracts attachment IDs from block attributes
- Processes nested blocks recursively
- Maintains source context for each image

## Caching Strategy

### Performance Optimization

**Cache Implementation**:
- Cache Group: `sarai_chinwag_images`
- Cache Duration: 1-2 hours depending on content type
- wp_cache_* functions for efficient retrieval

**Cache Keys**:
- Term images: `sarai_chinwag_term_images_{$term_id}_{$term_type}`
- Site images: `sarai_chinwag_all_site_images`
- Search images: `search_image_count_{md5($search_query)}`

**Cache Invalidation**:
- Automatic cache clearing on post save/delete
- Category and tag cache clearing when posts updated
- Manual cache flushing available for troubleshooting

### Cache Management Functions

#### Clear Image Cache
```php
sarai_chinwag_clear_image_cache_on_post_update($post_id)
```

**Triggers**:
- Post save (`save_post` hook)
- Post deletion (`delete_post` hook)
- Automatic cache maintenance

## Filtering and Sorting

### Filtered Image Extraction

#### Term-Based Filtering
```php
sarai_chinwag_get_filtered_term_images($term_id, $term_type, $sort_by, $post_type_filter, $loaded_images, $limit)
```

**Filtering Options**:
- **Sort By**: random, recent, oldest, popular
- **Post Type**: all, posts, recipes
- **Exclusions**: Previously loaded images
- **Limits**: Configurable result limits

#### Site-Wide Filtering
```php
sarai_chinwag_get_filtered_all_site_images($sort_by, $post_type_filter, $loaded_images, $limit)
```

**Advanced Filtering**:
- Real-time AJAX filtering
- Maintains filter state across load more
- Prevents duplicate image display

### Randomization System

**True Image Randomization**:
- Images shuffled at extraction level (not just random posts)
- Consistent randomization across initial load and AJAX
- Prevents predictable image ordering

**Performance Considerations**:
- MySQL randomization for large datasets
- Cached random results for efficiency
- Limited query sizes prevent memory issues

## Search Integration

### Image Search Functionality

#### Search Image Extraction
```php
sarai_chinwag_extract_images_from_search($search_query, $limit)
```

**Search Processing**:
- Text matching in post titles and content
- Image context preservation
- Search result image galleries
- Integrated with main search functionality

**Search Features**:
- Images from posts matching search terms
- Maintains source post context
- Gallery display of search results
- Preserves search query in image URLs

## Performance Considerations

### Memory Management

**Query Limitations**:
- Maximum 500 posts processed per extraction
- Configurable limits prevent memory exhaustion
- Early termination when sufficient images found

**Efficient Processing**:
- Duplicate prevention with seen_attachments array
- Optimized database queries
- Minimal memory footprint

### Database Optimization

**Query Efficiency**:
- Limited result sets for large sites
- Indexed meta queries for performance
- Efficient attachment relationship queries

**Caching Benefits**:
- Reduces database load
- Faster page load times
- Improved user experience

## Troubleshooting

### Image Extraction Issues

**Images Not Appearing**:
1. Verify posts have published status
2. Check image attachment to posts
3. Confirm block structure for Gutenberg content
4. Clear image cache and retry

**Performance Problems**:
1. Check query limits and memory usage
2. Monitor database query efficiency
3. Verify cache hit rates
4. Adjust extraction limits if needed

**Cache Issues**:
1. Clear wp_cache with `wp_cache_flush_group('sarai_chinwag_images')`
2. Verify cache key generation
3. Check cache expiration times
4. Monitor cache storage usage

### Block Processing Problems

**Gutenberg Images Missing**:
1. Verify block parsing functionality
2. Check block attribute structure
3. Confirm attachment ID extraction
4. Test with different block types

**Nested Block Issues**:
1. Enable recursive block processing
2. Verify inner block handling
3. Check complex layout support
4. Test with various page builders

## Advanced Usage

### Custom Image Sources

The extraction system can be extended to support additional image sources beyond the default Gutenberg blocks.

### Performance Tuning

Extraction limits, cache durations, and query parameters can be adjusted based on site size and hosting capabilities.

### Integration Examples

The image extraction system integrates seamlessly with:
- Gallery archive pages
- AJAX filtering system
- Search functionality  
- Random content discovery