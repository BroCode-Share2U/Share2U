<?php

namespace Model;

/**
 * @Entity()
 * @Table(item="user")
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
     * @Column(name="igdb_id", type="int", length=11, nullable=false)
     */
    private $igdb;
       
    /**
     * @Column(name="inserted_at", type="datetime", nullable=false)
     */
    private $inserted_at;
    
    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updated_at;
    
    
    
}