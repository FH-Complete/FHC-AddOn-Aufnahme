<div class="container">
    <div class="pull-right">
	<h1><?php echo $this->config->item("organisation")["bezeichnung"]; ?></h1>
	<span><?php echo $this->config->item("organisation")["strasse"]; ?></span></br>
	<span><?php echo $this->config->item("organisation")["plz"]; ?>&nbsp;<?php echo $this->config->item("organisation")["ort"]; ?></span></br>
	
	<span><?php echo ($this->config->item("organisation")["telefon"] != "") ?  "T: ".$this->config->item("organisation")["telefon"] : ""; ?></span></br>
	<span><?php echo ($this->config->item("organisation")["fax"] != "") ?  "F: ".$this->config->item("organisation")["fax"] : ""; ?></span></br>
	<span><?php echo ($this->config->item("organisation")["mail"] != "") ?  "E-Mail: ".$this->config->item("organisation")["mail"] : ""; ?></span></br>
    </div>
</div>
<div id="meta-footer" class="bar">
    <div class="container-fluid">
	<nav role="navigation">
	    <div>
		<ul class="nav navbar-nav navbar-right">
		    <li><?php echo $this->config->item("impressumLink"); ?></li>
		    <li><?php echo $this->config->item("anfahrtLink"); ?></li>
		</ul>
	    </div>
	</nav>
    </div>
</div>
</body>
</html>
