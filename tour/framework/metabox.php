<?php
class Plugin_Metabox_Tour extends Plugin_Metabox_With_Tabs {
	public $slug = 'tour';
	public $maxdays = 20;
	public function config(){
		return array(
			'title' => __('Tour Options','theme_admin'),
			'post_types' => array('tour'),
			'callback' => '',
			'context' => 'normal',
			'priority' => 'high',
		);
	}

	public function tabs(){
	
		$tourItineraryOptions = array(
					array(
						"name" => __("Tour Days Count",'theme_admin'),
						"id" => "_tour_days_count",
						"min" => "1",
						"max" => $this->maxdays,
						"step" => "1",
						'unit' => 'days',
						"default" => "1",
						"type" => "range",
						"prepare" => '_option_tour_days_count_prepare'
					)
		);
		for($i = 1; $i <= $this->maxdays; $i++) {
			$tourItineraryOptions[] = array(
						"name" => sprintf(__("Day %d Headline",'theme_admin'),$i),
						"id" => "_tour_day_{$i}_headline",
						"default" => "",
						"class" => 'full',
						"htmlspecialchars" => true,
						"type" => "text"
					);
			$tourItineraryOptions[] = 		array(
						"name" => sprintf(__("Day %d Content",'theme_admin'),$i),
						"id" => "_tour_day_{$i}_content",
						"default" => "",
						"htmlspecialchars" => true,
						"type" => "textarea"
					);
		}
	
		return array(
			array(
				"name" => __("Tour General fields",'theme_admin'),
				"options" => array(
					array(
						"name" => __("Tour code",'theme_admin'),
						"id" => "_tour_code",
						"default" => "",
						"class" => 'full',
						"htmlspecialchars" => true,
						"type" => "text"
					),
					array(
						"name" => __("Depart from",'theme_admin'),
						"id" => "_depart_from",
						"target" => "destination",
						"default" => "",
						"prompt" => __("choose one..",'theme_admin'),
						"type" => "select"
					),
					array(
						"name" => __("Depart to",'theme_admin'),
						"id" => "_depart_to",
						"target" => "destination",
						"default" => "",
						"prompt" => __("choose one..",'theme_admin'),
						"type" => "select"
					),
					array(
						"name" => __("Price",'theme_admin'),
						"id" => "_tour_price",
						"default" => "",
						"type" => "text"
					),
					array(
						"name" => __("Tour Type",'theme_admin'),
						"id" => "_tour_type",
						"options" => array(
							"Classic" => "Classic",
							"Adventure" => "Adventure",
							"Luxury" => "Luxury",
						),
						"default" => "classic",
						"type" => "select"
					),
				),
			),
			array(
				"name" => __("Tour Details",'theme_admin'),
				"options" => array(
					// array(
					// 	"name" => __("Tour Overview",'theme_admin'),
					// 	"id" => "_tour_overview",
					// 	"rows" => "10",
					// 	"default" => "",
					// 	"htmlspecialchars" => true,
					// 	"type" => "textarea"
					// ),
					array(
						"name" => __("Tour trip included",'theme_admin'),
						"id" => "_tour_trip_include",
						"rows" => "8",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => __("Tour trip excluded",'theme_admin'),
						"id" => "_tour_trip_excluded",
						"rows" => "8",
						"default" => "",
						"type" => "textarea"
					),
					
					array(
						"name" => __("Tour trip additional information",'theme_admin'),
						"id" => "_tour_trip_add_info",
						"rows" => "8",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => __("Tour Itinerary",'theme_admin'),
				"options" => $tourItineraryOptions
				
			),
			array(
				"name" => __("Tour Dates and Prices",'theme_admin'),
				"options" => array(
					array(
						"name" => __("Tour Dates and Prices",'theme_admin'),
						"id" => "_tour_dates_prices",
						"rows" => "10",
						"default" => "",
						"htmlspecialchars" => true,
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => __("Tour Photo",'theme_admin'),
				"options" => array(
					array(
						"name" => __("Tour Photo",'theme_admin'),
						"id" => "_tour_photo",
						"rows" => "10",
						"default" => "",
						"htmlspecialchars" => true,
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => __("Tour Last minute deals",'theme_admin'),
				"options" => array(
					array(
						"name" => __("Tour Last minute deals",'theme_admin'),
						"id" => "_tour_last_minute_deals",
						"rows" => "10",
						"default" => "",
						"htmlspecialchars" => true,
						"type" => "textarea"
					),
				)
			),
		);
	}

	function _option_tour_days_count_prepare($option){
		echo <<<HTML
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('[name="_tour_days_count"]').on("change",function(){
		console.dir(this.value);
		for(var i = 1; i<={$this->maxdays}; i++){
			if(i>this.value){
				jQuery('[name="_tour_day_'+i+'_headline"]').parents('.meta-box-item').hide();
				jQuery('[name="_tour_day_'+i+'_content"]').parents('.meta-box-item').hide();
			} else {
				jQuery('[name="_tour_day_'+i+'_headline"]').parents('.meta-box-item').show();
				jQuery('[name="_tour_day_'+i+'_content"]').parents('.meta-box-item').show();
			}
		}
	}).trigger("change");
});
</script>
HTML;
		return $option;
	}
}
