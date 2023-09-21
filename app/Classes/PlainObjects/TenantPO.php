<?php
namespace App\Classes\PlainObjects;

class TenantPO
{
    private $clientId;
    private $accessToken;
    private $password;
    private $apiVersion;
    private $apiActive;
    private $apiGrantedDate;


    /**
     * Get the value of clientId
     */ 
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set the value of clientId
     *
     * @return  self
     */ 
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Get the value of accessToken
     */ 
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the value of accessToken
     *
     * @return  self
     */ 
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get the value of apiVersion
     */ 
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Set the value of apiVersion
     *
     * @return  self
     */ 
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * Get the value of apiActive
     */ 
    public function getApiActive()
    {
        return $this->apiActive;
    }

    /**
     * Set the value of apiActive
     *
     * @return  self
     */ 
    public function setApiActive($apiActive)
    {
        $this->apiActive = $apiActive;
    }

    /**
     * Get the value of apiGrantedDate
     */ 
    public function getApiGrantedDate()
    {
        return $this->apiGrantedDate;
    }

    /**
     * Set the value of apiGrantedDate
     *
     * @return  self
     */ 
    public function setApiGrantedDate($apiGrantedDate)
    {
        $this->apiGrantedDate = $apiGrantedDate;
    }
}
