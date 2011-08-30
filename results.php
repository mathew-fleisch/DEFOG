<link rel="stylesheet" type="text/css" href="/defogInclude/ajax/css/ajax-tooltip.css"/>
<script type="text/javascript" src="/defogInclude/ajax/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="/defogInclude/ajax/js/ajax.js"></script>
<script type="text/javascript" src="/defogInclude/ajax/js/ajax-tooltip.js"></script>
<?php
include '/var/www/inc/defog_config.php';
if(isset($_GET['jobid']))
{
	$jobid = strip_tags(addslashes($_GET['jobid']));
}
else
{
	echo "Must have valid job id to view results";
	$jobid = null;
}


if($jobid)
{
	echo "
<script type=\"text/javascript\" src=\"/defogInclude/js/min/AC_OETags.min.js\"></script>
<script type=\"text/javascript\" src=\"/defogInclude/js/min/json2.min.js\"></script>
<script type=\"text/javascript\" src=\"/defogInclude/js/min/cytoscapeweb.min.js\"></script>
";
	$get = "SELECT *  FROM cluster WHERE jobid = '$jobid' ORDER BY parentid, level;";
	$res = mysql_query($get);
	if($res)
	{
		if(mysql_num_rows($res))
		{
			$names = array();
			$parents = array();
			$clusterIds = array();

			echo "
<script type=\"text/javascript\">
window.onload=function() {
	var div_id = \"cytoscapeweb\";
	var networ_json = {
		dataSchema: {
			nodes: [ { name:\"label\", type:\"string\", },
			         { name:\"weight\", type:\"double\" },
			         { name:\"term_id\", type:\"string\" },
				 { name:\"num_children\", type:\"double\" } ],
			edges: [ { name:\"label\", type:\"string\" } ]
		},
		data: { ";
			while($row = mysql_fetch_assoc($res))
			{

				$names = array_push_assoc($names, $row['id'], $row['name']);
				$parents = array_push_assoc($parents,$row['id'],$row['parentid']);

			}
			echo "
			nodes: [ ";
			$names_str = "";
			foreach($names as $key=>$val)
		      	{
				$temp = preg_split("/_/", $val);
				$dummy = $temp[2]+0;
				if($temp[2])
				{
					$getCount = "SELECT count(id) as `count`  FROM `cluster` WHERE `parentid` = '$key'";
					$countRes = mysql_query($getCount);
					$count = "";
					if($countRes && mysql_num_rows($countRes))
					{
						$countArr = mysql_fetch_assoc($countRes);
						$count = $countArr['count'];
					}
					$names_str .= "
				{ id: \"$key\", label: \"" . $temp[2] . "\", weight: $dummy , term_id: \"$key\", num_children:\"$count\" },";
				}
			}
			
			echo substr($names_str, 0, -1) . "],
			edges: [ ";
			$parents_str = "";
			foreach($parents as $key=>$val)
		      	{
				if($val != -1)
				{
					$parents_str .= "
				{ id: \"" . $key . "to" . $val . "\", label: \"" . $names[$key] . "\", target: \"$key\", source: \"$val\" },";
				}
			}
			echo substr($parents_str, 0, -1) . " ] }
	};

	var options = {
		swfPath: \"/defogInclude/swf/CytoscapeWeb\",
		flashInstallerPath: \"/defogInclude/swf/playerProductInstall\"
	};
	var vis = new org.cytoscapeweb.Visualization(div_id, options);
	vis.ready(function() {
		vis.addListener(\"click\", \"nodes\", function(event) {
			handle_click(event);
		});
		vis.addListener(\"dblclick\", \"nodes\", function(event) {
			handle_dblclick(event);
		});
		function handle_click(event) {
			var target = event.target;
			var crnt_name = target.data.term_id;
			$('#glu').attr(\"src\",\"/defogInclude/showTable.php?jobid=$jobid&clusterid=\"+crnt_name);
		}
		function handle_dblclick(event) {
			var target = event.target;
			var crnt_id = target.data.id;
			ajax_showTooltip(window.event,'/defogInclude/ajax/getGenes.php?id=' + crnt_id + '&jobid=$jobid', this);
		}
	});

	var sizeMapper = { attrName: \"weight\", minValue: 50, maxValue: 80, maxAttrValue: 50, minAttrValue: 0 };
	var colorMapper = { attrName: \"weight\", minValue: \"#dfebef\", maxValue: \"#037da2\", maxAttrValue: 50, minAttrValue: 0 };


	vis.draw({ 
		network: networ_json, 
		layout: {
			name: 'Tree',
			options: {
				depthSpace: 	20,	//vertical spacing
				breadthSpace: 	30,	//horizontal spacing
				subtreeSpace:	35	//subtree spacing
			}
		},
		visualStyle: {
			nodes: { 
				color: {continuousMapper: colorMapper}, labelFontSize: 30,";
			$org = "";
			$getOrg = "select * from jobs where jobid = '$jobid';";
			$orgRes = mysql_query($getOrg);
			if($orgRes && mysql_num_rows($orgRes))
			{
				$row = mysql_fetch_assoc($orgRes);
				$org = $row['organism'];
			}
			switch($org)
			{
				case "H. sapiens":
				echo "
				shape: \"ellipse\",
				size: {continuousMapper: sizeMapper}";
					break;
				case "M. musculus":
				echo "
				shape: \"rectangle\",
				size: {continuousMapper: sizeMapper}";
					break;
				case "D. melanogaster":
				echo "
				shape: \"triangle\",
				size: {continuousMapper: sizeMapper}";
					break;
				case "C. elegans":
				echo "
				shape: \"diamond\",
				size: {continuousMapper: sizeMapper}";
					break;
				case "S. cerevisiae":
				echo "
				shape: \"hexagon\",
				size: {continuousMapper: sizeMapper}";
					break;
				case "A. Thaliana":
				echo "
				shape: \"parallelogram\",
				size: {continuousMapper: sizeMapper}";
					break;
			}
				echo"
			}
		} 
	});
	";
/*	//Force Directed Layout
	echo "
	vis.layout('ForceDirected');
	var options = {
		drag:		0.2,
		gravitation: 	-200,
		minDistance:	100,
		maxDistance:	300,
		mass:		20,
		tension:	0.2,
		weightAttr:	\"num_children\",
		restLength:	100,
		iterations:	200,
		maxTime:	10000,
		autoStabilize:	true
	};
	vis.layout({ name: 'ForceDirected', options: options });"; 
 */
	echo "
};
</script>
";
		}
		else
		{
			echo "No information saved for jobid = $jobid<p>";
		}
	}
	else
	{
		echo "mysql error<p>$get<p>" . mysql_error();
	}
	echo "<div id=\"cytoscapeweb\" style=\"600px;height:400px;\"></div>";
}
function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;
}
?>
<script>
function calcHeight()
{
	var the_height=10;
	document.getElementById('glu').height=the_height;

	the_height=
		document.getElementById('glu').contentWindow.
		document.body.scrollHeight;
	document.getElementById('glu').height=
		the_height;
}
</script>
<iframe src="" width="96%" height="200" id="glu" frameborder="0" scrolling="no" onLoad="calcHeight()"></iframe>
