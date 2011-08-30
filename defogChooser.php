<style>
.deleteBox {
	border: 1px solid #ccc;
	color: #000;
	background-color: #eee;
	margin-top:3px;
}
</style>
<?php

if(isset($_POST['changeName']))
{
	$conf	= $_POST['jobid'];
	$time	= $_POST['modtime'];
	$name	= addslashes(strip_tags($_POST['newName']));
	if($name != $time && $name)//Don't save modtime as name... do nothing
	{
		$update = "update jobs set job_title='$name' where jobid='$conf';";
		$res = mysql_query($update);
		if(!$res)
			echo "There was a problem changing the name...";	
		else
			header('Location: ' . curPage());
	}
}


if(isset($_POST['deleteJob']))
{
	$conf = $_POST['jobid'];
	$del_clus1 = "delete from cluster where jobid = '$conf';";
	$delclus1 = mysql_query($del_clus1);
	if($delclus1)
	{
		$del_clus2 = "delete from clusterElements where jobid='$conf';";
		$delclus2 = mysql_query($del_clus2);
		if($delclus2)
		{
			$del_clus3 = "delete from clusterTerms where jobid = '$conf';";
			$delclus3 = mysql_query($del_clus3);
			if($delclus3)
			{
				$del_queue = "delete from jobs where jobid = '$conf';";
				$delRes = mysql_query($del_queue);
				if($delRes)
				{
					header('Location: ' . curPage());
				}
				else
					echo "<script>alert('There was a problem while the file was being deleted...');</script>";
			}
			else
				echo "<script>alert('There was a problem while the file was being deleted...');</script>";
		}
		else
			echo "<script>alert('There was a problem while the file was being deleted...');</script>";
	}
	else
		echo "<script>alert('There was a problem while the file was being deleted...');</script>";
}

/*
if(isset($_POST['refreshMe']))
{
	$cmd = "/var/www/mooneygroup/stopOutput/startscripts.sh";
	$result = passthru($cmd);
//	echo "<p>Processing complete.<p>";
}
*/
if($myUsername){
	include '/var/www/inc/defog_config.php';
	global $myEmail;
	$get = "select * from jobs where email='$myEmail' order by id desc;";
	$res = mysql_query($get);
	if($res)
	{
		if(mysql_num_rows($res))
		{
			$track = 0;
			echo "
<div class=\"rounded-block\">
	<div class=\"rounded-block-top-left\"></div>
	<div class=\"rounded-block-top-right\"></div>
	<div class=\"rounded-outside\">
	<div class=\"rounded-inside\">
	<p class=\"rounded-topspace\"></p>
	<h2 class=\"title block-title pngfix\">DEFOG Job Queue</h2>
	<div style=\"width:232px; background: url('/inc/topShadow.png') repeat-x; height:10px; position:absolute;\"></div>
	<div style=\"overflow:auto; height:200px;\">
	";

			$chkRes = mysql_query("select * from jobs where email='$myEmail';");
			if($chkRes && mysql_num_rows($chkRes))
			{

			/*echo "
	<form method=\"post\">
		<input type=\"submit\" name=\"refreshMe\" value=\"Refresh Queue\">
	</form>";*/
			}
			$numRows = mysql_num_rows($res);
			echo "
		<ul style=\"text-align: left;\">";
			while($job = mysql_fetch_assoc($res))
			{
				$track++;
				if($job['status'] == "e"){
					$status = "<br>Error: " . ucfirst($job['statusString']);
				}elseif($job['status'] != "c"){
					$status = "(Pending)";
					$refreshPage = "<a href=\"?action=refresh\">Rerun pending jobs...</a>";
				}else{
					$status = "";
					$refreshPage = "";
				}
				echo "
				<form method=\"post\">
					<input type=\"hidden\" name=\"jobid\" value=\"" . $job['jobid'] . "\">
					<li style=\"";
				if($track < $numRows)
					echo "border-bottom:2px solid #C7D0D8; padding: 0 0 8px 3px;";

				echo " margin: 0 3px 8px 3px; padding: 0 0 8px 3px;\">";
				if($job['job_title'])
				{
					echo "<b>" . $job['job_title'] . "</b> <a href=\"#\" onClick=\"show_" . $job['id'] . "()\" style=\"font-size:70%; margin: 0 0 5px 3px;\">[Rename]</a>
						<br style=\"font-size:70%\">" . $job['modtime'] . "</br>";
					$jobName = addslashes(strip_tags($job['job_title']));
				}
				else
				{
					echo $job['modtime'] . " <a href=\"#\" onClick=\"show_" . $job['id'] . "()\" style=\"font-size:70%; margin: 0 0 5px 3px;\">[Add Title]</a>";
					$jobName = $job['modtime'];
				}
				
				echo " $status<div></div>
					<script>
						function show_" . $job['id'] . "() { 
							$('#edit_" . $job['id'] . "').toggle(500);
						}
					</script>
					<div id=\"edit_" . $job['id'] . "\" style=\"display:none;\">
					<input type=\"hidden\" name=\"jobid\" value=\"" . $job['jobid'] . "\">
					<input type=\"hidden\" name=\"modtime\" value=\"" . $job['modtime'] . "\">
					<table>
						<tr>
							<td>
							<input type=\"text\" name=\"newName\" value=\"";
					if($job['job_title'])
						echo preg_replace("/\"/", "\\\"", $job['job_title']);
					echo "\" style=\"background-color:#fff; width:120px;\" class=\"deleteBox\">
							</td>
							<td>
							<input type=\"submit\" name=\"changeName\" value=\"Change\" class=\"deleteBox\">
							</td>
						</tr>
					</table>
					</div>
			";
				if($job['status'] == "c")
				{
					echo "
	<input type=\"button\" class=\"deleteBox\" onClick=\"window.location.href='/content/defog-results?jobid=" . $job['jobid'] . "'\" value=\"Bar Graph\">";
/*					echo "
	<input type=\"submit\" class=\"deleteBox\" onClick=\"window.open('/stop/include/downloadFile.php?conf=" . $job['jobid'] . "');\" value=\"Download CSV\">";*/
				}
	echo "
		<input type=\"submit\" value=\"Delete\" class=\"deleteBox\" name=\"deleteJob\" onClick=\"return confirm('Are you sure you want to delete this job: \\n$jobName ";
	if($jobName != $job['modtime'])
		echo "- (" . $job['modtime'] . ")";
	echo "\\nConf: " . $job['jobid'] . "');\">
";
	echo "</li>
		</form>
		";
				
			}
			echo "
		</ul>

	</div>
	<div style=\"width:100%; background: url('/inc/bottomShadow.png') repeat-x; height:10px; position:relative; margin-top: -8px;\"></div>
	<br>
	<a href=\"/logout\" style=\"padding-left:10px; text-decoration: none;\">logout</a>";
	if(!$myEmail)
		echo "<a href=\"/content/defog\" style=\"float:right; padding-right: 10px; text-decoration:none;\">DEFOG Input</a>";
	else
		echo "<a href=\"/content/defog?email=$myEmail\" style=\"float:right; padding-right:10px; text-decoration:none;\">DEFOG Input</a>";
	echo "	
	<p class=\"rounded-bottomspace\"></p>
	</div>
	</div>
	<div class=\"rounded-block-bottom-left\"></div>
	<div class=\"rounded-block-bottom-right\"></div>
</div>
";
		}
		else
		{
			echo "
<div class=\"rounded-block\">
	<div class=\"rounded-block-top-left\"></div>
	<div class=\"rounded-block-top-right\"></div>
	<div class=\"rounded-outside\">
	<div class=\"rounded-inside\">
	<p class=\"rounded-topspace\"></p>	
	<div style=\"text-align: center; padding: 5px 5px 5px 5px;\">
	You have not submitted anything yet.
	<br>
	<a href=\"/content/defog\">Click Here</a> to input your dataset to DEFOG. 
	</div>
	<p class=\"rounded-bottomspace\"></p>
	</div>
	</div>
	<div class=\"rounded-block-bottom-left\"></div>
	<div class=\"rounded-block-bottom-right\"></div>
</div>";

		}
	}
	else
	{
		echo "Error " . mysql_error();
	}
}
elseif(!$myUsername && $myEmail && isset($_GET['jobid'])){
	
	include '/var/www/inc/defog_config.php';
	$jobid = strip_tags($_GET['jobid']);
	global $myEmail;
	$get = "select * from jobs where jobid = '$jobid' order by id desc;";
	$res = mysql_query($get);
	if($res)
	{
		if(mysql_num_rows($res))
		{
			$track = 0;
			echo "
<div class=\"rounded-block\">
	<div class=\"rounded-block-top-left\"></div>
	<div class=\"rounded-block-top-right\"></div>
	<div class=\"rounded-outside\">
	<div class=\"rounded-inside\">
	<p class=\"rounded-topspace\"></p>
	<h2 class=\"title block-title pngfix\">DEFOG Job Queue</h2>
	<div style=\"width:232px; background: url('/inc/topShadow.png') repeat-x; height:10px; position:absolute;\"></div>
	<div style=\"overflow:auto; height:80px;\">



	<ul style=\"text-align: left;\">";
	

			$chkRes = mysql_query("select * from jobs where email='$myEmail';");
			if($chkRes && mysql_num_rows($chkRes))
			{
			/*
			echo "
	<form method=\"post\">
		<input type=\"submit\" name=\"refreshMe\" value=\"Refresh Queue\">
	</form>";
			*/
			}
			$numRows = mysql_num_rows($res);

			while($job = mysql_fetch_assoc($res))
			{
				$track++;
				if($job['status'] == "e"){
					$status = "<br>Error: " . ucfirst($job['progress']);
				}elseif($job['status'] != "c"){
					$status = "(Pending)";
					$refreshPage = "<a href=\"?action=refresh\">Rerun pending jobs...</a>";
				}else{
					$status = "";
					$refreshPage = "";
				}
				echo "
				<form method=\"post\">
					<li style=\"";
				if($track < $numRows)
					echo "border-bottom:2px solid #C7D0D8; padding: 0 0 8px 3px;";

				echo " margin: 0 3px 0 3px;\">";
				if($job['job_title'])
				{
					echo "<b>" . $job['job_title'] . "</b> <a href=\"#\" onClick=\"show_" . $job['id'] . "()\" style=\"font-size:70%; margin: 0 0 5px 3px;\">[Rename]</a>
						<br style=\"font-size:70%\">" . $job['modtime'] . "</br>";
					$jobName = addslashes(strip_tags($job['job_title']));
				}
				else
				{
					echo $job['modtime'] . " <a href=\"#\" onClick=\"show_" . $job['id'] . "()\" style=\"font-size:70%; margin: 0 0 5px 3px;\">[Add Title]</a>";

					$jobName = $job['modtime'];
				}
				
				echo " $status<div></div>
					<script>
						function show_" . $job['id'] . "() { 
							$('#edit_" . $job['id'] . "').toggle(500);
						}
					</script>
					<div id=\"edit_" . $job['id'] . "\" style=\"display:none;\">
					<input type=\"hidden\" name=\"jobid\" value=\"" . $job['jobid'] . "\">
					<input type=\"hidden\" name=\"modtime\" value=\"" . $job['modtime'] . "\">
					<table>
						<tr>
							<td>
							<input type=\"text\" name=\"newName\" value=\"";
					
					if($job['job_title'])
						echo preg_replace("/\"/", "'", $job['job_title']);

					echo "\" style=\"background-color:#fff; width:120px;\" class=\"deleteBox\">
							</td>
							<td>
							<input type=\"submit\" name=\"changeName\" value=\"Change\" class=\"deleteBox\">
							</td>
						</tr>
					</table>
					</div>
			";
				if($job['status'] == "c")
				{
					echo "
	<input type=\"button\" class=\"deleteBox\" onClick=\"window.location.href='/content/defog-results?email=$myEmail&jobid=" . $job['jobid'] . "'\" value=\"Bar Graph\"><br>
	<input type=\"submit\" class=\"deleteBox\" onClick=\"window.open('/stop/include/downloadFile.php?conf=" . $job['jobid'] . "');\" value=\"Download CSV\">";
				}
	echo "
		<input type=\"submit\" class=\"deleteBox\" value=\"Delete\" name=\"deleteJob\" onClick=\"return confirm('Are you sure you want to delete this job: \\n$jobName ";
	if($jobName != $job['modtime'])
		echo "- (" . $job['modtime'] . ")";
	echo "\\nConf: " . $job['jobid'] . "');\">
";
	echo "</li>
		</form>
		";
				
			}
			echo "
		</ul>


	</div>
	<div style=\"width:100%; background: url('/inc/bottomShadow.png') repeat-x; height:10px; position:relative; margin-top: -8px;\"></div>
	<br>
	<a href=\"/content/defog\" style=\"padding-left:10px; text-decoration: none;\">logout</a>";
	if(!$myEmail)
		echo "<a href=\"/content/defog\" style=\"float:right; padding-right: 10px; text-decoration:none;\">DEFOG Input</a>";
	else
		echo "<a href=\"/content/defog?email=$myEmail\" style=\"float:right; padding-right:10px; text-decoration:none;\">DEFOG Input</a>";
	echo "	
	<p class=\"rounded-bottomspace\"></p>
	</div>
	</div>
	<div class=\"rounded-block-bottom-left\"></div>
	<div class=\"rounded-block-bottom-right\"></div>
</div>
";
		}
		else
		{
			echo "
<div class=\"rounded-block\">
	<div class=\"rounded-block-top-left\"></div>
	<div class=\"rounded-block-top-right\"></div>
	<div class=\"rounded-outside\">
	<div class=\"rounded-inside\">
	<p class=\"rounded-topspace\"></p>	
	<div style=\"padding:5px;\">
	There is no data for these parameters...
	<br>
	<a href=\"/content/defog\" style=\"padding-left:10px; text-decoration: none;\">logout</a>";
	if(!$myEmail)
		echo "<a href=\"/content/defog\" style=\"float:right; padding-right: 10px; text-decoration:none;\">DEFOG Input</a>";
	else
		echo "<a href=\"/content/defog?email=$myEmail\" style=\"float:right; padding-right:10px; text-decoration:none;\">DEFOG Input</a>";
	echo "</div>
	<p class=\"rounded-bottomspace\"></p>
	</div>
	</div>
	<div class=\"rounded-block-bottom-left\"></div>
	<div class=\"rounded-block-bottom-right\"></div>
</div>";

		}
	}
	else
	{
		echo "Error " . mysql_error();
	}
}
else
{
	echo "
<div class=\"rounded-block\">
	<div class=\"rounded-block-top-left\"></div>
	<div class=\"rounded-block-top-right\"></div>
	<div class=\"rounded-outside\">
	<div class=\"rounded-inside\">
	<p class=\"rounded-topspace\"></p>
	<div style=\"text-align:center; padding: 5px;\">		
	";
	if(!$jobid)
	{
		if(isset($_POST['viewSingle']) && isset($_POST['new_conf']))
		{
			header('Location: http://mooneygroup.org/stop/bar-view?email='.$myEmail.'&jobid='.$_POST['new_conf']);
		}
		echo "Must have valid jobId to view DEFOG Job Queue";
		echo "<hr>
<form method=\"post\">
<table>
	<tr>
		<td valign=\"top\" height=\"20\">
			<input type=\"text\" name=\"new_conf\" value=\"Enter Confirmation ID\" onFocus=\"$(this).val(''); $(this).css('color','#000');\" style=\"color:#aaa; border: 2px solid #ccc; width:150px;\">
		</td>
		<td valign=\"top\" height=\"20\">
			<input type=\"submit\" name=\"viewSingle\" value=\"View\" class=\"deleteBox\" style=\"margin-top:0;\">
		</td>
	</tr>
	<tr>
		<td colspan=\"2\" align=\"left\">
			<a href=\"/content/defog\" style=\"font-size:80%; font-weight:bold;\">logout</a>
		</td>
	</tr>
</table>
</form>

			";
	}
	if(!$myEmail)
	{
		echo "Must be logged in (at least email) to use DEFOG
		<span style=\"font-size:80%; font-weight:bold; float:right; padding: 3px 0 0 0;\">
		<a href=\"/content/defog\">logout</a>
		</span>
			";
	}
	echo "
		

	</div>
	<p class=\"rounded-bottomspace\"></p>
	</div>
	</div>
	<div class=\"rounded-block-bottom-left\"></div>
	<div class=\"rounded-block-bottom-right\"></div>
</div>";
}



	
	
	
	
	

?>
