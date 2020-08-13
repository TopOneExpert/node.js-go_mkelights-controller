<?php 
    include('variables/variables.php');
    //date_default_timezone_set("America/Chicago");
    //the above line adds +1 hour
?>

<div id="footer">
	<p>
		<?php echo $footer ?><br />
		powered by: TroublesomeStudios<br />
        <?php
            $m_dt = date('Y-m-d H:i:s');  
            echo "Server Time: ".$m_dt;            
        ?>        
	</p>

</div> <!-- end #footer -->
