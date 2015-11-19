<?php
/**
 * Plugin Name: Story Plugin
 * Plugin URI: http://it.phuotky.net
 * Description: Plugin hỗ trợ việc post truyện trên website
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: http://phuotky.com
 * License: GPLv2 or later
 */
?>
<?php 
  if(!class_exists('Story_Plugin')) {
    class Story_Plugin {
      
      public function __construct() {
        //register action
        add_action('init',array(&$this, 'init'));
        add_action('edit_form_after_title', array($this,'admin_init'));
        add_action('save_post', array($this,'save_case_study_parent_id'));
      }
      
      public function init() {
        $this->cpt_story();
      }

      public function cpt_story() {
        $labels = array(
          'name'               => _x( 'Storys', 'post type general name', 'story-plugin' ),
          'singular_name'      => _x( 'story', 'post type singular name', 'story-plugin' ),
          'menu_name'          => _x( 'Storys', 'admin menu', 'story-plugin' ),
          'name_admin_bar'     => _x( 'story', 'add new on admin bar', 'story-plugin' ),
          'add_new'            => _x( 'Add New', 'Story', 'story-plugin' ),
          'add_new_item'       => __( 'Add New Story', 'story-plugin' ),
          'new_item'           => __( 'New story', 'story-plugin' ),
          'edit_item'          => __( 'Edit story', 'story-plugin' ),
          'view_item'          => __( 'View story', 'story-plugin' ),
          'all_items'          => __( 'All Storys', 'story-plugin' ),
          'search_items'       => __( 'Search Storys', 'story-plugin' ),
          'parent_item_colon'  => __( 'Parent Storys:', 'story-plugin' ),
          'not_found'          => __( 'No Storys found.', 'story-plugin' ),
          'not_found_in_trash' => __( 'No Storys found in Trash.', 'story-plugin' )
        );

        $args = array(
          'labels'             => $labels,
          'description'        => __( 'Description.', 'story-plugin' ),
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'show_in_menu'       => true,
          'query_var'          => true,
          'rewrite'            => array( 'slug' => 'story' ),
          'capability_type'    => 'post',
          'has_archive'        => true,
          'hierarchical'       => false,
          'menu_position'      => 5,
          'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'story', $args );
      }

      /**
       * [admin_init description]
       * @return [type] [description]
       */
      public function admin_init(){
        add_meta_box('case_study_parent_id', 'Case Study Parent ID', $this->set_case_study_parent_id(), 'casestudy', 'normal', 'low');
      }
      
      //Meta box for setting the parent ID
      public function set_case_study_parent_id() {
        global $post;
          $custom = get_post_custom($post->ID);
          $parent_id = $custom['parent_id'][0];
        ?>
        <p>Please specify the ID of the page or post to be a parent to this Case Study.</p>
        <p>Leave blank for no heirarchy.  Case studies will appear from the server root with no assocaited parent page or post.</p>
        <input type='text' id='parent_id' name='parent_id' value='<?php echo $post->post_parent; ?>' />
        <?php
        echo "<input type='hidden' name='parent_id_noncename' value='' . wp_create_nonce(__FILE__) . '' />";
      }

      
      public function save_case_study_parent_id($post_id) {
        global $post;
        if (!wp_verify_nonce($_POST['parent_id_noncename'],__FILE__)) return $post_id;
          if(isset($_POST['parent_id']) && ($_POST['post_type'] == 'casestudy')) {
            $data = $_POST['parent_id'];
            update_post_meta($post_id, 'parent_id', $data);
          }
      }
    }
  }

  if(class_exists('Story_Plugin')) {
    $Story_Plugin = new Story_Plugin();
  }

  
?>