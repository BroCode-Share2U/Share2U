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
     * @GeneratedValue(strategy="UUID")
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
     * @Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;
    
     /**
     * @Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;
    
      /**
     * @Column(name="inserted_at", type="datetime", nullable=true)
     */
    private $insertedAt;
    
      /**
     * @Column(name="updated_at", type="datetime", nullable=true)
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

    function getLatitude() {
        return $this->latitude;
    }
    
    function getLongitude() {
        return $this->longitude;
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

    function setLatitude($latitude) {
        $this->latitude = $latitude;
    }
    
    function setLongitude($longitude) {
        $this->longitude = $longitude;
        return $this;
    }

    function setInsertedAt($insertedAt) {
        $this->insertedAt = $insertedAt;
    }

    function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }


}