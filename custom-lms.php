<?php
/*
Plugin Name: LMS
Description: A custom LMS plugin for managing courses, quizzes, grading, and student progress tracking.
Version: 1.0
Author: Mohammed Numan Raza
License: GPL2
*/


if (!defined('ABSPATH')) {
    exit;
}

// Register Course Post Type
function custom_lms_register_post_types() {
    $course_labels = array(
        'name' => 'Courses',
        'singular_name' => 'Course',
        'add_new' => 'Add New Course',
        'add_new_item' => 'Add New Course',
        'edit_item' => 'Edit Course',
        'new_item' => 'New Course',
        'all_items' => 'All Courses',
        'view_item' => 'View Course',
        'search_items' => 'Search Courses',
        'not_found' => 'No Courses found',
        'not_found_in_trash' => 'No Courses found in Trash',
        'menu_name' => 'Courses'
    );
    
    $course_args = array(
        'labels' => $course_labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'courses'),
    );
    
    register_post_type('course', $course_args);

    // Register Lesson Post Type
    $lesson_labels = array(
        'name' => 'Lessons',
        'singular_name' => 'Lesson',
        'add_new' => 'Add New Lesson',
        'add_new_item' => 'Add New Lesson',
        'edit_item' => 'Edit Lesson',
        'new_item' => 'New Lesson',
        'all_items' => 'All Lessons',
        'view_item' => 'View Lesson',
        'search_items' => 'Search Lessons',
        'not_found' => 'No Lessons found',
        'not_found_in_trash' => 'No Lessons found in Trash',
        'menu_name' => 'Lessons'
    );
    
    $lesson_args = array(
        'labels' => $lesson_labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'lessons'),
    );
    
    register_post_type('lesson', $lesson_args);
}
add_action('init', 'custom_lms_register_post_types');

function custom_lms_register_taxonomies() {
    // Course Categories
    $labels = array(
        'name' => 'Course Categories',
        'singular_name' => 'Course Category',
        'search_items' => 'Search Course Categories',
        'all_items' => 'All Course Categories',
        'parent_item' => 'Parent Course Category',
        'parent_item_colon' => 'Parent Course Category:',
        'edit_item' => 'Edit Course Category',
        'update_item' => 'Update Course Category',
        'add_new_item' => 'Add New Course Category',
        'new_item_name' => 'New Course Category Name',
        'menu_name' => 'Course Categories',
    );

    register_taxonomy('course_category', array('course'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'course-category'),
    ));

    // Course Tags
    $labels = array(
        'name' => 'Course Tags',
        'singular_name' => 'Course Tag',
        'search_items' => 'Search Course Tags',
        'all_items' => 'All Course Tags',
        'edit_item' => 'Edit Course Tag',
        'update_item' => 'Update Course Tag',
        'add_new_item' => 'Add New Course Tag',
        'new_item_name' => 'New Course Tag Name',
        'menu_name' => 'Course Tags',
    );

    register_taxonomy('course_tag', array('course'), array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'course-tag'),
    ));
}
add_action('init', 'custom_lms_register_taxonomies');


function custom_lms_add_meta_boxes() {
    add_meta_box('lesson_details', 'Lesson Details', 'custom_lms_lesson_details_meta_box', 'lesson', 'normal', 'high');
}
add_action('add_meta_boxes', 'custom_lms_add_meta_boxes');

function custom_lms_lesson_details_meta_box($post) {
    wp_nonce_field('custom_lms_save_lesson_details', 'custom_lms_lesson_details_nonce');
    $duration = get_post_meta($post->ID, '_lesson_duration', true);
    ?>
    <p>
        <label for="lesson_duration">Duration (minutes):</label>
        <input type="number" id="lesson_duration" name="lesson_duration" value="<?php echo esc_attr($duration); ?>" />
    </p>
    <?php
}

function custom_lms_save_lesson_details($post_id) {
    if (!isset($_POST['custom_lms_lesson_details_nonce']) || !wp_verify_nonce($_POST['custom_lms_lesson_details_nonce'], 'custom_lms_save_lesson_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['lesson_duration'])) {
        update_post_meta($post_id, '_lesson_duration', sanitize_text_field($_POST['lesson_duration']));
    }
}
add_action('save_post', 'custom_lms_save_lesson_details');


function custom_lms_register_quiz_post_type() {
    $labels = array(
        'name' => 'Quizzes',
        'singular_name' => 'Quiz',
        'add_new' => 'Add New Quiz',
        'add_new_item' => 'Add New Quiz',
        'edit_item' => 'Edit Quiz',
        'new_item' => 'New Quiz',
        'all_items' => 'All Quizzes',
        'view_item' => 'View Quiz',
        'search_items' => 'Search Quizzes',
        'not_found' => 'No Quizzes found',
        'not_found_in_trash' => 'No Quizzes found in Trash',
        'menu_name' => 'Quizzes'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'),
        'rewrite' => array('slug' => 'quizzes'),
    );

    register_post_type('quiz', $args);
}
add_action('init', 'custom_lms_register_quiz_post_type');


function custom_lms_add_quiz_meta_boxes() {
    add_meta_box('quiz_scores', 'Quiz Scores', 'custom_lms_quiz_scores_meta_box', 'quiz', 'normal', 'high');
}
add_action('add_meta_boxes', 'custom_lms_add_quiz_meta_boxes');

function custom_lms_quiz_scores_meta_box($post) {
    wp_nonce_field('custom_lms_save_quiz_scores', 'custom_lms_quiz_scores_nonce');
    $scores = get_post_meta($post->ID, '_quiz_scores', true);
    ?>
    <p>
        <label for="quiz_scores">Scores (comma-separated):</label>
        <input type="text" id="quiz_scores" name="quiz_scores" value="<?php echo esc_attr($scores); ?>" />
    </p>
    <?php
}

function custom_lms_save_quiz_scores($post_id) {
    if (!isset($_POST['custom_lms_quiz_scores_nonce']) || !wp_verify_nonce($_POST['custom_lms_quiz_scores_nonce'], 'custom_lms_save_quiz_scores')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['quiz_scores'])) {
        update_post_meta($post_id, '_quiz_scores', sanitize_text_field($_POST['quiz_scores']));
    }
}
add_action('save_post', 'custom_lms_save_quiz_scores');


function custom_lms_courses_shortcode() {
    $args = array('post_type' => 'course', 'posts_per_page' => -1);
    $courses = new WP_Query($args);
    ob_start();
    if ($courses->have_posts()) {
        echo '<ul class="lms-courses">';
        while ($courses->have_posts()) {
            $courses->the_post();
            echo '<li>';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<div>' . get_the_content() . '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No courses found.</p>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('lms_courses', 'custom_lms_courses_shortcode');

function custom_lms_lessons_shortcode() {
    $args = array('post_type' => 'lesson', 'posts_per_page' => -1);
    $lessons = new WP_Query($args);
    ob_start();
    if ($lessons->have_posts()) {
        echo '<ul class="lms-lessons">';
        while ($lessons->have_posts()) {
            $lessons->the_post();
            echo '<li>';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<div>' . get_the_content() . '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No lessons found.</p>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('lms_lessons', 'custom_lms_lessons_shortcode');

function custom_lms_quizzes_shortcode() {
    $args = array('post_type' => 'quiz', 'posts_per_page' => -1);
    $quizzes = new WP_Query($args);
    ob_start();
    if ($quizzes->have_posts()) {
        echo '<ul class="lms-quizzes">';
        while ($quizzes->have_posts()) {
            $quizzes->the_post();
            echo '<li>';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<div>' . get_the_content() . '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No quizzes found.</p>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('lms_quizzes', 'custom_lms_quizzes_shortcode');


function custom_lms_enqueue_styles() {
    wp_enqueue_style('custom-lms-styles', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'custom_lms_enqueue_styles');



