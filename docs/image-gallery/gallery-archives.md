# Gallery Archives

The Sarai Chinwag theme provides specialized image gallery archives that display collections of images from specific categories, tags, or across the entire site in a responsive masonry layout.

## Gallery Archive Types

### Category Image Galleries

**URL Pattern**: `/category/[category-name]/images/`
**Content**: All images from posts within the specified category
**Example**: `/category/food/images/` shows all food-related images

**Features**:
- Automatic image extraction from category posts
- Maintains category context and branding
- Breadcrumb navigation back to category posts
- Filter integration for sorting and refinement

### Tag Image Galleries

**URL Pattern**: `/tag/[tag-name]/images/`
**Content**: All images from posts tagged with the specified tag  
**Example**: `/tag/desserts/images/` shows all dessert-tagged images

**Features**:
- Complete tag-based image collection
- Tag context preservation
- Navigation between tag posts and images
- AJAX filtering and sorting options

### Site-Wide Image Gallery

**URL Pattern**: `/images/`
**Content**: Images from all posts across the entire site
**Scope**: Comprehensive site image collection

**Features**:
- Complete site image inventory
- Random image discovery
- Global image browsing
- Filter by post type (posts vs recipes)

### Search Image Galleries

**URL Pattern**: `/search/[query]/?images=1` or `/search/[query]/images/`
**Content**: Images from posts matching search query
**Integration**: Seamless search result image display

## Gallery Layout System

### Responsive Masonry Layout

**Grid Structure**: 4-column masonry layout (desktop)
**Responsive Breakpoints**:
- Desktop: 4 columns
- Tablet: 3 columns  
- Mobile: 1 column

**Server-Side Column Building**:
- Columns constructed on server for immediate display
- Balanced distribution of images across columns
- No client-side layout shifting

### Image Display Features

**Image Information**:
- Full-resolution image display
- Source post title overlay on hover
- Direct links to source posts
- Responsive image sizing

**Loading Optimization**:
- Eager loading for first 12 images
- Lazy loading for subsequent images
- Optimized image sizes for gallery display
- Progressive image enhancement

## Gallery Navigation

### Image Item Components

**Gallery Item Template**: `template-parts/gallery-item.php`
**Image Display**:
- Responsive image element
- Source post overlay
- Click-through to source post
- Pinterest save button integration

**Metadata Display**:
- Source post title
- Publication date context
- Category/tag relationships
- Image dimensions and alt text

### Overlay System

**Hover Interactions**:
- Source post title appears on hover
- Smooth overlay transitions
- Accessible focus states
- Touch-friendly mobile interactions

**Link Behavior**:
- Images link to source posts (not image files)
- Maintains content context
- Encourages post engagement
- SEO-friendly linking structure

## Load More Functionality

### AJAX Load More System

**Implementation**: Dynamic content loading without page reload
**Performance**: Maintains filter state across load more
**User Experience**: Seamless infinite scroll browsing

**Load More Button**:
- Appears when more images available
- Updates text when exhausted ("No more images")
- Disabled state when complete
- Loading indicators during requests

### Load More Integration

**Filter Preservation**:
- Active sort order maintained
- Post type filters preserved
- Search context retained
- Category/tag context maintained

**Performance Features**:
- Prevents duplicate image loading
- Efficient database queries
- Cached image collections
- Memory-conscious loading

## Archive Context Detection

### Gallery Mode Detection

**URL Analysis**: Detects `/images/` pattern in URLs
**Query Variables**: Uses WordPress rewrite rules
**Context Preservation**: Maintains category/tag/search context

**Implementation**:
```php
$is_image_gallery = get_query_var('images') !== false;
$url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false;
```

### Context-Aware Display

**Category Context**:
- Category name in page title
- Breadcrumb navigation to category posts
- Category-specific image count
- Related category suggestions

**Tag Context**:
- Tag name and description
- Navigation to tag posts
- Tag-specific filtering
- Related tag discovery

**Site-Wide Context**:
- Global image browsing
- Cross-category discovery
- Total site image count
- Universal filtering options

## Gallery Integration Features

### Filter System Integration

**Sort Options Available**:
- Random (default)
- Most Popular (by source post views)
- Most Recent
- Oldest

**Post Type Filtering**:
- All content
- Posts only
- Recipes only (when enabled)

**Real-Time Updates**:
- AJAX-powered filtering
- No page reload required
- Maintains gallery layout
- Preserves user position

### Search Integration

**Image Search Results**:
- Gallery display of search matches
- Search query preservation
- Image context maintenance
- Integrated with main search

**Search Features**:
- Full-text search of source posts
- Image alt text searching
- Category and tag search
- Combined content and image results

## Performance Optimization

### Caching Strategy

**Image Collection Caching**:
- 1-hour cache for extracted images
- Category/tag specific cache keys
- Site-wide image cache
- Search result caching

**Cache Groups**:
- `sarai_chinwag_images` for image collections
- Automatic cache invalidation on content updates
- Manual cache clearing available

### Database Efficiency

**Query Optimization**:
- Limited result sets (30 images default)
- Efficient attachment queries
- Indexed database searches
- Memory-conscious processing

**Load Balancing**:
- Progressive image loading
- Lazy loading implementation
- Optimal image size serving
- Bandwidth optimization

## Gallery Archive Templates

### Template Hierarchy

**Primary Template**: `template-parts/content-image-gallery.php`
**Gallery Items**: `template-parts/gallery-item.php`
**Archive Layout**: Standard archive templates with gallery mode

### Template Features

**Responsive Design**:
- Mobile-optimized layouts
- Touch-friendly interactions
- Accessible navigation
- Cross-browser compatibility

**SEO Optimization**:
- Proper heading hierarchy
- Alt text preservation
- Structured data support
- Clean URL structure

## No Content Handling

### Empty Gallery States

**No Images Found Messages**:
- Context-specific messaging
- Navigation back to posts
- Helpful user guidance
- Professional error handling

**Fallback Behavior**:
- Graceful degradation
- Alternative content suggestions
- Clear navigation options
- User-friendly messaging

### Error States

**Loading Failures**:
- Connection error handling
- Timeout protection
- User feedback systems
- Retry mechanisms

## Troubleshooting

### Gallery Not Loading

**Common Issues**:
1. Check URL rewrite rules are active
2. Verify image extraction is working
3. Clear image cache if needed
4. Check for JavaScript errors

**Debug Steps**:
1. Test with individual category/tag galleries
2. Verify site-wide gallery functionality
3. Check AJAX endpoints are responding
4. Monitor browser network requests

### Images Missing from Gallery

**Troubleshooting**:
1. Confirm posts have published status
2. Verify images are attached to posts
3. Check category/tag assignments
4. Clear and regenerate image cache

### Performance Issues

**Optimization**:
1. Adjust image extraction limits
2. Monitor database query performance
3. Check cache hit rates
4. Optimize image sizes served

## Advanced Usage

### Custom Gallery Types

The gallery system can be extended to support additional taxonomy types or custom post type galleries.

### Gallery Customization

Gallery appearance, layout, and behavior can be customized through template modifications while maintaining core functionality.

### API Integration

Gallery data can be accessed programmatically for headless implementations or custom integrations.