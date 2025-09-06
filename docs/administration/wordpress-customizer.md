# WordPress Customizer Integration

The Sarai Chinwag theme provides extensive customization options through the WordPress Customizer with live preview functionality. Access via **Appearance → Customize**.

## Typography Section

### Heading Font Selection

**Location**: Appearance → Customize → Typography → Heading Font

**Functionality**:
- **Font Source**: Display category fonts from Google Fonts API
- **Default**: Gluten (theme default font)
- **Fallback Options**: System fonts if API unavailable
- **Live Preview**: Font changes visible in real-time during customization

**Font Categories**:
- Google Fonts Display category (optimized for headings)
- Theme default: Gluten
- System fallback: Helvetica, Arial, sans-serif

### Heading Font Size

**Location**: Appearance → Customize → Typography → Heading Font Size

**Configuration**:
- **Control Type**: Range slider (1-100%)
- **Default**: 50% (current theme size)
- **Scale**: Percentage-based scaling system
- **Live Preview**: Size changes update immediately

**Scaling Behavior**:
- 50% = baseline theme sizes (20px body, 1.75em h1)
- Values below 50% = smaller text
- Values above 50% = larger text
- All heading levels (h1-h6) scale proportionally

### Body Font Selection

**Location**: Appearance → Customize → Typography → Body Font

**Functionality**:
- **Font Sources**: Combined sans-serif and serif from Google Fonts API
- **Default**: System Fonts
- **Categories**: Sans-serif and serif fonts suitable for body text
- **Live Preview**: Font changes visible immediately

**Font Selection**:
- Google Fonts sans-serif category
- Google Fonts serif category  
- System fonts fallback
- Consistent typography between frontend and editors

### Body Font Size

**Location**: Appearance → Customize → Typography → Body Font Size

**Configuration**:
- **Control Type**: Range slider (1-100%)
- **Default**: 50% (current theme size) 
- **Responsive**: Maintains optimal reading size across devices
- **Live Preview**: Size updates in real-time

## Color Scheme Section

### Primary Color

**Location**: Appearance → Customize → Color Scheme → Primary Color

**Usage**:
- Buttons and links
- Main accent elements
- Active filter states
- Default: `#1fc5e2` (cyan blue)

### Secondary Color

**Location**: Appearance → Customize → Color Scheme → Secondary Color

**Usage**:
- Borders and highlights
- Secondary accent elements
- Tag badges and decorative elements
- Default: `#ff6eb1` (pink)

### Text Color

**Location**: Appearance → Customize → Color Scheme → Text Color

**Usage**:
- Main content text
- Paragraph and body text
- Default: `#000000` (black)

### Background Color

**Location**: Appearance → Customize → Color Scheme → Background Color

**Usage**:
- Site background
- Content area background
- Default: `#ffffff` (white)

### Header/Footer Background Color

**Location**: Appearance → Customize → Color Scheme → Header/Footer Background Color

**Usage**:
- Header section background
- Footer section background
- Navigation areas
- Default: `#000000` (black)

## Live Preview System

### Real-Time Updates

**Font Changes**:
- Typography updates instantly without page reload
- Font loading handled dynamically
- Fallback fonts maintain layout during loading

**Color Changes**:
- CSS custom properties update immediately
- All color-dependent elements reflect changes
- Consistent color application across components

### Preview JavaScript

**File**: `/js/customizer.js`

**Functionality**:
- Listens for customizer setting changes
- Updates CSS custom properties in real-time
- Handles font loading and fallback behavior
- Manages color scheme transitions

## Font Loading System

### Google Fonts API Integration

**Dynamic Loading**:
- Fonts loaded based on customizer selections
- Category-based filtering for appropriate font types
- Weight variations: 400, 500, 600, 700
- Display swap for performance

**Caching Strategy**:
- API responses cached for 24 hours
- Font selections cached in `sarai_chinwag_fonts` cache group
- Efficient loading prevents redundant requests

### Editor Integration

**Block Editor Support**:
- Selected fonts load automatically in Block Editor
- Consistent typography between editing and frontend
- CSS custom properties maintain scaling

**Classic Editor Support**:
- Font loading on post edit pages
- Editor-specific CSS targeting
- Matching font experience across editing modes

## CSS Implementation

### Root CSS Variables

**File**: `/css/root.css`

**Dynamic Generation**:
- CSS custom properties updated when settings change
- Centralized variable system for consistency
- Responsive typography using clamp() functions

**Key Variables**:
```css
:root {
    --font-heading: [Selected Font], Helvetica, Arial, sans-serif;
    --font-body: [Selected Font], Helvetica, Arial, sans-serif;
    --font-heading-scale: [Scale Value];
    --font-body-scale: [Scale Value];
    --color-primary: [Selected Color];
    --color-secondary: [Selected Color];
}
```

### Responsive Typography

**Mobile Optimization**:
- Specific breakpoints at 768px, 600px, 480px
- Clamp() functions for fluid scaling
- Optimized reading experience across devices

**Hierarchy Preservation**:
- All heading levels maintain proportional relationships
- Body text scales consistently
- Spacing scales with typography

## Performance Considerations

### Font Loading Optimization

**Selective Loading**:
- Only selected fonts loaded (not entire library)
- Font display: swap prevents invisible text
- Fallback fonts ensure immediate rendering

**Caching Strategy**:
- 24-hour cache for Google Fonts API responses
- CSS file updated only when settings change
- Efficient loading minimizes performance impact

### CSS Generation

**File-Based Approach**:
- CSS variables written to `/css/root.css`
- No inline styles in HTML
- Consistent styling across all page loads

**Update Triggers**:
- CSS file regenerated on customizer save
- Theme activation initializes CSS file
- Settings changes trigger immediate updates

## Troubleshooting

**Fonts Not Loading**:
1. Verify Google Fonts API key in Theme Settings
2. Check browser network requests for API calls
3. Confirm fallback fonts display correctly

**Live Preview Issues**:
1. Ensure JavaScript enabled in customizer
2. Check browser console for errors
3. Verify customizer.js loading correctly

**Color Changes Not Applied**:
1. Check CSS custom properties in browser dev tools
2. Verify color values in customizer
3. Clear any caching plugins

## Advanced Usage

### Custom Font Integration

The system supports extending font options by modifying the Google Fonts API integration in `/inc/admin/customizer.php`.

### CSS Variable Usage

Theme CSS extensively uses custom properties, making it easy to extend color and typography systems while maintaining consistency.