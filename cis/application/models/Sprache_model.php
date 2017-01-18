<?php

/**
 * ./cis/application/models/Oe_model.php
 *
 * @package default
 */
class Sprache_model extends REST_Model
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
     * @param unknown $sprache
     * @return unknown
     */
    public function getSprache($sprache, $authNotRequired = false)
    {
        return $this->loadOne('system/sprache/sprache',
            array(
                "sprache" => $sprache
            ),
            'Sprache.getSprache',
            $authNotRequired);
    }



}
