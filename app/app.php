<?php

    // path that link app.php to autoload.php
    require_once __DIR__."/../vendor/autoload.php";
    // path that link app.php to /src/Task.php
    require_once __DIR__."/../src/Task.php";
    // path that link app.php to /src/Category.php
    require_once __DIR__."/../src/Category.php";

    //used to give more information on debugging code
    use Symfony\Component\Debug\Debug;
    Debug::enable();

    //intialize Silex
    $app = new Silex\Application();

    //looks for silex bugs possibly
    $app['debug'] = true;


    //gives app access to database server
    $server = 'mysql:host=localhost;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    //tells app.php to use Silex/Twig and display results in views folder

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        //why does it need to be an array?
        'twig.path' => __DIR__.'/../views'
    ));


    // link to homepage
    $app->get("/", function() use ($app){

        // shows twig which view to display
        return $app['twig']->render('index.html.twig', array('categories'=>Category::getAll()));
  });

    $app->get("/tasks", function() use($app) {

        return $app['twig']->render('tasks.html.twig', array('tasks'=> Task::getAll()));
    });

    $app->get("/categories/{id}", function($id) use($app) {
        $category = Category::find($id);
        return $app['twig']->render('categories.html.twig', array('category'=>$category, 'tasks'=> $category->getTasks()));
    });

    $app->post("/tasks", function() use ($app){
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $task = new Task($description, $id = null, $category_id);
        $task->save();
        $category = Category::find($category_id);

        return $app['twig']->render('categories.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));

    });

    $app->post("/delete_tasks", function() use ($app){
        Task::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    });

    $app->post("/delete_categories", function() use($app) {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig', array('categories'=>Category::getAll()));
    });

return $app;

?>
