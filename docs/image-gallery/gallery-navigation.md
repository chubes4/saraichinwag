# Gallery Navigation

The Sarai Chinwag theme includes comprehensive navigation systems for image galleries, including responsive layouts, lightbox functionality, and intelligent distribution systems.

## Gallery Layout System

### Responsive Column System

**Column Distribution**:
- **Desktop** (>1200px): 4 columns
- **Tablet** (≤1200px): 3 columns  
- **Mobile** (<450px): 1 column

**Dynamic Column Creation**:
```javascript
SaraiGalleryUtils.getColumnCount()
```
- Viewport-based column calculation
- Automatic responsive adjustments
- Consistent across all gallery types

### Masonry Distribution

**Balanced Column Heights**:
- Round-robin distribution when columns are equal height
- Shortest column selection when height differences exist
- Smart image placement for visual balance

**Distribution Algorithm**:
```javascript
SaraiGalleryUtils.distributeFigures(figures, columns)
```
- Height calculation for each column
- Intelligent targeting of shortest columns
- Prevention of single long columns

## Gallery Item Components

### Individual Gallery Items

**Template**: `template-parts/gallery-item.php`
**Structure**:
- Responsive image element
- Source post overlay information
- Click-through navigation
- Accessibility features

### Image Display Features

**Image Element**:
- Optimized `grid-thumb` size (450x450px)
- Responsive width and height attributes
- Lazy loading for performance
- Pinterest save button integration

**Overlay System**:
- Source post title display
- Hover interaction states
- Touch-friendly mobile design
- Accessible focus management

### Gallery Item Metadata

**Source Post Information**:
- Post title as link anchor
- Publication date context
- Author attribution when relevant
- Category/tag relationship display

**Image Metadata**:
- Alt text for accessibility
- Image dimensions
- File size optimization
- Loading priority (eager/lazy)

## Navigation Utilities

### Gallery JavaScript Utilities

**Core Functions**: Provided by `SaraiGalleryUtils` object
**File**: `/js/gallery-utils.js`

### Column Management

#### Get Column Count
```javascript
SaraiGalleryUtils.getColumnCount()
```
**Returns**: Number of columns based on viewport width
**Usage**: Responsive layout calculations

#### Create Columns
```javascript
SaraiGalleryUtils.createColumns(count)
```
**Parameters**: Number of columns to create
**Returns**: Array of column DOM elements
**Usage**: Initial gallery structure creation

#### Shortest Column Selection
```javascript
SaraiGalleryUtils.getShortestColumn(columns)
```
**Parameters**: Array of column elements
**Returns**: Column element with smallest height
**Usage**: Balanced image distribution

### Template Loading System

#### Load Template Function
```javascript
SaraiGalleryUtils.loadTemplate(templateName, args)
```
**Purpose**: Dynamically load server-side templates
**Parameters**:
- `templateName`: Template file name (without extension)
- `args`: Arguments to pass to template

**Returns**: Promise resolving to template HTML
**Usage**: AJAX content loading and no-content messages

#### No Content Messages
```javascript
SaraiGalleryUtils.getNoContentMessage(contentType)
```
**Parameters**: Content type ('images', 'posts')
**Returns**: Promise with formatted no-content message
**Usage**: Empty state handling

## Lightbox Integration

### Image Lightbox System

**Functionality**: Full-screen image viewing with navigation
**File**: Integrated within `gallery-utils.js`
**Features**:
- Full-resolution image display
- Keyboard navigation support
- Touch/swipe gestures
- Close button and escape key support

### Lightbox Navigation

**Navigation Controls**:
- Previous/Next image buttons
- Keyboard arrow key support
- Touch swipe gestures (mobile)
- Thumbnail navigation strip

**Image Information Display**:
- Source post title and link
- Image alt text and description
- Navigation to source post
- Pinterest save functionality

## Gallery State Management

### Filter State Integration

**State Preservation**:
- Active filter settings maintained during navigation
- Sort order preserved across gallery interactions
- Post type filters maintained
- Search context preserved

**AJAX Integration**:
- Real-time filter updates without page reload
- Gallery layout maintained during updates
- Loading states for user feedback
- Error handling for failed requests

### Load More Integration

**Infinite Scroll Behavior**:
- Seamless additional content loading
- Maintains masonry distribution
- Prevents duplicate image loading
- Efficient database queries

**Load More Button**:
- Shows when additional content available
- Updates to "No more images" when exhausted
- Disabled state during loading
- Loading spinner integration

## Responsive Navigation

### Touch Interactions

**Mobile Optimization**:
- Touch-friendly button sizes (minimum 44px)
- Swipe gesture support
- Tap vs. long-press differentiation
- Responsive overlay interactions

**Accessibility Features**:
- Screen reader support
- Keyboard navigation
- Focus management
- ARIA labels and descriptions

### Viewport Adaptation

**Layout Adjustments**:
- Column count changes based on screen size
- Image size optimization for viewport
- Touch target size adjustments
- Text scaling for readability

## Gallery Discovery Features

### Related Gallery Navigation

**Gallery Discovery Badges**: Links to related image galleries
**Display Location**: Above "Keep Exploring" sections on single posts
**Context**: Shows galleries for post's categories and tags

**Badge System**:
- Category galleries (blue badges)
- Tag galleries (pink badges)  
- Image count display
- Direct navigation to galleries

### Cross-Gallery Navigation

**Archive Links**: Navigation between different gallery types
**Mode Switching**: Toggle between post and image views
**Breadcrumb Integration**: Context-aware navigation paths

## Performance Features

### Efficient Loading

**Image Optimization**:
- Lazy loading for below-fold images
- Optimized image sizes for gallery display
- Progressive enhancement
- Bandwidth-conscious loading

**JavaScript Performance**:
- Event delegation for gallery interactions
- Debounced resize handlers
- Efficient DOM manipulation
- Memory-conscious event handling

### Cache Integration

**Template Caching**: Server-side template fragments cached
**Image Metadata**: Cached image information for fast access
**Navigation State**: Cached navigation context preservation

## Error Handling

### Failed Image Loading

**Fallback Behavior**:
- Broken image replacement
- Alternative content display
- Error message integration
- Graceful degradation

### Network Issues

**Connection Handling**:
- Timeout protection
- Retry mechanisms
- User feedback systems
- Offline state detection

## Troubleshooting

### Gallery Layout Issues

**Column Distribution Problems**:
1. Check viewport width calculation
2. Verify column creation function
3. Test responsive breakpoints
4. Clear browser cache

**Image Loading Issues**:
1. Verify image URLs are accessible
2. Check lazy loading implementation
3. Monitor browser network requests
4. Test with different image sizes

### Navigation Problems

**AJAX Navigation Failures**:
1. Check JavaScript console for errors
2. Verify AJAX endpoints responding
3. Confirm nonce validation
4. Test network connectivity

**Lightbox Issues**:
1. Verify lightbox JavaScript loading
2. Check for conflicting plugins
3. Test keyboard navigation
4. Confirm image URLs accessible

### Performance Issues

**Slow Gallery Loading**:
1. Monitor image loading times
2. Check database query performance
3. Verify cache hit rates
4. Optimize image sizes

**Memory Issues**:
1. Limit number of images loaded
2. Implement proper cleanup
3. Monitor DOM element count
4. Use efficient event handlers

## Advanced Usage

### Custom Navigation

The gallery navigation system can be extended with custom navigation elements while maintaining core functionality.

### Integration Examples

Gallery navigation integrates with:
- Filter bar system
- Load more functionality
- Search interface
- Breadcrumb navigation

### API Extensions

Navigation functions can be extended for custom gallery implementations or third-party integrations.