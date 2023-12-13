<?php
/*
Plugin Name: footer_plugin
Plugin URI: https://codex.wordpress.org/Plugin_API/Action_Reference
Description: Basic footer for the website
Version: 1.0
Author: Huseyin Burak TURFANDA
Author URI: www.hbt.com.au
*/
add_action("wp_footer", function () { ?>

    <style>
        footer {
            background-color: #121212;
            padding: 20px;
            text-align: center;
        }
    </style>
    <footer>
        <div>
            <p style="color:white">
                &copy; 2023 My Rent Buddy. No copyright reserved.
            </p>
        </div>
    </footer>
<?php }, 9999); ?>