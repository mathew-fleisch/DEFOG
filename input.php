<?php
include '/var/www/inc/defog_config.php';

if(isset($_GET['email'])) 
	$email = "'" . strip_tags(trim($_GET['email'])) . "'";
else
	$email = "null";


if(isset($_POST['submit']))
{
	$newJobID = md5(uniqid(rand(), true));
	if(isset($_POST['organism']))
		$organism	= "'" . $_POST['organism'] . "'";
	else
		$organism	= "null";

	if(isset($_POST['genelist']))
		$geneList	= "'" . strip_tags($_POST['genelist']) . "'";
	else
		$geneList	= "null";

	if(isset($_POST['background']))
		$background	= "'" . strip_tags($_POST['background']) ."'";
	else
		$background	= "null";

	if(isset($_POST['networks']))
		$networks	= "'" . $_POST['networks'] . "'";
	else
		$networks	= "null";

	if(isset($_POST['combining_method']))
		$combining_meth = "'" . $_POST['combining_method'] . "'";
	else
		$combining_meth	= "null";

	if(isset($_POST['maxhierarchydepth']))
		$maxDepth	= "'" . $_POST['maxhierarchydepth'] . "'";
	else
		$maxDepth	= "null";

	if(isset($_POST['minsize']))
		$minsize	= "'" . $_POST['minsize'] . "'";
	else
		$minsize	= "null";

	if(isset($_POST['statistic']))
		$statistic	= "'" . $_POST['statistic'] . "'";
	else
		$statistic 	= "null";

	if(isset($_POST['multiplehypothesiscorrection']))
		$multHyp	= "'" . $_POST['multiplehypothesiscorrection'] . "'";	
	else
		$multHyp 	= "null";


	$insert = "insert into jobs (jobid, organism, email, status, statusString, data, background, networks, combining_method, maxhierarchydepth, minsize, statistic, multiplehypothesiscorrection)
		values ('$newJobID', $organism, $email, 'q', '', $geneList, $background,  $networks, $combining_meth, $maxDepth, $minsize, $statistic, $multHyp);";
	$result = mysql_query($insert);
	putenv("PATH=/sbin:/usr/sbin:/bin:/usr/bin:/usr/local/bin");
	$cmd = "mlaunch sh defog.sh $newJobID";
	$ps = shell_exec($cmd);
	if($result)
	{
		echo "
<script>
$(document).ready(function() {
	$('#formHolder').toggle(300);
});
</script>
";

		echo "Job Submitted...";
		echo "<iframe src=\"/defogInclude/queueStatus.php?jobid=$newJobID\" frameborder=\"0\" 
			onLoad=\"calcHeight();\" scrolling=\"no\" id=\"glu\" target=\"_self\"
			width=\"100%\" height=\"510\" style=\"padding:10px;\"></iframe>";
	}
	else
		echo "Error Submitting Job<br>$insert<br>" . mysql_error();
}
include 'inputForm.php';
?>
