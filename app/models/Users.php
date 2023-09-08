<?php

namespace App\Models;

class Users extends \Phalcon\Mvc\Model
{
    protected $source = 'users';

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $first_name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $last_name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $login;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    protected $profile_id;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field first_name
     *
     * @param string $first_name
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Method to set the value of field last_name
     *
     * @param string $last_name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $pass
     * @return $this
     */
    public function setPassword($pass)
    {
        $this->password = $pass;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field profile_id
     *
     * @param integer $id
     * @return $this
     */
    public function setProfileId($id)
    {
        $this->profile_id = $id;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Returns the value of field last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Returns the value of field pass
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field login
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field profile_id
     *
     * @return integer
     */
    public function getProfileId()
    {
        return $this->profile_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("");
    }

}
