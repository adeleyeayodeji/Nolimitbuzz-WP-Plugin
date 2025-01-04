<?php

namespace Nolimitbuzz\Includes;

/**
 * Nolimitbuzz class
 *
 * @package Nolimitbuzz
 */

//check for security
if (!defined('ABSPATH')) {
    exit;
}

class Nolimitbuzz
{
    /**
     * Instance
     * 
     * @var Nolimitbuzz
     */
    private static $instance;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        //register post types
        add_action('init', array($this, 'register_post_types'));
        //register taxonomies
        add_action('init', array($this, 'register_taxonomies'));
        //register shortcode
        add_shortcode('portfolio', array($this, 'portfolio_shortcode'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        //register team member post type
        add_action('init', array($this, 'register_team_member_post_type'));
        //template redirect for team members
        add_action('template_redirect', array($this, 'template_redirect_for_team_members'));
        add_action('init', function () {
            flush_rewrite_rules();
        });
    }

    /** 
     * Get instance
     * 
     * @return Nolimitbuzz
     */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * template_redirect_for_team_members
     * 
     * @return void
     */
    public function template_redirect_for_team_members()
    {
        // Check if the current post type is 'team_member'
        if (is_singular('team_member')) {
            //get the team member
            $team_member = get_post(get_the_ID());
            //get the team members views
            echo $this->get_team_members_views($team_member);
            exit;
        }
    }

    /**
     * Get team members views
     *  
     * @param WP_Post $team_member
     * @return mixed
     */
    public function get_team_members_views($team_member)
    {
        ob_start();
        //get the team member
        require_once NO_LIMIT_BUZZ_PLUGIN_DIR . 'templates/team-members.php';
        return ob_get_clean();
    }

    /**
     * Enqueue styles
     * 
     * @return void
     */
    public function enqueue_styles()
    {
        //enqueue styles for the plugin
        wp_enqueue_style('nolimitbuzz-style-for-portfolio', NO_LIMIT_BUZZ_PLUGIN_URL . 'assets/css/style.css', array(), NO_LIMIT_BUZZ_PLUGIN_VERSION, 'all');
    }

    /**
     * Register post types
     * 
     * @return void
     */
    public function register_post_types()
    {
        //register portfolio post type
        register_post_type('portfolio', array(
            'labels' => array(
                'name' => __('Portfolios'),
                'singular_name' => __('Portfolio')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'rewrite' => array('slug' => 'portfolios'),
        ));
    }

    /**
     * Register taxonomies for portfolios
     * 
     * @return void
     */
    public function register_taxonomies()
    {
        //register portfolio category taxonomy
        register_taxonomy('portfolio_category', 'portfolio', array(
            'labels' => array(
                'name' => __('Portfolio Categories'),
                'singular_name' => __('Portfolio Category')
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'portfolio-category'),
        ));
    }

    /**
     * Register team member post type
     * 
     * @return void
     */
    public function register_team_member_post_type()
    {
        //register team member post type
        register_post_type('team_member', array(
            'labels' => array(
                'name' => __('Team Members'),
                'singular_name' => __('Team Members'),
                'add_new_item' => __('Add New Team Members'),
                'edit_item' => __('Edit Team Members'),
                'new_item' => __('New Team Members'),
                'view_item' => __('View Team Members'),
                'search_items' => __('Search Team Members'),
                'not_found' => __('No team members found'),
                'not_found_in_trash' => __('No team members found in trash'),
            ),
            'public' => true,
            'has_archive' => true,
            'show_ui' => true,
            'query_var' => true,
            'hierarchical' => false,
            'supports' => array('title', 'thumbnail'),
            'rewrite' => array('slug' => 'team-members'),
        ));
    }

    /**
     * Portfolio shortcode
     * 
     * @return string
     */
    public function portfolio_shortcode()
    {
        //args for the query
        $args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        //get all portfolios
        $query = new \WP_Query($args);
        $output = '';

        //check if there are any portfolios
        if ($query->have_posts()) {
            //get all portfolio categories
            $terms = get_terms(array(
                'taxonomy' => 'portfolio_category',
                'hide_empty' => false,
            ));

            //check if there are any portfolio categories
            if ($terms) {
                //loop through portfolio categories
                foreach ($terms as $term) {
                    $output .= '<div class="portfolio-group">';
                    $output .= '<h2>' . esc_html($term->name) . '</h2>';
                    $output .= '<div class="portfolio-cards">'; // Changed from <ul> to <div> for card layout

                    //loop through portfolios
                    while ($query->have_posts()) {
                        $query->the_post();
                        if (has_term($term->term_id, 'portfolio_category')) {
                            $output .= '<div class="portfolio-card">'; // Card container
                            $output .= '<div class="portfolio-thumbnail">' . get_the_post_thumbnail(null, 'full') . '</div>'; // Thumbnail
                            $output .= '<h3>' . get_the_title() . '</h3>'; // Card title
                            $output .= '<p>' . get_the_excerpt() . '</p>'; // Excerpt
                            $output .= '</div>'; // End of card
                        }
                    }

                    $output .= '</div>'; // End of portfolio-cards
                    $output .= '</div>'; // End of portfolio-group
                    //reset post data
                    wp_reset_postdata();
                }
            }
        }

        //check if empty
        if (empty($output)) {
            $output .= '<p>' . __('No portfolios found', 'nolimitbuzz') . '</p>';
        }

        return $output;
    }
}
