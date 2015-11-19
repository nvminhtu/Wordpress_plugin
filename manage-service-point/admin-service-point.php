<?php 
  if(!class_exists('Admin_Service_Point')) {
    class Admin_Service_Point {
      
      /**
       * [__construct description]
       */
      public function __construct() {
        add_filter('manage_service-point_posts_columns', array($this,'service_point_table_head'));
        add_action('manage_service-point_posts_custom_column', array($this,'service_point_table_content'), 10, 2);
        add_filter('manage_edit-service-point_sortable_columns', array($this,'sortable_sp_column'));
        add_filter('requests', array($this,'service_point_column_sorting'));
        add_action('admin_head-edit.php', array($this,'move_quick_edit_links'));
        add_action('admin_head', array($this,'admin_style_column'));
      }
      
      /**
       * [service_point_table_head declare columns in admin view list]
       * @param  [type] $columns [show address, address2, type]
       * @return [column head]          [column head title in admin view list]
       */
      public function service_point_table_head($columns ) {
        $columns = array();
        
        $columns['cb'] = 'Select All';
        $columns['title'] = 'ID';
        $columns['address']  = 'Address';
        $columns['address2']  = 'Address 2';
        $columns['date'] = 'Date';

        return $columns;
      }

      /**
       * [admin_style_column description]
       * @return [type] [description]
       */
      public function admin_style_column() {
        echo '<style type="text/css">
            .column-title { text-align: left; width:50px !important; overflow:hidden }
            .column-address { text-align: left; width:500px !important; overflow:hidden }
        </style>';
      }

      /**
       * [move_quick_edit_links move quick edit to address]
       * @return [type] [description]
       */
      public function move_quick_edit_links() {
        global $current_screen;
        if( 'service-point' != $current_screen->post_type )
            return;
          ?>
            <script type="text/javascript">
            function doMove() {
                jQuery('td.title.page-title.column-title div.row-actions').each(function() {
                    var $list = jQuery(this);
                    var $firstChecked = $list.parent().parent().find('td.address.column-address');

                    if ( !$firstChecked.html() )
                        return;

                    $list.appendTo($firstChecked);
                }); 
            }
            jQuery(document).ready(function ($){
                doMove();
            });
            </script>
            <?php
      }

      /**
       * [service_point_table_content display content for each column]
       * @param  [type] $column_name [parse data to column name]
       * @param  [type] $post_id     [get custom id and show meta belong with field]
       * @return [value]             [post content value for each column]
       */
      public function service_point_table_content( $column_name, $post_id ) {
        switch ($column_name) {
          case 'address':
            $address = get_post_meta( $post_id, 'address', true );;
            $link = get_edit_post_link();
            echo "<a href='".$link."'>".$address."</a>";
            break;
          case 'address2': 
            $address2 = get_post_meta( $post_id, 'address2', true );
            echo $address2;
            break;
        }
      }

      /**
       * [sortable_sp_column sort column which you want to do]
       * @param  [type] $columns [description]
       * @return [columns]          [description]
       */
      public function sortable_sp_column($columns ) {
        $columns['address'] = 'address';
        $columns['address2'] = 'address2';
        unset($columns['title']);
        return $columns;
      }
     
     /**
      * [service_point_column_sorting display custom meta and sorting]
      * @param  [type] $vars [description]
      * @return [type]       [description]
      */
      public function service_point_column_sorting( $vars ){
        if( isset($vars['orderby']) && 'address' == $vars['orderby'] ){
          $vars = array_merge( $vars, array(
            'meta_key' => 'address',
            'orderby'  => 'meta_value'
          ));
        }

        if( isset($vars['orderby']) && 'address2' == $vars['orderby'] ){
          $vars = array_merge( $vars, array(
            'meta_key' => 'address2',
            'orderby'  => 'meta_value'
          ));
        }
        return $vars;
      }

    }
  }
?>