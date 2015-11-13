<?php
if(!class_exists('Service_Point_Settings')) {
  class Service_Point_Settings {

    /**
     * [__construct init]
     */
    public function __construct() {
     
    }

    public function parse_data() {
      $list = array();
      //Step 1: Load custom post type to php variables
      $query = new WP_Query( 
        array(
          'post_type' => 'service-point'
        )
      );

      if ($query->have_posts()) :
        while ( $query->have_posts() ) : $query->the_post();
          $sp_custom_fields = array();
          array_push($sp_custom_fields,get_the_title());
          array_push($list,$sp_custom_fields);
        endwhile; 
        wp_reset_postdata(); 
      endif;
      //Step 2: Load php variables as list of array
      // $list = array (
      //   array('dasaasf', 'bbb', 'ccc', 'dddd'),
      //   array('123', '456', '789'),
      //   array('"aaa"', '"bbb"')
      // );
   
      //Step 3: Write php data to CSV files (one array per row)
      $uri = CSV_PLUGIN_URL.'files'.'/promise-service-point.csv';
      $fp = fopen($uri, 'w');

      foreach ($list as $fields) {
        fputcsv($fp, $fields);
      }
      fclose($fp);

    }
  }
}
