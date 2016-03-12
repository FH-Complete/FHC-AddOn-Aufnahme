<?php $this->lang->load(array('registration'), $sprache); ?>

<div class="container">
	<?php $this->load->view('language'); ?>

	<ol class="breadcrumb">
		<li class="active">Registration</li>
	</ol>
	<form method="post" action="registration.php?method=registration" id="RegistrationLoginForm" name="RegistrationLoginForm" class="form-horizontal">
		<img style="width:150px;" class="center-block img-responsive" src="<?php echo base_url('themes/'.$this->config->item('theme')); ?>/logo.png">	
		<h2 class="text-center"><?php echo $this->lang->line('login_greeting_text');?></h2>		
					<p class="infotext">
						Bitte füllen Sie das Formular aus, wählen Sie die gewünschte(n) Studienrichtung(en) und klicken Sie auf "Abschicken".<br>Danach erhalten Sie eine E-Mail mit Zugangscode an die angegebene Adresse.
		Mit dem Zugangscode können Sie sich jederzeit einloggen, Ihre Daten vervollständigen, Studienrichtungen hinzufügen und sich unverbindlich bewerben.					</p>
					

					<div class="form-group">
						<label for="vorname" class="col-sm-3 control-label">
							Vorname						</label>
						<div class="col-sm-4">
							<input type="text" maxlength="32" name="vorname" id="vorname" value="" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="nachname" class="col-sm-3 control-label">
							Nachname						</label>
						<div class="col-sm-4">
							<input type="text" maxlength="64" name="nachname" id="nachname" value="" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="geburtsdatum" class="col-sm-3 control-label">
							Geburtsdatum						</label>
						<div class="col-sm-4">
							<input type="datetime" name="geb_datum" id="geburtsdatum"
								   value=""
								   class="form-control" placeholder="tt.mm.jjjj">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label">
							Geschlecht						</label>
						<div class="col-sm-4 text-center">
							<label class="radio-inline">
								<input type="radio" name="geschlecht" id="geschlechtm" value="m" >
								Männlich							</label>
							<label class="radio-inline">
								<input type="radio" name="geschlecht" id="geschlechtw" value="w" >
								Weiblich							</label>
						</div>
					</div>

					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">
							E-Mail Adresse						</label>
						<div class="col-sm-4">
							<input type="email" maxlength="128" name="email" id="email" value="" class="form-control">
						</div>
					</div>

										<div class="form-group">
						<label for="studiensemester_kurzbz" class="col-sm-3 control-label">
							Geplanter Studienbeginn						</label>
						<div class="col-sm-4 dropdown">
							<select id="studiensemester_kurzbz" name="studiensemester_kurzbz" class="form-control">
								<option value="">-- Bitte auswählen --</option>
																	<option value="WS2016"
										>
										Wintersemester 2016/2017 (ab 01.09.2016)									</option>
																	<option value="WS2017"
										>
										Wintersemester 2017/18 (ab 01.09.2017)									</option>
															</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">
							Gewünschte Studienrichtung(en)						</label>
						<div class="col-sm-6" id="liste-studiengaenge">
							<a href="#Bachelor" data-toggle="collapse"><h4>Bachelor  <small><span class="glyphicon glyphicon-collapse-down"></span></small></h4></a><div id="Bachelor" class="collapse">
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown227">
										<input type="checkbox" name="studiengaenge[]" value="227" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Biomedical Engineering
										<span class="badge" id="badge227"></span>
										<input type="hidden" id="anmerkung227" name="anmerkung[227]" value="">
										<input type="hidden" id="orgform227" name="orgform[227]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown254">
										<input type="checkbox" name="studiengaenge[]" value="254" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Elektronik
										<span class="badge" id="badge254"></span>
										<input type="hidden" id="anmerkung254" name="anmerkung[254]" value="">
										<input type="hidden" id="orgform254" name="orgform[254]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown255">
										<input type="checkbox" name="studiengaenge[]" value="255" 
												data-modal="1"
												data-modal-sprache="German,English"
												data-modal-orgform="BB,DL"
												data-modal-orgformsprache="BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English,BB_German,DL_English">
										Elektronik/Wirtschaft
										<span class="badge" id="badge255"></span>
										<input type="hidden" id="anmerkung255" name="anmerkung[255]" value="">
										<input type="hidden" id="orgform255" name="orgform[255]" value="">
									</label>
								</div>
								
										<div id="prio-dropown255" class="collapse"><div class="modal-dialog" style="margin: 10px 0 10px 20px;" data-stgkz="255">
										<div class="modal-content" style="box-shadow: none;">
										<div class="modal-header">
											<h4 class="modal-title">Organisationsform wählen</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12">
													<p>Bitte geben Sie an, für welche Organisationsform Sie sich interessieren. Für den Fall, dass alle Plätze in Ihrer gewünschten Organisationsform vergeben sind, können Sie optional eine Alternative angeben</p>
												</div>
											</div><div class="row" id="topprio255">
												<div class="col-sm-6 priogroup"><div class="radio" onchange="changePrio(255)">
															<label>
																<input type="radio" name="topprioOrgform255" value="BB_German">
																Berufsbegleitend - Deutsch
															</label>
														</div><div class="radio" onchange="changePrio(255)">
															<label>
																<input type="radio" name="topprioOrgform255" value="DL_English">
																Fernstudium - Englisch
															</label>
														</div></div></div><div class="row" id="alternative255">
												<div class="col-sm-12">
													<label data-toggle="collapse" data-target="#alternative-dropown255"><h5><b>Alternative (optional)</b> <span class="glyphicon glyphicon-collapse-down"></span></h5></label>
												</div>
												<div class="col-sm-6 priogroup collapse" id="alternative-dropown255">	<div class="radio" onchange="changePrio(255)">
																<label>
																	<input type="radio" name="alternativeOrgform255" value="keine">
																	gleichgültig
																</label>
															</div><div class="radio" onchange="changePrio(255)">
																<label>
																	<input type="radio" name="alternativeOrgform255" value="BB_German">
																	Berufsbegleitend - Deutsch
																</label>
															</div><div class="radio" onchange="changePrio(255)">
																<label>
																	<input type="radio" name="alternativeOrgform255" value="DL_English">
																	Fernstudium - Englisch
																</label>
															</div>
										</div></div></div></div></div></div>
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown257">
										<input type="checkbox" name="studiengaenge[]" value="257" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Informatik/Computer Science
										<span class="badge" id="badge257"></span>
										<input type="hidden" id="anmerkung257" name="anmerkung[257]" value="">
										<input type="hidden" id="orgform257" name="orgform[257]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown258">
										<input type="checkbox" name="studiengaenge[]" value="258" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Informations- und Kommunikationssysteme
										<span class="badge" id="badge258"></span>
										<input type="hidden" id="anmerkung258" name="anmerkung[258]" value="">
										<input type="hidden" id="orgform258" name="orgform[258]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown335">
										<input type="checkbox" name="studiengaenge[]" value="335" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Internationales Wirtschaftsingenieurwesen
										<span class="badge" id="badge335"></span>
										<input type="hidden" id="anmerkung335" name="anmerkung[335]" value="">
										<input type="hidden" id="orgform335" name="orgform[335]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown779">
										<input type="checkbox" name="studiengaenge[]" value="779" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Maschinenbau
										<span class="badge" id="badge779"></span>
										<input type="hidden" id="anmerkung779" name="anmerkung[779]" value="">
										<input type="hidden" id="orgform779" name="orgform[779]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown330">
										<input type="checkbox" name="studiengaenge[]" value="330" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Mechatronik/Robotik
										<span class="badge" id="badge330"></span>
										<input type="hidden" id="anmerkung330" name="anmerkung[330]" value="">
										<input type="hidden" id="orgform330" name="orgform[330]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown768">
										<input type="checkbox" name="studiengaenge[]" value="768" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Smart Homes und Assistive Technologien
										<span class="badge" id="badge768"></span>
										<input type="hidden" id="anmerkung768" name="anmerkung[768]" value="">
										<input type="hidden" id="orgform768" name="orgform[768]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown327">
										<input type="checkbox" name="studiengaenge[]" value="327" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Sports Equipment Technology / Sportgerätetechnik
										<span class="badge" id="badge327"></span>
										<input type="hidden" id="anmerkung327" name="anmerkung[327]" value="">
										<input type="hidden" id="orgform327" name="orgform[327]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown476">
										<input type="checkbox" name="studiengaenge[]" value="476" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Urbane Erneuerbare Energietechnologien
										<span class="badge" id="badge476"></span>
										<input type="hidden" id="anmerkung476" name="anmerkung[476]" value="">
										<input type="hidden" id="orgform476" name="orgform[476]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown333">
										<input type="checkbox" name="studiengaenge[]" value="333" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Verkehr und Umwelt
										<span class="badge" id="badge333"></span>
										<input type="hidden" id="anmerkung333" name="anmerkung[333]" value="">
										<input type="hidden" id="orgform333" name="orgform[333]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown256">
										<input type="checkbox" name="studiengaenge[]" value="256" 
												data-modal="1"
												data-modal-sprache="English,German"
												data-modal-orgform="BB,VZ,DL"
												data-modal-orgformsprache="DL_English,DDP_German,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,DDP_German,VZ_German,BB_German,DL_English,DDP_German,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,BB_German,DL_English,VZ_German,DL_English,DDP_German,VZ_German,BB_German,DL_English,DDP_German,VZ_German,BB_German,DL_English,DDP_German,VZ_German,BB_German">
										Wirtschaftsinformatik
										<span class="badge" id="badge256"></span>
										<input type="hidden" id="anmerkung256" name="anmerkung[256]" value="">
										<input type="hidden" id="orgform256" name="orgform[256]" value="">
									</label>
								</div>
								
										<div id="prio-dropown256" class="collapse"><div class="modal-dialog" style="margin: 10px 0 10px 20px;" data-stgkz="256">
										<div class="modal-content" style="box-shadow: none;">
										<div class="modal-header">
											<h4 class="modal-title">Organisationsform wählen</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12">
													<p>Bitte geben Sie an, für welche Organisationsform Sie sich interessieren. Für den Fall, dass alle Plätze in Ihrer gewünschten Organisationsform vergeben sind, können Sie optional eine Alternative angeben</p>
												</div>
											</div><div class="row" id="topprio256">
												<div class="col-sm-6 priogroup"><div class="radio" onchange="changePrio(256)">
															<label>
																<input type="radio" name="topprioOrgform256" value="BB_German">
																Berufsbegleitend - Deutsch
															</label>
														</div><div class="radio" onchange="changePrio(256)">
															<label>
																<input type="radio" name="topprioOrgform256" value="DL_English">
																Fernstudium - Englisch
															</label>
														</div><div class="radio" onchange="changePrio(256)">
															<label>
																<input type="radio" name="topprioOrgform256" value="VZ_German">
																Vollzeit - Deutsch
															</label>
														</div></div></div><div class="row" id="alternative256">
												<div class="col-sm-12">
													<label data-toggle="collapse" data-target="#alternative-dropown256"><h5><b>Alternative (optional)</b> <span class="glyphicon glyphicon-collapse-down"></span></h5></label>
												</div>
												<div class="col-sm-6 priogroup collapse" id="alternative-dropown256">	<div class="radio" onchange="changePrio(256)">
																<label>
																	<input type="radio" name="alternativeOrgform256" value="keine">
																	gleichgültig
																</label>
															</div><div class="radio" onchange="changePrio(256)">
																<label>
																	<input type="radio" name="alternativeOrgform256" value="BB_German">
																	Berufsbegleitend - Deutsch
																</label>
															</div><div class="radio" onchange="changePrio(256)">
																<label>
																	<input type="radio" name="alternativeOrgform256" value="DL_English">
																	Fernstudium - Englisch
																</label>
															</div><div class="radio" onchange="changePrio(256)">
																<label>
																	<input type="radio" name="alternativeOrgform256" value="VZ_German">
																	Vollzeit - Deutsch
																</label>
															</div>
										</div></div></div></div></div></div></div><a href="#Lehrgang" data-toggle="collapse"><h4>Lehrgang  <small><span class="glyphicon glyphicon-collapse-down"></span></small></h4></a><div id="Lehrgang" class="collapse">
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-16">
										<input type="checkbox" name="studiengaenge[]" value="-16" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German">
										App-Development (Postgradualer Lehrgang)
										<span class="badge" id="badge-16"></span>
										<input type="hidden" id="anmerkung-16" name="anmerkung[-16]" value="">
										<input type="hidden" id="orgform-16" name="orgform[-16]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-18">
										<input type="checkbox" name="studiengaenge[]" value="-18" 
												data-modal=""
												data-modal-sprache="English"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English">
										Pre College Program (Lehrgang zur Weiterbildung)
										<span class="badge" id="badge-18"></span>
										<input type="hidden" id="anmerkung-18" name="anmerkung[-18]" value="">
										<input type="hidden" id="orgform-18" name="orgform[-18]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-15">
										<input type="checkbox" name="studiengaenge[]" value="-15" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German">
										Projekt- und Prozessmanagement (Akademischer Lehrgang)
										<span class="badge" id="badge-15"></span>
										<input type="hidden" id="anmerkung-15" name="anmerkung[-15]" value="">
										<input type="hidden" id="orgform-15" name="orgform[-15]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-21">
										<input type="checkbox" name="studiengaenge[]" value="-21" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German">
										Projekt- und Prozessmanagement (Master Lehrgang)
										<span class="badge" id="badge-21"></span>
										<input type="hidden" id="anmerkung-21" name="anmerkung[-21]" value="">
										<input type="hidden" id="orgform-21" name="orgform[-21]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-27">
										<input type="checkbox" name="studiengaenge[]" value="-27" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German">
										Projekt- und Prozessmanagement (Zertifizierungslehrgang)
										<span class="badge" id="badge-27"></span>
										<input type="hidden" id="anmerkung-27" name="anmerkung[-27]" value="">
										<input type="hidden" id="orgform-27" name="orgform[-27]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-5">
										<input type="checkbox" name="studiengaenge[]" value="-5" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German">
										Social Media Management (Akademischer Lehrgang)
										<span class="badge" id="badge-5"></span>
										<input type="hidden" id="anmerkung-5" name="anmerkung[-5]" value="">
										<input type="hidden" id="orgform-5" name="orgform[-5]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-22">
										<input type="checkbox" name="studiengaenge[]" value="-22" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German">
										Social Media Management (Master Lehrgang)
										<span class="badge" id="badge-22"></span>
										<input type="hidden" id="anmerkung-22" name="anmerkung[-22]" value="">
										<input type="hidden" id="orgform-22" name="orgform[-22]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-25">
										<input type="checkbox" name="studiengaenge[]" value="-25" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German">
										Sporttechnologie (Akademischer Lehrgang)
										<span class="badge" id="badge-25"></span>
										<input type="hidden" id="anmerkung-25" name="anmerkung[-25]" value="">
										<input type="hidden" id="orgform-25" name="orgform[-25]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-19">
										<input type="checkbox" name="studiengaenge[]" value="-19" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform=""
												data-modal-orgformsprache="_German">
										User Experience Management (Akademischer Lehrgang)
										<span class="badge" id="badge-19"></span>
										<input type="hidden" id="anmerkung-19" name="anmerkung[-19]" value="">
										<input type="hidden" id="orgform-19" name="orgform[-19]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-20">
										<input type="checkbox" name="studiengaenge[]" value="-20" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform=""
												data-modal-orgformsprache="_German">
										User Experience Management (Master Lehrgang)
										<span class="badge" id="badge-20"></span>
										<input type="hidden" id="anmerkung-20" name="anmerkung[-20]" value="">
										<input type="hidden" id="orgform-20" name="orgform[-20]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-26">
										<input type="checkbox" name="studiengaenge[]" value="-26" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German">
										User Experience Management (Zertifizierungslehrgang)
										<span class="badge" id="badge-26"></span>
										<input type="hidden" id="anmerkung-26" name="anmerkung[-26]" value="">
										<input type="hidden" id="orgform-26" name="orgform[-26]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown-17">
										<input type="checkbox" name="studiengaenge[]" value="-17" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German">
										Web-Development (Postgradualer Lehrgang)
										<span class="badge" id="badge-17"></span>
										<input type="hidden" id="anmerkung-17" name="anmerkung[-17]" value="">
										<input type="hidden" id="orgform-17" name="orgform[-17]" value="">
									</label>
								</div>
								</div><a href="#Master" data-toggle="collapse"><h4>Master  <small><span class="glyphicon glyphicon-collapse-down"></span></small></h4></a><div id="Master" class="collapse">
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown228">
										<input type="checkbox" name="studiengaenge[]" value="228" 
												data-modal=""
												data-modal-sprache="English"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English,VZ_English">
										Biomedical Engineering Sciences
										<span class="badge" id="badge228"></span>
										<input type="hidden" id="anmerkung228" name="anmerkung[228]" value="">
										<input type="hidden" id="orgform228" name="orgform[228]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown297">
										<input type="checkbox" name="studiengaenge[]" value="297" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Embedded Systems
										<span class="badge" id="badge297"></span>
										<input type="hidden" id="anmerkung297" name="anmerkung[297]" value="">
										<input type="hidden" id="orgform297" name="orgform[297]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown578">
										<input type="checkbox" name="studiengaenge[]" value="578" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Erneuerbare Urbane Energiesysteme
										<span class="badge" id="badge578"></span>
										<input type="hidden" id="anmerkung578" name="anmerkung[578]" value="">
										<input type="hidden" id="orgform578" name="orgform[578]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown585">
										<input type="checkbox" name="studiengaenge[]" value="585" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Game Engineering und Simulation
										<span class="badge" id="badge585"></span>
										<input type="hidden" id="anmerkung585" name="anmerkung[585]" value="">
										<input type="hidden" id="orgform585" name="orgform[585]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown329">
										<input type="checkbox" name="studiengaenge[]" value="329" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Gesundheits- und Rehabilitationstechnik
										<span class="badge" id="badge329"></span>
										<input type="hidden" id="anmerkung329" name="anmerkung[329]" value="">
										<input type="hidden" id="orgform329" name="orgform[329]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown300">
										<input type="checkbox" name="studiengaenge[]" value="300" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Industrielle Elektronik
										<span class="badge" id="badge300"></span>
										<input type="hidden" id="anmerkung300" name="anmerkung[300]" value="">
										<input type="hidden" id="orgform300" name="orgform[300]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown303">
										<input type="checkbox" name="studiengaenge[]" value="303" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Informationsmanagement und Computersicherheit
										<span class="badge" id="badge303"></span>
										<input type="hidden" id="anmerkung303" name="anmerkung[303]" value="">
										<input type="hidden" id="orgform303" name="orgform[303]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown301">
										<input type="checkbox" name="studiengaenge[]" value="301" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Innovations- und Technologiemanagement
										<span class="badge" id="badge301"></span>
										<input type="hidden" id="anmerkung301" name="anmerkung[301]" value="">
										<input type="hidden" id="orgform301" name="orgform[301]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown334">
										<input type="checkbox" name="studiengaenge[]" value="334" 
												data-modal=""
												data-modal-sprache="English"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_English,VZ_English,VZ_English">
										Integrative Stadtentwicklung – Smart City
										<span class="badge" id="badge334"></span>
										<input type="hidden" id="anmerkung334" name="anmerkung[334]" value="">
										<input type="hidden" id="orgform334" name="orgform[334]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown336">
										<input type="checkbox" name="studiengaenge[]" value="336" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Internationales Wirtschaftsingenieurwesen
										<span class="badge" id="badge336"></span>
										<input type="hidden" id="anmerkung336" name="anmerkung[336]" value="">
										<input type="hidden" id="orgform336" name="orgform[336]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown331">
										<input type="checkbox" name="studiengaenge[]" value="331" 
												data-modal="1"
												data-modal-sprache="German"
												data-modal-orgform="BB,VZ"
												data-modal-orgformsprache="VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German,VZ_German,BB_German">
										Mechatronik/Robotik
										<span class="badge" id="badge331"></span>
										<input type="hidden" id="anmerkung331" name="anmerkung[331]" value="">
										<input type="hidden" id="orgform331" name="orgform[331]" value="">
									</label>
								</div>
								
										<div id="prio-dropown331" class="collapse"><div class="modal-dialog" style="margin: 10px 0 10px 20px;" data-stgkz="331">
										<div class="modal-content" style="box-shadow: none;">
										<div class="modal-header">
											<h4 class="modal-title">Organisationsform wählen</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12">
													<p>Bitte geben Sie an, für welche Organisationsform Sie sich interessieren. Für den Fall, dass alle Plätze in Ihrer gewünschten Organisationsform vergeben sind, können Sie optional eine Alternative angeben</p>
												</div>
											</div><div class="row" id="topprio331">
												<div class="col-sm-6 priogroup"><div class="radio" onchange="changePrio(331)">
															<label>
																<input type="radio" name="topprioOrgform331" value="BB_German">
																Berufsbegleitend - Deutsch
															</label>
														</div><div class="radio" onchange="changePrio(331)">
															<label>
																<input type="radio" name="topprioOrgform331" value="VZ_German">
																Vollzeit - Deutsch
															</label>
														</div></div></div><div class="row" id="alternative331">
												<div class="col-sm-12">
													<label data-toggle="collapse" data-target="#alternative-dropown331"><h5><b>Alternative (optional)</b> <span class="glyphicon glyphicon-collapse-down"></span></h5></label>
												</div>
												<div class="col-sm-6 priogroup collapse" id="alternative-dropown331">	<div class="radio" onchange="changePrio(331)">
																<label>
																	<input type="radio" name="alternativeOrgform331" value="keine">
																	gleichgültig
																</label>
															</div><div class="radio" onchange="changePrio(331)">
																<label>
																	<input type="radio" name="alternativeOrgform331" value="BB_German">
																	Berufsbegleitend - Deutsch
																</label>
															</div><div class="radio" onchange="changePrio(331)">
																<label>
																	<input type="radio" name="alternativeOrgform331" value="VZ_German">
																	Vollzeit - Deutsch
																</label>
															</div>
										</div></div></div></div></div></div>
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown299">
										<input type="checkbox" name="studiengaenge[]" value="299" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Softwareentwicklung
										<span class="badge" id="badge299"></span>
										<input type="hidden" id="anmerkung299" name="anmerkung[299]" value="">
										<input type="hidden" id="orgform299" name="orgform[299]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown328">
										<input type="checkbox" name="studiengaenge[]" value="328" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="VZ"
												data-modal-orgformsprache="VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German,VZ_German">
										Sports Equipment Technology / Sportgerätetechnik
										<span class="badge" id="badge328"></span>
										<input type="hidden" id="anmerkung328" name="anmerkung[328]" value="">
										<input type="hidden" id="orgform328" name="orgform[328]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown332">
										<input type="checkbox" name="studiengaenge[]" value="332" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Technisches Umweltmanagement und Ökotoxikologie
										<span class="badge" id="badge332"></span>
										<input type="hidden" id="anmerkung332" name="anmerkung[332]" value="">
										<input type="hidden" id="orgform332" name="orgform[332]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown298">
										<input type="checkbox" name="studiengaenge[]" value="298" 
												data-modal=""
												data-modal-sprache="German"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German,BB_German">
										Telekommunikation und Internettechnologien
										<span class="badge" id="badge298"></span>
										<input type="hidden" id="anmerkung298" name="anmerkung[298]" value="">
										<input type="hidden" id="orgform298" name="orgform[298]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown692">
										<input type="checkbox" name="studiengaenge[]" value="692" 
												data-modal=""
												data-modal-sprache="English"
												data-modal-orgform="BB"
												data-modal-orgformsprache="BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English,BB_English">
										Tissue Engineering and Regenerative Medicine
										<span class="badge" id="badge692"></span>
										<input type="hidden" id="anmerkung692" name="anmerkung[692]" value="">
										<input type="hidden" id="orgform692" name="orgform[692]" value="">
									</label>
								</div>
								
								<div class="checkbox">
									<label data-toggle="collapse" data-target="#prio-dropown302">
										<input type="checkbox" name="studiengaenge[]" value="302" 
												data-modal="1"
												data-modal-sprache="English,German"
												data-modal-orgform="BB,DL,PT"
												data-modal-orgformsprache="BB_German,DL_English,DDP_English,BB_German,DL_English,DDP_English,BB_German,DL_English,DDP_English,BB_German,DL_English,DDP_English,BB_German,DL_English,DDP_English,BB_German,DL_English,DDP_English,BB_German,DL_English,PT_English,BB_German,DL_English,PT_English,BB_German,DL_English,PT_English">
										Wirtschaftsinformatik
										<span class="badge" id="badge302"></span>
										<input type="hidden" id="anmerkung302" name="anmerkung[302]" value="">
										<input type="hidden" id="orgform302" name="orgform[302]" value="">
									</label>
								</div>
								
										<div id="prio-dropown302" class="collapse"><div class="modal-dialog" style="margin: 10px 0 10px 20px;" data-stgkz="302">
										<div class="modal-content" style="box-shadow: none;">
										<div class="modal-header">
											<h4 class="modal-title">Organisationsform wählen</h4>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-sm-12">
													<p>Bitte geben Sie an, für welche Organisationsform Sie sich interessieren. Für den Fall, dass alle Plätze in Ihrer gewünschten Organisationsform vergeben sind, können Sie optional eine Alternative angeben</p>
												</div>
											</div><div class="row" id="topprio302">
												<div class="col-sm-6 priogroup"><div class="radio" onchange="changePrio(302)">
															<label>
																<input type="radio" name="topprioOrgform302" value="BB_German">
																Berufsbegleitend - Deutsch
															</label>
														</div><div class="radio" onchange="changePrio(302)">
															<label>
																<input type="radio" name="topprioOrgform302" value="DL_English">
																Fernstudium - Englisch
															</label>
														</div><div class="radio" onchange="changePrio(302)">
															<label>
																<input type="radio" name="topprioOrgform302" value="PT_English">
																Part time - Englisch
															</label>
														</div></div></div><div class="row" id="alternative302">
												<div class="col-sm-12">
													<label data-toggle="collapse" data-target="#alternative-dropown302"><h5><b>Alternative (optional)</b> <span class="glyphicon glyphicon-collapse-down"></span></h5></label>
												</div>
												<div class="col-sm-6 priogroup collapse" id="alternative-dropown302">	<div class="radio" onchange="changePrio(302)">
																<label>
																	<input type="radio" name="alternativeOrgform302" value="keine">
																	gleichgültig
																</label>
															</div><div class="radio" onchange="changePrio(302)">
																<label>
																	<input type="radio" name="alternativeOrgform302" value="BB_German">
																	Berufsbegleitend - Deutsch
																</label>
															</div><div class="radio" onchange="changePrio(302)">
																<label>
																	<input type="radio" name="alternativeOrgform302" value="DL_English">
																	Fernstudium - Englisch
																</label>
															</div><div class="radio" onchange="changePrio(302)">
																<label>
																	<input type="radio" name="alternativeOrgform302" value="PT_English">
																	Part time - Englisch
																</label>
															</div>
										</div></div></div></div></div></div></div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-3">
							<img id="captcha" class="center-block img-responsive" src="https://cis.technikum-wien.at/include/securimage/securimage_show.php" alt="CAPTCHA Image" />
							<a href="#" onclick="document.getElementById('captcha').src = 'https://cis.technikum-wien.at/include/securimage/securimage_show.php?' + Math.random(); return false">
								Andere Grafik							</a>
						</div>
						<div class="col-sm-4">
							Geben Sie bitte hier die Zeichen aus der Grafik ein (Spamschutz).							<input type="text" name="captcha_code" maxlength="6" id="captcha" class="form-control">
							<input type="hidden" name="zugangscode" value="56cf33f6c3f22">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-3">
							<input type="submit" name="submit_btn" value="Abschicken" onclick="return checkRegistration() && validateEmail(document.RegistrationLoginForm.email.value) && submitPrio()" class="btn btn-primary">
						</div>
					</div>
				</form>
					</div>
			
		<script type="text/javascript">

			function changeSprache(sprache)
			{
				var method = 'registration';

				window.location.href = "registration.php?sprache=" + sprache + "&method=" + method + "&stg_kz=";
			}

			function checkRegistration()
			{
				if(document.RegistrationLoginForm.vorname.value == "")
				{
					alert("Bitte geben Sie Ihren Vornamen ein.");
					return false;
				}
				if(document.RegistrationLoginForm.nachname.value == "")
				{
					alert("Bitte geben Sie Ihren Nachnamen ein.");
					return false;
				}
				if(document.RegistrationLoginForm.geb_datum.value == "")
				{
					alert("Bitte geben Sie Ihr Geburtsdatum ein.");
					return false;
				}
				else
				{
					var gebDat = document.RegistrationLoginForm.geburtsdatum.value;
					gebDat = gebDat.split(".");

					if(gebDat.length !== 3)
					{
						alert("Bitte geben Sie Ihr Geburtsdatum ein.");
						return false;
					}

					if(gebDat[0].length !==2 && gebDat[1].length !== 2 && gebDat[2].length !== 4)
					{
						alert("Bitte geben Sie Ihr Geburtsdatum ein.");
						return false;
					}

					var date = new Date(gebDat[2], gebDat[1]-1, gebDat[0]);

					gebDat[0] = parseInt(gebDat[0], 10);
					gebDat[1] = parseInt(gebDat[1], 10);
					gebDat[2] = parseInt(gebDat[2], 10);

					if(!(date.getFullYear() === gebDat[2] && (date.getMonth()+1) === gebDat[1] && date.getDate() === gebDat[0]))
					{
						alert("Bitte geben Sie Ihr Geburtsdatum ein.");
						return false;
					}

					var heute = new Date();
					var jahr = heute.getFullYear();
					
					if(date.getFullYear()>=jahr)
					{
						alert("Bitte geben Sie Ihr Geburtsdatum ein.");
						return false;
					}

					
				}
				if((document.getElementById('geschlechtm').checked == false)&&(document.getElementById('geschlechtw').checked == false))
				{
					alert("Bitte geben Sie Ihr Geschlecht ein.");
					return false;
				}
				if(document.RegistrationLoginForm.email.value == "")
				{
					alert("Bitte geben Sie eine gültige E-Mail-Adresse ein.");
					return false;
				}
								if(document.RegistrationLoginForm.studiensemester_kurzbz.value == "")
				{
					alert("Bitte wählen Sie den gewünschten Studienbeginn.");
					return false;
				}
								return true;
			}
			
			function validateEmail(email) 
			{
				//var email = document.ResendCodeForm.email.value;
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				if(re.test(email)===false)
				{
					alert("Bitte geben Sie eine gültige E-Mail-Adresse ein.");
					return false;
				}
				else
					return true;
			}

			function checkPrios(stgkz) 
			{
				var anm = 'keine Prio';
				
				if($('#topprio'+stgkz+' input:checked').length !== 0)
				{
					anm = 'Prio: ' + $('#topprio'+stgkz+' input[name="topprioOrgform'+stgkz+'"]:checked').val();

					if($('#alternative'+stgkz+' input:checked').length !== 0) 
					{
						anm += '; Alt: ' + $('#alternative'+stgkz+' input[name="alternativeOrgform'+stgkz+'"]:checked').val();
					}
				}

				return anm;
			}

			function getPrioOrgform(stgkz) 
			{
				var orgform = '';
				orgform = $('#topprio'+stgkz+' input[name="topprioOrgform'+stgkz+'"]:checked').val();
				
				if(orgform == undefined)
					orgform = '';

				if(orgform!='')
					orgform = orgform.split('_')[0];

				return orgform;
			}
			function changePrio(stgkz) 
			{
				var anm, orgform;
				
				anm = checkPrios(stgkz);
				orgform = getPrioOrgform(stgkz);
				
				$('#anmerkung' + stgkz).val(anm);
				$('#badge' + stgkz).html(anm);
				$('#orgform' + stgkz).val(orgform);
				
			};
			function submitPrio(stg_kz)
			{
				inputs = document.getElementsByName('studiengaenge[]');
							
				if (inputs!=null) 
				{
					for(i=0;i<inputs.length;i++) 
					{
						if (inputs[i].checked==true) 
						{
							exists = $('#topprio'+inputs[i].value+' input[name="topprioOrgform'+inputs[i].value+'"]').val();
							if(typeof exists != 'undefined')
							{
								orgform = getPrioOrgform(inputs[i].value);
								if(orgform == '')
								{
									alert('Wenn Sie einen Studiengang mit mehreren Organisationsformen wählen, müssen Sie eine Priorität angeben');
									return false;
									break;
								}
							}
						}
					}
				}
		
			};
			


				
/*
			window.setTimeout(function() {
				$("#success-alert").fadeTo(500, 0).slideUp(500, function(){
					$(this).remove(); 
				});
			}, 1500);*/

		</script>


