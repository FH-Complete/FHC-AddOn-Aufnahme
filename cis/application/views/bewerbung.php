<?php
/**
 * ./cis/application/views/bewerbung.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'person'), $sprache);
$this->load->view('templates/cookieHeader');
$this->load->view('templates/metaHeader');

if (isset($error) && ($error->error === true))
    echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>
<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Bewerbung', 'numberOfUnreadMessages'=>$numberOfUnreadMessages, 'studiengaenge' => $studiengaenge));
    ?>
    <?php if(isset($studiengang_kz) && isset($studienplan_id))
    {
        ?>
        <div id="backToApplication">
            <span class="arrowLeft"></span><span><a
                        href="<?php echo base_url($this->config->config["index_page"] . "/Bewerbung"); ?>"><?php echo $this->lang->line("aufnahme/backToApplications"); ?></a></span>
        </div>
        <?php
    }
    ?>
    <?php
    if(!empty($studiengaenge))
    {
    foreach ($studiengaenge as $studiengang)
    {
        $data["studiengang"] = $studiengang;

        ?>
        <div class="row stg-row">
            <div class="col-sm-12">
                <?php $this->load_views('view_bewerbung_studiengang', $data); ?>
            </div>
        </div>
        <div class="row">
            <div id="<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>"
                 class="collapse <?php echo (isset($studiengang_kz) && ($studiengang_kz == $studiengang->studiengang_kz)) ? "in" : "" ?>">
                <div class="col-sm-4 navigation">
                    <?php echo
                    $this->template->widget(
                        "person_nav",
                        array(
                            'aktiv' => 'personalData',
                            "href" => array(
                                "send" => site_url("/Send?studiengang_kz=" . $studiengang->studiengang_kz . "&studienplan_id=" . $studiengang->studienplaene[0]->studienplan_id),
                                "summary" => site_url("/Summary?studiengang_kz=" . $studiengang->studiengang_kz . "&studienplan_id=" . $studiengang->studienplaene[0]->studienplan_id),
                                "requirements" => site_url("/Requirements?studiengang_kz=" . $studiengang->studiengang_kz . "&studienplan_id=" . $studiengang->studienplaene[0]->studienplan_id),
                                "personalData" => site_url("/Bewerbung/studiengang/" . $studiengang->studiengang_kz . "/" . $studiengang->studienplaene[0]->studienplan_id."/".$studiengang->prestudentstatus[0]->studiensemester_kurzbz)
                            ),
                            "studienplan_id"=>$studiengang->studienplaene[0]->studienplan_id
                        )
                    ); ?>
                </div>
                <div class="col-sm-8">
                    <div role="tabpanel" class="tab-pane" id="daten">
                        <?php $this->load_views('view_bewerbung', $data); ?>
                        <div class="row form-row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?php
                                    if((isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)))
                                    {
                                        echo '<a class="btn btn-primary icon-absenden" href="'.site_url("/Requirements?studiengang_kz=" . $studiengang->studiengang_kz . "&studienplan_id=" . $studiengang->studienplaene[0]->studienplan_id).'">
                                    '.$this->lang->line("person_weiter").'</a>';
                                    }
                                    else
                                    {
                                        $data = array("content" => $this->lang->line("person_speichern"), "name" => "submit_btn", "class" => "btn btn-primary icon-absenden", "type" => "submit");
                                        echo form_button($data);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
}
$this->load->view('templates/footer');
?>

<script type="text/javascript">
    function confirmStorno(studiengang_kz, studienplan_id)
    {
        <?php
        $phrase = "";
        if(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true))
        {
            $phrase = $this->getPhrase("Bewerbung/StornoConfirmation", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz);
        }
        else
        {
            $phrase = $this->getPhrase("Bewerbung/StornoConfirmation", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz);
        }
        ?>
        if(confirm("<?php echo $phrase; ?>"))
        {
            window.location.href = "<?php echo base_url($this->config->config["index_page"]."/Bewerbung/storno/") ?>" + "/"+ studiengang_kz+"/"+studienplan_id;
        }
    }

    function deleteDocument(dms_id, studienplan_id)
    {
        $.ajax({
            url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/deleteDocument"); ?>',
            type: 'POST',
            data: {
                "dms_id": dms_id
            },
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
                if(data.error !== 0)
                {
                    //TODO display error
                }
                else
                {
                    $("#"+data.dokument_kurzbz+"Upload_"+studienplan_id).show();
                    $("#"+data.dokument_kurzbz+"Delete_"+studienplan_id).html("");
                    $('#'+data.dokument_kurzbz+'FileUpload_'+studienplan_id).parent().show();
                    var ele = $('#'+data.dokument_kurzbz+'Progress_'+studienplan_id).show();
                    $("#"+data.dokument_kurzbz+"_hochgeladen_"+studienplan_id).html("<?php echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen'); ?>").after(ele);
                    $(ele).show();
                    $("#"+data.dokument_kurzbz+"_nachgereicht_"+studienplan_id).prop("disabled", false);
                    $("#"+data.dokument_kurzbz+"_logo_"+studienplan_id).html("");
                    toggleDocumentsComplete(studienplan_id);

                    $("#"+data.dokument_kurzbz+"_logo_"+studienplan_id).parent().addClass("has-error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }

    $(document).ready(function(){
        $(".infotext").each(function(i,v){
            var studiengang_kz = $(v).attr("studiengang_kz");
            var studienplan_id = $(v).attr("studienplan_id");
            checkDataCompleteness(studiengang_kz, studienplan_id);
        });

        $(".zustelladresse").each(function (i, v)
        {
            if ($(v).prop("checked"))
            {
                var id = $(v).attr("studienplan_id");
                $("#zustelladresse_" + id).show();
            }
        });

        $(".zustelladresse").click(function (event)
        {
            var id = $(event.currentTarget).attr("studienplan_id");
            if ($(event.currentTarget).prop("checked"))
            {
                $("#zustelladresse_" + id).show();
            }
            else
            {
                $("#zustelladresse_" + id).hide();
            }
        });

        $(".adresse_nation").on("change", function (event)
        {
            toggleAdresse(event.currentTarget);
        });

        $(".zustelladresse_nation").on("change", function (event)
        {
            toggleZustellAdresse(event.currentTarget);
        });

        $(".plz").on("change", function (event)
        {
            var plz = $(event.currentTarget).val();
            loadOrtData(plz, $(".ort_dropdown"));
        });

        $(".zustell_plz").on("change", function (event)
        {
            var plz = $(event.currentTarget).val();
            loadOrtData(plz, $(".zustell_ort_dropdown"));
        });

        toggleAdresse($(".adresse_nation"));
        toggleZustellAdresse($(".zustelladresse_nation"));
    });

    function toggleAdresse(target)
    {
        //var code = $("#adresse_nation option:selected").val();
        var code = $(target).val();
        if (code === "A")
        {
            hideElement($(".ort_input"));
            showElement($(".ort_dropdown"));
            var plz = $("#plz").val();
            loadOrtData(plz, $(".ort_dropdown"));
        }
        else
        {
            showElement($(".ort_input"));
            hideElement($(".ort_dropdown"));
        }
    }

    function toggleZustellAdresse(target)
    {
        //var code = $("#zustelladresse_nation option:selected").val();
        var code = $(target).val();
        if (code === "A")
        {
            hideElement($(".zustell_ort_input"));
            showElement($(".zustell_ort_dropdown"));
            var plz = $("#zustell_plz").val();
            loadOrtData(plz, $(".zustell_ort_dropdown"));
        }
        else
        {
            showElement($(".zustell_ort_input"));
            hideElement($(".zustell_ort_dropdown"));
        }
    }

    function hideElement(ele)
    {
        $(ele).hide();
    }

    function showElement(ele)
    {
        $(ele).show();
    }

    function checkDataCompleteness(studiengang_kz, studienplan_id)
    {
        $.ajax({
            url: '<?php echo base_url($this->config->config["index_page"]."/Helper/checkDataCompleteness"); ?>?studiengang_kz='+studiengang_kz,
            type: 'GET',
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
                if((data.person==false)
                    ||(data.adresse==false)
                    ||(data.dokumente==false)
                    ||(data.kontakt==false)
                    ||(data.zustelladresse==false)
                    || (data.spezialisierung == false)
                    || (data.requirements_dokumente == false)
                )
                {
                    $("#infotext_"+studienplan_id).html("<?php echo $this->lang->line('aufnahme/unvollstaendig'); ?>");
                }
                else
                {
                    checkDocuments(studiengang_kz, studienplan_id);
                }

                var allModulesComplete = true;
                //set module as checked
                if((data.person==true)
                    && (data.adresse==true)
                    && (data.dokumente==true)
                    && (data.kontakt==true)
                    && (data.zustelladresse==true)
                )
                {
                    $("#personalData_"+studienplan_id).addClass('check');
                }
                else
                {
                    allModulesComplete = false;
                }

                if((data.requirements_dokumente==true) && (data.spezialisierung == true))
                {
                    $("#requirements_"+studienplan_id).addClass('check');
                }
                else
                {
                    allModulesComplete = false;
                }

                if(allModulesComplete)
                {
                    $("#summary_"+studienplan_id).addClass('check');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }

    function checkDocuments(studiengang_kz, studienplan_id)
    {
        $.ajax({
            url: '<?php echo base_url($this->config->config["index_page"]."/Helper/areDocumentsComplete"); ?>',
            type: 'POST',
            data: {
                studiengang_kz: studiengang_kz
            },
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
                if((data.complete !== undefined) && (data.complete == false))
                {
                    $("#infotext_"+studienplan_id).html("<?php echo $this->lang->line('aufnahme/unvollstaendig'); ?>");
                }
                else
                {
                    if(data.abgeschickt == false)
                    {
                        $("#infotext_"+studienplan_id).html("<?php echo $this->lang->line('aufnahme/nochNichtAbgeschickt'); ?>");
                    }
                    else
                    {
                        $("#send_"+studienplan_id).addClass('check');
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }
</script>