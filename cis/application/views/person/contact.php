
<div role="tabpanel" class="tab-pane" id="kontakt">
	<h2>Kontaktinformationen</h2>
	<div class="alert alert-info">Da Sie bereits als InteressentIn bestätigt wurden oder Sie bereits einen Account an der FHTW haben, können Sie Ihre Stammdaten nicht mehr ändern. Sollten hier Daten fehlerhaft sein, wenden Sie sich bitte an die zuständige Assistenz.</div>

	<form method="POST" action="/addons/bewerbung/cis/bewerbung.php?active=kontakt" class="form-horizontal">
		<fieldset>
			<legend>Kontakt</legend>
			<div class="form-group ">
				<label for="email" class="col-sm-2 control-label">E-Mail Adresse*</label>
				<div class="col-sm-10">
					<input type="text" name="email" id="email" value="gerald_raab@gmx.net" disabled="disabled" size="32" class="form-control">
				</div>
			</div>
			<div class="form-group ">
				<label for="telefonnummer" class="col-sm-2 control-label">Telefonnummer*</label>
				<div class="col-sm-10">
					<input type="text" name="telefonnummer" id="telefonnummer"
					value="+43-699-12121212"  disabled="disabled" size="32" class="form-control">
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>Adresse (Hauptwohnsitz)</legend>
			<div class="form-group ">
				<label for="strasse" class="col-sm-2 control-label">Straße*</label>
				<div class="col-sm-10">
					<input type="text" name="strasse" id="strasse"
					value="Höchsätdtplatz 6"  disabled="disabled" class="form-control">
				</div>
			</div>
			<div class="form-group ">
				<label for="plz" class="col-sm-2 control-label">Postleitzahl*</label>
				<div class="col-sm-10">
					<input type="text" name="plz" id="plz" value="1200"  disabled="disabled" class="form-control">
				</div>
			</div>
			<div class="form-group ">
				<label for="ort" class="col-sm-2 control-label">Ort*</label>
				<div class="col-sm-10">
					<input type="text" name="ort" id="ort" value="Wien"  disabled="disabled" class="form-control">
				</div>
			</div>
			<div class="form-group ">
				<label for="nation" class="col-sm-2 control-label">Nation*</label>
				<div class="col-sm-10">
					<select name="nation" class="form-control" disabled="disabled" >
						<option value="">-- Bitte auswählen --</option>
													<option value="AFG" >
								Afghanistan							</option>
													<option value="ET" >
								Ägypten							</option>
													<option value="AL" >
								Albanien							</option>
													<option value="DZ" >
								Algerien							</option>
													<option value="AND" >
								Andorra							</option>
													<option value="AGL" >
								Angola							</option>
													<option value="ATB" >
								Antigua und Barbuda							</option>
													<option value="RA" >
								Argentinien							</option>
													<option value="ARM" >
								Armenien							</option>
													<option value="ASB" >
								Aserbaidschan							</option>
													<option value="ETH" >
								Äthiopien							</option>
													<option value="AUS" >
								Australien							</option>
													<option value="BS" >
								Bahamas							</option>
													<option value="BRN" >
								Bahrein							</option>
													<option value="BAN" >
								Bangladesch							</option>
													<option value="BDS" >
								Barbados							</option>
													<option value="B" >
								Belgien							</option>
													<option value="BLZ" >
								Belize							</option>
													<option value="DY" >
								Benin							</option>
													<option value="BHU" >
								Bhutan							</option>
													<option value="BOL" >
								Bolivien							</option>
													<option value="BSH" >
								Bosnien-Herzegowina							</option>
													<option value="RB" >
								Botswana							</option>
													<option value="BR" >
								Brasilien							</option>
													<option value="BRU" >
								Brunei							</option>
													<option value="BG" >
								Bulgarien							</option>
													<option value="HV" >
								Burkina Faso							</option>
													<option value="RU" >
								Burundi							</option>
													<option value="RCH" >
								Chile							</option>
													<option value="RC" >
								China (Republik/Taiwan)							</option>
													<option value="CHF" >
								China (Volksrepublik)							</option>
													<option value="CI" >
								Cote d'Ivoire							</option>
													<option value="DK" >
								Dänemark							</option>
													<option value="D" >
								Deutschland							</option>
													<option value="WD" >
								Dominica							</option>
													<option value="DCH" >
								Dominikanische Republik							</option>
													<option value="DJI" >
								Dschibuti							</option>
													<option value="EC" >
								Ekuador							</option>
													<option value="ES" >
								El Salvador							</option>
													<option value="ERI" >
								Eritrea							</option>
													<option value="ELD" >
								Estland							</option>
													<option value="FJI" >
								Fidschi							</option>
													<option value="SF" >
								Finnland							</option>
													<option value="F" >
								Frankreich							</option>
													<option value="GAB" >
								Gabun							</option>
													<option value="WAG" >
								Gambia							</option>
													<option value="GB" >
								Grossbrit. u. Nordirland							</option>
													<option value="GG" >
								Georgien							</option>
													<option value="GH" >
								Ghana							</option>
													<option value="WG" >
								Grenada							</option>
													<option value="GR" >
								Griechenland							</option>
													<option value="GCA" >
								Guatemala							</option>
													<option value="GN" >
								Guinea							</option>
													<option value="AGN" >
								Guinea (Äquatorial-g.)							</option>
													<option value="GNB" >
								Guinea-Bissau							</option>
													<option value="BRG" >
								Guyana							</option>
													<option value="RH" >
								Haiti							</option>
													<option value="BH" >
								Honduras							</option>
													<option value="IND" >
								Indien							</option>
													<option value="RI" >
								Indonesien							</option>
													<option value="IRQ" >
								Irak							</option>
													<option value="IR" >
								Iran							</option>
													<option value="IRL" >
								Irland							</option>
													<option value="IS" >
								Island							</option>
													<option value="IL" >
								Israel							</option>
													<option value="I" >
								Italien							</option>
													<option value="IST" >
								Italien (Südtirol)							</option>
													<option value="JA" >
								Jamaika							</option>
													<option value="J" >
								Japan							</option>
													<option value="JEM" >
								Jemen							</option>
													<option value="JOR" >
								Jordanien							</option>
													<option value="YU" >
								Jugoslawien (und Kosovo)							</option>
													<option value="K" >
								Königreich Kambodscha							</option>
													<option value="CAM" >
								Kamerun							</option>
													<option value="CDN" >
								Kanada							</option>
													<option value="KV" >
								Kap Verde							</option>
													<option value="KAS" >
								Kasachstan							</option>
													<option value="QTR" >
								Katar							</option>
													<option value="EAK" >
								Kenia							</option>
													<option value="KRG" >
								Kirgistan							</option>
													<option value="KIR" >
								Kiribati							</option>
													<option value="CO" >
								Kolumbien							</option>
													<option value="KM" >
								Komoren							</option>
													<option value="ZR" >
								Kongo (Demokrat.Republik)							</option>
													<option value="RCB" >
								Kongo (Republik)							</option>
													<option value="DVK" >
								Korea (demokrat. VR/Nord)							</option>
													<option value="ROK" >
								Korea (Republik/Süd)							</option>
													<option value="CR" >
								Kostarika							</option>
													<option value="CRO" >
								Kroatien							</option>
													<option value="C" >
								Kuba							</option>
													<option value="KT" >
								Kuwait							</option>
													<option value="LAO" >
								Laos							</option>
													<option value="LS" >
								Lesotho							</option>
													<option value="LLD" >
								Lettland							</option>
													<option value="RL" >
								Libanon							</option>
													<option value="LB" >
								Liberia							</option>
													<option value="LT" >
								Libyen							</option>
													<option value="FL" >
								Liechtenstein							</option>
													<option value="LIT" >
								Litauen							</option>
													<option value="L" >
								Luxemburg							</option>
													<option value="RM" >
								Madagaskar							</option>
													<option value="MW" >
								Malawi							</option>
													<option value="MAL" >
								Malaysia							</option>
													<option value="MDV" >
								Malediven							</option>
													<option value="RMM" >
								Mali							</option>
													<option value="M" >
								Malta							</option>
													<option value="MA" >
								Marokko							</option>
													<option value="MSH" >
								Marshallinseln							</option>
													<option value="RIM" >
								Mauretanien							</option>
													<option value="MS" >
								Mauritius							</option>
													<option value="MAZ" >
								Mazedonien (eh.Jug.Rep.)							</option>
													<option value="MEX" >
								Mexiko							</option>
													<option value="MIK" >
								Mikronesien							</option>
													<option value="MLD" >
								Moldova							</option>
													<option value="MC" >
								Monaco							</option>
													<option value="MGL" >
								Mongolei							</option>
													<option value="MO" >
								Republik Montenegro							</option>
													<option value="MBK" >
								Mosambik							</option>
													<option value="BUR" >
								Myanmar (Birma)							</option>
													<option value="NAM" >
								Namibia							</option>
													<option value="NR" >
								Nauru							</option>
													<option value="NEP" >
								Nepal							</option>
													<option value="NZ" >
								Neuseeland							</option>
													<option value="NL" >
								Niederlande							</option>
													<option value="NIG" >
								Niger							</option>
													<option value="WAN" >
								Nigeria							</option>
													<option value="NIC" >
								Nikaragua							</option>
													<option value="NU" >
								Niue							</option>
													<option value="N" >
								Norwegen							</option>
													<option value="OMN" >
								Oman							</option>
													<option value="A" selected>
								Österreich							</option>
													<option value="PAK" >
								Pakistan							</option>
													<option value="PST" >
								Palästina							</option>
													<option value="PAL" >
								Palau Inseln							</option>
													<option value="PA" >
								Panama							</option>
													<option value="PNG" >
								Papua-Neuguinea							</option>
													<option value="PY" >
								Paraguay							</option>
													<option value="PE" >
								Peru							</option>
													<option value="PI" >
								Philippinen							</option>
													<option value="PL" >
								Polen							</option>
													<option value="P" >
								Portugal							</option>
													<option value="R" >
								Rumänien							</option>
													<option value="RSF" >
								Rußland							</option>
													<option value="RWA" >
								Rwanda							</option>
													<option value="SHR" >
								Sahara							</option>
													<option value="SLM" >
								Salomonen							</option>
													<option value="Z" >
								Sambia							</option>
													<option value="WS" >
								Samoa							</option>
													<option value="WL" >
								Sankt Lucia							</option>
													<option value="RSM" >
								San Marino							</option>
													<option value="STP" >
								Sao Tome und Principe							</option>
													<option value="SA" >
								Saudi-Arabien							</option>
													<option value="S" >
								Schweden							</option>
													<option value="CH" >
								Schweiz							</option>
													<option value="SN" >
								Senegal							</option>
													<option value="SB" >
								Republik Serbien							</option>
													<option value="SBM" >
								Serbien/Montenegro							</option>
													<option value="SY" >
								Seychellen							</option>
													<option value="WAL" >
								Sierra Leone							</option>
													<option value="RSR" >
								Simbabwe							</option>
													<option value="SGP" >
								Singapur							</option>
													<option value="SQ" >
								Slowakei							</option>
													<option value="SLO" >
								Slowenien							</option>
													<option value="SP" >
								Somalia							</option>
													<option value="E" >
								Spanien							</option>
													<option value="CL" >
								Sri Lanka							</option>
													<option value="ZZZ" >
								Staatenlos							</option>
													<option value="XXX" >
								Stbg. ungeklärt							</option>
													<option value="SCN" >
								Sankt Kitts und Nevis							</option>
													<option value="WV" >
								Sankt Vincent/Grenadinen							</option>
													<option value="ZA" >
								Südafrika							</option>
													<option value="SUD" >
								Sudan							</option>
													<option value="SME" >
								Surinam							</option>
													<option value="SD" >
								Swasiland							</option>
													<option value="SYR" >
								Syrien							</option>
													<option value="TDS" >
								Tadschikistan							</option>
													<option value="EAT" >
								Tansania							</option>
													<option value="T" >
								Thailand							</option>
													<option value="TG" >
								Togo							</option>
													<option value="TA" >
								Tonga							</option>
													<option value="TT" >
								Trinidad und Tobago							</option>
													<option value="TD" >
								Tschad							</option>
													<option value="TCH" >
								Tschechien							</option>
													<option value="TN" >
								Tunesien							</option>
													<option value="TR" >
								Türkei							</option>
													<option value="TKM" >
								Turkmenistan							</option>
													<option value="TVL" >
								Tuvalu							</option>
													<option value="EAU" >
								Uganda							</option>
													<option value="UKR" >
								Ukraine							</option>
													<option value="H" >
								Ungarn							</option>
													<option value="U" >
								Uruguay							</option>
													<option value="USA" >
								Vereinigte St. v. Amerika							</option>
													<option value="UBK" >
								Usbekistan							</option>
													<option value="VTU" >
								Vanuatu							</option>
													<option value="VE" >
								Vereinigte arab. Emirate							</option>
													<option value="V" >
								Vatikan							</option>
													<option value="YV" >
								Venezuela							</option>
													<option value="VN" >
								Vietnam							</option>
													<option value="BLR" >
								Weißrußland							</option>
													<option value="RCA" >
								Zentralafrikan. Republik							</option>
													<option value="CY" >
								Zypern							</option>
											</select>
				</div>
			</div>
		</fieldset>
		<button class="btn-nav btn btn-default" type="button" data-jump-tab="daten">
			Zurück		</button>
		<button class="btn btn-success" type="submit"  disabled="disabled" name="btn_kontakt">
			Speichern		</button>
		<button class="btn-nav btn btn-default" type="button" data-jump-tab="zgv">
			Weiter		</button><br/><br/>
	</form>
</div>
