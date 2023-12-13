<?php
/*
Plugin Name: Current Time
Plugin URI:https://codex.wordpress.org/Plugin_API/Action_Reference
Description: clock for the current time
Version: 1.0
Author: Huseyin Burak TURFANDA
Author URI: www.hbt.com.au
*/
class clock
{
    public function __construct()
    {
        add_shortcode("real_timer", array($this, 'display_clock'));
    }

    public function show_clock()
    {
        ob_start(); ?>
        <div id="clock"></div>
        <script>
            function update_timer() {
                var date = new Date();
                var hours = date.getHours();
                var minutes = date.getMinutes();
                var seconds = date.getSeconds();

                hours = (hours < 10) ? "0" + hours : hours;
                minutes = (minutes < 10) ? "0" + minutes : minutes;
                seconds = (seconds < 10) ? "0" + seconds : seconds;

                var time = `${hours}:${minutes}:${seconds}`;

                document.getElementById("clock").innerHTML = time;
            }
            setInterval(update_timer, 1000);
        </script>
<?php return ob_get_clean();
    }
}
$real_timer = new clock();
?>