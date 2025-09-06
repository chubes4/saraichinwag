# Breadcrumb Navigation System

The Sarai Chinwag theme implements a dual navigation system: badge-style breadcrumbs for single posts and traditional breadcrumbs for archive pages.

## Navigation System Overview

### Dual Breadcrumb Approach

**Single Posts/Recipes**: Badge-style navigation with clickable category and tag elements
**Archive Pages**: Traditional hierarchical breadcrumb trails  
**Context-Aware**: Navigation adapts to page type and content context
**Accessibility**: Full ARIA labeling and semantic markup

## Single Post Badge Navigation

### Badge-Style Breadcrumbs

**Function**: `sarai_chinwag_post_badges()`
**Display Location**: Above post title on single posts and recipes
**Visual Design**: Colored badges instead of text links

#### Badge Types

**Category Badges**:
- Color: Blue styling
- Content: Primary category name
- Behavior: Links to category archive
- Limit: One primary category displayed

**Tag Badges**:  
- Color: Pink styling
- Content: Tag names
- Behavior: Links to individual tag archives
- Limit: Up to 3 most relevant tags

### Badge Implementation

**Template Integration**: Automatically displayed on single posts
**Conditional Display**: Only shows when categories or tags exist
**Responsive Design**: Mobile-friendly badge sizing
**Accessibility**: Proper ARIA labels and semantic markup

```php
sarai_chinwag_post_badges()
```

**Features**:
- Automatic category/tag detection
- Responsive badge layout
- SEO-friendly link structure
- Visual hierarchy with color coding

### Badge Styling

**Category Badge Styling**:
- Background color tied to theme primary color
- Rounded corners for modern appearance
- Hover effects for interactivity
- High contrast for accessibility

**Tag Badge Styling**:
- Background color tied to theme secondary color  
- Consistent sizing with category badges
- Touch-friendly mobile sizing
- Clear visual differentiation

## Archive Page Breadcrumbs

### Traditional Breadcrumb Trails

**Function**: `sarai_chinwag_archive_breadcrumbs()`
**Display Location**: Top of archive pages
**Format**: Home > Category/Tag/Search > Current Location

#### Breadcrumb Types

**Category Archives**:
- Pattern: Home > [Category Name]
- Context: Category archive pages
- Navigation: Direct link back to home

**Tag Archives**:
- Pattern: Home > Tags > [Tag Name] 
- Context: Tag archive pages
- Structure: Intermediate "Tags" level for clarity

**Search Results**:
- Pattern: Home > Search Results > "[Search Query]"
- Context: Search result pages
- Query Display: Search terms in quotes

**Author Archives**:
- Pattern: Home > Author > [Author Name]
- Context: Author archive pages
- Structure: Clear author context

**Date Archives**:
- Pattern: Home > Archives > [Date]
- Context: Date-based archives
- Formats: Year/Month/Day specific formatting

### Archive Breadcrumb Features

**Separator**: ` > ` character for clear hierarchy
**Home Link**: Always links to site homepage
**Current Location**: Final element not linked (current page)
**Responsive**: Mobile-optimized text sizing

## Navigation Context Detection

### Page Type Detection

**Single Post Detection**:
```php
is_singular(array('post', 'recipe'))
```
- Triggers badge navigation system
- Excludes pages and other post types
- Recipe-aware when functionality enabled

**Archive Detection**:
```php
is_archive() || is_search()
```
- Triggers traditional breadcrumb system
- Includes all archive types
- Comprehensive coverage of archive contexts

### Content Context Processing

**Category Context**:
- Primary category identification
- Category hierarchy handling (if implemented)
- Category-specific styling and behavior

**Tag Context**:
- Multiple tag handling
- Tag relevance sorting
- Limit management for display

**Search Context**:
- Search query extraction and display
- Query sanitization for security
- Search result context preservation

## Accessibility Features

### ARIA Implementation

**Navigation Labels**:
- `aria-label="Content navigation"` for post badges
- `aria-label="Page navigation"` for archive breadcrumbs
- Semantic `<nav>` elements for structure

**Link Context**:
- `rel="category tag"` for category badges
- `rel="tag"` for tag badges  
- Clear link purposes for screen readers

### Keyboard Navigation

**Focus Management**:
- Proper tab order through badges
- Visible focus indicators
- Keyboard activation support

**Screen Reader Support**:
- Meaningful link text
- Context information provided
- Logical navigation structure

## SEO Benefits

### Internal Link Structure

**Link Equity Distribution**:
- Strategic internal linking through badges
- Category and tag page link building
- Natural anchor text usage

**Crawling Efficiency**:
- Clear site structure for search engines
- Logical hierarchy representation
- Consistent internal linking patterns

### User Experience Benefits

**Reduced Navigation Depth**:
- Quick access to related content
- Visual category/tag relationships
- Intuitive content organization

## Mobile Responsiveness

### Touch-Friendly Design

**Badge Sizing**:
- Minimum 44px touch targets
- Adequate spacing between elements
- Thumb-friendly interaction areas

**Responsive Typography**:
- Scalable text sizing
- Readable font weights
- Optimal contrast ratios

### Mobile Layout

**Stacked Badges**: Vertical stacking on narrow screens
**Flexible Wrapping**: Badge wrapping on medium screens
**Consistent Spacing**: Maintained spacing across breakpoints

## Integration Features

### Theme Integration

**Color Scheme Integration**:
- Badge colors tied to theme customizer colors
- Consistent visual branding
- Dynamic color updates

**Typography Integration**:
- Badge text uses theme font settings
- Consistent font scaling
- Readable typography hierarchy

### Content Integration

**Recipe Integration**:
- Recipe posts use same badge system
- Recipe-specific styling when needed
- Consistent behavior across post types

**Multi-language Support**:
- Translation-ready text strings
- RTL language support
- Localized navigation terms

## Performance Considerations

### Efficient Processing

**Conditional Loading**:
- Breadcrumbs only generated when needed
- Context detection prevents unnecessary processing
- Efficient database queries for taxonomy data

**Caching Integration**:
- Badge data can be cached for performance
- Taxonomy query optimization
- Minimal database impact

## Troubleshooting

### Badge Navigation Issues

**Badges Not Displaying**:
1. Verify posts have categories or tags assigned
2. Check template integration
3. Confirm function execution
4. Review CSS styling

**Wrong Badge Colors**:
1. Check theme customizer color settings
2. Verify CSS custom property loading
3. Clear browser cache
4. Test color customization

### Archive Breadcrumb Issues

**Breadcrumbs Missing**:
1. Confirm page is archive or search type
2. Check function execution in template
3. Verify breadcrumb content generation
4. Review conditional display logic

**Incorrect Hierarchy**:
1. Check page context detection
2. Verify taxonomy relationships
3. Test with different archive types
4. Review breadcrumb generation logic

### Mobile Issues

**Touch Problems**:
1. Verify touch target sizes
2. Check responsive CSS rules
3. Test on various devices
4. Confirm touch event handling

## Advanced Usage

### Custom Badge Types

The badge system can be extended to support custom taxonomies while maintaining the existing design patterns.

### Breadcrumb Customization

Breadcrumb formats and structures can be customized for specific content types or site requirements.

### Schema Markup

Breadcrumb navigation can be enhanced with structured data markup for search engine rich snippets.