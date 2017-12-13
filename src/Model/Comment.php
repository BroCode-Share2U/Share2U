<?php

namespace Model;

/**
 * @Entity()
 * @Table(name="comment")
 */
class Comment
{
      /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="text", type="text", nullable=false)
     */
    private $text;
    
    /**
     * @Column(name="rating", type="smallint", length=1, nullable=false)
     */
    private $rating;
     
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

    function getText() {
        return $this->text;
    }

    function getRating() {
        return $this->rating;
    }

    function getInsertedAt() {
        return $this->insertedAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setRating($rating) {
        $this->rating = $rating;
    }

    function setInsertedAt($insertedAt) {
        $this->insertedAt = $insertedAt;
    }

    function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
}   