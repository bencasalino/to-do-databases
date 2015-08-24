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


    // possibly used for override GET and POST ?
    use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();



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
        return $app['twig']->render('index.html.twig', array('categories'=>Category::getAll(),'tasks' => Task::getAll()));

  });




  // tasks
    $app->get("/tasks", function() use($app) {

        return $app['twig']->render('tasks.html.twig', array('tasks'=> Task::getAll()));
    });




    // cats
    $app->get("/categories", function() use($app) {
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));

    });




    // path to edit ind category
    $app->get("/categories/{id}", function($id) use($app) {
        $category = Category::find($id);
        return $app['twig']->render('category_edit.html.twig', array('category'=>$category, 'tasks'=> $category->getTasks()));
    });



    // patch update for CATEGORY
    $app->patch("/categories/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $category = Category::find($id);
        $category->update($name);
        return $app['twig']->render('categories.html.twig', array('category' => $category, 'tasks' => $category->getTasks()));
    });




    // post tasks
    $app->post("/tasks", function() use ($app){
        $description = $_POST['description'];
        $id = null;
        $task = new Task ($id, $description);
        $task->save();

        return $app['twig']->render('tasks.html.twig', array('tasks' =>             Task::getAll()));


        // post add tasks

        $app->post("/add_tasks", function() use ($app) {
            $category = Category::find($_POST['category_id']);
            $task = Task::find($_POST['task_id']);
            $category->addTask($task);
            return $app['twig']->render('category.html.twig', array('category' => $category, 'categories' => Category::getAll(), 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
        });

        // post add category

        $app->post("/add_categories", function() use ($app) {
            $category = Category::find($_POST['category_id']);
            $task = Task::find($_POST['task_id']);
            $task->addCategory($category);
            return $app['twig']->render('task.html.twig', array(
                'task' => $task,
                'tasks' => Task::getAll(),
                'categories' => $task->getCategories(),
                'all_categories' => Category::getAll()
            ));
        });

    // del tasks
    $app->post("/delete_tasks", function() use ($app){
        Task::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    // post cats
    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });


    // post del cats
    $app->post("/delete_categories", function() use($app) {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig', array('categories'=>Category::getAll()));
    });



    // get cat ID
    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });



    // GET CAT ID EDIT
    $app->get("/categories/{id}/edit", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category_edit.html.twig', array('category' => $category));
    });


    // del cat ID
    $app->delete("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        $category->delete();
        return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    });

    // get tasks id
    $app->get("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task.html.twig', array('task' => $task, 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });



    return $app;

?>
