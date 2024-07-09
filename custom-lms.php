<?php
/*
Plugin Name: Custom LMS
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

