# Google Fonts Integration

The Sarai Chinwag theme features advanced Google Fonts integration with API-powered font selection, live preview, and consistent typography across frontend and WordPress editors.

## Google Fonts API Integration

### API Configuration

**Settings Location**: Settings → Theme Settings → Google Fonts API Key
**Required**: API key must be configured for font selection functionality
**API Documentation**: [Google Fonts Developer API](https://developers.google.com/fonts/docs/developer_api)

### API Key Setup

**Getting API Key**:
1. Visit Google Cloud Console
2. Enable Google Fonts Developer API
3. Create credentials (API key)
4. Configure API key restrictions (optional)
5. Enter key in Theme Settings

**API Security**: 
- Key stored securely in WordPress database
- Optional domain restrictions supported
- Server-side validation and sanitization

## Font Selection System

### Category-Based Font Loading

#### Display Fonts (Headers)
- **API Category**: `display`
- **Usage**: Headings (h1-h6)
- **Characteristics**: Decorative fonts optimized for large sizes
- **Default**: Gluten (theme's built-in font)

#### Body Fonts
- **API Categories**: Combined `sans-serif` and `serif`
- **Usage**: Body text, paragraphs, content
- **Characteristics**: Readable fonts optimized for text blocks
- **Default**: System Fonts (Helvetica, Arial fallback)

### Font Caching System

**Cache Strategy**:
- Cache Group: `sarai_chinwag_fonts`
- Cache Duration: 24 hours
- API Response Caching: Prevents redundant API calls
- Performance: Dramatically reduces API requests

**Cache Keys**:
- Display fonts: `google_fonts_display`
- Body fonts: `google_fonts_sans-serif` + `google_fonts_serif`
- Dynamic cache invalidation on settings changes

## WordPress Customizer Integration

### Typography Controls

**Customizer Section**: Appearance → Customize → Typography

#### Heading Font Control
- **Type**: Select dropdown
- **Options**: Display category fonts from Google Fonts API
- **Live Preview**: Real-time font changes without page reload
- **Fallback**: Theme default and system fonts always available

#### Body Font Control
- **Type**: Select dropdown  
- **Options**: Combined sans-serif and serif fonts
- **Live Preview**: Immediate font updates in customizer
- **Fallback**: System fonts when API unavailable

### Live Preview System

**JavaScript File**: `/js/customizer.js`
**Functionality**:
- Real-time font loading and display
- CSS custom property updates
- Fallback font handling during loading
- Smooth font transition effects

**Implementation**:
- Listens to customizer setting changes
- Dynamically loads Google Fonts
- Updates CSS custom properties instantly
- Handles font loading states

## Font Loading Implementation

### Frontend Font Loading

**Function**: `sarai_chinwag_enqueue_google_fonts()`
**Hook**: `wp_enqueue_scripts`

**Loading Strategy**:
- Only selected fonts loaded (not entire library)
- Google Fonts API v2 compatibility
- Multiple font weights: 400, 500, 600, 700
- `display=swap` for performance

**URL Format**:
```
https://fonts.googleapis.com/css2?family=FontName:wght@400;500;600;700&display=swap
```

### Editor Integration

#### Block Editor (Gutenberg)
**Function**: `sarai_chinwag_enqueue_block_editor_assets()`
**Hook**: `enqueue_block_editor_assets`

**Features**:
- Same fonts as frontend loaded in editor
- Real-time font updates in editor
- Consistent typography experience
- CSS custom properties integration

#### Classic Editor
**Function**: `sarai_chinwag_enqueue_admin_google_fonts()`  
**Hook**: `admin_enqueue_scripts`

**Scope**: Post edit pages (`post.php`, `post-new.php`)
**Integration**: Matches frontend font selections
**Typography**: Consistent editing experience

## CSS Implementation

### CSS Custom Properties

**Root Variables**: Defined in `/inc/assets/css/root.css`
```css
:root {
    --font-heading: [Selected Font], Helvetica, Arial, sans-serif;
    --font-body: [Selected Font], Helvetica, Arial, sans-serif;
}
```

**Dynamic Generation**: CSS file updated when customizer settings change
**File Location**: `/inc/assets/css/root.css`
**Update Triggers**: Customizer save, theme activation
**Asset Management**: Loaded via centralized system in `inc/core/assets.php`

### Font Family Implementation

**Function**: `sarai_chinwag_get_font_family($font_name)`

**Fallback Strategy**:
1. Selected Google Font (properly quoted for fonts with spaces)
2. Gluten (theme default, quoted)
3. Helvetica (system fallback, quoted)
4. Arial (secondary fallback, no quotes needed)
5. sans-serif (generic fallback, no quotes needed)

**Example Output**:
```css
font-family: 'Open Sans', 'Helvetica', Arial, sans-serif;
```

**Quoting**: Font names with spaces (like "Open Sans", "Playfair Display") are properly quoted to ensure CSS validity and consistent rendering across all browsers and editors.

## Performance Optimization

### Font Loading Performance

**Loading Strategy**:
- `font-display: swap` prevents invisible text
- Preconnect to Google Fonts domain
- Efficient font subset selection
- Cached font files by browser

**Network Optimization**:
- Single font request for multiple weights
- API response caching (24 hours)
- Conditional loading (only selected fonts)
- Fallback fonts prevent layout shift

### Caching Strategy

**Multi-Level Caching**:
1. Google Fonts API responses (24 hours)
2. Font selection choices (customizer)
3. CSS file generation (on-demand)
4. Browser font file caching (automatic)

## Font Management

### Font Selection Function

```php
sarai_chinwag_get_fonts_to_load()
```

**Purpose**: Determine which Google Fonts need loading
**Returns**: Array of font family names
**Usage**: Shared between frontend and editor loading

**Logic**:
- Excludes system fonts from loading
- Prevents duplicate font loading
- Maintains selection consistency

### Font Loading Control

**Conditional Loading**: Fonts only loaded when selected
**System Font Support**: Immediate rendering with system fonts
**Graceful Degradation**: Works without API key (system fonts only)
**Error Handling**: Fallback fonts when API fails

## Integration Features

### Editor Consistency

**Typography Matching**:
- Block Editor fonts match frontend exactly
- Classic Editor fonts match frontend exactly
- Font scaling preserved in editors
- Real-time updates in customizer preview

**User Experience**:
- WYSIWYG accuracy between editing and published content
- Consistent font experience across all interfaces
- No surprises when content published

### Theme Integration

**Color Scheme**: Font rendering adapts to theme colors
**Responsive Design**: Fonts scale properly across devices
**Accessibility**: Font choices maintain readability standards
**Performance**: Integration optimized for speed

## Troubleshooting

### Fonts Not Loading

**API Key Issues**:
1. Verify API key entered correctly in Theme Settings
2. Check API key has proper permissions
3. Confirm Google Fonts API is enabled in Google Console
4. Test API key with direct API request

**Network Issues**:
1. Check site can make external HTTP requests
2. Verify Google Fonts domain not blocked
3. Test from different networks/locations
4. Check for proxy or firewall restrictions

### Editor Font Issues

**Block Editor Problems**:
1. Clear browser cache and test
2. Check browser console for JavaScript errors
3. Verify CSS custom properties loading
4. Test with different fonts

**Classic Editor Problems**:
1. Confirm admin_enqueue_scripts hook firing
2. Check post edit page detection
3. Verify CSS loading in admin
4. Test font fallback behavior

### Customizer Issues

**Live Preview Problems**:
1. Check customizer JavaScript loading
2. Verify font loading in preview frame
3. Test with browser developer tools
4. Confirm CSS property updates

**Font Selection Issues**:
1. Verify API key configured properly
2. Check API response caching
3. Test with different font categories
4. Clear font cache if needed

## Advanced Usage

### Custom Font Categories

The system can be extended to support additional Google Fonts categories by modifying the API integration functions.

### Font Subset Control

Font loading can be optimized by specifying character subsets for international typography requirements.

### Performance Tuning

Font loading can be further optimized with preloading, resource hints, and advanced caching strategies based on site requirements.