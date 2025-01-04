<?php
//check for security
if (!defined('ABSPATH')) {
    exit;
}

//get the header
get_header();

?>

<article id="post-<?php echo $team_member->ID; ?>" <?php post_class("no-limit-buzz--post"); ?>>
    <header class="no-limit-buzz--post-header">
        <img src="<?php echo get_the_post_thumbnail_url($team_member->ID); ?>" alt="<?php echo $team_member->post_title; ?>">
    </header><!-- .entry-header -->

    <div class="no-limit-buzz--post-content">
        <div class="no-limit-buzz--post-content-title">
            <h1><?php echo $team_member->post_title; ?></h1>
        </div>
        <hr>
        <div class="no-limit-buzz--post-content-text">
            <?php
            //get ACF Fields
            $fields = get_fields($team_member->ID);
            //field
            $field = $fields['team_members'];
            //position
            $position = $field['position'];
            //linkedin profile
            $linkedin_profile = $field['linkedin_profile'];
            //profile picture
            $profile_picture = $field['profile_picture'];
            ?>
            <div class="no-limit-buzz--post-content-profile-data">
                <img src="<?php echo esc_url($profile_picture); ?>" alt="<?php echo esc_attr($team_member->post_title); ?>">
                <h4><?php echo esc_html($position); ?></h4>
                <a href="<?php echo esc_url($linkedin_profile); ?>" target="_blank">LinkedIn</a>
            </div>
        </div>
    </div><!-- .entry-content -->

    <footer class="no-limit-buzz--post-footer">
        <a href="javascript:void(0)" class="no-limit-buzz--post-footer-button">
            <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 41.8334C32.5 41.8334 41.8333 32.5 41.8333 21C41.8333 9.50004 32.5 0.166702 21 0.166702C9.49996 0.166702 0.166626 9.50004 0.166626 21C0.166626 32.5 9.49996 41.8334 21 41.8334ZM23.0833 21V29.3334H18.9166V21H12.6666L21 12.6667L29.3333 21H23.0833Z" fill="#662BC5" />
            </svg>
        </a>
    </footer><!-- .entry-footer -->
</article>

<?php

//get the footer
get_footer();
