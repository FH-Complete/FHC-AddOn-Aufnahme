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
        $this->PhraseModel->getPhrasen(
            'aufnahme',
            ucfirst($this->getData('sprache'))
        );

        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

        $this->setData('person', $this->PersonModel->getPerson());

		if($this->input->get("studiengang_kz") != null)
		{
			$this->setRawData("studiengang_kz", $this->input->get("studiengang_kz"));
			$this->setData("studiengang", $this->StudiengangModel->getStudiengang($this->getData('studiengang_kz')));
		}

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
            $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                $this->getData('studiensemester')->studiensemester_kurzbz,
                '',
                'Interessent'
            ));
        }

        $prestudent = $this->PrestudentModel->getLastStatuses(
            $this->getData('person')->person_id,
            $this->getData('studiensemester')->studiensemester_kurzbz,
            null,
            'Interessent'
        );
		
		//load preinteressent data
        $this->setData('prestudent', $prestudent);

        //test if data in session is complete -> otherwise new call to API
        if(!isset($this->getData('prestudent')[0]->status_kurzbz))
        {
            $prestudent = $this->PrestudentModel->getLastStatuses(
                $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz,
                null,
                'Interessent',
                true
            );

            $this->setData('prestudent', $prestudent);
        }

        $geplanter_abschluss = array();
        $spezialisierung = array();
        foreach($this->getData('prestudent') as $prestudent)
        {
            if((isset($this->input->get()["studiengang_kz"]))
                && ($this->input->get()["studiengang_kz"]
                    === $prestudent->studiengang_kz))
            {
                if (($prestudent->status_kurzbz === "Interessent"
                        || $prestudent->status_kurzbz === "Bewerber")
                )
                {
                    //var_dump($prestudent);
                    $prestudent->spezialisierung = $this->PrestudentModel->getSpecialization($prestudent->prestudent_id)->retval;

                    $spezialisierung[$prestudent->studiengang_kz] = $prestudent->spezialisierung;

                    if ($prestudent->bewerbung_abgeschicktamum != null)
                    {
                        $this->setRawData("bewerbung_abgeschickt", true);
                    }
                }
            }

            $geplanter_abschluss[$prestudent->studiengang_kz] = $prestudent->zgvdatum;
            if(isset($this->input->post()["studiengang_kz"]))
            {
                if($prestudent->studiengang_kz == $this->input->post()["studiengang_kz"])
                {
                    {
                        $prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ]."_nachreichenDatum_".$this->input->post("studienplan_id"))));
                        $prestudent->zgvort = "geplanter Abschluss";
                        $prestudent = (array) $prestudent;

                        $updatePrestudent = $this->PrestudentModel->savePrestudent((array)$prestudent);

                        if(!isSuccess($updatePrestudent))
                        {
                            $this->_setError(true, "could not save data");
                        }
                    }
                }
            }
        }

        $this->setRawData('geplanter_abschluss', $geplanter_abschluss);
        $this->setRawData('spezialisierung', $spezialisierung);

        //reload saved data
        $prestudent = $this->PrestudentModel->getLastStatuses(
            $this->getData('person')->person_id,
            $this->getData('studiensemester')->studiensemester_kurzbz,
            null,
            'Interessent'
        );

        //load preinteressent data
        $this->setData('prestudent', $prestudent);

		/*
		$this->_data["studiengaenge"] = array();
		$this->_data["geplanter_abschluss"] = array();
		foreach ($this->getData("prestudent") as $prestudent)
		{
			if(isset($this->input->post()["studiengang_kz"]))
			{
				if($prestudent->studiengang_kz == $this->input->post()["studiengang_kz"])
				{
					{
						$prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis"]."_nachreichenDatum_".$this->input->post("studienplan_id"))));
						$prestudent->zgvort = "geplanter Abschluss";
						$this->_savePrestudent($prestudent);
					}
				}
			}

			if((isset($this->input->get()["studiengang_kz"])) && ($this->input->get()["studiengang_kz"] === $prestudent->studiengang_kz))
            {
                //load studiengaenge der prestudenten
                $this->data["studiengang"] = $studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
                $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

                $this->_data["geplanter_abschluss"][$prestudent->studiengang_kz] = $prestudent->zgvdatum;

                if ((!empty($prestudent->prestudentStatus))
                    && ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
                        || $prestudent->prestudentStatus->status_kurzbz === "Bewerber")
                )
                {
                    $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
                    $studiengang->studienplan = $studienplan;

                    $prestudent->spezialisierung = $this->_getSpecialization($prestudent->prestudent_id);
                    $this->_data["spezialisierung"][$prestudent->studiengang_kz] = $prestudent->spezialisierung;

                    if ($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null)
                    {
                        $this->_data["bewerbung_abgeschickt"] = true;
                    }
                    array_push($this->_data["studiengaenge"], $studiengang);
                }
            }
		}
		*/
		
		if((!empty($this->input->post())) && ($this->getData("bewerbung_abgeschickt") !== null) && ($this->getData("bewerbung_abgeschickt") == true))
		{
			redirect("/Summary?studiengang_kz=".$this->input->get()["studiengang_kz"]."&studienplan_id=".$this->input->get()["studienplan_id"]);
		}
		
		if(count($this->getData("studiengaenge")) > 1)
		{
			//usort($this->getData("studiengaenge"), array($this, "cmpStg"));
		}

		//load Dokumente from Studiengang
		$dokumenteStudiengang = array();

		//foreach($this->_data["studiengaenge"] as $stg)
		//{
			$dokumenteStudiengang[$this->getData("studiengang_kz")] = $this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($this->getData('studiengang_kz'), true, null)->retval;
			$this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);
		//}
	
		//load dokumente
        $this->setRawData('dokumente', $this->AkteModel->getAktenAccepted()->retval);

		if(($this->input->post("doktype") != null) && ($this->input->post("doktype") !== ""))
		{
			if(isset($this->getData("dokumente")[$this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData("studiengang")->typ]]))
			{
				$akte = $this->getData("dokumente")[$this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData("studiengang")->typ]];
				$akte->anmerkung = $this->input->post("doktype");
				$akte->updateamum = date('Y-m-d H:i:s');
				$akte->updatevon = 'online';

				if(is_null($akte->dms_id))
					unset($akte->dms_id);
				if(is_null($akte->nachgereicht_am))
					unset($akte->nachgereicht_am);
				if(is_null($akte->uid))
					unset($akte->uid);

				unset($akte->inhalt_vorhanden);
				
			}
			else
			{
				$akte = new stdClass();
				$akte->person_id = $this->getData("person")->person_id;

				$akte->dokument_kurzbz = $this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ];
				$akte->insertvon = 'online';
				$akte->anmerkung = $this->input->post("doktype");

//				$this->_saveAkte($akte);
			}
			
			if($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ]."_nachgereicht_".$this->input->post("studienplan_id")) !== null)
			{
				$akte->nachgereicht = true;
			}
			$akte = (array) $akte;
			$updateAkte = $this->AkteModel->saveAkte((array)$akte);

			if(!isSuccess($updateAkte))
            {
                $this->_setError(true, "could not save document");
            }
		}
		else
		{
			if(!empty($this->input->post()))
			{
				$this->_setError(true);
			}
		}

        $this->setRawData('dokumente', $this->AkteModel->getAktenAccepted()->retval);

		$temp_doks = array();
		if(isset($this->input->post()["studiengang_kz"]))
		{
			$studiengang_kz = $this->input->post()["studiengang_kz"];
			foreach ($this->getData("dokumenteStudiengang")[$studiengang_kz] as $dok)
			{
				if (($this->input->post($dok->dokument_kurzbz."_nachgereicht") !== null))
				{
					$akte = new stdClass();
					$akte->person_id = $this->getData("person")->person_id;

					$akte->dokument_kurzbz = $dok->dokument_kurzbz;
					$akte->insertvon = 'online';
					$akte->nachgereicht = true;
					$akte->anmerkung = null;
					$akte->nachgereicht_am = null;
					
					if(($this->input->post($dok->dokument_kurzbz."_nachreichenAnmerkung") == '') || ($this->input->post($dok->dokument_kurzbz."_nachreichenDatum") == ''))
					{
						$this->_setError(true);
						$akte->dms_id = null;
						$temp_doks[$dok->dokument_kurzbz] = $akte;
						$this->getData("dokError")[$dok->dokument_kurzbz] = true;
					}
					else
					{
						$akte->anmerkung = $this->input->post($dok->dokument_kurzbz."_nachreichenAnmerkung");
						$akte->nachgereicht_am = date("Y-m-d", strtotime($this->input->post($dok->dokument_kurzbz."_nachreichenDatum")));
                        $akte = (array) $akte;
                        $updateAkte = $this->AkteModel->saveAkte($akte);
                        if(!isSuccess($updateAkte))
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
        if(!isset($this->getData('dokumente')[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
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

		if((isset($this->input->post()["spezialisierung"])) && (is_array($this->input->post()["spezialisierung"])))
		{
            $spezialisierung = array();
			foreach ($this->getData("prestudent") as $prestudent)
			{
				if(($prestudent->studiengang_kz === $this->input->get("studiengang_kz")) && (!isset($prestudent->spezialisierung) ||empty($prestudent->spezialisierung)))
				{
					if (($prestudent->status_kurzbz === "Interessent"
							|| $prestudent->status_kurzbz === "Bewerber"))
					{
						$text = "";
						foreach($this->input->post()["spezialisierung"] as $spez)
						{
							$text .= $spez.";";
						}
						$text = substr($text, 0, -1);
						if (substr_count($text, ';') !== strlen($text))
						{
							$insertSpecialization = $this->PrestudentModel->saveSpecialization(array("prestudent_id" => (int) $prestudent->prestudent_id, 'text'=>$text));

                            if(isSuccess($insertSpecialization))
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
			}
            $this->setRawData("spezialisierung" ,$spezialisierung);
		}

        if($this->getData("dokumente") === null)
        {
            $this->setRawData('dokumente', $temp_doks);
        }
		else
        {
            $this->setRawData('dokumente', array_merge($this->getData("dokumente"), $temp_doks));
        }
		
		$letztGueltigesZeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["letztGueltigesZeugnis"])->retval;
		$this->setRawData("personalDocuments",  array($this->config->item("dokumentTypen")["letztGueltigesZeugnis"]=>$letztGueltigesZeugnis));

		if(($this->getData("error") === null) && (isset($this->input->get()["studiengang_kz"])) && (isset($this->input->get()["studienplan_id"])) && (!empty($this->input->post())))
		{
			redirect("/Summary?studiengang_kz=".$this->input->get()["studiengang_kz"]."&studienplan_id=".$this->input->get()["studienplan_id"]);
			$this->load->view('requirements', $this->getAllData());
		}
		else
		{
			$this->load->view('requirements', $this->getAllData());
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

            $this->setData('prestudent', $this->PrestudentModel->getPrestudentByPersonId());

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
							
							foreach($this->getData("prestudent") as $prestudent)
							{
								if($prestudent->studiengang_kz == $this->input->post()["studiengang_kz"])
								{
//									if(($prestudent->zgvdatum == null) && ($prestudent->zgvort == null))
									{
										$prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_".$this->getData('studiengang')->typ]."_nachreichenDatum_".$this->input->post("studienplan_id"))));
										$prestudent->zgvort = "geplanter Abschluss";
										$prestudent = (array) $prestudent;
	                                    $updatePrestudent = $this->PrestudentModel->savePrestudent($prestudent);
                                        if(!isSuccess($updatePrestudent))
                                        {
                                            $this->_setError(true, "could not save data");
                                        }
									}
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
//				var_dump($result);
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

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
        }

        $prestudenten = $this->PrestudentModel->getLastStatuses(
            $this->getData('person')->person_id,
            $this->getData('studiensemester')->studiensemester_kurzbz,
            null,
            'Interessent'
        )->retval;

//		$this->_data["studiengaenge"] = array();
		foreach ($prestudenten as $prestudent)
		{
			if($prestudent->studiengang_kz === $studiengang_kz)
			{
				//$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

				if (($prestudent->status_kurzbz === "Interessent"
						|| $prestudent->status_kurzbz === "Bewerber"))
				{
					$prestudent->spezialisierung = $this->PrestudentModel->getSpecialization($prestudent->prestudent_id)->retval;

					if((!empty($prestudent->spezialisierung)) && ($prestudent->spezialisierung->notiz_id === $notiz_id))
					{
						$this->PrestudentModel->removeSpecialization(array('notiz_id' => $notiz_id));
						redirect("/Requirements?studiengang_kz=".$studiengang_kz."&tudienplan_id=".$prestudent->prestudentStatus->studienplan_id);
					}
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
