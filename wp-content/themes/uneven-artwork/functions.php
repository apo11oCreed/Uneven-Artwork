<?php 

function custom_scripts(){

    $parenthandle = 'highresponsive-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );

    wp_enqueue_style('google-styles', 'https://fonts.googleapis.com/css2?family=Neucha&display=swap', array(), false);

    wp_enqueue_style( 'ncorreia-style', get_stylesheet_directory_uri() . '/css/style.css',
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );

    wp_register_script('ncorreia-js', get_stylesheet_directory_uri() . '/js/script.js',  array ('jquery'), true);

    wp_enqueue_script('ncorreia-js');

}

add_action( 'wp_enqueue_scripts', 'custom_scripts' );

?>