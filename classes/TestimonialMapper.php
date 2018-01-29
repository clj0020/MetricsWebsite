<?php

class TestimonialMapper extends Mapper {

    public function getTestimonials() {
        $sql = "SELECT id, author_name, content
                FROM testimonials";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new Testimonial($row);
        }
        return $results;
    }

    public function getTestimonialById($id) {
        $sql = "SELECT id, author_name, content
                  FROM testimonials
                  WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);
        if($result) {
            return new Testimonial($stmt->fetch());
        }
    }

    public function save(Testimonial $testimonial) {
        $sql = "insert into testimonials
            (author_name, content) values
            (:author_name, :content)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "author_name" => $testimonial->getAuthorName(),
            "content" => $testimonial->getContent(),
        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }
    }

    public function update(Testimonial $testimonial) {
        $sql = "UPDATE testimonials
              SET author_name=:author_name, content = :content
               WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "author_name" => $testimonial->getAuthorName(),
            "content" => $testimonial->getContent(),
            "id" => $testimonial->getId()
        ]);
        if(!$result) {
            throw new Exception("could not update record");
        }
    }

}
