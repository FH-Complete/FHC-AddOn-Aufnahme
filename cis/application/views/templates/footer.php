<div class="panel-footer footer">
    <div class="pull-right">
	<h1><?php echo $this->config->item("organisation")["bezeichnung"]; ?></h1>
	<span><?php echo $this->config->item("organisation")["strasse"]; ?></span></br>
	<span><?php echo $this->config->item("organisation")["plz"]; ?>&nbsp;<?php echo $this->config->item("organisation")["ort"]; ?></span></br>
	
	<span><?php echo ($this->config->item("organisation")["telefon"] != "") ?  "T: ".$this->config->item("organisation")["telefon"] : ""; ?></span></br>
	<span><?php echo ($this->config->item("organisation")["fax"] != "") ?  "F: ".$this->config->item("organisation")["fax"] : ""; ?></span></br>
	<span><?php echo ($this->config->item("organisation")["mail"] != "") ?  "E-Mail: ".$this->config->item("organisation")["mail"] : ""; ?></span></br>
    </div>
</div>
</body>
</html>
