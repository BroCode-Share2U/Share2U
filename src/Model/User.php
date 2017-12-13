<?php

namespace Model;

/**
 * @Entity()
 * @Table(name="user")
 */
class User
{
    /**
     * @Id()
     * @GeneratedValue()
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
     * @Column(name="gender", type="int", length=5, nullable=false)
     */
    private $gender;    
    
    
    /**
     * @Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @Column(name="avatar_path", type="string", length=255, nullable=false)
     */
    private $avatar;

    /**
     * @Column(name="token", type="string", length=255, nullable=false)
     */
    private $token;

    /**
     * @Column(name="role", type="string", length=64, nullable=false)
     */
    private $role;    
    
    /**
     * @Column(name="inserted_at", type="datetime", nullable=false)
     */
    private $inserted_at;
    
    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updated_at;

    /**
     * @ManyToOne(targetEntity="Address")
     * @JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;
    
    
}