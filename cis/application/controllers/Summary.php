<?php
/**
 * ./cis/application/controllers/Summary.php
 *
 * @package default
 */


class Summary extends UI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 
		$currentLanguage = $this->getCurrentLanguage();
		if (hasData($currentLanguage))
		{
			$this->setData('sprache', $currentLanguage);
			$this->lang->load(array('summary'), $this->getData('sprache'));
		}
		
		$this->load->model('system/Phrase_model', 'PhraseModel');
		$this->load->model('system/Message_model', 'MessageModel');
		
		$this->load->model('codex/Nation_model', 'NationModel');
		$this->load->model('codex/Bundesland_model', 'BundeslandModel');
		
		$this->load->model('content/Dms_model', 'DmsModel');
		
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
		$this->load->model('organisation/Studienplan_model', 'StudienplanModel');
		
		$this->load->model('person/Person_model', 'PersonModel');
		$this->load->model('person/Adresse_model', 'AdresseModel');
		$this->load->model('person/Kontakt_model', 'KontaktModel');
		
		$this->load->model('crm/Akte_model', 'AkteModel');
		$this->load->model('crm/Dokument_model', 'DokumentModel');
		$this->load->model('crm/Prestudent_model', 'PrestudentModel');
		$this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');
        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');
	}

	/**
	 *
	 */
	public function index()
	{
        if ((isset($this->input->get()['studiengang_kz']) && ($this->input->get()['studiengang_kz'] !== null) && ($this->input->get()['studiengang_kz'] !== ''))
            && (isset($this->input->get()['studienplan_id'])) && ($this->input->get()['studienplan_id'] !== null) && ($this->input->get()['studienplan_id'] !== '')
        )
        {
            $this->PhraseModel->getPhrasen(
                'aufnahme',
                ucfirst($this->getData('sprache'))
            );

            $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
            $this->setData('person', $this->PersonModel->getPerson());

            $this->setRawData('studiengang_kz', $this->input->get('studiengang_kz'));
            $this->setRawData('studienplan_id', $this->input->get('studienplan_id'));

            $studiensemester = $this->StudiensemesterModel->getAktStudiensemester();
            if (hasData($studiensemester))
            {
                $this->setData('studiensemester', $studiensemester);
                $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengangFromNow(
                    '',
                    true
                ));
            }

            //setting selected Studiengang by GET Param
            $abgeschickt_array = array();
            $studiengaenge = array();

            foreach($this->getData('studiengaenge') as $stg)
            {
                if((count($stg->prestudenten) > 1) && (count($stg->prestudentstatus) > 1))
                {
                    foreach($stg->prestudenten as $key => $ps)
                    {
                        $tempStg = clone $stg;
                        $tempStg->prestudenten = array();
                        $tempStg->prestudenten[0] = $ps;
                        $tempStg->prestudentstatus = array();
                        $tempStg->prestudentstatus[0] = $stg->prestudentstatus[$key];
                        $tempStg->studienplaene = array();
                        $tempStg->studienplaene[0] = $stg->studienplaene[$key];
                        array_push($studiengaenge, $tempStg);

                        if ($tempStg->studiengang_kz === $this->getData('studiengang_kz') && ($tempStg->prestudentstatus[0]->studienplan_id === $this->getData('studienplan_id')))
                        {
                            $this->setRawData('studiengang', $tempStg);
                        }

                        if ($tempStg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                        {
                            $this->setRawData('bewerbung_abgeschickt', true);
                            $abgeschickt_array[$tempStg->studiengang_kz] = true;
                        }
                    }
                }
                else
                {
                    array_push($studiengaenge, $stg);
                    if ($stg->studiengang_kz === $this->getData('studiengang_kz') && ($stg->prestudentstatus[0]->studienplan_id === $this->getData('studienplan_id')))
                    {
                        $this->setRawData('studiengang', $stg);
                    }
                }
            }

            $this->setRawData('studiengaenge', $studiengaenge);
            $this->setRawData('abgeschickt_array', $abgeschickt_array);

            $this->setRawData('studiengaenge', array($this->getData('studiengang')));

            $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

            $this->setData('adresse', $this->AdresseModel->getAdresse());

            $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

            $this->setData(
                'prestudent',
                $this->PrestudentModel->getLastStatuses(
                    $this->getData('person')->person_id,
                    $this->getData('studiensemester')->studiensemester_kurzbz,
                    null,
                    null,
                    true
                )
            );

            //manually parsing udf_values
            $prestudent = $this->getData('studiengang')->prestudenten[0];

            if(isset($prestudent->udf_values))
            {
                $udf_values = json_decode($prestudent->udf_values);
                $udf_values = (array) $udf_values;
                if(is_array($udf_values) && (count($udf_values) > 0))
                {
                    foreach($udf_values as $udf_key => $udf_value)
                    {
                        $prestudent->{$udf_key} = $udf_value;
                    }
                }
            }

            $this->setRawData('prestudent', $prestudent);

            $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

            //load Dokumente from Studiengang
            $dokumenteStudiengang = array();
            $dokumenteStudiengang[$this->getData('studiengang')->studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($this->getData('studiengang')->studiengang_kz, true, true)->retval;
            $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

            $person = $this->getData('person');

            if((isset($person->bundesland_code)) && ($person->bundesland_code !== null))
            {
                $bundesland = $this->BundeslandModel->getBundesland($person->bundesland_code);

                if ($bundesland !== null)
                {
                    $person->bundesland_bezeichnung = $bundesland->bezeichnung;
                }
                else
                {
                    $person->bundesland_bezeichnung = null;
                }
            }

            $nation = $this->NationModel->getNation($person->geburtsnation);

            if ($nation !== null)
            {

                $person->geburtsnation_text = $nation->kurztext;
            }
            else
            {
                $person->geburtsnation_text = null;
            }

            $this->setRawData('person', $person);

            //load data for specialization
            $spezialisierung = array();
            $spezialisierung[$this->getData('prestudent')->studiengang_kz] = $this->PrestudentModel->getSpecialization($this->getData('prestudent')->prestudent_id, true)->retval;
            $this->setRawData('spezialisierung', $spezialisierung);

            //load bundeslaender
            $this->setData('bundeslaender', $this->BundeslandModel->getAll());

            //load nationen
            $this->setData('nationen', $this->NationModel->getAll());

            //load adresse
            $this->setData('adresse', $this->AdresseModel->getAdresse());

            //load kontakt
            $this->setData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId());

            //load dokumente
            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

            $this->_getPersonalDocuments();

            //load phrase for specialization
            $spezPhrase = array();
            $spezPhrase[$this->getData('prestudent')->studiengang_kz] = $this->getPhrase('Aufnahme/Spezialisierung', $this->getData('sprache'), $this->getData('studiengang')->oe_kurzbz, $this->getData('studiengang')->studienplaene[0]->orgform_kurzbz);
            $this->setRawData('spezPhrase', $spezPhrase);

            $this->load->view('summary', $this->getAllData());

        }
        else
        {
            redirect('/Bewerbung');
        }
	}

	/**
	 * 
	 */
	private function _getPersonalDocuments()
	{
		$personalDocumentsArray = array();
		
		if (isSuccess($reisepass = $this->DokumentModel->getDokument($this->config->item('dokumentTypen')['reisepass'])))
		{
			$personalDocumentsArray[$this->config->item('dokumentTypen')['reisepass']] = $reisepass->retval;
		}
		
		if (isSuccess($lebenslauf = $this->DokumentModel->getDokument($this->config->item('dokumentTypen')['lebenslauf'])))
		{
			$personalDocumentsArray[$this->config->item('dokumentTypen')['lebenslauf']] = $lebenslauf->retval;
		}
		
		$this->setData('personalDocuments', success($personalDocumentsArray));
	}

    function getPhrase($phrase, $sprache, $oe_kurzbz = null, $orgform_kurzbz = null)
    {
        $result = null;
        $phrasen = null;

        if (isset($this->session->userdata()['Phrase.getPhrasen:' . $sprache]))
        {
            $result = $this->session->userdata()['Phrase.getPhrasen:' . $sprache];
        }

        if (hasData($result))
        {
            $phrasen = $result->retval;

            if (is_array($phrasen))
            {
                $text = '';
                $sprache = ucfirst($sprache);

                foreach ($phrasen as $p)
                {
                    if($p->phrase == $phrase)
                    {
                        if (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . ' <i>[$p->phrase]</i>';
                            else
                                $text = $p->text;
                        }
                        elseif (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . ' <i>[$p->phrase]</i>';
                            else
                                $text = $p->text;
                        }
                        elseif (($p->orgeinheit_kurzbz == $this->config->item('root_oe')) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . ' <i>[$p->phrase]</i>';
                            else
                                $text = $p->text;
                        }
                        elseif (($p->orgeinheit_kurzbz == null) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . ' <i>[$p->phrase]</i>';
                            else
                                $text = $p->text;
                        }
                    }
                }

                if($text != '')
                    return $text;

                if ($this->config->item('display_phrase_name'))
                    return '<i>[$phrase]</i>';
            }
            else
            {
                return $phrasen;
            }
        }
        else
        {
            return 'Please load phrases first';
        }
    }
}