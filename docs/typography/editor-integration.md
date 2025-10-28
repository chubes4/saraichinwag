# Editor Integration

The Sarai Chinwag theme provides seamless typography integration between WordPress editors and the frontend, ensuring content creators see exactly how their content will appear to visitors.

## Editor Typography System

### WYSIWYG Consistency

**Core Principle**: What You See Is What You Get - editors match frontend exactly
**Implementation**: Same Google Fonts and scaling loaded in editors
**User Experience**: Eliminates guesswork about final post appearance
**Professional Workflow**: Consistent editing environment matching site design

### Supported Editors

#### Block Editor (Gutenberg)
- **Full Integration**: Complete font and scaling system
- **Live Updates**: Typography changes reflect immediately in editor
- **Block Compatibility**: Works with all WordPress blocks
- **Real-Time Preview**: Customizer changes appear in editor

#### Classic Editor
- **Legacy Support**: Full typography integration maintained
- **Post Edit Pages**: Font loading on post edit screens
- **Consistent Experience**: Matches Block Editor functionality
- **Migration Ready**: Seamless transition between editors

## Block Editor Integration

### Font Loading System

**Function**: `sarai_chinwag_enqueue_block_editor_assets()`
**Hook**: `enqueue_block_editor_assets`
**Priority**: Loads after root CSS for proper cascading

**Loading Sequence**:
1. Root CSS with custom properties loaded first
2. Google Fonts loaded based on customizer selections
3. Editor-specific CSS applied for WordPress compatibility

### Block Editor CSS Implementation

**Target Selectors**:
- `.editor-styles-wrapper` - Main editor container
- `.wp-block-editor` - Block editor interface
- `.wp-block-*` - Individual block styling

**Custom Properties Integration**:
```css
.editor-styles-wrapper {
    font-family: var(--font-body);
    font-size: calc(var(--font-size-base) * var(--font-body-scale));
}
```

### Block-Specific Typography

**Heading Blocks**: Use heading font and scaling from customizer with font-weight: 600
**Paragraph Blocks**: Use body font and scaling from customizer with font-weight: 400 and line-height: 1.9
**List Blocks**: Inherit body typography settings with font-weight: 400 and line-height: 1.9
**Quote Blocks**: Enhanced typography with proper scaling and body font weight

**Implementation**: CSS custom properties ensure consistent styling with explicit font-weight and line-height declarations
**Body Font Consistency**: Matches frontend with font-weight: 400 (normal) and line-height: 1.9
**WordPress Block Targeting**: Includes specific selectors for `.wp-block-paragraph`, `.wp-block-list`, `.wp-block-quote`
**Responsive**: Editor typography scales with customizer settings
**Live Updates**: Changes in customizer immediately visible in editor

## Classic Editor Integration

### Editor Page Detection

**Function**: `sarai_chinwag_enqueue_admin_google_fonts($hook)`
**Hook**: `admin_enqueue_scripts`
**Target Pages**: `post.php`, `post-new.php`

**Conditional Loading**:
- Fonts loaded only on post edit pages
- Efficient loading prevents unnecessary requests
- Performance optimized for admin interface

### Classic Editor CSS Targeting

**Editor Container**: Targets Classic Editor content area
**TinyMCE Integration**: Works with visual editor mode
**Text Editor**: Consistent fonts in text editor mode
**Preview Mode**: Accurate preview with correct typography

**CSS Implementation**:
```css
#postdivrich .wp-editor-area {
    font-family: var(--font-body);
    font-size: calc(var(--font-size-base) * var(--font-body-scale));
}
```

## Editor CSS File System

### Root CSS Integration

**File**: `/inc/assets/css/root.css`
**Loading Priority**: Highest priority to establish CSS custom properties
**Editor Loading**: Same file loaded in editors and frontend
**Consistency**: Ensures identical typography across all contexts

### Editor-Specific CSS

**File**: `/inc/assets/css/editor.css`
**Purpose**: WordPress editor-specific styling adjustments with body font weight and line-height
**Integration**: Works with root CSS custom properties
**Scope**: Editor interface only, not frontend
**Loading**: Block Editor via `enqueue_block_editor_assets` hook in functions.php, Classic Editor via `admin_enqueue_scripts` in customizer.php

### Dynamic CSS Generation

**Update Triggers**: CSS regenerated when customizer settings change
**Real-Time**: Editor typography updates immediately after customizer save
**Performance**: File-based approach for efficient loading
**Caching**: Static files can be cached effectively

## Font Selection Integration

### Shared Font Loading

**Function**: `sarai_chinwag_get_fonts_to_load()`
**Usage**: Shared between frontend and editor font loading
**Consistency**: Identical font selection across all contexts
**Efficiency**: Single source of truth for font selections

### Google Fonts API Integration

**Editor Loading**: Same Google Fonts API integration as frontend
**Font Categories**: Same display/body font categorization
**Weights**: Same font weights (400, 500, 600, 700) loaded
**Performance**: `display=swap` for editor font loading

## Font Scaling Integration

### Real-Time Scaling Updates

**Customizer Integration**: Editor typography updates when scaling changes
**CSS Properties**: Same scaling variables used in editors
**Live Preview**: Scaling changes visible in editor during customization
**Consistency**: Identical scaling behavior across all contexts

### Responsive Scaling

**Editor Viewport**: Editor typography adapts to editor window size
**Mobile Preview**: Block Editor mobile preview shows correct scaling
**Clamp Functions**: Same responsive typography calculations
**Breakpoints**: Editor respects mobile/tablet/desktop breakpoints

## Performance Optimization

### Conditional Loading

**Smart Enqueuing**: Fonts loaded only when editors are active
**Page Detection**: Accurate detection of editor pages
**Resource Efficiency**: Prevents unnecessary font loading
**Admin Performance**: Optimized admin interface loading

### Caching Strategy

**Font Caching**: Google Fonts cached by browser for editor use
**CSS Caching**: Editor CSS files cached for performance
**API Caching**: Font API responses shared between frontend and editor
**Update Efficiency**: Only reload when settings actually change

## User Experience Benefits

### Content Creation Workflow

**Accurate Previewing**: Content creators see exact final appearance
**Design Confidence**: No surprises when content is published
**Professional Environment**: Consistent branding throughout editing
**Reduced Iterations**: Less need to preview during content creation

### Typography Consistency

**Brand Consistency**: Site typography maintained during editing
**Visual Hierarchy**: Proper heading hierarchy visible during editing
**Reading Experience**: Accurate line lengths and spacing in editor
**Color Integration**: Typography works with theme color schemes

## Troubleshooting

### Editor Fonts Not Loading

**Common Issues**:
1. Check Google Fonts API key in Theme Settings
2. Verify editor page detection working
3. Confirm CSS files loading in admin
4. Check browser console for errors

**Debug Steps**:
1. Test with browser developer tools in editor
2. Check network requests for Google Fonts
3. Verify CSS custom properties in editor
4. Test with different post types

### Scaling Issues in Editor

**Editor Scaling Problems**:
1. Check CSS custom property loading
2. Verify scaling variables in editor CSS
3. Test customizer live preview in editor
4. Clear browser cache and retry

**Block Editor Specific**:
1. Check editor styles wrapper targeting
2. Verify block-specific CSS rules
3. Test with different block types
4. Confirm custom properties cascading

### Classic Editor Issues

**TinyMCE Problems**:
1. Verify Classic Editor CSS loading
2. Check editor content area targeting
3. Test visual vs text editor modes
4. Confirm font fallback behavior

## Advanced Usage

### Custom Editor Styling

The editor integration system can be extended with custom CSS rules while maintaining typography consistency.

### Plugin Compatibility

Editor typography integration works with most WordPress plugins and can be adapted for specific plugin requirements.

### Multi-Site Integration

Editor typography settings work consistently across WordPress multisite installations while respecting individual site customizations.