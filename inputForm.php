<link rel="stylesheet" type="text/css" href="/defogCake/css/defog.css" />
<link rel="stylesheet" type="text/css" href="/defogInclude/style.css" />
<script type="text/javascript" src="/defogInclude/jquery.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$('#showAdvancedLink').click(function() {
		$('#showAdvancedLink').toggle();
		$('#hideAdvancedLink').toggle();
		$('#hiddenText').toggle(500);
	});
	$('#hideAdvancedLink').click(function() {
		$('#showAdvancedLink').toggle();
		$('#hideAdvancedLink').toggle();
		$('#hiddenText').toggle(500);
	});
	$('#background_set').hide();
	$('#backLink').click(function() {
		$('#backLink').toggle();
		$('#removeBack').toggle();
		$('#background_set').toggle(400);
	});
	$('#removeBack').click(function() {
		$('#backLink').toggle();
		$('#removeBack').toggle();
		$('#textbox1').val("");
		$('#background_set').toggle(400);
	});
});
</script>
<center>
<div id="formHolder">
<table class="CALogin">
<tr>
<td class="CALogin">
<form id="jobsInputForm" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<div style="display:none;">
	<input type="hidden" name="_method" value="POST" />
</div><p><b>Please select your organism: </b>
<select class="dropDown" name="organism" id="jobsOrganism">
<option value="H. sapiens">H. sapiens</option>
<option value="M. musculus">M. musculus</option>
<option value="D. melanogaster">D. melanogaster</option>
<option value="C. elegans">C. elegans</option>

<option value="S. cerevisiae">S. cerevisiae</option>
<option value="A. Thaliana">A. Thaliana</option>
</select></p><br />
<p><b>Please paste in a gene list below (genes on separate lines).</b></p>

<textarea name="genelist" <?php /* rows="15" cols="50" id="jobsGenelist"*/ ?> id="textbox" ></textarea>
<br />

<p>
<a href="javascript:;" id="backLink">Define Custom Background</a>
<a href="javascript:;" id="removeBack" style="display:none;">Remove Custom Background</a>
<div id="background_set">
Background:
<p style="text-align:center">
<textarea name="background" id="textbox1"></textarea>
</p>
</div>
</td>
</tr>
<tr></tr>

<tr></tr>
<tr></tr>
<tr><td class="CALogin"> 
<a href="javascript:;" id="showAdvancedLink"> Show advanced options</a>
<a href="javascript:;" id="hideAdvancedLink" style="display:none;"> Hide advanced options</a>
<br>
<div id="hiddenText" style="display:none;"> 



<h3 style="border-bottom: 1px solid #444; padding-left: 5px;">Functional networks (GeneMANIA) options:</h3>
<div style="padding: 0 10px 0 10px;">
<b>Please select the  the networks: </b><br>
<select class="dropDown" name="networks" id="jobsNetworks">
<option value="coexp">coexp</option>

<option value="coloc">coloc</option>
<option value="gi">gi</option>
<option value="pi">pi</option>
<option value="predict">predict</option>
<option value="spd">spd</option>
<option value="other">other</option>
<option value="all">all</option>
<option value="preferred">preferred</option>
<option value="default" selected>default</option>
</select></p><br />

<br><p><b>Please select the combining method for the networks: </b><br>
<select class="dropDown" name="combining_method" id="jobsCombiningMethod">
<option value="automatic_relevance">automatic_relevance</option>
<option value="average">average</option>
<option value="average_category">average_category</option>
<option value="bp">bp</option>
<option value="cc">cc</option>
<option value="mf">mf</option>
</select></p><br />
</div>
<h3 style="border-bottom: 1px solid #444; padding-left: 5px;">Clustering options:</h3>
<div style="padding: 0 10px 0 10px;">
<b>Please select the maximal depth of the hierarchy: </b><br>
<select class="dropDown" name="maxhierarchydepth" id="jobsMaxhierarchydepth">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>

<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10" selected="selected">10</option>
<option value="11">11</option>
<option value="12">12</option>

<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="30">30</option>
</select></p><br />
<br><p><b>Please select the minimal size of groups to be still displayed: </b><br>
<select class="dropDown" name="minsize" id="jobsMinsize">
<option value="1">1</option>
<option value="2">2</option>

<option value="3">3</option>
<option value="4">4</option>
<option value="5" selected="selected">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>

<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="30">30</option>
</select></p><br />
</div>
<h3 style="border-bottom: 1px solid #444; padding-left: 5px;">Enrichment options:</h3>
<div style="padding: 0 10px 0 10px;">
<b>Please select the statistic: </b><br>
<select class="dropDown" name="statistic" id="jobsStatistic">
<option value="Term-For-Term" selected="selected">Term-For-Term</option>

<option value="Parent-Child-Union">Parent-Child-Union</option>
<option value="Parent-Child-Intersection">Parent-Child-Intersection</option>
</select></p><br />
<br><p><b>Please select the method for multiple hypothesis correction: </b><br>
<select class="dropDown" name="multiplehypothesiscorrection" id="jobsMultiplehypothesiscorrection">
<option value=""></option>
<option value="Benjamini-Hochberg" selected="selected">Benjamini-Hochberg</option>
<option value="Benjamini-Yekutieli">Benjamini-Yekutieli</option>
<option value="Bonferroni">Bonferroni</option>
<option value="Bonferroni-Holm">Bonferroni-Holm</option>
<option value="Westfall-Young-Single-Step">Westfall-Young-Single-Step</option>

<option value="Westfall-Young-Step-Down">Westfall-Young-Step-Down</option>
</select></p><br />

</div>
</div>
</td></tr>

</table>
<div class="submit"><input type="submit" value="Start Analysis" name="submit"/></div></form>
</center>
</div>
