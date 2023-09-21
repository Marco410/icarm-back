<?php
namespace App\Classes\PlainObjects;


class AvatarPO
{

    private $avatarBase64;
    

    /**
     * Get the value of avatarBase64
     */ 
    public function getAvatarBase64()
    {
        return $this->avatarBase64;
    }

    /**
     * Set the value of avatarBase64
     *
     * @return  self
     */ 
    public function setAvatarBase64($avatarBase64)
    {
        $this->avatarBase64 = $avatarBase64;
    }
}