<?php
/**
 * Plugin Name: Minimize Admin
 * Plugin URI: 
 * Description: Customize admin in wordpress
 * Version: 0.1
 * Author: ORO VIETNAM
 * Author URI: 
 */

if(!class_exists('Minimize_Admin')) {
  class Minimize_Admin {

    public function __construct() {
      add_action('admin_head', array( $this, 'custom_admin_head'), 11 );
      add_filter('admin_footer_text', array( $this, 'custom_admin_footer'));
      add_action('admin_bar_menu', array( $this, 'remove_admin_bar_menu'), 70);
      add_action('admin_menu', array( $this, 'remove_menus'));
      add_action('init', array( $this, 'redirect_dashboard'));
      add_action('wp_dashboard_setup', array( $this, 'remove_dashboard_widgets'));
      add_filter('manage_pages_columns', array( $this, 'custom_columns'));
      add_action('admin_menu', array( $this, 'remove_default_post_screen_metaboxes'));
      add_action('welcome_panel', array( $this, 'promise_panel') );
      add_action('login_enqueue_scripts', array( $this, 'change_admin_login_logo'));
      $this->remove_version_wp();
    }

    public static function activate() {
      //do action after activiting plugin
    }

    public static function deactivate() {
      //do action after deactiviting plugin 
    }

    /**
     * [custom_admin_head add style/javascript for admin dashboard]
     * @return [none] 
     */
    public function custom_admin_head() {
      if (current_user_can('level_10')) return; // Except Administrator

      echo '<link rel="stylesheet" href="'.plugin_dir_path( __FILE__ ).'/inc/admin-style.css" type="text/css" media="all" />';
      echo '<script type="text/javascript" src="'.plugin_dir_path( __FILE__ ).'/inc/admin-style.js"></script>';
    }
    /**
     * [custom_admin_footer filter admin information in footer]
     * @return [none]
     */
    public function custom_admin_footer() {
      if (current_user_can('level_10')) return; // Except Administrator
      echo '';
    }
    /**
     * [remove_admin_bar_menu prevent access admin bar in frontend]
     * @param  [remove items] $wp_admin_bar [remove unused items]
     * @return [remove admin bar] [customize admin bar]
     */
    public function remove_admin_bar_menu( $wp_admin_bar ) {
      if (current_user_can('level_10')) return; // Except Administrator

      $wp_admin_bar->remove_menu('wp-logo');
      $wp_admin_bar->remove_menu('comments');
    }
    /**
     * [remove_menus only admin can view and access menu]
     * @return [unset menu] [remove some menu]
     */
    public function remove_menus() {
      if (current_user_can('level_10')) return; // Except Administrator
        global $menu;
        unset($menu[2]); // Dashboard
        unset($menu[4]); // Line 1
        unset($menu[5]); // Posts
        unset($menu[10]); // Media
        unset($menu[25]); // Comments
        unset($menu[59]); // Line 2
        unset($menu[60]); // Appearance
        unset($menu[65]); // Plugins
        unset($menu[75]); // Tools
        unset($menu[80]); // Settings
        unset($menu[90]); // Line 3
        remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page'); // Pages - Add New
    }

    /**
     * [redirect_dashboard apply for users who is not administrator]
     * @return [redirect] [redirect to edit page for non-administrator]
     */
    public function redirect_dashboard() {
      if ( !current_user_can('manage_options') && ($_SERVER['REQUEST_URI'] == '/cms/wp-admin/index.php' || $_SERVER['REQUEST_URI'] == '/cms/wp-admin/' ) ) {
        wp_redirect(get_option('siteurl') . '/wp-admin/edit.php?post_type=page');
        exit(0);
      }
    }

    /**
     * [Filter wordpress information in panel]
     * @return [none] 
     */
    public function remove_version_wp() {
      add_filter('pre_site_transient_update_core', '__return_zero');
      remove_action('wp_version_check', 'wp_version_check');
      remove_action('admin_init', '_maybe_update_core');
    }

    /**
     * [remove_dashboard_widgets remove widgets we dont need]
     * @return [dashboard] [dashbard is available]
     */
    public function remove_dashboard_widgets() {
      global $wp_meta_boxes;
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // Right Now
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Recent Comments
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // Incoming Links
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Plugins
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']); // Activity
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // QuickPress
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // Recent Drafts
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPress Blog
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPress Forum
    }

    /**
     * [custom_columns filter columns and remove some unused columns]
     * @param  $columns [description]
     * @return columns after filter
     */
    public function custom_columns($columns) {
      unset($columns['author']);
      unset($columns['comments']);
      unset($columns['date']);
      return $columns;
    }
    /**
     * [remove_default_post_screen_metaboxes description]
     * @return [post_screen] [customized screen for only admin can edit]
     */
    public function remove_default_post_screen_metaboxes() {
      if (current_user_can('level_10')) return; // Except Administrator
      remove_meta_box( 'postcustom','post','normal' ); // Custom Fields
      remove_meta_box( 'postexcerpt','post','normal' ); // Excerpt
      remove_meta_box( 'commentstatusdiv','post','normal' ); // Discussion
      remove_meta_box( 'commentsdiv','post','normal' ); // Comments
      remove_meta_box( 'trackbacksdiv','post','normal' ); // Trackback
      remove_meta_box( 'authordiv','post','normal' ); // Author
      remove_meta_box( 'slugdiv','post','normal' ); // Slug
      remove_meta_box( 'revisionsdiv','post','normal' ); // Revision
      remove_meta_box( 'pageparentdiv','page','side' ); // Page Attributes
    }

    /**
     * [promise_panel customize own Promise panel]
     * @return [type] [add information about Promise]
     */
    public function promise_panel() { ?>
    <script type="text/javascript">
    /* Hide default welcome message */
    jQuery(document).ready( function($) 
    {
      $('div.welcome-panel-content').hide();
    });
    </script>
      <div class="custom-welcome-panel-content">
      <h3><?php _e( 'Welcome to Promise CMS Dashboard!' ); ?></h3>
      <div class="welcome-panel-column-container">
      <div class="welcome-panel-column">
        <h4><?php _e( "Modify page contents" ); ?></h4>
        <?php printf( '<a href="%s" class="button button-primary button-hero load-customize hide-if-no-customize">' . __( 'Manage pages' ) . '</a>', admin_url( 'edit.php?post_type=page' ) ); ?>
          <p class="hide-if-no-customize"><?php printf( __( 'or, <a href="%s">edit your site settings</a>' ), admin_url( 'options-general.php' ) ); ?></p>
      </div>
      <div class="welcome-panel-column">
        <h4><?php _e( 'Feature' ); ?></h4>
        <ul>
          <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
          <li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Add a new News' ) . '</a>', admin_url( 'post-new.php?post_type=news' ) ); ?></li>
        </ul> 
      </div>
      <div class="welcome-panel-column">
        <h4><?php _e( 'View your site' ); ?></h4>
        <ul>
          <li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">' . __( 'View your site' ) . '</a>', home_url( '/' ) ); ?></li>
        </ul>
      </div>
      </div>
    <?php
    }

    /**
     * [change_admin_login_logo change to promise logo]
     * @return [admin logo] [customize admin logo]
     */
    public function change_admin_login_logo() {
      $logo_location = plugin_dir_url( __FILE__ ).'/img/logo-promise.jpg';
    ?>
       <style type="text/css">
          .login h1 a {
            background-image: url(<?php echo $logo_location; ?>)!important;
            background-size: 184px!important;
            width: 100%;
            height: 70px;
            text-indent: -99999px!important;
          }
        </style>
    <?php }
  }
}

if(class_exists('Minimize_Admin')) {
  register_activation_hook(__FILE__, array('Minimize_Admin', 'activate'));
  register_deactivation_hook(__FILE__, array('Minimize_Admin', 'deactivate'));

  $Minimize_Admin = new Minimize_Admin();
}