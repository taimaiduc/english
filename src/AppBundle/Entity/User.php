<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="register.email.unique")
 * @UniqueEntity(fields={"username"}, message="register.username.unique")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-z\-0-9]+$/",
     *     match=true,
     *     message="register.username.regex"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="SavedLesson", mappedBy="user")
     */
    private $savedLessons;

    /**
     * @ORM\OneToMany(targetEntity="DoneLesson", mappedBy="user")
     */
    private $doneLessons;

    /**
     * @ORM\OneToMany(targetEntity="Progress", mappedBy="user")
     */
    private $progress;

    /**
     * @ORM\Column(type="integer")
     */
    private $point = 0;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Registration"})
     */
    private $plainPassword;

    public function __construct()
    {
        $now = new \DateTime();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return DoneLesson[]
     */
    public function getDoneLessons()
    {
        return $this->doneLessons;
    }

    /**
     * @param DoneLesson $doneLessons
     */
    public function setDoneLessons(DoneLesson $doneLessons)
    {
        $this->doneLessons = $doneLessons;
    }

    /**
     * @return Progress[]
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param Progress $progress
     */
    public function setProgress(Progress $progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return SavedLesson[]
     */
    public function getSavedLessons()
    {
        return $this->savedLessons;
    }

    /**
     * @param mixed $savedLessons
     */
    public function setSavedLessons($savedLessons)
    {
        $this->savedLessons = $savedLessons;
    }

    public function addSavedLesson(SavedLesson $saveLesson)
    {
        $this->savedLessons[] = $saveLesson;
    }

    /**
     * @param int $point
     */
    public function addPoint($point)
    {
        $this->point += $point;
    }

    /**
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }
}