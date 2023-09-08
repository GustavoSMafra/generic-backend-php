<?php

namespace App\Models;

class Permissions extends \Phalcon\Mvc\Model
{
    protected $source = 'permissions';

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
     * @Column(type="string", length=45, nullable=true)
     */
    protected $name;

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
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
{
    $this->name = $name;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
{
    return $this->name;
}

    /**
     * Initialize method for model.
     */
    public function initialize()
{
    $this->setSchema("");
}
}