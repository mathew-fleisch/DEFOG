<style>
.studyContainer a, .studyContainer a:link, .studyContainer a:visited {
	color: #144A6E;
}
.studyContainer a:hover, .studyContainer a:active {
	color: #33839A;
}

#updown:hover {
	cursor: pointer;
}
</style>

<div id="closeSpacer" style="height:5px; width:100%;"></div>
<?php


//include '../../d/scripts/annSumConfig.php';
include '/var/www/inc/defog_config.php';
if(isset($_GET['id']))
	$id = $_GET['id'];
else
	$id = "Error";

if(isset($_GET['jobid']))
	$jobid = $_GET['jobid'];
else
	$jobid = "Error";


$ont_id_array = array();
if($jobid != "Error" && $title != "Error")
{
	//echo "<script>alert('titleafter=" . addslashes($title) . "');</script>";
	echo "<div class=\"topTermBar\">" . ucfirst(strtolower($id)) . "</div>";
	$getTerm = "select clusterElements.id, clusterElements.element, clusterElements.jobid, genes.name, genes.id as `geneID`
		from clusterElements, genes 
		where clusterElements.id = '$id' and clusterElements.jobid = '$jobid' and clusterElements.element = genes.name;";
	$termRes = mysql_query($getTerm);
	$msg = "";
	if($termRes && mysql_num_rows($termRes))
	{
		echo "<p>";
		while($term = mysql_fetch_assoc($termRes))
		{
			$msg .= "<a href=\"http://www.ncbi.nlm.nih.gov/gene/" . $term['geneID'] . "\" target=\"_blank\">" . $term['element'] . "</a>, ";

		}
		$msg = substr($msg, 0, -2);
	}


			echo "
				<div id=\"updown\"></div>
				<div class=\"clear\"></div>
				<div id=\"studyContainer\">$msg</div>



				</div>";
}
else
{
	echo "Error getting term info...";
	exit();
}
?>
