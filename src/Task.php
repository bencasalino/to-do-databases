<?php
class Task
{
    private $id;
    private $description;
    private $date_due;
    private $time_due;
    private $category_id;

    function __construct($id = null, $description, $date_due, $time_due = '09:00', $category_id)
    {
        $this->id = $id;
        $this->description = $description;
        $this->date_due = $date_due;
        $this->time_due = $time_due;
        $this->category_id = $category_id;
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

    function getDateDue()
    {
        return $this->date_due;
    }

    function getTimeDue()
    {
        return $this->time_due;
    }


    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO tasks (description, date_due, time_due, category_id) VALUES ('{$this->getDescription()}', '{$this->getDateDue()}', '{$this->getTimeDue()}', {$this->getCategoryId()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }



    function update($new_name)
    {
        $GLOBALS['DB']->exec("UPDATE tasks SET name = '{$new_name}' WHERE id = {$this->getId()};");
        $this->setName($new_name);
    }



    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();
        foreach($returned_tasks as $task) {

            $id = $task['id'];
            $description = $task['description'];
            $date_due = $task['date_due'];
            $time_due = $task['time_due'];
            $category_id = $task['category_id'];
            $new_task = new Task($id, $description, $date_due, $time_due, $category_id);
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
}
?>
