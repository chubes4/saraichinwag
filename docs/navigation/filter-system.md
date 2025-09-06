# Advanced Filter System

The Sarai Chinwag theme features a comprehensive AJAX-powered filter system that enables real-time content sorting and filtering without page reloads.

## Filter Bar Overview

### Filter Bar Components

**Template**: `template-parts/filter-bar.php`
**JavaScript**: `/js/filter-bar.js`
**Styling**: Integrated responsive design with mobile-friendly buttons

**Filter Categories**:
1. **Sort Filters** - Content ordering options
2. **Type Filters** - Content type and mode selection
3. **Context Data** - Hidden fields for JavaScript processing

## Sort Filter Options

### Available Sort Methods

#### Random (Default)
- **Button Label**: "Random"
- **Behavior**: Randomized content order for discovery
- **Implementation**: True randomization at content level
- **Cache**: Optimized random ID caching for performance

#### Most Popular
- **Button Label**: "Most Popular"  
- **Sorting**: Based on post view counts
- **Metric**: `_post_views` post meta field
- **Order**: Highest view counts first

#### Most Recent
- **Button Label**: "Most Recent"
- **Sorting**: Publication date descending
- **Behavior**: Newest content first
- **Default**: WordPress standard chronological order

#### Oldest
- **Button Label**: "Oldest"
- **Sorting**: Publication date ascending  
- **Behavior**: Oldest content first
- **Discovery**: Encourages historical content exploration

### Sort Implementation

**AJAX Processing**: Real-time sorting without page reload
**State Persistence**: Active sort maintained across load more
**Performance**: Cached sort results for efficiency
**Visual Feedback**: Active button highlighting

## Type Filter Options

### Content Type Filtering

#### All Content (When Available)
- **Display Condition**: Both posts and recipes exist
- **Button Label**: "All"
- **Content**: Mixed posts and recipes
- **Default State**: Active when not in image mode

#### Posts Only
- **Button Label**: "Posts"
- **Content**: Standard WordPress posts only
- **Availability**: Always available
- **Behavior**: Filters out recipe content

#### Recipes Only
- **Display Condition**: Recipe functionality enabled
- **Button Label**: "Recipes"  
- **Content**: Recipe post type only
- **Integration**: Respects recipe toggle setting

#### Images Mode
- **Button Label**: "Images"
- **Behavior**: Switches to image gallery view
- **URL Change**: Navigates to `/images/` variant of current page
- **Context**: Preserves category/tag/search context

### Type Filter Logic

**Conditional Display**: Buttons shown based on available content types
**Recipe Integration**: Respects universal theme toggle
**Mode Switching**: Intelligent navigation between post and image views
**Context Preservation**: Maintains current page context

## AJAX Filter Processing

### Real-Time Updates

**JavaScript Implementation**: Advanced AJAX processing with state management
**No Page Reload**: All filtering happens client-side
**Loading States**: Visual feedback during processing
**Error Handling**: Graceful degradation for failed requests

### Filter State Management

**Current Filter State**:
```javascript
currentFilters = {
    sort_by: 'random',
    post_type_filter: 'all',
    category: '[category-slug]',
    tag: '[tag-slug]',
    search: '[search-term]'
}
```

**State Persistence**: Filter settings maintained across interactions
**Load More Integration**: Filters preserved during pagination
**URL Synchronization**: Filter state reflected in navigation

### AJAX Endpoints

#### Filter Posts
- **Action**: `filter_posts`
- **Endpoint**: `/wp-admin/admin-ajax.php`
- **Purpose**: Real-time post filtering and sorting
- **Security**: Nonce verification required

#### Filter Images  
- **Action**: `filter_images`
- **Endpoint**: `/wp-admin/admin-ajax.php`
- **Purpose**: Image gallery filtering
- **Integration**: Seamless with gallery system

## Context Detection

### Page Context Processing

**Category Context**:
- Automatic detection of category archives
- Category slug extraction for filtering
- Category-specific image gallery support

**Tag Context**:
- Tag archive detection and processing
- Tag slug preservation across filters  
- Tag-based image collection filtering

**Search Context**:
- Search query preservation
- Search term integration with filtering
- Search result image gallery support

**Home Context**:
- Site-wide content filtering
- Global image gallery access
- Mixed content type support

### Image Gallery Mode Detection

**URL Pattern Detection**:
```javascript
const isImageGallery = /\/images\/?$/.test(path);
```

**Context Preservation**: Gallery mode maintains page context
**Mode Switching**: Intelligent navigation between modes
**Filter Adaptation**: Filters adapt to gallery vs post modes

## Mobile Responsiveness

### Responsive Filter Design

**Mobile Optimizations**:
- Touch-friendly button sizing (44px minimum height)
- Collapsible filter sections
- Swipe gesture support
- Accessible touch targets

**Responsive Breakpoints**:
- Mobile: Stacked filter sections
- Tablet: Condensed horizontal layout
- Desktop: Full horizontal filter bar

### Touch Interactions

**Button Behavior**:
- Clear visual feedback on touch
- Prevent accidental double-taps
- Smooth animation transitions
- Accessible focus states

## Filter Performance

### Optimization Strategies

**Cached Queries**: wp_cache_* implementation for filtered results
**Limited Results**: Configurable limits prevent memory issues
**Efficient AJAX**: Minimal data transfer for filter updates
**Smart Loading**: Only load content when filter changes

**Cache Groups**:
- `sarai_chinwag_random`: Random content caching
- `sarai_chinwag_images`: Image collection caching
- Context-specific cache keys for efficiency

### Database Efficiency

**Query Optimization**:
- Indexed database queries
- Limited result sets
- Efficient meta queries for sorting
- Optimized taxonomy queries

## Integration Features

### Load More Integration

**Seamless Pagination**: Load more preserves active filters
**State Consistency**: Filter state maintained across loads
**Performance**: Efficient incremental loading
**User Experience**: Smooth infinite scroll behavior

### Search Integration

**Search Query Preservation**: Active search terms maintained
**Image Search Support**: Search results in gallery mode
**Filter Combination**: Search + sort + type filtering
**Context Switching**: Search results between post and image modes

## Error Handling

### Network Issues

**Connection Problems**:
- Timeout protection with user feedback
- Retry mechanisms for failed requests  
- Graceful degradation to non-AJAX behavior
- Clear error messaging

### Content Issues

**No Results Handling**:
- Context-appropriate no content messages
- Navigation suggestions for users
- Fallback content recommendations
- Professional empty state design

### JavaScript Errors

**Error Recovery**:
- Try/catch blocks around critical functions
- Console error logging for debugging
- Fallback to standard page behavior
- User notification of issues

## Troubleshooting

### Filter Not Working

**Common Issues**:
1. Check JavaScript console for errors
2. Verify AJAX endpoints are responding
3. Confirm nonce validation is working
4. Test network connectivity

**Debug Steps**:
1. Monitor browser network requests
2. Check filter state management
3. Verify button click handlers
4. Test with different content types

### Performance Issues

**Slow Filtering**:
1. Monitor database query performance
2. Check cache hit rates
3. Verify query limits are appropriate
4. Optimize image loading if needed

### Mobile Issues

**Touch Problems**:
1. Verify touch event handlers
2. Check button sizing and spacing
3. Test responsive layout behavior
4. Confirm accessibility features

## Advanced Usage

### Custom Filter Options

The filter system can be extended with additional sort methods or filter criteria while maintaining the existing architecture.

### API Integration

Filter functionality can be accessed programmatically for custom implementations or headless applications.

### Performance Tuning

Filter performance can be optimized by adjusting cache durations, query limits, and loading strategies based on site size and hosting environment.