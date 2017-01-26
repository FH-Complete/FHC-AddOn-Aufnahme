<?php
/**
 * ./cis/application/controllers/Requirements.php
 *
 * @package default
 */
class Requirements extends UI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

        //
        $this->load->library('form_validation');

        //
        $currentLanguage = $this->getCurrentLanguage();
        if (hasData($currentLanguage))
        {
            $this->setData('sprache', $currentLanguage);
            $this->lang->load(array('aufnahme', 'login'), $this->getData('sprache'));
        }

        //
        $this->load->helper('form');

        $this->load->model('organisation/Studiengang_model', "StudiengangModel");
        $this->load->model('organisation/Studienplan_model', "StudienplanModel");
        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');

        $this->load->model('person/Person_model', "PersonModel");

        $this->load->model('crm/Akte_model', 'AkteModel');
        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');
        $this->load->model('crm/Dokument_model', 'DokumentModel');
        $this->load->model('crm/DokumentStudiengang_model', "DokumentStudiengangModel");

        $this->load->model('content/Dms_model', 'DmsModel');

        $this->load->model('system/Message_model', 'MessageModel');
        $this->load->model('system/Phrase_model', 'PhraseModel');
	}

	/**
	 *
	 */
	public function index()
	{
        if ((isset($this->input->get()["studiengang_kz"]) && ($this->input->get()["studiengang_kz"] !== null) && ($this->input->get()["studiengang_kz"] !== ''))
            && (isset($this->input->get()['studienplan_id'])) && ($this->input->get()["studienplan_id"] !== null) && ($this->input->get()["studienplan_id"] !== '')
        )
        {
            $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
            $this->setData('person', $this->PersonModel->getPerson());

            $this->setRawData("studiengang_kz", $this->input->get("studiengang_kz"));
            $this->setRawData("studienplan_id", $this->input->get("studienplan_id"));

            $this->PhraseModel->getPhrasen(
                'aufnahme',
                ucfirst($this->getData('sprache'))
            );

            $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
            if (hasData($studiensemester))
            {
                $this->setData('studiensemester', $studiensemester);
                $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                    $this->getData('studiensemester')->studiensemester_kurzbz,
                    '',
                    'Interessent',
                    true
                ));
            }

            //setting selected Studiengang by GET Param
            foreach ($this->getData('studiengaenge') as $stg)
            {
                if ($stg->studiengang_kz === $this->getData('studiengang_kz'))
                {
                    $this->setRawData("studiengang", $stg);
                }

                if($stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                {
                    $this->setRawData("bewerbung_abgeschickt", true);
                }
            }

            $this->setRawData("studiengaenge", array($this->getData('studiengang')));

            $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
            $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

            //load Dokumente from Studiengang
            $dokumenteStudiengang = array();
            $dokumenteStudiengang[$this->getData('studiengang')->studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($this->getData('studiengang')->studiengang_kz, true, true)->retval;
            $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

            //load data for specialization
            $spezialisierung = array();
            $spezialisierung[$this->getData('prestudent')->studiengang_kz] = $this->PrestudentModel->getSpecialization($this->getData('prestudent')->prestudent_id)->retval;
            $this->setRawData('spezialisierung', $spezialisierung);


            $geplanter_abschluss = array();
            $geplanter_abschluss[$this->getData('prestudent')->studiengang_kz] = $this->getData('prestudent')->zgvdatum;
            $this->setRawData('geplanter_abschluss', $geplanter_abschluss);

            if (isset($this->input->post()["studiengang_kz"]))
            {
                if ($this->getData('prestudent')->studiengang_kz == $this->input->post()["studiengang_kz"])
                {
                    $prestudent = $this->getData('prestudent');
                    $prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ] . "_nachreichenDatum_" . $this->input->post("studienplan_id"))));
                    $prestudent->zgvort = "geplanter Abschluss";

                    $updatePrestudent = $this->PrestudentModel->savePrestudent((array)$prestudent);

                    if (!isSuccess($updatePrestudent))
                    {
                        $this->_setError(true, "could not save data");
                    }
                }
            }

            //reload saved data
            $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                $this->getData('studiensemester')->studiensemester_kurzbz,
                '',
                'Interessent',
                true
            ));

            //setting selected Studiengang by GET Param
            foreach ($this->getData('studiengaenge') as $stg)
            {
                if ($stg->studiengang_kz === $this->getData('studiengang_kz'))
                {
                    $this->setRawData("studiengang", $stg);
                }

                if($stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                {
                    $this->setRawData("bewerbung_abgeschickt", true);
                }
            }

            $this->setRawData("studiengaenge", array($this->getData('studiengang')));

            $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
            $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

            if ((!empty($this->input->post())) && ($this->getData("bewerbung_abgeschickt") !== null) && ($this->getData("bewerbung_abgeschickt") == true))
            {
                redirect("/Summary?studiengang_kz=" . $this->input->get()["studiengang_kz"] . "&studienplan_id=" . $this->input->get()["studienplan_id"]);
            }

            //load dokumente
            $this->setRawData('dokumente', $this->AkteModel->getAktenAccepted()->retval);

            if (($this->input->post("doktype") != null) && ($this->input->post("doktype") !== ""))
            {
                if (isset($this->getData("dokumente")[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData("studiengang")->typ]]))
                {
                    $akte = $this->getData("dokumente")[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData("studiengang")->typ]];
                    $akte->anmerkung = $this->input->post("doktype");
                    $akte->updateamum = date('Y-m-d H:i:s');
                    $akte->updatevon = 'online';

                    if (is_null($akte->dms_id))
                    {
                        unset($akte->dms_id);
                    }
                    if (is_null($akte->nachgereicht_am))
                    {
                        unset($akte->nachgereicht_am);
                    }
                    if (is_null($akte->uid))
                    {
                        unset($akte->uid);
                    }

                    unset($akte->inhalt_vorhanden);

                }
                else
                {
                    $akte = new stdClass();
                    $akte->person_id = $this->getData("person")->person_id;

                    $akte->dokument_kurzbz = $this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ];
                    $akte->insertvon = 'online';
                    $akte->anmerkung = $this->input->post("doktype");

                }

                if ($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ] . "_nachgereicht_" . $this->input->post("studienplan_id")) !== null)
                {
                    $akte->nachgereicht = true;
                }
                $akte = (array)$akte;
                $updateAkte = $this->AkteModel->saveAkte((array)$akte);

                if (!isSuccess($updateAkte))
                {
                    $this->_setError(true, "could not save document");
                }
            }
            else
            {
                if (!empty($this->input->post()))
                {
                    $this->_setError(true);
                }
            }

            $this->setRawData('dokumente', $this->AkteModel->getAktenAccepted()->retval);

            $temp_doks = array();
            if (isset($this->input->post()["studiengang_kz"]))
            {
                $studiengang_kz = $this->input->post()["studiengang_kz"];
                foreach ($this->getData("dokumenteStudiengang")[$studiengang_kz] as $dok)
                {
                    if (($this->input->post($dok->dokument_kurzbz . "_nachgereicht") !== null))
                    {
                        $akte = new stdClass();
                        $akte->person_id = $this->getData("person")->person_id;

                        $akte->dokument_kurzbz = $dok->dokument_kurzbz;
                        $akte->insertvon = 'online';
                        $akte->nachgereicht = true;
                        $akte->anmerkung = null;
                        $akte->nachgereicht_am = null;

                        if (($this->input->post($dok->dokument_kurzbz . "_nachreichenAnmerkung") == '') || ($this->input->post($dok->dokument_kurzbz . "_nachreichenDatum") == ''))
                        {
                            $this->_setError(true);
                            $akte->dms_id = null;
                            $temp_doks[$dok->dokument_kurzbz] = $akte;
                            $this->getData("dokError")[$dok->dokument_kurzbz] = true;
                        }
                        else
                        {
                            $akte->anmerkung = $this->input->post($dok->dokument_kurzbz . "_nachreichenAnmerkung");
                            $akte->nachgereicht_am = date("Y-m-d", strtotime($this->input->post($dok->dokument_kurzbz . "_nachreichenDatum")));
                            $akte = (array)$akte;
                            $updateAkte = $this->AkteModel->saveAkte($akte);
                            if (!isSuccess($updateAkte))
                            {
                                $this->_setError(true, "could not save document");
                            }
                        }
                    }
                }
            }

            //load dokumente
            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

            //adding abschlusszeugnis if it is not present in dokumente
            if (!isset($this->getData('dokumente')[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
            {
                $akten = $this->AkteModel->getAktenAccepted();

                if (hasData($akten))
                {
                    if (isset($akten->retval[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
                    {
                        $dok = $akten->retval[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]];
                        $dokumente = $this->getData('dokumente');
                        $dokumente[$dok->dokument_kurzbz] = $dok;
                        $this->setRawData('dokumente', $dokumente);
                    }
                }
            }

            if ((isset($this->input->post()["spezialisierung"])) && (is_array($this->input->post()["spezialisierung"])))
            {
                $spezialisierung = array();
                $prestudent = $this->getData('prestudent');
                $prestudentStatus = $this->getData('prestudentStatus');
                if (($prestudent->studiengang_kz === $this->input->get("studiengang_kz")) && ($this->getData('spezialisierung') !== null))
                {
                    if (($prestudentStatus->status_kurzbz === "Interessent"
                        || $prestudentStatus->status_kurzbz === "Bewerber")
                    )
                    {
                        $text = "";
                        $count = 0;
                        foreach ($this->input->post()["spezialisierung"] as $spez)
                        {
                            if ($spez !== "")
                            {
                                $count++;
                                $text .= $spez . ";";
                            }
                        }

                        if (isset($this->input->post()['mandatory']))
                        {
                            if ($count < $this->input->post()['mandatory'])
                            {
                                $this->_setError(true);
                                $this->setRawData('spezialisierung_error', true);
                            }
                        }

                        $text = substr($text, 0, -1);

                        if (substr_count($text, ';') !== strlen($text) && ($this->getData('error') === null))
                        {
                            $insertSpecialization = $this->PrestudentModel->saveSpecialization(array("prestudent_id" => (int)$prestudent->prestudent_id, 'text' => $text));

                            if (isSuccess($insertSpecialization))
                            {
                                $prestudent->spezialisierung = $this->PrestudentModel->getSpecialization($prestudent->prestudent_id)->retval;
                                $prestudent->spezialisierung;
                                $spezialisierung[$prestudent->studiengang_kz] = $prestudent->spezialisierung;
                            }
                            else
                            {
                                $this->_setError(true, "could not save data");
                            }
                        }
                    }
                }
                $this->setRawData("spezialisierung", $spezialisierung);
            }

            if ($this->getData("dokumente") === null)
            {
                $this->setRawData('dokumente', $temp_doks);
            }
            else
            {
                $this->setRawData('dokumente', array_merge($this->getData("dokumente"), $temp_doks));
            }

            $letztGueltigesZeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["letztGueltigesZeugnis"])->retval;
            $this->setRawData("personalDocuments", array($this->config->item("dokumentTypen")["letztGueltigesZeugnis"] => $letztGueltigesZeugnis));

            if (($this->getData("error") === null) && (isset($this->input->get()["studiengang_kz"])) && (isset($this->input->get()["studienplan_id"])) && (!empty($this->input->post())))
            {
                redirect("/Summary?studiengang_kz=" . $this->input->get()["studiengang_kz"] . "&studienplan_id=" . $this->input->get()["studienplan_id"]);
                $this->load->view('requirements', $this->getAllData());
            }
            else
            {
                $this->load->view('requirements', $this->getAllData());
            }
        }
        else
        {
            redirect("/Bewerbung");
        }
	}

	/**
	 *
	 */
	public function uploadFiles($typ)
	{
		$files = $_FILES;

		if (count($files) > 0)
		{
			//load person data
            $this->setData('person', $this->PersonModel->getPerson());

            $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');

            $this->setRawData("studiengang_kz", $this->input->post("studiengang_kz"));

            if (hasData($studiensemester))
            {
                $this->setData('studiensemester', $studiensemester);
                $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                    $this->getData('studiensemester')->studiensemester_kurzbz,
                    '',
                    'Interessent',
                    true
                ));

            }

            //setting selected Studiengang by GET Param
            foreach ($this->getData('studiengaenge') as $stg)
            {
                if ($stg->studiengang_kz === $this->getData('studiengang_kz'))
                {
                    $this->setRawData("studiengang", $stg);
                }

                if($stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                {
                    $this->setRawData("bewerbung_abgeschickt", true);
                }
            }

            $this->setRawData("studiengaenge", array($this->getData('studiengang')));

            $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);

			//load dokumente
            $this->setRawData('dokumente' , $this->AkteModel->getAktenAccepted()->retval);

			foreach($this->getData("dokumente") as $akte)
			{
				if ($akte->dms_id != null)
				{
                    $dms = $this->DmsModel->getDms($akte->dms_id)->retval;
					$akte->dokument = $dms;
				}
			}

			foreach ($files as $key => $file)
			{
				if (is_uploaded_file($file["tmp_name"][0]))
				{
					$obj = array();
					$obj['new'] = true;
					$akte = new stdClass();

					$obj['version'] = 0;
					$obj['mimetype'] = $file["type"][0];
					$obj['name'] = $file["name"][0];
					$obj['oe_kurzbz'] = null;
					//$obj['dokument_kurzbz'] = $key;
					
					if ($typ)
						$obj['dokument_kurzbz'] = $typ;

					foreach($this->getData("dokumente") as $akte_temp)
					{
						if (($akte_temp->dokument_kurzbz == $obj['dokument_kurzbz']) && ($obj['dokument_kurzbz'] != $this->config->item('dokumentTypen')["sonstiges"]))
						{
							//       $dms = $this->_loadDms($akte_temp->dms_id);
							//       $obj['version = $dms->version+1;
							$akte = $akte_temp;
							$akte->updateamum = date("Y-m-d H:i:s");
							$akte->updatevon = "online";

							if ($akte->dms_id != null && !is_null($akte->dokument))
							{
								$obj = (array) $akte->dokument;
								$obj['new'] = true;
								$obj['version'] = ($obj['version']+1);

								//    $obj['version'] = ($akte->dokument->version+1);
								$obj['mimetype'] = $file["type"][0];
								$obj['name'] = $file["name"][0];
							}
						}
					}

					$obj['kategorie_kurzbz'] = "Akte";

					$type = pathinfo($file["name"][0], PATHINFO_EXTENSION);
					$data = file_get_contents($file["tmp_name"][0]);
					$obj['file_content'] = base64_encode($data);

					$result = new stdClass();
					$insertResult = $this->DmsModel->saveDms($obj);
					if (isSuccess($insertResult))
					{
						if ($obj['version'] >= 0)
						{
							$akte->dms_id = $insertResult->retval->dms_id;
							$result->dms_id = $akte->dms_id;
							$akte->person_id = $this->getData("person")->person_id;
							$akte->mimetype = $file["type"][0];

							$akte->bezeichnung = mb_substr($obj['name'], 0, 32);
							$akte->dokument_kurzbz = $obj['dokument_kurzbz'];
							$akte->titel = $key;
							$akte->insertvon = 'online';
							$akte->nachgereicht = 'f';

							unset($akte->uid);
							unset($akte->inhalt_vorhanden);
							$akte->dokument = null;
							unset($akte->dokument);
							unset($akte->nachgereicht_am);

							$akte = (array) $akte;
                            $akteInsertResult = $this->AkteModel->saveAkte($akte);

							if (isSuccess($akteInsertResult))
							{
								$result->success = true;
								$result->akte_id = $akteInsertResult->retval;
								$result->bezeichnung = $obj['name'];
								$result->mimetype = $akte['mimetype'];
							}
							else
							{
								$result->success = false;
							}
						}
						else
						{
							$akte->mimetype = $file["type"][0];
							$akte->bezeichnung = mb_substr($obj['name'], 0, 32);
							$akte->dokument_kurzbz = $obj['dokument_kurzbz'];
							$akte->titel = $key;

							unset($akte->uid);
							unset($akte->inhalt_vorhanden);
							$akte->dokument = null;
							unset($akte->dokument);
							unset($akte->nachgereicht_am);

                            $akte = (array) $akte;
                            $akteInsertResult = $this->AkteModel->saveAkte($akte);

                            if (isSuccess($akteInsertResult))
							{
								$result->success = true;

							}
							else
							{
								$result->success = false;
							}
						}
						
						if($typ == $this->config->item('dokumentTypen')["letztGueltigesZeugnis"])
						{
							$akte = new stdClass();

                            $this->setData('studiengang', $this->StudiengangModel->getStudiengang($this->input->post()["studiengang_kz"]));

							foreach($this->getData("dokumente") as $akte_temp)
							{
								if (($akte_temp->dokument_kurzbz == $this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ]))
								{
									$akte = $akte_temp;
								}
							}
						
							$akte->person_id = $this->getData("person")->person_id;
							$akte->dokument_kurzbz = $this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ];
							$akte->insertvon = 'online';
							$akte->nachgereicht = true;
							if(isset($this->input->post()["doktype"]))
								$akte->anmerkung = $this->input->post("doktype");

							$prestudent = $this->getData('prestudent');

                            if($prestudent->studiengang_kz == $this->input->post()["studiengang_kz"])
                            {
                                $prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ]."_nachreichenDatum_".$this->input->post("studienplan_id"))));
                                $prestudent->zgvort = "geplanter Abschluss";
                                $updatePrestudent = $this->PrestudentModel->savePrestudent((array)$prestudent);
                                if(!isSuccess($updatePrestudent))
                                {
                                    $this->_setError(true, "could not save data");
                                }
                            }

							//TODO set geplanter Abschluss
							//$akte->geplanterAbschluss = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis"]."_nachreichenDatum_".$this->input->post("studienplan_id"))));

                            $akte = (array) $akte;
                            $updateAkte = $this->AkteModel->saveAkte($akte);
                            if(!isSuccess($updateAkte))
                            {
                                $this->_setError(true, "could not save document");
                            }
						}
						
						echo json_encode($result);
					}
					else
					{
						//TODO handle error
						$result->success = false;
						echo json_encode($result);
						$this->_setError(true, $this->DmsModel->getErrorMessage());
					}

					if (unlink($file["tmp_name"][0]))
					{
						//removing tmp file successful
					}
				}
			}
		}
	}
	
	/**
	 * 
	 * @return unknown
	 */
	public function deleteDocument()
	{
		$result = new stdClass();
		if((isset($this->input->post()["dms_id"])))
		{
			$dms_id = $this->input->post()["dms_id"];
            $this->setRawData('dokumente' , $this->AkteModel->getAktenAccepted()->retval);

			foreach($this->getData("dokumente") as $dok)
			{
				if(($dok->dms_id === $dms_id) && ($dok->accepted == false))
				{
					$result = $this->DmsModel->deleteDms($dok->dms_id);
					$result->dokument_kurzbz = $dok->dokument_kurzbz;
				}
			}
		}
		else
		{
			//TODO parameter missing
			$result->error = true;
			$result->msg = "dms_id is missing";
		}

		echo json_encode($result);
	}
	
	/**
	 *
	 * @param type $notiz_id
	 */
	public function deleteSpezialisierung($notiz_id, $studiengang_kz)
	{
        $this->setData('person', $this->PersonModel->getPerson());
        $this->setRawData("studiengang_kz", $studiengang_kz);

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
            $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                $this->getData('studiensemester')->studiensemester_kurzbz,
                '',
                'Interessent',
                true
            ));
        }

        //setting selected Studiengang by GET Param
        foreach ($this->getData('studiengaenge') as $stg)
        {
            if ($stg->studiengang_kz === $this->getData('studiengang_kz'))
            {
                $this->setRawData("studiengang", $stg);
            }
        }

        $this->setRawData("studiengaenge", array($this->getData('studiengang')));

        $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
        $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);


        $prestudent = $this->getData('prestudent');

        if($prestudent->studiengang_kz === $studiengang_kz)
        {
            if (($prestudent->status_kurzbz === "Interessent"
                || $prestudent->status_kurzbz === "Bewerber"))
            {
                $prestudent->spezialisierung = $this->PrestudentModel->getSpecialization($prestudent->prestudent_id)->retval;

                if((!empty($prestudent->spezialisierung)) && ($prestudent->spezialisierung->notiz_id === $notiz_id))
                {
                    $this->PrestudentModel->removeSpecialization(array('notiz_id' => $notiz_id));
                    redirect("/Requirements?studiengang_kz=".$studiengang_kz."&tudienplan_id=".$this->getData('prestudentStatus')->studienplan_id);
                }
            }
        }
	}
	
	public function getOption()
	{
	    if(isset($this->input->post()["studiengangtyp"]))
        {
            $this->setData('person', $this->PersonModel->getPerson());
            if ($this->getData("person") !== null)
            {
                $result = new stdClass();
                $this->setRawData('dokumente' , $this->AkteModel->getAktenAccepted()->retval);

                if ((isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->input->post()["studiengangtyp"]]])) && ($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->input->post()["studiengangtyp"]]]->anmerkung != null))
                {
                    $result->error = 0;
                    $result->result = $this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->input->post()["studiengangtyp"]]]->anmerkung;
                }
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }
	}

    /**
     * @param $bool
     * @param null $msg
     */
    private function _setError($bool, $msg = null)
    {
        $error = new stdClass();
        $error->error = $bool;
        $error->msg = $msg;

        $this->setRawData('error', $error);
    }
}
