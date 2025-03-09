<?php
session_start();
class UserReg
{
    public $id;
    public $login;
    public $pass;
    public $username;
    public $admin;
    public $birthday;
    public $email;
    public $con;

    public function createConnection(){
        $this->con = mysqli_connect('mysql_db','root','toor','users')
        or die("Fieled to connect: ".mysqli_error($this->con));
    }

    public function __construct($userId, $conn){
        $this->con = $conn;
        $sql = "SELECT * FROM usr WHERE id ='{$userId}'";
        $row = mysqli_fetch_array(mysqli_query($conn, $sql));
        $this->id = $row['id'];
        $this->login = $row['login'];
        $this->pass = $row['password'];
        if (!isset($row['lastname'])) $row['lastname'] = '';
        $this->username = $row['firstname']." ".$row['lastname'];
        $this->admin = $row['admin'];
        $this->birthday = $row['birthday'];
        $this->email = $row['email'];
    }
    static public function login($login, $pass, $conn)
    {
        $search_term = mysqli_real_escape_string($conn, $login);
        $sql = "SELECT * FROM usr WHERE login='{$login}' OR email='{$login}' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        if (isset($row)) {
            if (md5($pass) == $row['password']) {
//                $us = new UserReg($row['id'], $conn);
                $_SESSION['login'] = $login;
                $_SESSION['pass'] = md5($pass);
                return true;
            }
        }
        else { return true; }
    }

    static public function loginById($id, $conn)
    {
        $sql = "SELECT * FROM usr WHERE id ='{$id}'";
        $row = mysqli_fetch_array(mysqli_query($conn, $sql));
        if (isset($row)) {
            $user = new UserReg($id, $conn);
            return $user;
        }
        else { return null; }
    }
    static public function loginSession($login, $hashPass, $conn)
    {
        $sql = "SELECT * FROM usr WHERE login='{$login}' OR email='{$login}' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        if (isset($row)) {
            if ($hashPass == $row['password']) {
                return new UserReg($row['id'], $conn);
            }
        }
        return false;
    }
    static public function register($login, $password, $firstname, $lastname, $email, $birthday, $conn)
    {
        $pass = md5($password);
        $admin = 0;
        $sql = "INSERT INTO usr (login, password, firstname, lastname, email, birthday, admin) VALUES ('{$login}', '{$pass}', '{$firstname}', '{$lastname}', '{$email}', '{$birthday}', '{$admin}')";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        $_SESSION['login'] = $login;
        $_SESSION['pass'] = $pass;
    }
    static public function loginRepeat($login, $conn){
        $sql = "SELECT * FROM usr WHERE login='{$login}' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        return isset($row);
    }
    static public function emailRepeat($email, $conn){
        $sql = "SELECT * FROM usr WHERE email='{$email}' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        return isset($row);
    }
}
