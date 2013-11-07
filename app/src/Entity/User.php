<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\Unique;

/**
 * @ORM\Entity
 * @Unique(fields="username", message="user.name.already_used", groups={"Registration"})
 * @Unique(fields="email", message="user.email.already_used", groups={"Registration"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank()
     * @Assert\Length(min = "3", max = "20")
     */
    protected $username;

    /**
     * @ORM\Column
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(min = "4", groups={"Registration"})
     */
    protected $password;

    /**
     * @ORM\Column
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\Column
     */
    protected $salt;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;


    public function __construct()
    {
        $this->salt = uniqid('', true);
        $this->roles = array('ROLE_USER');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($name)
    {
        $this->username = $name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }

    public function encodePassword(PasswordEncoderInterface $encoder)
    {
        $this->password = $encoder->encodePassword($this->password, $this->salt);
    }
}
