<?php
/**
 * ./cis/application/controllers/Requirements.php
 *
 * @package default
 */
class Requirements extends MY_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('requirements', $this->get_language());
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->load->model('studiengang_model', "StudiengangModel");
		$this->load->model('prestudent_model', "PrestudentModel");
		$this->load->model('prestudentStatus_model', "PrestudentStatusModel");
		$this->load->model('studienplan_model', "StudienplanModel");
		$this->load->model('dms_model', "DmsModel");
		$this->load->model('akte_model', "AkteModel");
		$this->load->model('person_model', "PersonModel");
		$this->load->model('DokumentStudiengang_model', "DokumentStudiengangModel");
		$this->_data["numberOfUnreadMessages"] = $this->_getNumberOfUnreadMessages();
	}

	/**
	 *
	 */
	public function index()
	{
		$this->checkLogin();
		$this->_data['sprache'] = $this->get_language();

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load studiengang
//		$this->_data["studiengang"] = $this->_loadStudiengang($this->input->get()["studiengang_kz"]);
		
		if($this->input->get("studiengang_kz") != null)
		{
			$this->_data["studiengang_kz"] = $this->input->get("studiengang_kz");
		}
		
		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();
		
		$this->_data["studiengaenge"] = array();
		foreach ($this->_data["prestudent"] as $prestudent)
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
			//load studiengaenge der prestudenten
			$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
			$this->_data["geplanter_abschluss"] = $prestudent->zgvdatum;

			if ((!empty($prestudent->prestudentStatus))
				&& ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
					|| $prestudent->prestudentStatus->status_kurzbz === "Bewerber")) {
				$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
				$studiengang->studienplan = $studienplan;
				
				if($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null)
				{
					$this->_data["bewerbung_abgeschickt"] = true;
				}
				array_push($this->_data["studiengaenge"], $studiengang);
			}
		}

		//load Dokumente from Studiengang
		$this->_data["dokumenteStudiengang"] = array();
		foreach($this->_data["studiengaenge"] as $stg)
		{
			$this->_data["dokumenteStudiengang"][$stg->studiengang_kz] = $this->_loadDokumentByStudiengang($stg->studiengang_kz);
		}
	
		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);
		
		if(($this->input->post("doktype") != null) && ($this->input->post("doktype") !== ""))
		{
			if(isset($this->_data["dokumente"][$this->config->item('dokumentTypen')["abschlusszeugnis"]]))
			{
				$akte = $this->_data["dokumente"][$this->config->item('dokumentTypen')["abschlusszeugnis"]];
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
				$akte->person_id = $this->_data["person"]->person_id;

				$akte->dokument_kurzbz = $this->config->item('dokumentTypen')["abschlusszeugnis"];
				$akte->insertvon = 'online';
				$akte->anmerkung = $this->input->post("doktype");

//				$this->_saveAkte($akte);
			}
			
			if($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis"]."_nachgereicht_".$this->input->post("studienplan_id")) !== null)
			{
				$akte->nachgereicht = true;
			}
			
			$this->_saveAkte($akte);
		}
		
		$this->_loadDokumente($this->session->userdata()["person_id"]);

		if(isset($this->input->post()["studiengang_kz"]))
		{
			$studiengang_kz = $this->input->post()["studiengang_kz"];
			foreach ($this->_data["dokumenteStudiengang"][$studiengang_kz] as $dok)
			{
				if (($this->input->post($dok->dokument_kurzbz."_nachgereicht") !== null))
				{
					$akte = new stdClass();
					$akte->person_id = $this->_data["person"]->person_id;

					$akte->dokument_kurzbz = $dok->dokument_kurzbz;
					$akte->insertvon = 'online';
					$akte->nachgereicht = true;
					$akte->anmerkung = $this->input->post($dok->dokument_kurzbz."_nachreichenAnmerkung");
					$akte->nachgereicht_am = date("Y-m-d", strtotime($this->input->post($dok->dokument_kurzbz."_nachreichenDatum")));

					$this->_saveAkte($akte);
				}
			}
		}

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);
		
		foreach($this->_data["dokumente"] as $akte)
		{
			if ($akte->dms_id != null)
			{
				$dms = $this->_loadDms($akte->dms_id);
				$akte->dokument = $dms;
			}
		}

		if(!isset($this->_data["error"]) && (isset($this->input->get()["studiengang_kz"])) && (isset($this->input->get()["studienplan_id"])) && (!empty($this->input->post())))
		{
			redirect("/Summary?studiengang_kz=".$this->input->get()["studiengang_kz"]."&studienplan_id=".$this->input->get()["studienplan_id"]);
		}
		else
		{
			$this->load->view('requirements', $this->_data);
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
			$this->_data["person"] = $this->_loadPerson();
			
			$this->_data["prestudent"] = $this->_loadPrestudent();

			//load dokumente
			$this->_loadDokumente($this->session->userdata()["person_id"]);

			foreach($this->_data["dokumente"] as $akte)
			{
				if ($akte->dms_id != null)
				{
					$dms = $this->_loadDms($akte->dms_id);
					$akte->dokument = $dms;
				}
			}

			foreach ($files as $key => $file)
			{
				if (is_uploaded_file($file["tmp_name"][0]))
				{
					$obj = new stdClass();
					$obj->new = true;
					$akte = new stdClass();

					$obj->version = 0;
					$obj->mimetype = $file["type"][0];
					$obj->name = $file["name"][0];
					$obj->oe_kurzbz = null;
					//$obj->dokument_kurzbz = $key;
					
					if ($typ)
						$obj->dokument_kurzbz = $typ;

					foreach($this->_data["dokumente"] as $akte_temp)
					{
						if (($akte_temp->dokument_kurzbz == $obj->dokument_kurzbz) && ($obj->dokument_kurzbz != $this->config->item('dokumentTypen')["sonstiges"]))
						{
							//       $dms = $this->_loadDms($akte_temp->dms_id);
							//       $obj->version = $dms->version+1;
							$akte = $akte_temp;
							$akte->updateamum = date("Y-m-d H:i:s");
							$akte->updatevon = "online";

							if ($akte->dms_id != null && !is_null($akte->dokument))
							{
								$obj = $akte->dokument;
								$obj->new = true;
								$obj->version = ($obj->version+1);

								//    $obj->version = ($akte->dokument->version+1);
								$obj->mimetype = $file["type"][0];
								$obj->name = $file["name"][0];
							}
						}
					}

					$obj->kategorie_kurzbz = "Akte";

					$type = pathinfo($file["name"][0], PATHINFO_EXTENSION);
					$data = file_get_contents($file["tmp_name"][0]);
					$obj->file_content = base64_encode($data);

					$result = new stdClass();
					$this->_saveDms($obj);
					if ($this->DmsModel->result->error == 0)
					{
						if ($obj->version >= 0)
						{
							$akte->dms_id = $this->DmsModel->result->retval->dms_id;
							$result->dms_id = $akte->dms_id;
							$akte->person_id = $this->_data["person"]->person_id;
							$akte->mimetype = $file["type"][0];

							$akte->bezeichnung = mb_substr($obj->name, 0, 32);
							$akte->dokument_kurzbz = $obj->dokument_kurzbz;
							$akte->titel = $key;
							$akte->insertvon = 'online';
							$akte->nachgereicht = 'f';

							unset($akte->uid);
							unset($akte->inhalt_vorhanden);
							$akte->dokument = null;
							unset($akte->dokument);
							unset($akte->nachgereicht_am);

							if ($this->_saveAkte($akte))
							{
								$result->success = true;
								$result->akte_id = $this->AkteModel->result->retval;
								$result->bezeichnung = $obj->name;
								$result->mimetype = $akte->mimetype;
							}
							else
							{
								$result->success = false;
							}
						}
						else
						{
							$akte->mimetype = $file["type"][0];
							$akte->bezeichnung = mb_substr($obj->name, 0, 32);
							$akte->dokument_kurzbz = $obj->dokument_kurzbz;
							$akte->titel = $key;

							unset($akte->uid);
							unset($akte->inhalt_vorhanden);
							$akte->dokument = null;
							unset($akte->dokument);
							unset($akte->nachgereicht_am);

							if ($this->_saveAkte($akte))
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
							
							foreach($this->_data["dokumente"] as $akte_temp)
							{
								if (($akte_temp->dokument_kurzbz == $this->config->item('dokumentTypen')["abschlusszeugnis"]))
								{
									$akte = $akte_temp;
								}
							}
						
							$akte->person_id = $this->_data["person"]->person_id;
							$akte->dokument_kurzbz = $this->config->item('dokumentTypen')["abschlusszeugnis"];
							$akte->insertvon = 'online';
							$akte->nachgereicht = true;
							if(isset($this->input->post()["doktype"]))
								$akte->anmerkung = $this->input->post("doktype");
							
							foreach($this->_data["prestudent"] as $prestudent)
							{
								if($prestudent->studiengang_kz == $this->input->post()["studiengang_kz"])
								{
//									if(($prestudent->zgvdatum == null) && ($prestudent->zgvort == null))
									{
										$prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis"]."_nachreichenDatum_".$this->input->post("studienplan_id"))));
										$prestudent->zgvort = "geplanter Abschluss";
										$this->_savePrestudent($prestudent);
									}
								}
							}
							//TODO set geplanter Abschluss
							//$akte->geplanterAbschluss = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis"]."_nachreichenDatum_".$this->input->post("studienplan_id"))));

							$this->_saveAkte($akte);
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
			$this->_loadDokumente($this->session->userdata()["person_id"]);

			foreach($this->_data["dokumente"] as $dok)
			{
				if(($dok->dms_id === $dms_id))
				{
					$result = $this->_deleteDms($dms_id);
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
	 * @param unknown $stgkz (optional)
	 * @return unknown
	 */
	private function _loadStudiengang($stgkz = null)
	{
		if (is_null($stgkz))
		{
			$stgkz = $this->_data["prestudent"][0]->studiengang_kz;
		}

		$this->StudiengangModel->getStudiengang($stgkz);
		if ($this->StudiengangModel->isResultValid() === true)
		{
			if (count($this->StudiengangModel->result->retval) == 1)
			{
				return $this->StudiengangModel->result->retval[0];
			}
			else
			{
				return $this->StudiengangModel->result->retval;
			}
		}
		else
		{
			$this->_setError(true, $this->StudiengangModel->getErrorMessage());
		}
	}

	private function _loadPrestudent()
	{
		$this->PrestudentModel->getPrestudent(array("person_id"=>$this->session->userdata()["person_id"]));
		if ($this->PrestudentModel->isResultValid() === true)
		{
			return $this->PrestudentModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _loadPrestudentStatus($prestudent_id)
	{
		//$this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
		$this->PrestudentStatusModel->getLastStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "status_kurzbz"=>"Interessent"));
		if($this->PrestudentStatusModel->isResultValid() === true)
		{
			if (($this->PrestudentStatusModel->result->error == 0) && (count($this->PrestudentStatusModel->result->retval) == 1))
			{
				return $this->PrestudentStatusModel->result->retval[0];
			}
			else
			{
				return $this->PrestudentStatusModel->result->retval;
			}
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _loadStudienplan($studienplan_id)
	{
		$this->StudienplanModel->getStudienplan($studienplan_id);
		if ($this->StudienplanModel->isResultValid() === true)
		{
			if (count($this->StudienplanModel->result->retval) == 1)
			{
				return $this->StudienplanModel->result->retval[0];
			}
			else
			{
				return $this->StudienplanModel->result->retval;
			}
		}
		else
		{
			$this->_setError(true, $this->StudienplanModel->getErrorMessage());
		}
	}

	private function _loadDokumente($person_id, $dokumenttyp_kurzbz=null)
	{
		$this->_data["dokumente"] = array();
		$this->AkteModel->getAktenAccepted($person_id, $dokumenttyp_kurzbz);

		if ($this->AkteModel->isResultValid() === true)
		{
			foreach($this->AkteModel->result->retval as $akte)
			{
				$this->_data["dokumente"][$akte->dokument_kurzbz] = $akte;
			}
		}
		else
		{
			$this->_setError(true, $this->AkteModel->getErrorMessage());
		}
	}

	private function _loadPerson()
	{
		$this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"]));
		if ($this->PersonModel->isResultValid() === true)
		{
			if (count($this->PersonModel->result->retval) == 1)
			{
				return $this->PersonModel->result->retval[0];
			}
			else
			{
				return $this->PersonModel->result->retval;
			}
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}

	private function _saveDms($dms)
	{
		$this->DmsModel->saveDms($dms);
		if ($this->DmsModel->isResultValid() === true)
		{
			//TODO saved successfully
		}
		else
		{
			$this->_setError(true, $this->DmsModel->getErrorMessage());
		}
	}

	private function _saveAkte($akte)
	{
		$this->AkteModel->saveAkte($akte);
		if ($this->AkteModel->isResultValid() === true)
		{
			//TODO saved successfully
			return true;
		}
		else
		{
			$this->_setError(true, $this->AkteModel->getErrorMessage());
		}
	}

	private function _loadDms($dms_id)
	{
		$this->DmsModel->loadDms($dms_id);
		if ($this->DmsModel->isResultValid() === true)
		{
			if (count($this->DmsModel->result->retval) == 1)
			{
				return $this->DmsModel->result->retval[0];
			}
			else
			{
				$this->_setError(true, "Dokument konnte nicht gefunden werden.");
			}
		}
		else
		{
			$this->_setError(true, $this->DmsModel->getErrorMessage());
		}
	}

	private function _loadDokumentByStudiengang($studiengang_kz)
	{
		$this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($studiengang_kz, true, null);
		if ($this->DokumentStudiengangModel->isResultValid() === true)
		{
			return $this->DokumentStudiengangModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->DokumentStudiengangModel->getErrorMessage());
		}
	}
	
	private function _deleteDms($dms_id)
	{
		$this->DmsModel->deleteDms($this->session->userdata("person_id"), $dms_id);
		if ($this->DmsModel->isResultValid() === true)
		{
			return $this->DmsModel->result;
		}
		else
		{
			$this->_setError(true, $this->DmsModel->getErrorMessage());
		}
	}
	private function _savePrestudent($prestudent)
	{
		$this->PrestudentModel->savePrestudent($prestudent);

		if ($this->PrestudentModel->isResultValid() === true)
		{
			return $this->PrestudentModel->result;
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}
}
