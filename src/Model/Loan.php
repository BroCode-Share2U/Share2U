<?php

namespace Model;

/**
 * @Entity()
 * @Table(Loan="user")
 */
class Loan
{
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="name", type="int", length=5, nullable=false)
     */
    private $status;
    
    /**
     * @Column(name="request_message", type="text", nullable=false)
     */
    private $request_message;
       
    /**
     * @Column(name="requested_at", type="datetime", nullable=false)
     */
    private $requested_at;
    
    /**
     * @Column(name="comfirmed_at", type="datetime", nullable=false)
     */
    private $comfirmed_at;
    
        /**
     * @Column(name="closed_at", type="datetime", nullable=false)
     */
    private $closed_at;
    
    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updated_at;
    
    
}