<?php
if(!class_exists('Service_Point_Settings')) {
  class Service_Point_Settings {

    /**
     * [__construct init admin functions and admin menu]
     */
    public function __construct() {
      $this->parse_data();
    }

    public function parse_data() {
      $list = array();
      $count = 1;

      $articles = get_posts(
       array(
        'numberposts' => -1,
        'orderby'    => 'ID',
        'post_type' => 'service-point'
       )
      );
  
      $data = array('id', 'address', 'address2', 'tel', 'fax', 'update_status', 'category', 'prefecture', 'district', 'pic_appearance', 'pic_map', 'type', 'lat', 'lng');
      array_push($list, $data);
      
      foreach ($articles as $article) { 
        $pic_appearance = wp_get_attachment_image_src(get_field('pic_appearance',$article->ID), 'full');
        $pic_map = wp_get_attachment_image_src(get_field('pic_map',$article->ID), 'full');
        // if($article->ID == '1194')
        // {
        //   print_r($pic_appearance);
        //   exit;
        // }
        $data = array();

        array_push($data, get_the_title($article->ID));
        array_push($data, get_field('address',$article->ID));
        array_push($data, get_field('address2',$article->ID));
        array_push($data, get_field('tel',$article->ID));
        array_push($data, get_field('fax',$article->ID));
        array_push($data, get_field('update_status',$article->ID));
        array_push($data, get_field('category',$article->ID));
        array_push($data, get_field('prefecture',$article->ID));
        array_push($data, get_field('district',$article->ID));
        array_push($data, $pic_appearance[0]);
        array_push($data, $pic_map[0]);
        array_push($data, get_field('type',$article->ID));
        array_push($data, get_field('lat',$article->ID));
        array_push($data, get_field('lng',$article->ID));

        array_push($list,$data);
          $count++;
      }
      
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
