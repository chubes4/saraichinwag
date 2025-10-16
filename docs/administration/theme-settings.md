# Theme Settings

The Sarai Chinwag theme provides a centralized settings page accessible via **Settings → Theme Settings** in the WordPress admin. This page configures API integrations and controls core theme functionality.

## Settings Location

Navigate to **WordPress Admin → Settings → Theme Settings** to access all configuration options.

## API Configuration Settings

### IndexNow API Key

**Purpose**: Enables automatic search engine indexing when posts are published or updated.

**Configuration**:
- **Field Type**: Text input (50 characters)
- **Format**: 32-character hexadecimal string
- **Example**: `4ee5f0302df14ea9b2d2f5e9dd919fb0`
- **Optional**: Leave empty to disable IndexNow functionality

**How to Get API Key**:
1. Visit an IndexNow-compatible search engine (Bing, Yandex)
2. Generate a 32-character hexadecimal key
3. Verify the key with the search engine

### Google Fonts API Key

**Purpose**: Enables dynamic font loading from Google Fonts API in the WordPress Customizer.

**Configuration**:
- **Field Type**: Text input (50 characters)  
- **Required**: Must be configured to access Google Fonts
- **Get API Key**: Visit [Google Fonts Developer API](https://developers.google.com/fonts/docs/developer_api)

**Features Enabled**:
- Live font selection in Customizer
- Category-based font filtering (Display, Sans-serif, Serif)
- Real-time preview of font changes
- Consistent fonts between editor and frontend

### Pinterest Username

**Purpose**: Displays Pinterest follow widgets and enables Pinterest save buttons.

**Configuration**:
- **Field Type**: Text input (30 characters)
- **Format**: Username only (without URL)
- **Example**: If Pinterest URL is `pinterest.com/yourname`, enter `yourname`
- **Optional**: Leave empty to disable Pinterest integration

**Features Enabled**:
- Pinterest follow widget in sidebar
- Automatic Pinterest save buttons on images
- Enhanced social media connectivity

## Contact Form Settings

### Cloudflare Turnstile Site Key

**Purpose**: Enables bot protection for contact forms using Cloudflare Turnstile.

**Configuration**:
- **Field Type**: Text input (50 characters)
- **Required**: Must be configured for contact forms to function
- **Get Key**: Visit [Cloudflare Turnstile Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile)
- **Type**: Site key (public key)

**Features Enabled**:
- Bot protection on contact forms
- Spam prevention
- User verification without CAPTCHA complexity

### Cloudflare Turnstile Secret Key

**Purpose**: Server-side verification of Turnstile tokens.

**Configuration**:
- **Field Type**: Password input (50 characters)
- **Required**: Must be configured for contact forms to function
- **Security**: Stored securely in database
- **Type**: Secret key (private key)

**Technical Details**:
- Used for server-side token verification
- Never exposed to frontend
- Required for form processing

### Contact Form Recipient Email

**Purpose**: Sets the email address where contact form submissions are sent.

**Configuration**:
- **Field Type**: Email input
- **Default**: Site admin email (`admin_email` option)
- **Validation**: Must be valid email format
- **Required**: Must be configured for contact forms

**Email Features**:
- Receives formatted contact form submissions
- Includes submitter details and message
- Timestamp and IP address for moderation

### Send Copy to Submitter

**Purpose**: Controls whether confirmation emails are sent to form submitters.

**Configuration**:
- **Field Type**: Checkbox
- **Default**: Unchecked (no copy sent)
- **Optional**: Can be enabled per site preference

**Email Content**:
- Thank you message from site
- Copy of submitted message
- Professional confirmation format

## Theme Functionality Settings

### Disable Recipe Functionality

**Purpose**: Converts the theme from recipe-focused to universal blog theme.

**Configuration**:
- **Field Type**: Checkbox
- **Default**: Unchecked (recipes enabled)
- **Effect**: When enabled, completely disables all recipe-related features

**When Recipes Disabled**:
- Recipe post type becomes inaccessible in admin
- Recipe-specific templates and widgets hidden
- Default 5-star rating system disabled
- User rating system disabled
- Embedded Schema.org recipe markup removed
- Recipe filtering removed from archives
- Random recipe functionality redirects to random posts
- Theme operates as standard blog

**When Recipes Enabled** (Default):
- Full recipe post type functionality
- Recipe ratings and reviews
- Embedded Schema.org structured data
- Recipe-specific templates
- Recipe filtering in archives and search

## Settings Validation

The theme includes built-in validation for all settings:

### IndexNow API Key Validation
- Must be exactly 32 characters
- Must contain only hexadecimal characters (a-f, 0-9)
- Invalid keys display error message and revert to previous value

### Input Sanitization
- All text fields sanitized with `sanitize_text_field()`
- Checkbox values validated as boolean
- API keys stored securely in database options

## Settings Storage

All settings are stored as WordPress options:
- `sarai_chinwag_indexnow_key`
- `sarai_chinwag_google_fonts_api_key`  
- `sarai_chinwag_pinterest_username`
- `sarai_chinwag_disable_recipes`

## Helper Functions

### Check Recipe Status
```php
sarai_chinwag_recipes_disabled()
```
Returns `true` if recipes are disabled, `false` if enabled. Use this function in custom code to conditionally show recipe-related features.

## Settings Impact

Changes to these settings affect:

**Google Fonts API Key**:
- WordPress Customizer typography options
- Frontend font loading
- Editor font integration

**Pinterest Username**:
- Sidebar widget display
- Image save button functionality
- Social media integration

**Recipe Toggle**:
- Post type registration
- Template availability
- Archive filtering options
- Random content routing

## Troubleshooting

**Google Fonts Not Loading**:
1. Verify API key is correctly entered
2. Check API key has proper permissions
3. Ensure WordPress site can make external HTTP requests

**IndexNow Not Working**:
1. Confirm key format (32 hexadecimal characters)
2. Verify key is registered with search engine
3. Check for plugin conflicts with indexing

**Recipe Toggle Issues**:
- Changes take effect immediately
- Existing recipe posts remain accessible via direct URL when disabled
- Re-enabling recipes restores full functionality

## Contact Form Usage

### Embedding Contact Forms

**Shortcode**: Use `[sarai_contact_form]` to embed contact forms in any page or post
**Automatic Loading**: Scripts and styles load only when shortcode is present
**Multiple Forms**: Can use multiple contact forms on different pages

### Form Configuration Requirements

**Turnstile Setup**: Both site key and secret key must be configured
**Email Settings**: Recipient email must be set
**Testing**: Test forms after configuration to ensure proper functionality

### Troubleshooting Contact Forms

**Form Not Loading**:
1. Verify Turnstile keys are configured
2. Check that shortcode is properly formatted
3. Ensure page is published and accessible

**Emails Not Sending**:
1. Verify recipient email is configured
2. Check WordPress email configuration
3. Review server email logs

**Turnstile Errors**:
1. Confirm both site and secret keys are correct
2. Check Turnstile dashboard for key status
3. Verify domain configuration in Turnstile

**AJAX Issues**:
1. Check browser console for JavaScript errors
2. Verify nonce generation and validation
3. Test with different browsers