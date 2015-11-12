<?php
/**
 * Plugin Name: Auto Add Tag to title
 * Plugin URI: 
 * Description: Automate add tag to tile in wordpress
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: 
 * License: GPLv2 or later
 */
?>
<?php 
if(!class_exists('Auto_Tag_To_Title')) {
  class Auto_Tag_To_Title {

    public function __construct() {
      add_filter('the_title', array($this, 'auto_add_tag'),10,2);
    }

    public function auto_add_tag($title, $post_id) {
      global $post;
      $result = '';
      $add_tags = '';
      $i = 0;

      if(in_the_loop()) {
        $p = get_post($post_id);
        $cur_title = $p->post_title;
        
        $post_tags = get_the_tags($post_id);
        if ($post_tags) {
          foreach($post_tags as $tag) {
            //get the first post tag
            if($i==0) {
              $add_tags.= '&lsqb;'.$tag->name.'&rsqb;&nbsp;'; 
            }
            $i++;
          }
        }  

        $result = $add_tags.$cur_title;
      } else {
        $result = $title;
      }
      return $result;
    } 
  }
}

if(class_exists('Auto_Tag_To_Title')) {
  $Auto_Tag_To_Title = new Auto_Tag_To_Title();
}