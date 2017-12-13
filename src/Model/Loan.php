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
     * @Column(name="status", type="smallint", length=1, nullable=false)
     */
    private $status;
    
    /**
     * @Column(name="request_message", type="text", nullable=false)
     */
    private $requestMessage;
       
    /**
     * @Column(name="requested_at", type="datetime", nullable=false)
     */
    private $requestedAt;
    
    /**
     * @Column(name="confirmed_at", type="datetime", nullable=false)
     */
    private $confirmedAt;
    
        /**
     * @Column(name="closed_at", type="datetime", nullable=false)
     */
    private $closedAt;
    
    /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;
    
    const STATUS_REQUESTED = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_DENIED = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_CLOSED = 4;
    
    function getId() {
        return $this->id;
    }
    
    function getStatus() {
        return $this->status;
    }

    function getRequestMessage() {
        return $this->requestMessage;
    }

    function getRequestedAt() {
        return $this->requestedAt;
    }

    function getConfirmedAt() {
        return $this->confirmedAt;
    }

    function getClosedAt() {
        return $this->closedAt;
    }

    function getUpdatedAt() {
        return $this->updatedAt;
    }

    function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    function setRequestMessage($requestMessage) {
        $this->requestMessage = $requestMessage;
        return $this;
    }

    function setRequestedAt($requestedAt) {
        $this->requestedAt = $requestedAt;
        return $this;
    }

    function setConfirmedAt($comfirmedAt) {
        $this->confirmedAt = $comfirmedAt;
        return $this;
    }

    function setClosedAt($closedAt) {
        $this->closedAt = $closedAt;
        return $this;
    }

    function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }


}