<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repository\UserRepository")
 * @Table(name="user")
 */
class User
{
    /**
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="firstname", type="string", length=32, nullable=false)
     */
    private $firstname;
    
    /**
     * @Column(name="lastname", type="string", length=32, nullable=false)
     */
    private $lastname;
    
    /**
     * @Column(name="username", type="string", length=45, nullable=false)
     */
    private $username;

    /**
     * @Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @Column(name="email", type="string", length=64, nullable=false)
     */
    private $email;  
    
    /**
     * @Column(name="gender", type="smallint", length=1, nullable=false)
     */
    private $gender;    
    
    /**
     * @Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @Column(name="avatar_path", type="string", length=255, nullable=false)
     */
    private $avatarPath;

    /**
     * @Column(name="token", type="string", length=255, nullable=false)
     */
    private $token;

    /**
     * @Column(name="role", type="smallint", length=1, nullable=false)
     */
    private $role;    
    
    /**
     * @Column(name="inserted_at", type="datetime", nullable=false)
     */
    private $insertedAt;
    
    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * One User has One Address.
     * @OneToOne(targetEntity="Address")
     * @JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;
    
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;
    const GENDER_OTHER = 2;
    const GENDER_UNSPECIFIED = 3;
    
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;

    function getId() {
        return $this->id;
    }

    function getFirstname() {
        return $this->firstname;
    }

    function getLastname() {
        return $this->lastname;
    }

    function getUsername() {
        return $this->username;
    }

    function getDescription() {
        return $this->description;
    }

    function getEmail() {
        return $this->email;
    }

    function getGender() {
        return $this->gender;
    }

    function getPassword() {
        return $this->password;
    }

    function getAvatarPath() {
        return $this->avatarPath;
    }

    function getToken() {
        return $this->token;
    }

    function getRole() {
        return $this->role;
    }

    function getInsertedAt() {
        return $this->insertedAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function getAddress() {
        return $this->address;
    }

    function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    function setAvatarPath($avatarPath) {
        $this->avatarPath = $avatarPath;
        return $this;
    }

    function setToken($token) {
        $this->token = $token;
        return $this;
    }

    function setRole($role) {
        $this->role = $role;
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

    function setAddress($address) {
        $this->address = $address;
        return $this;
    }


    
}