<?php
class Dao_Mail
{
    private $container;
    private $from_name;
    private $from_email;
    private $subject;
    private $message;
    public $errors = array();

    function __construct(Utils_Container $c, $data)
    {
        $this->container = $c;
        $this->from_email = trim($data->email);
        $this->from_name = trim($data->name);
        $this->subject = trim($data->subject);
        $this->message = $data->message;
    }

    public function checkData()
    {
        if (!$this->isValidEmail($this->from_email)) {
            $this->errors['email'] = 'Email non valide';
        }
        if(strlen($this->message) < 5) {
            $this->errors['message'] = 'Message non valide';
        }

        return $this->errors;
    }

    private function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function Send()
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=\"UTF-8\";\r\n";
        $headers .= "From: ". $this->from_name . " <" . $this->from_email . ">\r\n";
        $headers .= "Reply-To: ".$this->from_email."\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();"\r\n";

        return mail($this->container['email'],$this->subject,$this->message,$headers);
    }
}
