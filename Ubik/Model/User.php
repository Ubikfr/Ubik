<?php

class Model_User extends Model_Base
{
    protected $email;
    protected $password;
    protected $nom;
    protected $prenom;
    protected $role;
    protected $lastLogin;
    protected $token;
    protected $lostPassword;
    protected $active;
    protected $remove;

    /**
     * Get email
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);
    }

    /**
     * Get password
     * @return string password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }    

    /**
     * Get role
     * @return string User role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     * @param string $role User role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
    /**
     * Get lastLogin
     * @return DateTime User's last login time
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set lastLogin
     * @param DateTime $lastLogin the value to set
     */
    public function setLastLogin(DateTime $lastLogin = null)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Get name
     * @return string nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set name
     * @param string $emailCanonical
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get first name
     * @return string prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set first name
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get token
     * @return string token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Returns array representation of User
     * @return array User data
     */
    public function toArray()
    {
        $data = array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'nom' => $this->getNom(),
            'prenom' => $this->getPrenom(),
            'role' => $this->getRole(),
            'lastLogin' => $this->getLastLogin(),
            'token' => $this->getToken(),
        );

        return $data;
    }
}
