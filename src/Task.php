<?php
class Task
{
    private $id;
    private $description;



    function __construct($id = null, $description)
    {
        $this->id = $id;
        $this->description = $description;

    }

    function getId()
    {
        return $this->id;
    }

    function setDescription ($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getCategoryId()
    {
        return $this->category_id;
    }

    function getDescription()
    {
        return $this->description;
    }

    // function getDateDue()
    // {
    //     return $this->date_due;
    // }
    //
    // function getTimeDue()
    // {
    //     return $this->time_due;
    // }


    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }


    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();

        foreach($returned_tasks as $task) {

            $id = $task['id'];
            $description = $task['description'];

            $new_task = new Task($id, $description);
            array_push($tasks, $new_task);
        }

        return $tasks;

    }

    static function deleteAll() {
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    static function find($search_id)
    {
        $found_task = null;
        $tasks = Task::getAll();
        foreach($tasks as $task) {
            $task_id = $task->getId();
            if ($task_id == $search_id) {
                $found_task = $task;
            }
        }
        return $found_task;
    }

    function update($new_description)
        {
            $GLOBALS['DB']->exec("UPDATE tasks SET description = '{$new_description}' WHERE id = {$this->getId()};");
            $this->setDescription($new_description);
        }


        function addCategory($category)
        {
            $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$category->getId()}, {$this->getId()});");
        }

        function getCategories()
        {
            $query = $GLOBALS['DB']->query("SELECT category_id FROM categories_tasks WHERE task_id = {$this->getId()};");
            $category_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $categories = array();
            foreach($category_ids as $id) {
                $category_id = $id['category_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM categories WHERE id = {$category_id};");
                $returned_category = $result->fetchAll(PDO::FETCH_ASSOC);

                $name = $returned_category[0]['name'];
                $id = $returned_category[0]['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            return $categories;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE task_id = {$this->getId()};");
        }
    }
?>
