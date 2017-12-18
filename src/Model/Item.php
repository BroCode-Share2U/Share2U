<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repository\ItemRepository")
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

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner' => $this->owner->toArray(),
            'igdbId' => $this->igdbId,
            'insertedAt' => $this->insertedAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

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

    public function getOwner()
    {
        return $this->owner;
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

    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

}