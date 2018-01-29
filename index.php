<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

date_default_timezone_set('America/Chicago');

require 'vendor/autoload.php';

spl_autoload_register(function ($classname) {
  require ('classes/' . $classname . '.php');
});

$config['displayErrorDetails'] = true;
$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "root";
$config['db']['dbname'] = "MetricsFitnessLab";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['view'] = new \Slim\Views\PhpRenderer("templates/");

$container['logger'] = function($c) {
  $logger = new \Monolog\Logger('my_logger');
  $file_handler = new \Monolog\Handler\StreamHandler("logs/app.log");
  $logger->pushHandler($file_handler);
  return $logger;
};

$container['db'] = function ($c) {
  $db = $c['settings']['db'];
  $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
  $db['user'], $db['pass']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  return $pdo;
};

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
  "path" => "/admin",
  "secure" => true,
  "relaxed" => ["localhost", "dev.example.com"],
  "users" => [
    "chappy" => "MetricsWorkout101"
  ]
]));


$app->get('/', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "homepage.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('home');

$app->post('/', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $user_data = [];
  $user_data['email'] = $data['email'];
  $user_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);

  $user = new User($user_data);
  $user_mapper = new UserMapper($this->db);
  $user_mapper->save($user);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('home'));

  return $response;
});



$app->get('/about', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "about-us.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('about');

$app->get('/blog', function (Request $request, Response $response) {
  $this->logger->addInfo("Blog List");
  $mapper = new Blog($this->db);
  $blogs = $mapper->getBlogs();
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "blog.phtml", ["blogs" => $blogs, "mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('blog');

$app->get('/blog/{id}', function (Request $request, Response $response, $args) {
  $blog_id = (int)$args['id'];
  $mapper = new Blog($this->db);
  $blog = $mapper->getBlogById($blog_id);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "blog-detail.phtml", ["blog" => $blog, "mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('blog-detail');

$app->get('/signup', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "sign-up.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('signup');

$app->get('/calendar', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "calendar.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('calendar');

$app->get('/crew', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "crew.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('crew');

$app->get('/testimonials', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "testimonials.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('testimonials');

$app->get('/faq', function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $mostRecentBlog = $mapper->getMostRecentBlog();

  $response = $this->view->render($response, "faq.phtml", ["mostRecentBlog" => $mostRecentBlog, "router" => $this->router]);
  return $response;
})->setName('faq');

$app->get('/api', function (Request $request, Response $response, $args) {
  $startTimestamp = $request->getQueryParam('start');
  $endTimestamp = $request->getQueryParam('end');
  $mapper = new Calendar($this->db);
  $events = $mapper->getEvents($startTimestamp, $endTimestamp);

  return $response->withJson($events);

})->setName('api');

$app->get("/admin", function (Request $request, Response $response) {
  $mapper = new Blog($this->db);
  $blogs = $mapper->getBlogs();

  $testimonialMapper = new TestimonialMapper($this->db);
  $testimonials = $testimonialMapper->getTestimonials();

  $userMapper = new UserMapper($this->db);
  $users = $userMapper->getUsers();

  $response = $this->view->render($response, "admin.phtml", ["blogs" => $blogs, "testimonials" => $testimonials, "users" => $users, "router" => $this->router]);
  return $response;
})->setName('admin');

$app->get('/admin/blog/new', function (Request $request, Response $response) {
  $response = $this->view->render($response, "newblog.phtml", ["router" => $this->router]);
  return $response;
})->setName('new-blog');

$app->post('/admin/blog/new', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $blog_data = [];
  $blog_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
  $blog_data['content'] = filter_var($data['content'], FILTER_SANITIZE_STRING);
  $blog_data['username'] = filter_var($data['author'], FILTER_SANITIZE_STRING);
  $blog_data['date'] = date("Y-m-d H:i:s");

  $imgFile = $_FILES['blog_image']['name'];
  $tmp_dir = $_FILES['blog_image']['tmp_name'];
  $imgSize = $_FILES['blog_image']['size'];
  $imgType = $_FILES['blog_image']['type'];
  $imgErr = $_FILES['blog_image']['error'];

  $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

  $blogPic = rand(1000,1000000).".".$imgExt;

  // Check for errors
  if($imgErr > 0){
    die('An error ocurred when uploading.');
  }

  if(!getimagesize($tmp_dir)){
    die('Please ensure you are uploading an image.');
  }

  // Check filetype
  if($imgType != 'image/png' && $imgType != 'image/jpg' && $imgType != 'image/jpeg'){
    die('Unsupported filetype uploaded.');
  }

  // Check filesize
  if($imgSize > 500000){
    die('File uploaded exceeds maximum upload size.');
  }

  // Check if the file exists
  if(file_exists(__DIR__.'/img/blog/' . $blogPic)){
    die('File with that name already exists.');
  }

  // Upload file
  if(!move_uploaded_file($_FILES['blog_image']['tmp_name'], __DIR__.'/img/blog/' . $blogPic)){
    die('Error uploading file - check destination is writeable.');
  }

  $blog_data['picture_url'] = 'img/blog/'.$blogPic;

  $blog = new BlogEntity($blog_data);
  $blog_mapper = new Blog($this->db);
  $blog_mapper->save($blog);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('admin'));

  return $response;
});

$app->get("/admin/edit-blog/{id}", function (Request $request, Response $response, $args) {
  $blog_id = (int)$args['id'];
  $mapper = new Blog($this->db);
  $blog = $mapper->getBlogById($blog_id);
  $response = $this->view->render($response, "edit-blog-post.phtml", ["blog" => $blog, "router" => $this->router]);
  return $response;
})->setName('edit-blog-post');

$app->post("/admin/edit-blog/{id}", function (Request $request, Response $response, $args) {
  $blog_id = (int)$args['id'];
  $mapper = new Blog($this->db);
  $blog = $mapper->getBlogById($blog_id);

  $data = $request->getParsedBody();

  $blog->setTitle(filter_var($data['title'], FILTER_SANITIZE_STRING));
  $blog->setContent(filter_var($data['content'], FILTER_SANITIZE_STRING));
  $blog->setUsername(filter_var($data['author'], FILTER_SANITIZE_STRING));

  $imgFile = $_FILES["blog_image"]["name"];
  $tmp_dir = $_FILES['blog_image']['tmp_name'];
  $imgSize = $_FILES['blog_image']['size'];
  $imgType = $_FILES['blog_image']['type'];
  $imgErr = $_FILES['blog_image']['error'];


  if ($imgFile) {
    $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));

    $blogPic = rand(1000,1000000).".".$imgExt;

    // Check for errors
    if($imgErr > 0){
      die('An error ocurred when uploading.');
    }

    if($imgSize == 0){
      die('Please ensure you are uploading an image.');
    }

    // Check filetype
    if($imgType != 'image/png' && $imgType != 'image/jpg' && $imgType != 'image/jpeg'){
      die('Unsupported filetype uploaded. PNG, JPG, or JPEG are the only types supported.');
    }

    // Check filesize
    if($imgSize > 1000000){
      die('File uploaded exceeds maximum upload size. Make sure the image is under 1MB');
    }

    // Check if the file exists
    if(file_exists(__DIR__.'/img/blog/' . $blogPic)){
      die('File with that name already exists.');
    }

    // Upload file
    if(!move_uploaded_file($_FILES['blog_image']['tmp_name'], __DIR__.'/img/blog/' . $blogPic)){
      die('Error uploading file - check destination is writeable.');
    }

    $blog->setPictureUrl('img/blog/'.$blogPic);
  }
  $blog_mapper = new Blog($this->db);
  $blog_mapper->update($blog);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('admin'));

  return $response;
});

$app->get("/admin/calendar/new-event", function (Request $request, Response $response, $args) {
  $response = $this->view->render($response, "new-event.phtml", ["router" => $this->router]);
  return $response;
})->setName('new-event');

$app->post('/admin/calendar/new-event', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $event_data = [];
  $event_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
  $event_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
  $event_data['start'] = date('Y-m-d H:i:s', strtotime($data['start']));
  $event_data['end'] = date('Y-m-d H:i:s', strtotime($data['end']));


  $event = new Event($event_data);
  $event_mapper = new Calendar($this->db);
  $event_mapper->save($event);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('admin'));

  return $response;
});

$app->get("/admin/calendar/event/{id}", function (Request $request, Response $response, $args) {
  $event_id = (int)$args['id'];
  $mapper = new Calendar($this->db);
  $event = $mapper->getEventById($event_id);
  $response = $this->view->render($response, "edit-event.phtml", ["event" => $event, "router" => $this->router]);
  return $response;
})->setName('edit-event');

$app->post("/admin/calendar/event/{id}", function (Request $request, Response $response, $args) {
  $event_id = (int)$args['id'];
  $data = $request->getParsedBody();
  $mapper = new Calendar($this->db);
  $event = $mapper->getEventById($event_id);

  $event->setTitle($data['title']);
  $event->setDescription($data['description']);
  $event->setStart($data['start']);
  $event->setEnd($data['end']);

  $mapper->update($event);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('admin'));

  return $response;
});

$app->get('/admin/testimonial/new', function (Request $request, Response $response) {
  $response = $this->view->render($response, "new-testimonial.phtml", ["router" => $this->router]);
  return $response;
})->setName('new-testimonial');

$app->post('/admin/testimonial/new', function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $testimonial_data = [];
  $testimonial_data['author_name'] = filter_var($data['author_name'], FILTER_SANITIZE_STRING);
  $testimonial_data['content'] = filter_var($data['content'], FILTER_SANITIZE_STRING);

  $testimonial = new Testimonial($testimonial_data);
  $testimonial_mapper = new TestimonialMapper($this->db);
  $testimonial_mapper->save($testimonial);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('admin'));

  return $response;
});

$app->get("/admin/testimonials/{id}", function (Request $request, Response $response, $args) {
  $testimonial_id = (int)$args['id'];
  $mapper = new TestimonialMapper($this->db);
  $testimonial = $mapper->getTestimonialById($testimonial_id);
  $response = $this->view->render($response, "edit-testimonial.phtml", ["testimonial" => $testimonial, "router" => $this->router]);
  return $response;
})->setName('edit-testimonial');

$app->post("/admin/testimonials/{id}", function (Request $request, Response $response, $args) {
  $testimonial_id = (int)$args['id'];
  $mapper = new TestimonialMapper($this->db);
  $testimonial = $mapper->getTestimonialById($testimonial_id);

  $data = $request->getParsedBody();

  $testimonial->setAuthorName(filter_var($data['author_name'], FILTER_SANITIZE_STRING));
  $testimonial->setContent(filter_var($data['content'], FILTER_SANITIZE_STRING));

  $mapper->update($testimonial);

  $router = $this->router;
  $response = $response->withRedirect($router->pathFor('admin'));

  return $response;
});



$app->run();

?>
