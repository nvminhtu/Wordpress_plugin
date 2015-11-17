<?php
  public function parse_csv_to_array($file_src) {
    $data = array();
    $errors = array();

    $file = CSV_PLUGIN_URL.$file_src;
    // Check permission
    if ( ! is_readable( $file ) ) {
      chmod( $file, 0744 );
    }

    // Check if file is writable, then open it in 'read only' mode
    if ( is_readable( $file ) && $_file = fopen( $file, "r" ) ) {
      $post = array();
      $header = fgetcsv( $_file );
      while ( $row = fgetcsv( $_file ) ) {
        foreach ( $header as $i => $key ) {
          $post[$key] = $row[$i];
        }
        $data[] = $post;
      }
      fclose( $_file );
    } else {
      $errors[] = "File '$file' could not be opened. Check the file's permissions to make sure it's readable by your server.";
    }


    if ( ! empty( $errors ) ) {
      // errors message for notice
    }
    return $data;
  }

  public function check_post_exists($title, $wpdb, $arr_fields) {
    $posts = $wpdb->get_col(
     "SELECT post_title 
      FROM {$wpdb->posts} 
      WHERE post_type = '{$arr_fields["custom-post-type"]}'"
    );
    // Check if the passed title exists in array
    return in_array( $title, $posts );
  }

?>