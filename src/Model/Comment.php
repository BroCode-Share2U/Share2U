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
     * @Column(name="address2", type="int", length=5, nullable=false)
     */
    private $rating;
     
      /**
     * @Column(name="inserted_at", type="datetime", nullable=false)
     */
    private $inserted_at;
    
      /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updated_at;
    
   
    
    function getId() {
        return $this->id;
    }

    function getText() {
        return $this->text;
    }

    function getRating() {
        return $this->rating;
    }

    function getInserted_at() {
        return $this->inserted_at;
    }

    function getUpdated_at() {
        return $this->updated_at;
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

    function setInserted_at($inserted_at) {
        $this->inserted_at = $inserted_at;
    }

    function setUpdated_at($updated_at) {
        $this->updated_at = $updated_at;
    }
}   