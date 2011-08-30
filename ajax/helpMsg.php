<?php
if(isset($_GET['id']))
{
	$msgID = $_GET['id'];
	switch($msgID)
	{
		case 1:
			echo "<p>
				<center>For <b>Genes</b>, enter a comma delimited list of Entrez Gene ID's.</center>
			<br><b>Example:</b><br>10815,1621,27429,120892,4137,4729";
			echo "<hr><p>
				<center>For <b>Proteins</b>, enter a comma delimited list of UniProt Protein ID's.</center>
			<br><b>Example:</b><br>O14810,P09172,O43464,Q5S007,P10636";
			break;
	}
}
?>
