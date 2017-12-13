<?php

namespace Model;

    /**
 * @Entity()
 * @Table(name="address")
 */
class Address
{
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="address1", type="string", length=255, nullable=false)
     */
    private $address1;
    
    /**
     * @Column(name="address2", type="string", length=64, nullable=false)
     */
    private $address2;
    
    /**
     * 
     * @Column(name="zip_code", type="string", length=10, nullable=false)
     */
    private $zipCode;
    
     /**
     * @Column(name="city", type="string", length=64, nullable=false)
     */
    private $city;
    
      /**
     * @Column(name="country", type="string", length=64, nullable=false)
     */
    private $country;
    
      /**
     * @Column(name="lat", type="float", nullable=false)
     */
    private $lat;
    
     /**
     * @Column(name="long", type="float", nullable=false)
     */
    private $long;
    
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

    function getAddress1() {
        return $this->address1;
    }

    function getAddress2() {
        return $this->address2;
    }

    function getZipCode() {
        return $this->zipCode;
    }

    function getCity() {
        return $this->city;
    }

    function getCountry() {
        return $this->country;
    }

    function getLat() {
        return $this->lat;
    }
    
    function getLong() {
        return $this->long;
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

    function setAddress1($address1) {
        $this->address1 = $address1;
    }

    function setAddress2($address2) {
        $this->address2 = $address2;
    }

    function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }

    function setCity($city) {
        $this->city = $city;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setLat($lat) {
        $this->lat = $lat;
    }
    
    function setLong($long) {
        $this->long = $long;
        return $this;
    }

    function setInsertedAt($insertedAt) {
        $this->insertedAt = $insertedAt;
    }

    function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }


}