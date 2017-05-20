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

    public function getAllUsers() {
      $sql = "SELECT * FROM users";
      $stmt = $this->db->prepare($sql);
      $stmt->execute();
      return $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGroups() {
      $sql = "SELECT * FROM groups";
      $stmt = $this->db->prepare($sql);
      $stmt->execute();
      return $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function registerNewUser($username, $password, $email, $birthTimestamp, $role = 'newbie') {
        $sql = "INSERT INTO users (username, pw, email, role, birthTimestamp) VALUES(?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$username, $password, $email, $role, $birthTimestamp])) {
          return $this->db->lastInsertId();
        } else {
          return false;
        }
    }

    public function sendPrivateMessage($from_userid, $to_userid, $subject, $text, $timestamp, $isRead = false, $replyTo = NULL) {
      $sql = "INSERT INTO messages (from_userid, to_userid, subject, text, timestamp, isRead, replyTo) VALUES(?, ?, ?, ?, ?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      if ($stmt->execute([$from_userid, $to_userid, $subject, $text, $timestamp, $isRead, $replyTo])) {
        return $this->db->lastInsertId();
      } else {
        return false;
      }
    }

    public function searchUsers($pattern) {
      $sql = "SELECT username FROM users WHERE username LIKE ?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$pattern]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $ans = [];
      foreach($rows as $user) {
        $ans[] = $user['username'];
      }
      return $ans;
    }

    public function getMusicGenres() {
      $sql = "SELECT * FROM musicgenres";
      $stmt = $this->db->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $ans = [];
      foreach($rows as $user) {
        $ans[] = $user['musicgenre'];
      }
      return $ans;
    }

    public function getUserMusicGenres($uid) {
      $sql = "SELECT * FROM user_genre_styles WHERE user=?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$uid]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $ans = [];
      foreach($rows as $user) {
        $ans[] = $user['genre'];
      }
      return $ans;
    }

    public function setUserMusicGenres($uid, $musicGenres) {
      try {
        $this->db->beginTransaction();
        $genre = '';
        $stmt = $this->db->prepare("INSERT INTO user_genre_styles(user, genre) VALUES(:user, :genre)");
        $stmt->bindValue(':user', $uid, PDO::PARAM_INT);
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        foreach($musicGenres as $genre) {
          $stmt->execute();
        }
        $this->db->commit();
      } catch (PDOException $e) {
        $this->db->rollback();
        echo $e->getMessage();
      }
    }

    public function editUserData($uid, $email, $role, $birthTimestamp) {
      $sql = "UPDATE users SET email=?,role=?,birthTimestamp=? WHERE id=?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$email, $role, $birthTimestamp, $uid]);
    }

}

$db = new Database('jukenet', 'jukenet', 'jukenet', 'localhost');
