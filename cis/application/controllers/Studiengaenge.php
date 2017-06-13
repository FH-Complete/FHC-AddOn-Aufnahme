<?php

/**
 * ./cis/application/controllers/Studiengaenge.php
 *
 * @package default
 */
class Studiengaenge extends UI_Controller
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
            $this->lang->load(array('studiengaenge'), $this->getData('sprache'));
        }

        // Loading the
        $this->load->model('system/Phrase_model', 'PhraseModel');

        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisation/Studiengang_model', 'StudiengangModel');

        $this->load->model('person/Person_model', 'PersonModel');

        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('crm/Bewerbungstermine_model', 'BewerbungstermineModel');

        $this->load->model('system/Message_model', 'MessageModel');

        $this->load->model('codex/Organisationsform_model', 'OrgformModel');
	}

	/**
	 *
	 */
	public function index()
	{
		$this->benchmark->mark('code_start');

        $this->PhraseModel->getPhrasen(
            'aufnahme',
            ucfirst($this->getData('sprache'))
        );

        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());


		//load person data
        $this->setData('person', $this->PersonModel->getPerson());

        if($this->input->get("studiengang_kz") != null)
        {
            $this->setRawData("studiengang_kz", $this->input->get("studiengang_kz"));
            //$this->setData("studiengang", $this->StudiengangModel->getStudiengang($this->getData('studiengang_kz')));
        }

		$this->setRawData('title', 'Overview');

		$orgform = $this->OrgformModel->getAll();

		if(hasData($orgform))
        {
            $this->setData('orgform', $orgform);
        }
        else
        {
            $this->_setError(true, $this->OrgformModel->getErrorMessage($orgform));
        }

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
            $this->setRawData('studiensemester_kurzbz', $studiensemester->retval->studiensemester_kurzbz);
            $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                $this->getData('studiensemester')->studiensemester_kurzbz,
                '',
                'Interessent'
            ));

            $studiengaenge = array();

            if(is_array($this->getData("studiengaenge")))
            {
                foreach ($this->getData("studiengaenge") as $stg)
                {
                    if ((count($stg->prestudenten) > 1) && (count($stg->prestudentstatus) > 1))
                    {
                        foreach ($stg->prestudenten as $key => $ps)
                        {
                            $tempStg = clone $stg;
                            $tempStg->prestudenten = array();
                            $tempStg->prestudenten[0] = $ps;
                            $tempStg->prestudentstatus = array();
                            $tempStg->prestudentstatus[0] = $stg->prestudentstatus[$key];
                            $tempStg->studienplaene = array();
                            $tempStg->studienplaene[0] = $stg->studienplaene[$key];
                            array_push($studiengaenge, $tempStg);
                        }
                    }
                    else
                    {
                        array_push($studiengaenge, $stg);
                    }
                }
            }

            $this->setRawData("studiengaenge", $studiengaenge);

			$this->benchmark->mark('codepart_start');
			$this->setData("studiengaengeForBewerbung", $this->StudiengangModel->getStudiengangBewerbung());
			$this->benchmark->mark('codepart_end');
			log_message('debug', 'Time elapsed for Studiengaenge/index->getStudienplan: ' . $this->benchmark->elapsed_time('codepart_start', 'codepart_end') . 'ms');

            $this->benchmark->mark('codepart_start');
			$bewerbungstermine = $this->BewerbungstermineModel->getCurrent()->retval;
            $this->benchmark->mark('codepart_end');
            log_message('debug', 'Time elapsed for Studiengaenge/index->getBewerbungstermine: ' . $this->benchmark->elapsed_time('codepart_start', 'codepart_end') . 'ms');

            $this->benchmark->mark('foreach_start');
			foreach ($this->getData("studiengaengeForBewerbung") as $stg)
			{
				if ($stg->onlinebewerbung === true)
				{
					$this->benchmark->mark('codepart_start');

					foreach ($stg->studienplaene as $key_studienplaene => $row_studienplaene)
					{
						$stg->studienplaene[$key_studienplaene]->fristen = array();
						foreach ($bewerbungstermine as $row_bewerbungstermin)
						{
							if ($row_studienplaene->studienplan_id == $row_bewerbungstermin->studienplan_id)
							{
								$stg->studienplaene[$key_studienplaene]->fristen[] = $row_bewerbungstermin;
							}
						}
						if(empty($stg->studienplaene[$key_studienplaene]->fristen))
                        {
                            unset($stg->studienplaene[$key_studienplaene]);
                        }
					}
                    $stg->studienplaene = array_values($stg->studienplaene);
					$this->benchmark->mark('codepart_end');
					log_message('debug', 'Time elapsed for Studiengaenge/index->Reihunstest/Termin: ' . $this->benchmark->elapsed_time('codepart_start', 'codepart_end') . 'ms');

					if (($this->getData("studiengang_kz") !== null) && ($stg->studiengang_kz === $this->getData("studiengang_kz")))
						if (count($stg->studienplaene) === 1)
							redirect("/Bewerbung/studiengang/" . $stg->studiengang_kz . "/" . $stg->studienplaene[0]->studienplan_id);
				}
			}
			$this->benchmark->mark('foreach_end');
			log_message('debug', 'Time elapsed for Studiengaenge/index->foreach: ' . $this->benchmark->elapsed_time('foreach_start', 'foreach_end') . 'ms');

			//load preinteressent data
			$this->setData("prestudent", $this->PrestudentModel->getLastStatuses(
			    $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz
            ));
			$aktiveBewerbungen = array();
			if($this->getData('prestudent') !== null)
            {
                foreach ($this->getData("prestudent") as $prestudent)
                {
                    //load studiengaenge der prestudenten
                    //$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                    if (($prestudent->status_kurzbz === "Interessent" || $prestudent->status_kurzbz === "Bewerber"))
                    {
                        $aktiveBewerbungen[$prestudent->studienplan_id] = $prestudent->studienplan_id;
                    }
                }
            }

			$this->setRawData("aktiveBewerbungen", $aktiveBewerbungen);

            $this->benchmark->mark('load_view_start');
			$this->load->view('studiengaenge', $this->getAllData());
            $this->benchmark->mark('load_view_end');
            log_message('debug', 'Time elapsed for Studiengaenge/index->loadView: ' . $this->benchmark->elapsed_time('load_view_start', 'load_view_end') . 'ms');
		}
		else
		{
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage($studiensemester));
		}

		$this->benchmark->mark('code_end');
		log_message('debug', 'Time elapsed for Studiengaenge/index(): ' . $this->benchmark->elapsed_time('code_start', 'code_end') . 'ms');
	}
}
