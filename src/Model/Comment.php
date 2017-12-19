<?php

namespace Model;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Entity(repositoryClass="Model\Repository\CommentRepository")
 * @Table(name="comment")
 */
class Comment
{
    /*---------------------
            Param
    -------------------- */
      /**
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", type="guid", nullable=false)
     */
    protected $id;
    
    /**
     * @Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @Column(name="rating", type="integer", nullable=false)
     */
    private $rating;
     
      /**
     * @Column(name="inserted_at", type="datetime", nullable=false)
     */
    private $insertedAt;
    
      /**
     * @Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @ManyToOne(targetEntity="Loan")
     * @JoinColumn(name="loan_id", referencedColumnName="id")
     */
    private $loan;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /*---------------------
            Getter
    -------------------- */
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return DateTime
     */
    public function getInsertedAt()
    {
        return $this->insertedAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Loan
     */
    public function getLoan()
    {
        return $this->loan;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }



    /*---------------------
            Setter
    -------------------- */
    /**
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param integer $rating
     * @return Comment
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @param DateTime $insertedAt
     * @return Comment
     */
    public function setInsertedAt($insertedAt)
    {
        $this->insertedAt = $insertedAt;
        return $this;
    }

    /**
     * @param DateTime $updatedAt
     * @return Comment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param Loan $loan
     * @return Comment
     */
    public function setLoan($loan)
    {
        $this->loan = $loan;
        return $this;
    }

    /**
     * @param User $author
     * @return Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param User $user
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }


}