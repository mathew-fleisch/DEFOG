<style>
.textBox {
	font-family: verdana, arial, tahoma;
}
</style>
<?php
include '/var/www/inc/defog_config.php';

if(isset($_GET['jobid']))
{
	$jobid = strip_tags(addslashes($_GET['jobid']));
}
else
	$jobid = null;

if(isset($_GET['clusterid']))
{
	$clusterid = strip_tags(addslashes($_GET['clusterid']));
}
else
	$clusterid = null;

if(!$jobid || !$clusterid)
{
	echo "Must have valid jobId and clusterId variables";
}
else
{
	$getTable = "select * from clusterTerms where jobid = '$jobid' and id = '$clusterid' order by corrected_pvalue asc;";
	$tableRes = mysql_query($getTable);
	if($tableRes)
	{
		if(mysql_num_rows($tableRes))
		{
			echo "
<table width=\"100%\" cellspacing=\"2\" cellpadding=\"3\" >
<thead>
	<tr style=\"background-color:#015c79\" >
		<td class=\"textBox\" width=\"5%\" align=\"center\" style=\"border-bottom: 2px solid #444;\" >
			<b style=\"color: rgb(255,255,255)\">NSP</b>
		</td>
		<td class=\"textBox\" width=\"55%\" style=\"border-bottom: 2px solid #444;\">
			<b style=\"color: rgb(255,255,255)\">Term Name</b>
		</td>
		<td class=\"textBox\" width=\"8%\" align=\"right\" style=\"border-bottom: 2px solid #444;\">
			<b style=\"color: rgb(255,255,255)\">Background</b>
		</td>
		<td class=\"textBox\" width=\"8%\" align=\"right\" style=\"border-bottom: 2px solid #444;\">
			<b style=\"color: rgb(255,255,255)\">Cluster</b>
		</td>
		<td class=\"textBox\" width=\"12%\" align=\"right\"  style=\"border-bottom: 2px solid #444;\">
			<b style=\"color: rgb(255,255,255)\">P-Value</b>
		</td>
		<td class=\"textBox\" width=\"12%\" align=\"right\" style=\"border-bottom: 2px solid #444;\">
			<b style=\"color: rgb(255,255,255)\">Corrected P-Value</b>
		</td>
	</tr>
</thead>
<tbody>
";
			$track = 0;
			while($row = mysql_fetch_assoc($tableRes))
			{
				if($row['corrected_pvalue']<0.05)
				{
				if($track % 2)
				{
					echo "
	<tr>";
				}
				else
				{
				echo "
	<tr style=\"background-color:#dfebef;\">";
				}
				$track++;
				echo "
		<td class=\"textBox\" align=\"center\">"  . $row['ontology_name'] . "</td>
		<td class=\"textBox\"><a href=\"http://amigo.geneontology.org/cgi-bin/amigo/term_details?term=" . $row['term_id'] . "\" style=\"color: rgb(0,0,0)\"><font color=\"#000000\"><b>" . $row['term_name'] . "</b></font></a></td>
		<td class=\"textBox\" align=\"right\">" .  $row['number_elements_population']."/".$row['number_elements_population_total'] . "</td>
		<td class=\"textBox\" align=\"right\">" .  $row['number_elements_study']."/".$row['number_elements_study_total'] . "</td>
		<td class=\"textBox\" align=\"right\">" . sprintf("%.2e", $row['pvalue']) . "</td>
		<td class=\"textBox\"style=\"width:170px;\" align=\"right\"><b>" . sprintf("%.2e", $row['corrected_pvalue']) . "</b></td>

	</tr>
";
}
			}
			echo "
</tbody>
</table>
";
		}
		else
			echo "No results";
	}
	else
		echo "mysql error<br>$getTable<br>" . mysql_error();
	
}

?>
