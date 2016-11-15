<?php
//Function to render the Past Orders page
function orderupdatePastOrders(){ ?>
	<div class="wrap">
	<h2>Past Orders</h2>
	<p>Click to see file.</p>
	<?php
  //Create new array
  $FoundFiles = array();
  //Create a new array of files in the order folder
	$dir = new DirectoryIterator(plugin_dir_path( __FILE__ ).'orders');
  //Directory Address
  $fullDir ='/wp-content/plugins/orderupdate/orders/';
    //Loop through each file and get name and last modified time
    foreach ($dir as $fileinfo) {
		    if (!$fileinfo->isDot()) {
		        $fileName = $fileinfo->getFilename();
            $date = $fileinfo->getMTime();
            //Push data in to assositive FoundFiles array
            $FoundFiles[] = array(
                                  "fileName" => $fileName,
                                  "date"     => $date
                                  );


		    }
		}
    //Sort the FoundFiles array in descending order, according to the value
    arsort( $FoundFiles );
    ?>
    <ul>
     <?php foreach( $FoundFiles as $File ): //Loop through the array and output files ?>
        <li><a href="<?php echo $fullDir; ?><?php echo $File["fileName"] ?>" target="_blank"><?php echo $File["fileName"] ?></a></li>
     <?php endforeach; ?>
  </ul>
	</div>
<?php }




?>
