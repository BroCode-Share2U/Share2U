<?php

namespace Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Entity(repositoryClass="Model\Repository\UserRepository")
 * @Table(name="user")
 */
class User implements UserInterface
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
    private $roles =  ['ROLE_USER'];
    
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

    public function getId() {
        return $this->id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getAvatarPath() {
        return $this->avatarPath;
    }

    public function getToken() {
        return $this->token;
    }

  /*  public function getRole() {
        return $this->role;
    }
*/
    public function getInsertedAt() {
        return $this->insertedAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setAvatarPath($avatarPath) {
        $this->avatarPath = $avatarPath;
        return $this;
    }

    public function setToken($token) {
        $this->token = $token;
        return $this;
    }
/*
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }
*/
    public function setInsertedAt($insertedAt) {
        $this->insertedAt = $insertedAt;
        return $this;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
     /*   $roles = [];
        foreach ($this->roles as $role){
            $roles[] = $role->getrole();
        }*/
        return ! $this->roles ? [] : explode(',', $this->roles);
    }

    public function setRoles($roles)
    {
   /*     $this->roles = [];
        foreach ($roles as $role){
            $this->addRole($role);
        }*/
        return $this;
    }

    function addRole(Role $role)
    {
        if (in_array($role, $this->roles)){
            return $this;
        }
        $this->roles[] = $role;
        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return;
    }


}