# Font Scaling System

The Sarai Chinwag theme implements a comprehensive percentage-based font scaling system that maintains typographic hierarchy while providing flexible size control across all devices.

## Scaling System Overview

### Percentage-Based Scaling

**Scale Range**: 1-100% with 50% as baseline
**Baseline Reference**: 50% = current theme typography sizes
**Control Type**: Range slider with real-time preview
**Implementation**: CSS custom properties with clamp() functions

### Dual Scaling Controls

#### Heading Font Size
- **Location**: Appearance → Customize → Typography → Heading Font Size
- **Range**: 1-100%
- **Default**: 50% (baseline)
- **Effect**: Scales all heading levels (h1-h6) proportionally

#### Body Font Size
- **Location**: Appearance → Customize → Typography → Body Font Size
- **Range**: 1-100%
- **Default**: 50% (baseline)  
- **Effect**: Scales body text, paragraphs, and content text

## Baseline Typography Sizes

### 50% Baseline Reference

**Base Font Size**: 20px (1.25rem)
**Heading Hierarchy**:
- h1: 1.575em (31.5px at baseline)
- h2: 1.38em (27.6px at baseline)
- h3: 1.2em (24px at baseline)
- h4-h6: Proportionally scaled

**Body Typography**:
- Paragraph: 20px base size
- Small text: 0.85em
- Navigation: Proportional to base

### Scale Calculations

**Heading Scale Formula**:
```css
--font-heading-scale: [slider-value] / 50
```

**Body Scale Formula**:
```css
--font-body-scale: [slider-value] / 50
```

**Applied Scaling**:
- 25% setting = 0.5 scale (half size)
- 50% setting = 1.0 scale (baseline)
- 75% setting = 1.5 scale (1.5x size)
- 100% setting = 2.0 scale (double size)

## Responsive Typography Implementation

### CSS Custom Properties

**Root Variables** (in `/css/root.css`):
```css
:root {
    --font-heading-scale: 1.0;
    --font-body-scale: 1.0;
    --font-size-base: 1.25rem;
    --font-size-h1: 1.575em;
    --font-size-h2: 1.38em;
}
```

### Fluid Typography with Clamp()

**Responsive Scaling**: Uses `clamp()` functions for optimal display
**Breakpoints**: Specific optimizations at 768px, 600px, 480px
**Mobile Priority**: Enhanced mobile typography scaling

**Example Implementation**:
```css
h1 {
    font-size: clamp(
        calc(var(--font-size-h1) * var(--font-heading-scale) * 0.8),
        calc(var(--font-size-h1) * var(--font-heading-scale)),
        calc(var(--font-size-h1) * var(--font-heading-scale) * 1.2)
    );
}
```

## Hierarchy Preservation

### Proportional Scaling

**Maintained Relationships**: All heading levels maintain proportional relationships
**Visual Hierarchy**: Clear distinction between heading levels at all scales
**Content Readability**: Optimal reading sizes maintained across scales

### Typography Harmony

**Font Pairing**: Heading and body scaling work harmoniously
**Line Height**: Line heights scale appropriately with font sizes
**Spacing**: Margins and padding scale with typography
**Visual Balance**: Consistent visual weight across all scales

## Live Preview Integration

### Real-Time Updates

**Customizer Preview**: Changes visible immediately without page reload
**JavaScript File**: `/js/customizer.js`
**CSS Property Updates**: Dynamic custom property modification

**Implementation**:
```javascript
// Listen for font size changes
wp.customize('sarai_chinwag_heading_font_size', function(setting) {
    setting.bind(function(value) {
        const scale = value / 50;
        document.documentElement.style.setProperty('--font-heading-scale', scale);
    });
});
```

### Preview Accuracy

**Consistent Experience**: Preview matches published site exactly
**Cross-Device Testing**: Responsive scaling visible in customizer
**Immediate Feedback**: Users see exact results of their changes

## Editor Integration

### Block Editor Scaling

**Editor Fonts**: Block Editor typography scales with customizer settings
**Consistency**: Editing experience matches frontend display
**Real-Time Updates**: Editor typography updates when customizer changes saved

**Implementation**: CSS custom properties loaded in Block Editor
**Selectors**: `.editor-styles-wrapper`, `.wp-block-editor`
**Scaling**: Same scale factors applied to editor content

### Classic Editor Scaling

**Post Edit Pages**: Typography scaling applied to Classic Editor
**Visual Consistency**: Editor content matches frontend typography
**Font Integration**: Combined with Google Fonts loading

## Responsive Breakpoint Optimization

### Mobile Typography

**Mobile Breakpoints**:
- 480px: Minimal scaling for readability
- 600px: Intermediate responsive scaling
- 768px: Full responsive scaling

**Mobile Considerations**:
- Smaller screens limit maximum effective scaling
- Touch-friendly sizing maintained
- Readability optimized for mobile viewing

### Desktop Typography

**Large Screen Optimization**:
- Full scaling range available
- Maximum typography impact
- Optimal reading distances supported

**Wide Screen Adaptation**:
- Typography scales effectively on large displays
- Line lengths maintained for readability
- Visual hierarchy preserved at all sizes

## Performance Considerations

### CSS Generation Efficiency

**File-Based Approach**: CSS custom properties written to `/css/root.css`
**Update Triggers**: File regenerated only when settings change
**No Inline Styles**: Clean HTML without style attributes
**Caching Friendly**: Static file can be cached effectively

### Browser Performance

**CSS Custom Property Support**: Modern browser optimization
**Fallback Strategy**: Graceful degradation for older browsers
**Render Performance**: Efficient CSS property updates
**Layout Stability**: Minimal layout shifting during scale changes

## Accessibility Features

### Readability Standards

**Minimum Sizes**: Lower scaling limits maintain readability
**High Contrast**: Typography scaling works with theme colors
**User Preferences**: Respects system accessibility settings
**Screen Reader**: Proper heading hierarchy maintained

### Visual Accessibility

**Clear Hierarchy**: Heading relationships preserved at all scales
**Sufficient Contrast**: Text remains readable at all sizes
**Focus Indicators**: Proper focus states maintained
**Touch Targets**: Interactive elements maintain proper sizes

## Integration with Theme Systems

### Color Integration

**Typography Colors**: Scaling works with customizer color system
**Color Contrast**: Maintained across all typography scales
**Visual Harmony**: Typography and colors work together

### Layout Integration

**Grid Systems**: Typography scaling adapts to layout grids
**Spacing Systems**: Margins and padding scale appropriately
**Responsive Design**: Typography scaling enhances responsive layouts

## Troubleshooting

### Scaling Not Applied

**Common Issues**:
1. Check CSS custom properties loading
2. Verify customizer settings saving correctly
3. Clear browser cache
4. Test with browser developer tools

**Debug Steps**:
1. Inspect CSS custom property values
2. Check `/css/root.css` file updates
3. Verify JavaScript customizer integration
4. Test live preview functionality

### Editor Scaling Issues

**Block Editor Problems**:
1. Verify editor CSS loading
2. Check custom property application
3. Clear block editor cache
4. Test with different blocks

**Classic Editor Problems**:
1. Confirm editor CSS enqueuing
2. Check admin page CSS loading
3. Verify scaling variables available
4. Test with different post types

### Mobile Scaling Issues

**Responsive Problems**:
1. Check mobile breakpoint CSS
2. Verify clamp() function support
3. Test on actual devices
4. Confirm responsive scaling calculations

## Advanced Usage

### Custom Scale Ranges

The scaling system can be modified to support different percentage ranges or scale factors while maintaining the proportional relationships.

### Additional Typography Elements

The scaling system can be extended to include additional typography elements like buttons, form fields, or navigation items.

### Performance Optimization

Scaling calculations can be optimized for specific use cases or performance requirements while maintaining the visual hierarchy.