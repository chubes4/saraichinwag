<?php

function sarai_chinwag_wrap_ingredients($content) {
    $ingredients_pattern = '/(<h2[^>]*>Ingredients<\/h2>)(.*?)(<h2[^>]*>|<h3[^>]*>|<h4[^>]*>|<h5[^>]*>|<h6[^>]*>|<\/div>)/is';

    if (preg_match($ingredients_pattern, $content, $ingredients_matches)) {
        $ingredients_section = $ingredients_matches[2];

        // Use another regex to find each <li> within the ingredients section, including nested <li>
        $ingredients_section = preg_replace_callback(
            '/<li[^>]*>(.*?)<\/li>/is',
            function ($li_matches) {
                // If the <li> contains a nested <ul>, only wrap the outer <li> content
                if (strpos($li_matches[1], '<ul') !== false) {
                    return '<li><span itemprop="ingredients">' . preg_replace('/<ul[^>]*>/', '</span><ul>', $li_matches[1]) . '</li>';
                }
                return '<li><span itemprop="ingredients">' . $li_matches[1] . '</span></li>';
            },
            $ingredients_section
        );

        $content = str_replace($ingredients_matches[2], $ingredients_section, $content);
    }

    return $content;
}

function sarai_chinwag_wrap_description($content) {
    $description_pattern = '/<p>(.*?)<\/p>/is';

    if (preg_match($description_pattern, $content, $description_matches)) {
        $description = $description_matches[0];
        $content = str_replace($description, '<p itemprop="description">' . $description_matches[1] . '</p>', $content);
    }

    return $content;
}


function sarai_chinwag_wrap_total_time($content) {
    $total_time_pattern = '/<strong>Total Time:<\/strong>\s*(.*?)\s*(<br>|<\/p>|<\/div>|<\/strong>)/is';

    if (preg_match($total_time_pattern, $content, $total_time_matches)) {
        $total_time = $total_time_matches[1];
        $iso_total_time = sarai_chinwag_convert_to_iso8601_duration($total_time);
        $content = str_replace($total_time_matches[0], '<strong>Total Time:</strong> <span itemprop="totalTime" content="' . $iso_total_time . '">' . $total_time . '</span>' . $total_time_matches[2], $content);
    }

    return $content;
}

function sarai_chinwag_convert_to_iso8601_duration($time_string) {
    // Regex to capture hours, minutes, and seconds
    $time_pattern = '/(?:(\d+)\s*h(?:ours?)?)?\s*(?:(\d+)\s*m(?:inutes?)?)?\s*(?:(\d+)\s*s(?:econds?)?)?/i';
    if (preg_match($time_pattern, $time_string, $time_matches)) {
        $hours = isset($time_matches[1]) ? (int)$time_matches[1] : 0;
        $minutes = isset($time_matches[2]) ? (int)$time_matches[2] : 0;
        $seconds = isset($time_matches[3]) ? (int)$time_matches[3] : 0;

        $duration = 'PT';
        if ($hours > 0) {
            $duration .= $hours . 'H';
        }
        if ($minutes > 0) {
            $duration .= $minutes . 'M';
        }
        if ($seconds > 0) {
            $duration .= $seconds . 'S';
        }

        return $duration;
    }

    return 'PT0M'; // Default to 0 minutes if no match
}



function sarai_chinwag_wrap_yield($content) {
    $yield_pattern = '/<strong>Yield:<\/strong>\s*(.*?)\s*(<br>|<\/p>|<\/div>|<\/strong>)/is';

    if (preg_match($yield_pattern, $content, $yield_matches)) {
        $recipe_yield = $yield_matches[1];
        $content = str_replace($yield_matches[0], '<strong>Yield:</strong> <span itemprop="recipeYield">' . $recipe_yield . '</span>' . $yield_matches[2], $content);
    }

    return $content;
}

function sarai_chinwag_wrap_cuisine($content) {
    $cuisine_pattern = '/<strong>Cuisine:<\/strong>\s*(.*?)\s*(<br>|<\/p>|<\/div>|<\/strong>)/is';

    if (preg_match($cuisine_pattern, $content, $cuisine_matches)) {
        $recipe_cuisine = $cuisine_matches[1];
        $content = str_replace($cuisine_matches[0], '<strong>Cuisine:</strong> <span itemprop="recipeCuisine">' . $recipe_cuisine . '</span>' . $cuisine_matches[2], $content);
    }

    return $content;
}

function sarai_chinwag_wrap_nutrition($content) {
    $nutrition_pattern = '/<strong>Nutrition Information:<\/strong>\s*(.*?)\s*(<br>|<\/p>|<\/div>|<\/strong>)/is';

    if (preg_match($nutrition_pattern, $content, $nutrition_matches)) {
        $nutrition_info = $nutrition_matches[1];
        $content = str_replace($nutrition_matches[0], '<strong>Nutrition Information:</strong> <span itemprop="nutrition">' . $nutrition_info . '</span>' . $nutrition_matches[2], $content);
    }

    return $content;
}


function sarai_chinwag_wrap_images($content) {
    $image_pattern = '/<figure[^>]*>.*?<img[^>]*src=["\']([^"\']+)["\'][^>]*>.*?<\/figure>|<img[^>]*src=["\']([^"\']+)["\'][^>]*>/is';
    $image_count = 0;

    $content = preg_replace_callback(
        $image_pattern,
        function ($image_matches) use (&$image_count) {
            $image_count++;
            if ($image_count <= 6) {
                return str_replace('<img', '<img itemprop="image"', $image_matches[0]);
            }
            return $image_matches[0];
        },
        $content
    );

    return $content;
}

function sarai_chinwag_wrap_categories($content) {
    $categories_pattern = '/<a[^>]*rel=["\']category tag["\'][^>]*>(.*?)<\/a>/is';

    $content = preg_replace_callback(
        $categories_pattern,
        function ($matches) {
            return str_replace('<a', '<a itemprop="recipeCategory"', $matches[0]);
        },
        $content
    );

    return $content;
}


function sarai_chinwag_wrap_keywords($content) {
    $keywords_pattern = '/<a[^>]*rel=["\']tag["\'][^>]*>(.*?)<\/a>/is';

    $content = preg_replace_callback(
        $keywords_pattern,
        function ($matches) {
            return str_replace('<a', '<a itemprop="keywords"', $matches[0]);
        },
        $content
    );

    return $content;
}

function sarai_chinwag_wrap_author($content) {
    $author_name = 'Sarai Chinwag';

    // Add the author name schema markup
    $author_markup = '<div itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="' . esc_attr($author_name) . '"></div>';

    // Prepend or append the author markup to the content
    // Here, we prepend it to ensure it is included at the beginning of the content
    $content = $author_markup . $content;

    return $content;
}

function sarai_chinwag_wrap_instructions($content) {
    $instructions_pattern = '/(<h2[^>]*>Instructions<\/h2>)(<ol>.*?<\/ol>)/is';

    if (preg_match($instructions_pattern, $content, $instructions_matches)) {
        $instructions_section = $instructions_matches[2];

        // Use another regex to find each <li> within the instructions section
        $instructions_section = preg_replace_callback(
            '/<li[^>]*>(.*?)<\/li>/is',
            function ($li_matches) {
                // Extract the step text and remove any <strong> tags within <li> to avoid incorrect schema parsing
                $step_text = preg_replace('/<strong[^>]*>(.*?)<\/strong>/is', '$1', $li_matches[1]);
                return '<li itemprop="recipeInstructions" itemscope itemtype="http://schema.org/HowToStep"><span itemprop="text">' . $step_text . '</span></li>';
            },
            $instructions_section
        );

        $content = str_replace($instructions_matches[2], $instructions_section, $content);
    }

    return $content;
}




function sarai_chinwag_wrap_recipe_schema($content) {
    if (is_singular('recipe')) {
        $content = sarai_chinwag_wrap_ingredients($content);
        $content = sarai_chinwag_wrap_total_time($content);
        $content = sarai_chinwag_wrap_yield($content);
        $content = sarai_chinwag_wrap_images($content);
        $content = sarai_chinwag_wrap_description($content);
        $content = sarai_chinwag_wrap_cuisine($content); // Added wrapper for recipeCuisine
        $content = sarai_chinwag_wrap_nutrition($content); // Added wrapper for nutrition
        $content = sarai_chinwag_wrap_author($content); // Added wrapper for author
        $content = sarai_chinwag_wrap_instructions($content); // Added wrapper for instructions
    }

    return $content;
}

add_filter('the_content', 'sarai_chinwag_wrap_recipe_schema');




function extra_chill_recipe_schema() {
    if ( !is_singular('recipe') ) {
        return '';
    }

    global $post;

    $rating_value = get_post_meta($post->ID, 'rating_value', true);
    $review_count = get_post_meta($post->ID, 'review_count', true);

    // Set default values if not provided
    $rating_value = $rating_value ? floatval($rating_value) : 0;
    $review_count = $review_count ? intval($review_count) : 0;

    // Ensure rating is within 1-5 range and at least one review exists
    if ($review_count > 0 && $rating_value >= 1 && $rating_value <= 5) {
        $average_rating = round($rating_value, 2);
        $rating_display = "($average_rating/5 based on $review_count reviews)";
    } else {
        // Return empty if the rating is not within the valid range
        return [
            'output' => '',
            'rating_display' => "(Not yet rated)"
        ];
    }

    ob_start();
    ?>
    <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?php echo esc_attr($average_rating); ?>" />
        <meta itemprop="reviewCount" content="<?php echo esc_attr($review_count); ?>" />
    </div>
    <?php

    $output = ob_get_clean();
    return [
        'output' => $output,
        'rating_display' => $rating_display
    ];
}

