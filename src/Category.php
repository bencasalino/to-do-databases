<?php


    class Category
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            // "this"
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }



        function getTasks()
        {
        $tasks = array();
          //goes to database and searches for the input id
          $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE category_id = {$this->getId()};");
          //loops through search results and pulls out correct property values
          foreach($returned_tasks as $task) {
              $id = $task['id'];
              $description = $task['description'];
              $date_due = $task['date_due'];
              $time_due = $task['time_due'];
              $category_id = $task['category_id'];
              //creates a new Task from the found property values
              $new_task = new Task($id, $description, $date_due, $time_due, $category_id);
              //this returns multiple matches and returns all matches
              array_push($tasks, $new_task);
        }
          return $tasks;
        }



          function update($new_name)
          {
          $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
          $this->setName($new_name);
          }

          function save()
          {
              // this puts info from Class Category into the actual database
              $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES ('{$this->getName()}')");
              $this->id = $GLOBALS['DB']->lastInsertId();
          }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();

            foreach ($returned_categories as $category) {
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push ($categories, $new_category);
            }

            return $categories;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories;");
        }

        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $category) {
                $category_id = $category->getId();
                if ($category_id == $search_id) {
                    $found_category = $category;
                }
            }

            return $found_category;
        }
    }

?>
