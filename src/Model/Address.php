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
     * @Column(name="zip_code", type="int", length=11, nullable=false)
     */
    private $zip_code;
    
     /**
     * @Column(name="city", type="string", length=64, nullable=false)
     */
    private $city;
    
      /**
     * @Column(name="country", type="string", length=64, nullable=false)
     */
    private $country;
    
      /**
     * @Column(name="lat", type="int", length=11, nullable=false)
     */
    private $lat;
    
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

    function getAddress1() {
        return $this->address1;
    }

    function getAddress2() {
        return $this->address2;
    }

    function getZip_code() {
        return $this->zip_code;
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

    function getInserted_at() {
        return $this->inserted_at;
    }

    function getUpdated_at() {
        return $this->updated_at;
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

    function setZip_code($zip_code) {
        $this->zip_code = $zip_code;
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

    function setInserted_at($inserted_at) {
        $this->inserted_at = $inserted_at;
    }

    function setUpdated_at($updated_at) {
        $this->updated_at = $updated_at;
    }


}