<?php
namespace App\Classes\PlainObjects;

use App\Classes\PlainObjects\UserPO;
use App\Classes\PlainObjects\PersonPO;
use App\Classes\PlainObjects\TenantPO;


class ExternalUserPO
{

    private TenantPO    $tenant;
    private UserPO      $user;
    private PersonPO    $person;


    /**
     * Get the value of tenant
     */ 
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Set the value of tenant
     *
     * @return  self
     */ 
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get the value of person
     */ 
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set the value of person
     *
     * @return  self
     */ 
    public function setPerson($person)
    {
        $this->person = $person;
    }
}