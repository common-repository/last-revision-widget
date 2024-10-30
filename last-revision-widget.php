<?php

/*
Plugin Name: Last Revision Date Widget
Plugin URI: 
Description: Show last revision date for the current page/post
Version: 1.0.0
Author: Alessandra Citterio
Author URI:
License: GPLv2
*/

/* 
Copyright (C) 2014 gmt04

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

class LastRevisionDateWidget extends WP_Widget
{
  function LastRevisionDateWidget()
  {
    $widget_ops = array('classname' => 'LastRevisionDateWidget', 'description' => 'Show last revision date' );
    $this->WP_Widget('LastRevisionDateWidget', 'Last revision date', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    $id = get_the_ID();  
   
    $revs = wp_get_post_revisions($id, array('order'=> 'DESC'));
    if (count($revs > 0)) {
        //echo "<h2>Last update</h2>";

        foreach ($revs as $rev) { ?>
            <div class="post revision" id="post-<?php echo $rev->ID; ?>">
                    <p class="postmetadata">
                        <small>This document was saved
                          on <?php echo mysql2date('l, F jS, Y', $rev->post_date) ?>
                          at <?php echo mysql2date('H:m:s', $rev->post_date)  ?>
                        </small>
                    </p>
                    <?php break; ?> /* I take just the last revision */
            </div>
         <?php } /* end foreach */
      } /* end if any revisions */
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("LastRevisionDateWidget");') );?>