<script>
function calcHeight()
{
	var the_height=510;
	document.getElementById('glu').height=the_height;
	
	the_height=
		document.getElementById('glu').contentWindow.
		document.body.scrollHeight;
	document.getElementById('glu').height=the_height;
}
</script>
<style>
a:link, a:visited {
	text-decoration:none; 
	color:#33839A;
}
a:hover {
	text-decoration:none;
	color: #144A6E;
}
</style>
<div style="font-family: Tahoma, Verdana, Arial, Helvetica; line-height:150%; color: #555; width:600px; height:280px;">
<?php
@apache_setenv('no-gzip',1);
@ini_set('zlib.output_conpresion',0);
@ini_set('implicit_flush',1);
for($i=0;$i<ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);
include '/var/www/inc/defog_config.php';

if(isset($_GET['jobid']))
{
$jobid = $_GET['jobid'];
}
else
{
echo "Must have Confirmation ID";
exit();
}


flush_buffers();
$trigger = true;
$status = null;
$prev = $status;
$track = 0;
$start = microtime(true);
$dotTrack = 0;
while($trigger)
{
	$chkStatus = "select * from jobs where jobid = '$jobid'";
	//echo $chkStatus . "<p>";
	$statusRes = mysql_query($chkStatus);
	if($statusRes && mysql_num_rows($statusRes))
	{
		$queue = mysql_fetch_assoc($statusRes);
		$title = $queue['modtime'];
		$status = $queue['statusString'];
		if(!$track)
			echo "Processing started on $title<p style=\"margin:0; font-size:75%; border: 3px solid #ccc; padding:10px; height: 280px; line-height: 100%;\" id=\"crazy\">";

		if($status != $prev && trim($status))
		{	
			echo "<script language=\"JavaScript\">document.getElementById(\"crazy\").innerHTML=''</script>";
			echo trim($status);
			$dotTrack = 0;
		}
		elseif($queue['status'] == 'q')
		{
			if(!$dotTrack)
				echo "Waiting in queue";
			if($dotTrack > 1 && $dotTrack < 10)
				echo ".";
			elseif(!($dotTrack % 10))
				echo ".";
			$dotTrack++;
		}
		$prev = $status;
	}
	$track++;
	if(trim($status) == "done" || $queue['status'] == 'c')
	{
		$end = microtime(true);
		$time = ceil($end-$start);
		echo " ($time seconds to process)<br>
			<center><b><a href=\"/content/defog-results?jobid=$jobid\" target=\"_parent\">
			Click Here to view results</a></b></center>";
		$trigger = false;
	}
	if($track > 1000)
		$trigger = false;
	sleep(1);
	flush_buffers();
}

echo "</p>";

function flush_buffers() 
{
ob_end_flush();
ob_flush();
flush();
ob_start();
}

?>
</div>
