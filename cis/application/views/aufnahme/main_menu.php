<?php $this->lang->load(array('aufnahme'), $sprache); ?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bewerber-navigation" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="bewerber-navigation">
			<ul class="nav nav-tabs">
				<?php foreach($tabs as $t): ?>
					<li class="<?php echo (isset($tab) && $tab==$t["id"]) ? 'active' : ''; ?>">
						<a href="<?php  echo base_url($this->config->config["index_page"]."/Aufnahme?tab=".$t["id"]); ?>">
							<?php echo $this->lang->line($t["label"]); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
<!--			<ul class="nav navbar-nav">
				<li>
					<a href="#allgemein" aria-controls="allgemein" role="tab" data-toggle="tab">
						Allgemein <br> &nbsp;
					</a>
				</li>
				<li>
					<a href="#daten" aria-controls="daten" role="tab" data-toggle="tab" style="background-color: #DFF0D8 !important">
						Persönliche Daten <br> <span style="color: #3c763d;">vollständig</span>							</a>
				</li>
				<li>
					<a href="#kontakt" aria-controls="kontakt" role="tab" data-toggle="tab" style="background-color: #DFF0D8 !important">
						Kontaktinformationen <br> <span style="color: #3c763d;">vollständig</span>							</a>
				</li>
				
										<li>
					<a href="#zgv" aria-controls="zgv" role="tab" data-toggle="tab" style="background-color: #DFF0D8 !important">
						Zugangsvoraussetzungen <br> <span style="color: #3c763d;">vollständig</span>							</a>
				</li>
				
				
										<li>
					<a href="#abschicken" aria-controls="abschicken" role="tab" data-toggle="tab">
						Abschließen <br> &nbsp;
					</a>
				</li>
				<li>
					<a href="bewerbung.php?logout=true">
						Logout <br> <span class="glyphicon glyphicon-log-out"></span>
					</a>
				</li>-->
			</ul>
		</div>
	</div>
</nav>
