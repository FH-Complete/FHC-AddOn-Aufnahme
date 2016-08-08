<footer id="portal-footer" class="module shadow-top" role="contentinfo">
    <div class="container">
	<section class="row">
	    <div class="col-sm-4">
	    </div>
	    <div class="col-sm-4">
	    </div>
	    <div class="col-sm-4 adress">
		<h3><?php echo $this->config->item("organisation")["bezeichnung"]; ?></h3>
		<span><?php echo $this->config->item("organisation")["strasse"]; ?></span></br>
		<span><?php echo $this->config->item("organisation")["plz"]; ?>&nbsp;<?php echo $this->config->item("organisation")["ort"]; ?></span></br>

		<span><?php echo ($this->config->item("organisation")["telefon"] != "") ?  "T: ".$this->config->item("organisation")["telefon"] : ""; ?></span></br>
		<span><?php echo ($this->config->item("organisation")["fax"] != "") ?  "F: ".$this->config->item("organisation")["fax"] : ""; ?></span></br>
		<span><?php echo ($this->config->item("organisation")["mail"] != "") ?  "E-Mail: ".$this->config->item("organisation")["mail"] : ""; ?></span></br>
	    </div>
	</section>
    </div>
</footer>
<footer id="meta-footer" class="bar">
    <div class="container-fluid">
	<nav role="navigation">
	    <div>
		<ul class="nav navbar-nav navbar-right">
		    <li class="impressum"><?php echo $this->config->item("impressumLink"); ?></li>
		    <li class="anfahrt"><?php echo $this->config->item("anfahrtLink"); ?></li>
		</ul>
	    </div>
	</nav>
    </div>
</footer>
</body>
</html>
