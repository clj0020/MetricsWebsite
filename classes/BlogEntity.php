<?php
class BlogEntity {
    protected $id;
    protected $title;
    protected $username;
    protected $picture_url;
    protected $date;
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
        $this->title = $data['title'];
        $this->username = $data['username'];
        $this->picture_url = $data['picture_url'];
        $this->date = $data['date'];
        $this->content = $data['content'];
    }
    public function getId() {
        return $this->id;
    }
    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function getUsername() {
        return $this->username;
    }
    public function setUsername($username) {
        $this->username = $username;
    }
    public function getPictureUrl() {
        return $this->picture_url;
    }
    public function setPictureUrl($picture_url) {
        $this->picture_url = $picture_url;
    }
    public function getDate() {
        return $this->date;
    }
    public function getContent() {
        return $this->content;
    }
    public function setContent($content) {
        $this->content = $content;
    }
    public function getShortContent() {
        return $this->content."...";
    }
}
