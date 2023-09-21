<?php
namespace App\Classes\PlainObjects;


class UserPO
{

    private $username;
    private $password;
    private $email;
    private $active;
    private $createdAt;
    private $tempAccessToken;
    private $rememberToken;

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;
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
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get the value of active
     */ 
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of active
     *
     * @return  self
     */ 
    public function setActive($active)
    {
        $this->active = $active;
    }
    

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the value of tempAccessToken
     */ 
    public function getTempAccessToken()
    {
        return $this->tempAccessToken;
    }

    /**
     * Set the value of tempAccessToken
     *
     * @return  self
     */ 
    public function setTempAccessToken($tempAccessToken)
    {
        $this->tempAccessToken = $tempAccessToken;
    }

    /**
     * Get the value of rememberToken
     */ 
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * Set the value of rememberToken
     *
     * @return  self
     */ 
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;
    }
}