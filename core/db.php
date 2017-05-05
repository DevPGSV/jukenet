<?php


class Database {
    private $database;
    private $username;
    private $password;
    private $host;

    private $db;

    public function __construct($database, $username, $password, $host = 'localhost') {
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->host     = $host;

        $this->dbConnect();
    }

    private function dbConnect() {
      // Check $this->host and $this->database are alphanumeric (with underscores and dashes allowed)
      try {
        $this->db = new PDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      } catch (PDOException $e) { // Debugging!
        echo $e->getMessage()."<br>\n";
          die('ERROR');
      }
    }

    public function getUserDataByUsername($user) {
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

    public function getUserDataById($userid) {
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

    public function getUserDataByEmail($email) {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) === 1) {
            return $rows[0];
        } else {
            return false;
        }
    }

    public function getUserMessages($userid) {
        $sql = "SELECT
            messages.id, messages.subject, messages.text, messages.timestamp, messages.isRead,
            from_user.id as `from_user.id`, from_user.username as `from_user.username`, from_user.email as `from_user.email`, from_user.role as `from_user.role`,
            to_user.id as `to_user.id`, to_user.username as `to_user.username`, to_user.email as `to_user.email`, to_user.role as `to_user.role`
          FROM messages
          JOIN users from_user ON from_user.id = messages.from_userid
          JOIN users to_user ON to_user.id = messages.to_userid
          WHERE
            messages.to_userid=? OR
            messages.from_userid=?
          ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userid,$userid]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function getMessageById($messageid) {
      $sql = "SELECT
          messages.id, messages.subject, messages.text, messages.timestamp, messages.isRead,
          from_user.id as `from_user.id`, from_user.username as `from_user.username`, from_user.email as `from_user.email`, from_user.role as `from_user.role`,
          to_user.id as `to_user.id`, to_user.username as `to_user.username`, to_user.email as `to_user.email`, to_user.role as `to_user.role`
        FROM messages
        JOIN users from_user ON from_user.id = messages.from_userid
        JOIN users to_user ON to_user.id = messages.to_userid
        WHERE messages.id=?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$messageid]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (count($rows) === 1) return $rows[0];
      return false;
    }

    public function markMessageReadById($messageid, $markRead = true) {
      $sql = "UPDATE messages SET isRead = ? WHERE id=?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$markRead, $messageid]);
    }

    public function registerNewUser($username, $password, $email, $role = 'newbie') {
        $sql = "INSERT INTO users (username, pw, email, role) VALUES(?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$username, $password, $email, $role])) {
          return $this->db->lastInsertId();
        } else {
          return false;
        }
    }

}

$db = new Database('jukenet', 'jukenet', 'jukenet', 'localhost');
