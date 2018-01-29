<?php

class UserMapper extends Mapper {
    public function getUsers() {
        $sql = "SELECT *
                FROM users";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new User($row);
        }
        return $results;
    }

    public function save(User $user) {
        $sql = "insert into users
            (email, name) values
            (:email, :name)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "email" => $user->getEmail(),
            "name" => $user->getName()
        ]);
    }
}
