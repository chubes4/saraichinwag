# Standard Posts

The Sarai Chinwag theme enhances WordPress standard posts with advanced features including view tracking, image extraction, and optimized display templates.

## Enhanced Post Features

### View Counter System

**Automatic Tracking**: Post views tracked automatically on page load
**Storage**: View counts stored in `_post_views` post meta
**Usage**: Powers "Most Popular" sorting in filter system
**Performance**: Cached view counts for efficient queries

**Implementation**:
- Function: `sarai_chinwag_track_post_view($post_id)`
- Tracking Hook: Executes on `wp_head` for singular posts
- Cache Group: `sarai_chinwag_views` for performance optimization

### Image Extraction

**Automatic Discovery**: All images extracted from post content
**Sources Supported**:
- Featured images (highest priority)
- Gutenberg block images (core/image blocks)
- Gallery blocks (core/gallery blocks) 
- Media & text blocks (core/media-text blocks)

**Image Data Collected**:
- Attachment ID and post relationship
- Image metadata (dimensions, URLs)
- Alt text and captions
- Source post information
- Multiple image sizes (full, medium, thumbnail)

### Post Templates

**Template Hierarchy**:
- `single.php` - Single post display
- `template-parts/content-single.php` - Single post content
- `template-parts/content.php` - Archive post content
- `template-parts/content-image-gallery.php` - Gallery view posts

## Post Display Features

### Badge Navigation System

**Single Post Navigation**: Category and tag badges replace traditional breadcrumbs
**Visual Design**:
- Blue badges for categories  
- Pink badges for tags
- Clickable navigation elements
- Responsive badge layout

**Implementation**:
- Function: `sarai_chinwag_post_badges()`
- Display: Primary category + up to 3 tags
- Location: Above post title on single posts

### Featured Image Integration

**Pinterest Integration**: Featured images include Pinterest save button data
**Responsive Images**: Uses optimized `grid-thumb` image size (450x450px)
**Lazy Loading**: Images beyond initial viewport load lazily
**Performance**: Single optimized image size reduces bandwidth

## Archive Integration

### Home Page Display

**Randomized Order**: Posts appear in random order (not chronological)
**Grid Layout**: 4-column responsive grid layout
**Full-Width**: Sidebar removed for maximum content visibility
**Mixed Content**: Posts and recipes displayed together (when recipes enabled)

### Category and Tag Archives

**Filter Integration**: Advanced filtering with sort options
**Post Type Filtering**: Filter between posts and recipes
**Image Gallery Mode**: Switch to image-only view of category/tag content
**Breadcrumb Navigation**: Traditional breadcrumbs on archive pages

### Search Integration

**Enhanced Search**: Posts included in all search queries
**Image Search**: Posts contribute images to image search results
**Filter Compatibility**: Search results work with filter system
**Mixed Results**: Posts and recipes in search results

## Performance Optimizations

### Caching Strategy

**View Counter Cache**: wp_cache_* for view count queries
**Related Posts Cache**: 15-minute cache for related content
**Image Cache**: 1-hour cache for extracted image data
**Query Optimization**: Limited post queries prevent memory issues

### Database Efficiency

**Indexed Queries**: Optimized database queries for post retrieval
**Limited Results**: Post queries limited to prevent memory issues
**Meta Query Optimization**: Efficient queries for post metadata
**Cache Groups**: Segregated caching for different post data types

## Post Content Features

### Block Editor Integration

**Full Support**: Complete Gutenberg block support
**Image Extraction**: Automatic image discovery from blocks
**Typography Consistency**: Editor fonts match frontend display
**Schema Integration**: Structured data from post content

### Classic Editor Support

**Legacy Compatibility**: Full Classic Editor support maintained
**Font Integration**: Consistent typography in Classic Editor
**Image Handling**: Images extracted from Classic Editor content
**Migration Support**: Seamless content migration between editors

## RSS Feed Integration

### Feed Inclusion

**Main Feed**: Posts automatically included in RSS feeds
**Category Feeds**: Posts appear in category-specific RSS feeds
**Tag Feeds**: Posts included in tag-based RSS feeds
**Mixed Feeds**: Posts and recipes together in feeds (when recipes enabled)

## Social Media Integration

### Pinterest Features

**Save Buttons**: Automatic Pinterest save buttons on featured images
**Data Attributes**: Pinterest-specific data attributes on images
**Follow Integration**: Pinterest follow widgets (when configured)
**Social Sharing**: Enhanced social media connectivity

### OpenGraph Support

**Social Metadata**: Proper OpenGraph tags for social sharing
**Image Optimization**: Featured images optimized for social platforms
**Title and Description**: SEO-optimized social media previews

## Advanced Post Features

### Random Access

**Random Post URL**: `/random-post` redirects to random post
**Random All URL**: `/random-all` includes posts and recipes
**Discovery System**: Encourages serendipitous content discovery
**Performance**: Cached random ID system for efficiency

### Related Content

**Smart Relations**: Related posts based on categories and tags
**Performance Cache**: 15-minute cache for related post queries
**Discovery Integration**: Related posts enhance content discovery
**Visual Display**: Grid layout for related content

## Gallery Discovery Integration

### Gallery Discovery Badges

**Implementation**: `sarai_chinwag_gallery_discovery_badges()` function
**Display Location**: Below post content on single posts
**Purpose**: Encourages exploration of related image galleries

**Badge Features**:
- **Category Galleries**: Links to category-specific image galleries
- **Tag Galleries**: Links to tag-specific image galleries
- **Image Counts**: Shows accurate number of images in each gallery
- **Caching**: Cached per-post with 15-minute expiration using `sarai_chinwag_related` cache group
- **Conditional Display**: Only shows galleries with images

**Visual Design**:
- Category badges: Blue styling
- Tag badges: Pink styling
- Count display: Parenthetical numbers
- Responsive layout: Mobile-friendly design

### Image Anchoring

**Function**: Content filter for deep linking to images
**Implementation**: Automatic anchorable spans on images with `wp-image-{ID}` class
**Purpose**: Enables direct linking to specific images using `#sc-image-{ID}` hash fragments
**Scope**: Only applies to singular pages (posts, recipes)
**Performance**: Lightweight content filter with minimal processing overhead

## Template Customization

### Template Parts

**Modular Design**: Post display split into reusable template parts
**Content Types**: Different templates for different display contexts
**Customization**: Easy template customization while maintaining functionality
**Responsive Design**: Templates adapt to different screen sizes

### Display Contexts

**Single Post**: Full post display with sidebar and related content
**Archive Display**: Grid-based post display for browsing
**Image Gallery**: Image-focused display when in gallery mode
**Search Results**: Optimized display for search result pages

## Troubleshooting

### View Counter Issues

**Views Not Tracking**:
1. Check JavaScript console for errors
2. Verify `wp_head` hook executing
3. Confirm post is singular (not archive)

**Popular Sort Not Working**:
1. Verify view counter data exists
2. Check meta query for `_post_views`
3. Clear view counter cache if needed

### Image Extraction Problems

**Images Not Appearing in Gallery**:
1. Verify images are properly attached to posts
2. Check image extraction function execution
3. Clear image cache and reload

**Featured Image Issues**:
1. Confirm featured image is set on post
2. Check image size generation
3. Verify Pinterest data attributes

### Template Display Issues

**Layout Problems**:
1. Check template hierarchy loading correct files
2. Verify CSS and JavaScript enqueueing
3. Clear any caching plugins

**Content Not Displaying**:
1. Confirm post status is published
2. Check user permissions
3. Verify template part includes

## Advanced Usage

### Custom Post Meta

Posts support additional custom meta fields that integrate with the view counter and caching systems.

### Performance Tuning

The post system includes multiple performance optimizations that can be adjusted based on site size and hosting environment.