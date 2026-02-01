<?php
/**
 * 404 Error Page Template
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

<div class="content-wrap" role="main" lang="en" itemscope itemtype="https://schema.org/WebPage">
    <meta itemprop="inLanguage" content="en">
    <main id="primary" class="site-main error-404">
        <header class="entry-header">
            <h1 class="entry-title">Page Not Found</h1>
        </header>

        <div class="entry-content">
            <p>Oops! The page you're looking for seems to have wandered off. Maybe it's exploring the spiritual meaning of getting lost? 🦋</p>
            
            <div class="error-search">
                <h2>Try searching:</h2>
                <?php get_search_form(); ?>
            </div>

            <div class="error-suggestions">
                <h2>Or explore something random:</h2>
                <p>
                    <a href="<?php echo esc_url(home_url('/random-all')); ?>" class="button">
                        ✨ Surprise Me
                    </a>
                </p>
            </div>

            <div class="error-popular">
                <h2>Popular reads:</h2>
                <ul>
                    <?php
                    $popular_posts = new WP_Query(array(
                        'posts_per_page' => 5,
                        'orderby' => 'comment_count',
                        'order' => 'DESC',
                        'post_status' => 'publish'
                    ));
                    
                    if ($popular_posts->have_posts()) :
                        while ($popular_posts->have_posts()) : $popular_posts->the_post();
                            echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </main>
</div>

<style>
.error-404 {
    text-align: center;
    padding: 2rem;
    max-width: 600px;
    margin: 0 auto;
}

.error-404 .entry-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.error-404 .entry-content p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.error-404 h2 {
    font-size: 1.3rem;
    margin: 2rem 0 1rem;
}

.error-404 .error-search {
    margin: 2rem 0;
}

.error-404 .searchform {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.error-404 .searchform input[type="text"] {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    border: 2px solid #ddd;
    border-radius: 4px;
    width: 250px;
}

.error-404 .searchform input[type="submit"] {
    padding: 0.5rem 1.5rem;
    font-size: 1rem;
    background: #333;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.error-404 .button {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: #333;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 1rem;
}

.error-404 .button:hover {
    background: #555;
}

.error-404 .error-popular ul {
    list-style: none;
    padding: 0;
    text-align: left;
    max-width: 400px;
    margin: 0 auto;
}

.error-404 .error-popular li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.error-404 .error-popular a {
    color: #333;
    text-decoration: none;
}

.error-404 .error-popular a:hover {
    text-decoration: underline;
}
</style>

<?php
get_footer();
