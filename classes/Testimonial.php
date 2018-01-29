<?php
class Testimonial {
  protected $id;
  protected $author_name;
  protected $content;

  /**
  * Accept an array of data matching properties of this class
  * and create the class
  *
  * @param array $data The data to use to create
  */
  public function __construct(array $data) {
    // no id if we're creating
    if(isset($data['id'])) {
      $this->id = $data['id'];
    }
    $this->author_name = $data['author_name'];
    $this->content = $data['content'];
  }

  public function getId() {
    return $this->id;
  }
  public function getAuthorName() {
    return $this->author_name;
  }
  public function setAuthorName($author_name) {
    $this->author_name = $author_name;
  }
  public function getContent() {
    return $this->content;
  }
  public function setContent($content) {
    $this->content = $content;
  }

}
