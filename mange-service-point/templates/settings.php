<div id="csv-admin-wrap">
  <h2>Manage Service Point</h2>
  <form enctype="multipart/form-data" name="frmCSV" action="<?php esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
    <?php @settings_fields('csv-fields'); ?>
    <?php @do_settings_fields('csv-fields'); ?>
    <?php do_settings_sections('manage-service-point'); ?>
  </form>
	<?php if(isset($_POST['csv-submit'])) {
		
		$uri = CSV_PLUGIN_URL.'files'.'/';
		$target_dir = $uri;
		
		echo $filename = $_FILES["upload-csv"]["name"];
		$extension = pathinfo($target_file,PATHINFO_EXTENSION);

		$extension= end(explode(".", $filename));
		$default_name = "promise-service-point";

		$newfilename= $default_name.".".$extension;
		$target_file = $target_dir . basename($newfilename);

		$upload_flag = 1;

		//check conditionals for file upload
		if ($_FILES["upload-csv"]["size"] > 20000000) {
	    echo "<div class='warning'>Sorry, your file is too large.</div>";
	    $upload_flag = 0;
		}
		
		if($extension != "csv") {
	    echo "<div class='info'>Sorry, only CSV files are allowed.</div>";
	    $upload_flag = 0;
		}

		//if can be uploaded.
		if ($upload_flag == 0) {
		  echo "<div class='error'>Sorry, your file was not uploaded.</div>";
		} else {
			
	    if (move_uploaded_file($_FILES["upload-csv"]["tmp_name"], $target_file)) {
	        echo "<div class='success'>The file ". basename( $_FILES["upload-csv"]["name"]). " has been uploaded successfully!!!</div>";
		    } else {
	        echo "<div class='error'>Sorry, there was an error uploading your file.</div>";
	    }
		}
	}
	?>
<!-- /#csv-admin-wrap --></div>
