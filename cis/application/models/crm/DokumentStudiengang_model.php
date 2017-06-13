<?php

/**
 * ./cis/application/models/DokumentStudiengang_model.php
 *
 * @package default
 */
class DokumentStudiengang_model extends REST_Model
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function getDokumentstudiengangByStudiengang_kz($studiengang_kz, $onlinebewerbung, $pflicht)
    {
        return $this->load('crm/dokumentstudiengang/DokumentstudiengangByStudiengang_kz', array("studiengang_kz" => $studiengang_kz, "onlinebewerbung" => $onlinebewerbung, "pflicht" => $pflicht), 'DokumentstudiengangByStudiengang_kz:'.$studiengang_kz);
    }


}
