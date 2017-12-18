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
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @Column(name="platform", type="string", length=64, nullable=false)
     */
    private $plateform;

    /**
     * @Column(name="summary", type="string", nullable=false)
     */
    private $summary;

    /**
     * @Column(name="cover", type="string", length=255, nullable=false)
     */
    private $cover;

    /**
     * @Column(name="description", type="string", length=64, nullable=false)
     */
    private $description;

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
            'plateform' => $this->plateform,
            'summary' => $this->summary,
            'cover' => $this->cover,
            'description' => $this->description,
            'owner' => $this->owner->toArray(),
            'igdbId' => $this->igdbId,
            'insertedAt' => $this->insertedAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPlateform()
    {
        return $this->plateform;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getIgdbId()
    {
        return $this->igdbId;
    }

    /**
     * @return mixed
     */
    public function getInsertedAt()
    {
        return $this->insertedAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $name
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $plateform
     * @return Item
     */
    public function setPlateform($plateform)
    {
        $this->plateform = $plateform;
        return $this;
    }

    /**
     * @param mixed $sumary
     * @return Item
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param mixed $cover
     * @return Item
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * @param mixed $description
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $igdbId
     * @return Item
     */
    public function setIgdbId($igdbId)
    {
        $this->igdbId = $igdbId;
        return $this;
    }

    /**
     * @param mixed $insertedAt
     * @return Item
     */
    public function setInsertedAt($insertedAt)
    {
        $this->insertedAt = $insertedAt;
        return $this;
    }

    /**
     * @param mixed $updatedAt
     * @return Item
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param mixed $owner
     * @return Item
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

}