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

    public function deleteUser($uid) {
      $sql = "DELETE FROM users WHERE id=?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$uid]);
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

    public function getUserGroupMessages($userid) {
      $sql = "SELECT group_messages.id, users.username 'from_username', users.id 'from_id', group_messages.to_group, group_messages.text
        FROM group_messages
        JOIN user_group_replation ON user_group_replation.groupname = group_messages.to_group
        JOIN users ON users.id = group_messages.from_user
        WHERE user_group_replation.user = ?
        ";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$userid]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    }

    public function getBroadcasts() {
      $sql = "SELECT broadcasts.id, users.username 'from_username', users.id 'from_id', broadcasts.text, broadcasts.timestamp
        FROM broadcasts
        JOIN users ON users.id = broadcasts.from_user
        ";
      $stmt = $this->db->prepare($sql);
      $stmt->execute();
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

    public function sendGroupMessage($from_userid, $to_group, $text) {
      $sql = "INSERT INTO group_messages (from_user, to_group, text) VALUES(?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      if ($stmt->execute([$from_userid, $to_group, $text])) {
        return $this->db->lastInsertId();
      } else {
        return false;
      }
    }

    public function sendBroadcast($from_user, $text, $timestamp) {
      $sql = "INSERT INTO broadcasts (from_user, text, timestamp) VALUES(?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      if ($stmt->execute([$from_user, $text, $timestamp])) {
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

    public function clearUserMusicGenres($uid) {
      $stmt = $this->db->prepare("DELETE FROM user_genre_styles WHERE user=?");
      return $stmt->execute([$uid]);
    }

    public function setUserMusicGenres($uid, $musicGenres) {
      if (!$this->clearUserMusicGenres($uid)) return false;
      $isOk = false;
      try {
        $this->db->beginTransaction();
        $genre = '';
        $stmt = $this->db->prepare("INSERT IGNORE INTO user_genre_styles(user, genre) VALUES(:user, :genre)");
        $stmt->bindValue(':user', $uid, PDO::PARAM_INT);
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        foreach($musicGenres as $genre) {
          $stmt->execute();
        }
        $this->db->commit();
        $isOk = true;
      } catch (PDOException $e) {
        $this->db->rollback();
        //echo $e->getMessage();
      }
      return $isOk;
    }

    public function editUserData($uid, $email, $role, $birthTimestamp) {
      $sql = "UPDATE users SET email=?,role=?,birthTimestamp=? WHERE id=?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$email, $role, $birthTimestamp, $uid]);
    }

    public function getGroupData($gname) {
      $sql = "SELECT * FROM groups WHERE name=?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$gname]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (count($rows) === 1) return $rows[0];
      return false;
    }

    public function addGroup($name, $musicGenre, $minAge, $maxAge) {
      $sql = "INSERT INTO groups (name, musicgenre, minage, maxage) VALUES(?, ?, ?, ?)";
      $stmt = $this->db->prepare($sql);
      if ($stmt->execute([$name, $musicGenre, $minAge, $maxAge])) {
        return true;
      } else {
        return false;
      }
    }

    public function addUsersToGroup($gname) {
      $sql = "INSERT IGNORE INTO user_group_replation(user, groupname)
        SELECT DISTINCT users.id, :groupname 'groupname'
        FROM users
        JOIN user_genre_styles ON users.id = user_genre_styles.user
        WHERE
          user_genre_styles.genre = (SELECT DISTINCT musicgenre FROM groups WHERE name = :groupname2) AND
          ((UNIX_TIMESTAMP() - users.birthTimestamp)/60/60/24/365) BETWEEN
            (SELECT DISTINCT minAge FROM groups WHERE groups.name = :groupname3) AND
            (SELECT DISTINCT maxAge FROM groups WHERE groups.name = :groupname4)
        ";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':groupname',  $gname, PDO::PARAM_STR);
      $stmt->bindValue(':groupname2', $gname, PDO::PARAM_STR);
      $stmt->bindValue(':groupname3', $gname, PDO::PARAM_STR);
      $stmt->bindValue(':groupname4', $gname, PDO::PARAM_STR);
      return $stmt->execute();
    }

    public function deleteGroup($gname) {
      $sql = "DELETE FROM groups WHERE name=?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$gname]);
    }

    public function getUserGroups($uid) {
      $sql = "SELECT * FROM user_group_replation WHERE user=?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$uid]);
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $ans = [];
      foreach($rows as $row) {
        $ans[] = $row['groupname'];
      }
      return $ans;
    }

}

$db = new Database('jukenet', 'jukenet', 'jukenet', 'localhost');
