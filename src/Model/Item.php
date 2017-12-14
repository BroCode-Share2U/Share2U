<?php

namespace Model;

/**
 * @Entity()
 * @Table(name="item")
 */
class Item
{    
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;
    
    /**
     * @Column(name="igdb_id", type="integer", length=11, nullable=false)
     */
    private $igdbId;
       
    /**
     * @Column(name="inserted_at", type="datetime", nullable=false)
     */
    private $insertedAt;
    
    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getIgdbId() {
        return $this->igdbId;
    }

    function getInsertedAt() {
        return $this->insertedAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function setIgdbId($igdbId) {
        $this->igdbId = $igdbId;
        return $this;
    }

    function setInsertedAt($insertedAt) {
        $this->insertedAt = $insertedAt;
        return $this;
    }

    function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    
}