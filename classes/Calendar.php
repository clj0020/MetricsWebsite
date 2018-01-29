<?php

class Calendar extends Mapper {

    public function getEvents($start, $end) {
        $sql = "SELECT * FROM events
                WHERE start >= :start AND end < :end
                ORDER BY start ASC";
        $stmt = $this->db->prepare($sql);
        // $stmt->bindParam(':start', $start, \PDO::PARAM_INT);
        // $stmt->bindParam(':end', $end, \PDO::PARAM_INT);
        $stmt->execute(["start" => $start, "end" => $end]);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new Event($row);
        }
        return $results;
    }

    public function getEvent($start, $end) {
        $sql = "SELECT * FROM events
                  WHERE start >= :start AND end < :end
                  ORDER BY start ASC";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["start" => $start, "end" => $end]);
        if($result) {
            return new Event($stmt->fetch());
        }
    }

    public function getEventById($id) {
        $sql = "SELECT * FROM events
                  WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);
        if($result) {
            return new Event($stmt->fetch());
        }
    }

    public function save(Event $event) {
        $sql = "INSERT INTO events
                (start, end, title, description) values
                (:start, :end, :title, :description)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "start" => $event->getStart(),
            "end" => $event->getEnd(),
            "title" => $event->getTitle(),
            "description" => $event->getDescription()
        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }
    }

    public function update(Event $event) {
        $sql = "UPDATE events
                SET start = :start, end = :end, title = :title, description = :description
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "start" => $event->getStart(),
            "end" => $event->getEnd(),
            "title" => $event->getTitle(),
            "description" => $event->getDescription(),
            "id" => $event->getId()
        ]);
        if(!$result) {
            throw new Exception("could not update record");
        }
    }

}
