<?php

/**
 * Dao User
 */
class Dao_User
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Find user by id
     * @param  int   $id User id
     * @return array User data
     */
    public function findById($id)
    {
        $q = 'SELECT * FROM Users WHERE id = :id';
        $s = $this->db->prepare($q);
        $s->bindValue(':id', $id);
        $s->setFetchMode(PDO::FETCH_CLASS, 'Model_User');
        $s->execute();

        return $s->fetch();
    }

    /**
     * Find user by email address
     * @param  string $email User's email address
     * @return array  User record
     */
    public function findByEmail($email)
    {
        $q = 'SELECT * FROM Users WHERE email = :email';
        $s = $this->db->prepare($q);
        $s->bindValue(':email', $email, PDO::PARAM_STR);
        $s->execute();

        $data = $s->fetch();

        if (!$data) {
            return null;
        }

        return new Model_User($data);
    }

    /**
     * Find user by email and password
     * @param  string $email User's email address
     * @param  string $password User's password
     * @return array  User record
     */
    public function findByEmailPassword($email, $password)
    {
        try{
            //$q = "SELECT UserId, UserUniqId, Email, Password, FirstName, LastName, 
            //  Role, Language, SiteId, Created, Token 
            //  FROM Users WHERE Email=?";
            $q = 'SELECT * FROM Users WHERE email = :email';
            $s = $this->db->prepare($q);
            $s->bindValue(':email', $email, PDO::PARAM_STR);
            $s->execute();
            $data = $s->fetch(PDO::FETCH_ASSOC);
            if($data){
                $hash = $data['password'];
                // need to check the password
                $hash_cost_log2 = 8;
                $hash_portable = FALSE;
                $hasher = new Utils_PasswordHash($hash_cost_log2, $hash_portable);
                if($hasher->CheckPassword($password, $hash)){ // success
                    unset($hasher);
                    return new Model_User($data);
                }
                else{ // failure
                    unset($hasher);
                    return null;
                }
            }  
        } catch(PDOException $e){
            die('[Dao_User::findByEmailPassword] PDO Error: '.$e->getMessage());
        }       
    }

    /**
     * Updates user email address
     * @param  User $user User to update
     * @return User Updated user
     */
    public function updateEmail(Model_User $user)
    {
        $q = 'UPDATE users SET email = :email, emailCanonical = :emailCanonical WHERE id = :id';
        $s = $this->db->prepare($q);
        $s->execute(array(
            'email' => $user->getEmail(),
            'emailCanonical' => $user->getEmailCanonical(),
            'id' => $user->getId()
        ));

        return $this->find($user->getId());
    }

    /**
     * Changes user password
     * @param  int    $id              User id
     * @param  string $newPassword New password hash
     * @return array  Updated user
     */
    public function changePassword($id, $newPassword)
    {
        $q = 'UPDATE users SET password = :password WHERE id = :id';
        $s = $this->db->prepare($q);
        $s->execute(array('password' => $newPassword, 'id' => $id));

        return $this->find($id);
    }

    /**
     * Returns all users in the database
     * @return array Users
     */
    public function findAll()
    {
        $result = $this->db->query('SELECT * FROM users')->fetchAll();

        $users = array();

        foreach ($result as $row) {
            $users[] = new Model_User($row);
        }

        return $users;
    }

    /**
     * Updates login timestamp
     * @param  User $user User
     * @return bool True on success, false on failure
     */
    public function recordLogin(Model_User $user)
    {
        $q = "UPDATE Users SET lastLogin = CURDATE(), token = :token WHERE email = :email";
        $s = $this->db->prepare($q);

        return $s->execute(array('email' => $user->getEmail(), 'token' => $user->getToken()));
    }

    /**
     * Creates a new user
     * @param  string $email        Email address
     * @param  string $passwordHash Hashed password
     * @return array  User data
     */
    public function createUser($email, $passwordHash)
    {
        if (!$passwordHash) {
            throw new InvalidArgumentException('Password hash must not be null');
        }

        $q = 'INSERT INTO users (email, emailCanonical, role, passwordHash) VALUES (:email, :emailCanonical, :role, :passwordHash)';
        $s = $this->db->prepare($q);
        $s->execute(
            array(
                'email' => $email,
                'emailCanonical' => strtolower($email),
                'password' => $password,
                'role' => 'admin'
            )
        );

        return $this->findByEmail($email);
    }

    /**
     * Login user
     * @param User $user            User Model
     * @param string $token         Private token form user
     */
    public function loginUser(Model_User $user, $token)
    {
        $user->setToken($token);
        $this->recordLogin($user);
        session_regenerate_id();
        $_SESSION['UserId'] = $user->getId();
        $_SESSION['Role'] = $user->getRole();
        $_SESSION['UserMail'] = $user->getEmail();
        $nom = $user->getNom();
        $prenom = $user->getPrenom();
        $_SESSION['UserNom'] = strtoupper($prenom[0]).'. '.$nom;
    }

    /**
     * Logout user
     * @param  User $user User
     * @return bool True on success, false on failure
     */
    public function logoutUser(Model_User $user)
    {
        if(isset($_SESSION['UserId']) && $_SESSION['UserId'] == $user->getId()) {
            session_unset();
            session_destroy();
            return true;
        }
        else {
            return false;
        }
    }
}
