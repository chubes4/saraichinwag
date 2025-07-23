<?php
/**
 * WordPress Customizer integration for fonts and colors
 *
 * @package Sarai_Chinwag
 */

/**
 * Add customizer sections and controls
 */
function sarai_chinwag_customize_register($wp_customize) {
    
    // Typography Section
    $wp_customize->add_section('sarai_chinwag_typography', array(
        'title' => __('Typography', 'sarai-chinwag'),
        'description' => __('Customize fonts for your site.', 'sarai-chinwag'),
        'priority' => 30,
    ));

    // Color Scheme Section
    $wp_customize->add_section('sarai_chinwag_colors', array(
        'title' => __('Color Scheme', 'sarai-chinwag'),
        'description' => __('Customize colors for your site.', 'sarai-chinwag'),
        'priority' => 40,
    ));

    // Heading Font Control
    $wp_customize->add_setting('sarai_chinwag_heading_font', array(
        'default' => 'Gluten',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('sarai_chinwag_heading_font', array(
        'label' => __('Heading Font', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_typography',
        'type' => 'select',
        'choices' => sarai_chinwag_get_google_fonts('display'),
        'description' => __('Choose a display font for headings (h1-h6).', 'sarai-chinwag'),
    ));

    // Heading Font Size Control
    $wp_customize->add_setting('sarai_chinwag_heading_font_size', array(
        'default' => 50,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('sarai_chinwag_heading_font_size', array(
        'label' => __('Heading Font Size', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_typography',
        'type' => 'range',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
        'description' => __('Scale heading sizes (50% = current theme size).', 'sarai-chinwag'),
    ));

    // Body Font Control
    $wp_customize->add_setting('sarai_chinwag_body_font', array(
        'default' => 'System Fonts',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('sarai_chinwag_body_font', array(
        'label' => __('Body Font', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_typography',
        'type' => 'select',
        'choices' => sarai_chinwag_get_google_fonts('body'),
        'description' => __('Choose a font for body text and paragraphs.', 'sarai-chinwag'),
    ));

    // Body Font Size Control
    $wp_customize->add_setting('sarai_chinwag_body_font_size', array(
        'default' => 50,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('sarai_chinwag_body_font_size', array(
        'label' => __('Body Font Size', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_typography',
        'type' => 'range',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
        'description' => __('Scale body text size (50% = current theme size).', 'sarai-chinwag'),
    ));


    // Primary Color Control
    $wp_customize->add_setting('sarai_chinwag_primary_color', array(
        'default' => '#1fc5e2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sarai_chinwag_primary_color', array(
        'label' => __('Primary Color', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_colors',
        'description' => __('Used for buttons, links, and main accents.', 'sarai-chinwag'),
    )));

    // Secondary Color Control
    $wp_customize->add_setting('sarai_chinwag_secondary_color', array(
        'default' => '#ff6eb1',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sarai_chinwag_secondary_color', array(
        'label' => __('Secondary Color', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_colors',
        'description' => __('Used for borders, highlights, and secondary accents.', 'sarai-chinwag'),
    )));

    // Text Color Control
    $wp_customize->add_setting('sarai_chinwag_text_color', array(
        'default' => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sarai_chinwag_text_color', array(
        'label' => __('Text Color', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_colors',
        'description' => __('Main text color for content.', 'sarai-chinwag'),
    )));

    // Background Color Control
    $wp_customize->add_setting('sarai_chinwag_background_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sarai_chinwag_background_color', array(
        'label' => __('Background Color', 'sarai-chinwag'),
        'section' => 'sarai_chinwag_colors',
        'description' => __('Main background color for the site.', 'sarai-chinwag'),
    )));
}
add_action('customize_register', 'sarai_chinwag_customize_register');

/**
 * Fetch Google Fonts from API by category
 */
function sarai_chinwag_fetch_google_fonts_by_category($category = '') {
    $api_key = get_option('sarai_chinwag_google_fonts_api_key', '');
    
    if (empty($api_key)) {
        return array();
    }
    
    $transient_key = 'sarai_chinwag_google_fonts_' . $category;
    $fonts = get_transient($transient_key);
    
    if (false === $fonts) {
        $api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $api_key . '&sort=popularity';
        
        if (!empty($category)) {
            $api_url .= '&category=' . urlencode($category);
        }
        
        $response = wp_remote_get($api_url);
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return array();
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        $fonts = array();
        
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $font) {
                $fonts[$font['family']] = $font['family'];
            }
        }
        
        set_transient($transient_key, $fonts, DAY_IN_SECONDS);
    }
    
    return $fonts;
}

/**
 * Get fonts for customizer dropdowns with fallback options
 */
function sarai_chinwag_get_google_fonts($type = '') {
    $fonts = array();
    
    // Add theme fallback fonts first
    $fallback_fonts = array(
        'Gluten' => 'Gluten (Theme)',
        'System Fonts' => 'System Fonts'
    );
    
    if ($type === 'display') {
        // Header fonts: Display category only
        $api_fonts = sarai_chinwag_fetch_google_fonts_by_category('display');
    } elseif ($type === 'body') {
        // Body fonts: Combine sans-serif and serif
        $sans_serif_fonts = sarai_chinwag_fetch_google_fonts_by_category('sans-serif');
        $serif_fonts = sarai_chinwag_fetch_google_fonts_by_category('serif');
        $api_fonts = array_merge($sans_serif_fonts, $serif_fonts);
    } else {
        $api_fonts = array();
    }
    
    // If API call failed, return just fallback fonts
    if (empty($api_fonts)) {
        return $fallback_fonts;
    }
    
    // Merge fallback fonts with API fonts
    return array_merge($fallback_fonts, $api_fonts);
}

/**
 * Enqueue Google Fonts based on customizer selections
 */
function sarai_chinwag_enqueue_google_fonts() {
    // Get only the 2 font settings
    $fonts_to_check = array(
        get_theme_mod('sarai_chinwag_heading_font', 'Gluten'),
        get_theme_mod('sarai_chinwag_body_font', 'System Fonts')
    );
    
    $fonts_to_load = array();
    
    // Check each font and add to load list if it's a Google Font
    foreach ($fonts_to_check as $font) {
        if ($font !== 'Gluten' && $font !== 'System Fonts' && !in_array($font, $fonts_to_load)) {
            $fonts_to_load[] = $font;
        }
    }
    
    // Enqueue Google Fonts if any are selected
    if (!empty($fonts_to_load)) {
        $fonts_url = 'https://fonts.googleapis.com/css2?';
        foreach ($fonts_to_load as $font) {
            $fonts_url .= 'family=' . str_replace(' ', '+', $font) . ':wght@400;500;600;700&';
        }
        $fonts_url .= 'display=swap';
        
        wp_enqueue_style('sarai-chinwag-google-fonts', $fonts_url, array(), null);
    }
}
add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_google_fonts');

/**
 * Generate custom CSS from customizer values
 */
function sarai_chinwag_customizer_css() {
    $heading_font = get_theme_mod('sarai_chinwag_heading_font', 'Gluten');
    $body_font = get_theme_mod('sarai_chinwag_body_font', 'System Fonts');
    $heading_font_size = get_theme_mod('sarai_chinwag_heading_font_size', 50);
    $body_font_size = get_theme_mod('sarai_chinwag_body_font_size', 50);
    $primary_color = get_theme_mod('sarai_chinwag_primary_color', '#1fc5e2');
    $secondary_color = get_theme_mod('sarai_chinwag_secondary_color', '#ff6eb1');
    $text_color = get_theme_mod('sarai_chinwag_text_color', '#000000');
    $background_color = get_theme_mod('sarai_chinwag_background_color', '#ffffff');
    
    // Generate font family values
    $heading_font_family = sarai_chinwag_get_font_family($heading_font);
    $body_font_family = sarai_chinwag_get_font_family($body_font);
    
    // Convert percentage to scale (50% = 1.0, 100% = 2.0, 1% = 0.02)
    $heading_scale = $heading_font_size / 50;
    $body_scale = $body_font_size / 50;
    
    $css = "
    :root {
        --font-heading: {$heading_font_family};
        --font-body: {$body_font_family};
        --font-heading-scale: {$heading_scale};
        --font-body-scale: {$body_scale};
        --color-primary: {$primary_color};
        --color-secondary: {$secondary_color};
        --color-text: {$text_color};
        --color-background: {$background_color};
    }
    
    /* Scale body font size (current: 20px at 50%) */
    body {
        font-size: calc(20px * var(--font-body-scale));
    }
    
    /* Scale heading sizes while maintaining hierarchy */
    h1.entry-title {
        font-size: calc(1.75em * var(--font-heading-scale));
    }
    h2 {
        font-size: calc(1.38em * var(--font-heading-scale));
    }
    
    /* Scale responsive heading sizes */
    @media (max-width: 768px) {
        h1.entry-title {
            font-size: calc(1.5em * var(--font-heading-scale));
        }
        h2.entry-title, .related-item h4 {
            font-size: calc(1.1em * var(--font-heading-scale));
        }
        h1.page-title {
            font-size: calc(22px * var(--font-heading-scale));
        }
    }
    
    @media (max-width: 600px) {
        body {
            font-size: calc(22px * var(--font-body-scale));
        }
        h1.page-title {
            font-size: calc(24px * var(--font-heading-scale));
        }
    }
    
    @media (max-width: 480px) {
        body {
            font-size: calc(25px * var(--font-body-scale));
        }
        h1.page-title {
            font-size: calc(30px * var(--font-heading-scale));
        }
        h2.entry-title {
            font-size: calc(1.25em * var(--font-heading-scale));
        }
    }
    ";
    
    return $css;
}

/**
 * Get font family CSS value with simple fallback strategy
 */
function sarai_chinwag_get_font_family($font_name) {
    switch ($font_name) {
        case 'Gluten':
            return "'Gluten', serif";
        case 'System Fonts':
            return "'Helvetica', Arial, sans-serif";
        default:
            // Simple fallback: Google Font → Gluten → System
            return "'{$font_name}', 'Gluten', 'Helvetica', Arial, sans-serif";
    }
}

/**
 * Output customizer CSS
 */
function sarai_chinwag_output_customizer_css() {
    wp_add_inline_style('sarai-chinwag-style', sarai_chinwag_customizer_css());
}
add_action('wp_enqueue_scripts', 'sarai_chinwag_output_customizer_css');

/**
 * Enqueue customizer preview script
 */
function sarai_chinwag_customize_preview_js() {
    wp_enqueue_script('sarai-chinwag-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), '1.0.0', true);
}
add_action('customize_preview_init', 'sarai_chinwag_customize_preview_js');
?>