<?php

class Blog extends Mapper {

    public function getBlogs() {
        $sql = "SELECT id, title, picture_url, date_format(date, '%M %d, %Y %r') as date, content, author as username
                FROM blog
                ORDER BY date DESC";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new BlogEntity($row);
        }
        return $results;
    }

    public function getBlogById($blog_id) {
        $sql = "SELECT id, title, picture_url, date_format(date, '%M %d, %Y %r') as date, content, author as username
                  FROM blog
                  WHERE blog.id = :blog_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["blog_id" => $blog_id]);
        if($result) {
            return new BlogEntity($stmt->fetch());
        }
    }

    public function getMostRecentBlog() {
        $sql = "SELECT id, title, picture_url, date_format(date, '%M %d, %Y %r') as date, content, author as username
                  FROM blog
                  ORDER BY date DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();
        if($result) {
          if ($stmt->fetch() != false) {
            return new BlogEntity($stmt->fetch());
          }

        }
    }

    public function save(BlogEntity $blog) {
        $sql = "insert into blog
            (title, picture_url, date, content, author) values
            (:title, :picture_url, :date, :content, :author)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "title" => $blog->getTitle(),
            "picture_url" => $blog->getPictureUrl(),
            "date" => $blog->getDate(),
            "content" => $blog->getContent(),
            "author" => $blog->getUsername(),

        ]);
        if(!$result) {
            throw new Exception("could not save record");
        }
    }

    public function update(BlogEntity $blog) {
        $sql = "UPDATE blog
              SET title=:title,
               picture_url = :picture_url,
               content = :content,
               author = :author
               WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "title" => $blog->getTitle(),
            "picture_url" => $blog->getPictureUrl(),
            "content" => $blog->getContent(),
            "author" => $blog->getUsername(),
            "id" => $blog->getId()
        ]);
        if(!$result) {
            throw new Exception("could not update record");
        }
    }

}
