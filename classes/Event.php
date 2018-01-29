<?php
class Event implements JsonSerializable {
  protected $id;
  protected $title;
  protected $description;
  protected $location;
  protected $contact;
  protected $url;
  protected $start;
  protected $end;
  protected $allDay;


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
    $this->description = $data['description'];
    $this->location = $data['location'];
    $this->contact = $data['contact'];
    $this->url = $data['url'];
    $this->start = $data['start'];
    $this->end = $data['end'];
    $this->allDay = $data['allDay'];
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
  public function getDescription() {
    return $this->description;
  }
  public function setDescription($description) {
    $this->description = $description;
  }
  public function getLocation() {
    return $this->location;
  }
  public function getContact() {
    return $this->contact;
  }
  public function getUrl() {
    return $this->url;
  }

  public function getStart() {
    return $this->start;
  }
  public function setStart($start) {
    $this->start = date('Y-m-d H:i:s', strtotime($start));
  }
  public function getEnd() {
    return $this->end;
  }
  public function setEnd($end) {
    $this->end = date('Y-m-d H:i:s', strtotime($end));
  }
  public function getAllDay() {
    return $this->allDay;
  }


  function jsonSerialize() {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->description,
      'location' => $this->location,
      'contact' => $this->contact,
      'url' => $this->url,
      'start' => $this->start,
      'end' => $this->end,
      'allDay' => $this->allDay,

    ];
  }

}
