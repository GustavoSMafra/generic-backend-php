<?php

namespace App\Models;

class Rules extends \Phalcon\Mvc\Model
{
    protected $source = 'rules';

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
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    protected $modules_id;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    protected $permissions_id;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    protected $profiles_id;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    protected $status;

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
     * Method to set the value of field module_id
     *
     * @param integer $module_id
     * @return $this
     */
    public function setModulesId($module_id)
    {
        $this->modules_id = $module_id;

        return $this;
    }

    /**
     * Method to set the value of field permissions_id
     *
     * @param integer $permissions_id
     * @return $this
     */
    public function setPermissionsId($permissions_id)
    {
        $this->permissions_id = $permissions_id;

        return $this;
    }

    /**
     * Method to set the value of field profiles_id
     *
     * @param integer $profiles_id
     * @return $this
     */
    public function setProfilesId($profiles_id)
    {
        $this->profiles_id = $profiles_id;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Returns the value of field modules_id
     *
     * @return integer
     */
    public function getModulesID()
    {
        return $this->modules_id;
    }

    /**
     * Returns the value of field permissions_id
     *
     * @return integer
     */
    public function getPermissionsId()
    {
        return $this->permissions_id;
    }

    /**
     * Returns the value of field profiles_id
     *
     * @return integer
     */
    public function getProfilesId()
    {
        return $this->profiles_id;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("");
    }
}