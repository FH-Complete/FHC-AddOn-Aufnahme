<?php

/**
 * ./cis/application/controllers/Downloads.php
 *
 * @package default
 */
class Downloads extends UI_Controller
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
		$this->load->model('person/Person_model', 'PersonModel');
		$this->load->model('system/Message_model', 'MessageModel');
        
        // 
		$this->setData('sprache', $this->getCurrentLanguage());
		
        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
    }
	
    /**
     *
     */
    public function index()
    {
        $this->_loadData();
		
        $this->load->view('downloads', $this->getAllData());
    }
	
    /**
     *
     */
    private function _loadData()
    {
        //load person data
		$this->setData('person', $this->PersonModel->getPerson());
		
		$studiengaengeArray = array();
		
		$studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
		if (hasData($studiensemester))
		{
			$this->setData('studiensemester', $studiensemester);
			$studiengaenge = $this->StudiengangModel->getAppliedStudiengang(
				$this->getData('studiensemester')->studiensemester_kurzbz,
				'',
				'Interessent',
                true
			);
			
			if (hasData($studiengaenge))
			{
				foreach ($studiengaenge->retval as $studiengang)
				{
					$studiengaengeArray[$studiengang->oe_kurzbz] = $studiengang->bezeichnung;
				}
			}
		}
		
		$this->setRawData('studiengaenge', $studiengaengeArray);
		
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
    }
}