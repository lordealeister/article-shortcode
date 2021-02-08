<?php
/**
 * Plugin Name: Article shortcode
 * Description: Adds a button to the editor for inserting the article shortcode
 * Plugin URI:  https://github.com/zero4281/tinymce-post-dropdown
 * Version:     0.0.1
 * Author:      Lorde Aleister
 * Author URI:  https://github.com/lordealeister
 * Text Domain: article-shortcode
 */

if(!class_exists('ArticleShortcode')):
    class ArticleShortcode {

        public function __construct() {
            add_action('admin_head', array($this, 'articleShortcodeTinymce'));    
            add_action('wp_ajax_articles_search', array($this, 'articleShortcodeSearch'));
            add_shortcode('article', array($this, 'articleShortcodeOutput'));
        }

        public function articleShortcodeTinymce() {
            add_filter('mce_external_plugins', array($this, 'articleShortcodeTinymcePlugin'));
            // Add to line 1 form WP TinyMCE
            add_filter('mce_buttons', array($this, 'articleShortcodeTinymceButton'));
        }
        
        // inlcude the js for tinymce
        public function articleShortcodeTinymcePlugin($plugin_array) {
            $plugin_array['article_shortcode_button'] = plugins_url('/article-shortcode.js', __FILE__);

            return $plugin_array;
        }

        // Add the button key for address via JS
        public function articleShortcodeTinymceButton($buttons) {
            array_push($buttons, 'article_shortcode');

            return $buttons;
        }

        public function articleShortcodeSearch() {
            $search = $_POST['search'];

            $query = new WP_Query(array(
                'post_type' => 'post', 
                'nopaging' => true,
                's' => $search,
            ));

            echo wp_json_encode($query->posts);

            wp_die(); // this is required to terminate immediately and return a proper response
        }

        /*
        TMCEPD_URL_dropdown_key
        */
        //function to output shortcode
        public function articleShortcodeOutput($atts) {
            return apply_filters('article_shortcode_output', $atts);
        }

    }

    new ArticleShortcode();
endif;

?>
