
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
                        <?php
                        foreach ($nationen as $n) {
                            echo "<option value=" . $n->nation_code . ">" . $n->kurztext . "</option>";
                        }
                        ?>
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

