<?php


class Database
{
    private $database;
    private $username;
    private $password;
    private $host;

    private $db;

    public function __construct($database, $username, $password, $host = 'localhost')
    {
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->host     = $host;

        $this->dbConnect();
    }

    private function dbConnect()
    {
        // Check $this->host and $this->database are alphanumeric (with underscores and dashes allowed)
      try {//
        $this->db = new PDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->username, $this->password);
          $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
          $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      } catch (PDOException $e) { // Debugging!
        echo $e->getMessage()."<br>\n";
          die('ERROR');
      }
    }

    public function getUserDataByUsername($user)
    {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) === 1) {
            return $rows[0];
        } else {
            return false;
        }
    }

    public function getUserDataById($userid)
    {
        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userid]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) === 1) {
            return $rows[0];
        } else {
            return false;
        }
    }

    public function getUserDataByEmail($userid)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userid]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) === 1) {
            return $rows[0];
        } else {
            return false;
        }
    }
}
