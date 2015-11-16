<?php
if(!class_exists('Service_Point_Settings')) {
  class Service_Point_Settings {

    /**
     * [__construct init]
     */
    public function __construct() {
      $this->parse_data();
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
        $data = array('id', 'address', 'address2', 'tel', 'fax', 'category', 'prefecture', 'district', 'pic_appearance', 'pic_map', 'type', 'lat', 'lng');
        array_push($list, $data);
        $count = 1;
        while ( $query->have_posts() ) : $query->the_post();
          $data = array();
          $pic_appearance = wp_get_attachment_image_src(get_field('pic_appearance'));
          $pic_map = wp_get_attachment_image_src(get_field('pic_map'));

          array_push($data, $count);
          array_push($data, get_field('address'));
          array_push($data, get_field('address2'));
          array_push($data, get_field('tel'));
          array_push($data, get_field('fax'));
          array_push($data, get_field('category'));
          array_push($data, get_field('prefecture'));
          array_push($data, get_field('district'));
          array_push($data, $pic_appearance[0]);
          array_push($data, $pic_map[0]);
          array_push($data, get_field('type'));
          array_push($data, get_field('lat'));
          array_push($data, get_field('lng'));

          array_push($list,$data);
          $count++;
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
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        if ($fp) {
          fputcsv($fp, $fields);
        }
      }
      fclose($fp);
    }
  }
}
