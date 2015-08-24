<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";
    require_once "src/Task.php";

    $server = 'mysql:host=localhost; dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CategoryTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function test_getName()
        {
            $name = "Work stuff";
            $test_Category = new Category($name);

            $result = $test_Category->getName();

            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            $name = "Work stuff";
            $id = 1;
            $test_Category = new Category($name, $id);

            $result = $test_Category->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function testGetTasks()
        {
            //Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category ($name, $id);
            $test_category->save();

            $test_category_id = $test_category->getId();

            $description = "Email Client";
            $date_due = "1990-12-12";
            $time_due = "06:12:11";
            $test_task = new Task($id, $description, $date_due, $time_due, $test_category_id);
            $test_task->save();

            $description2 = "Meet with the Boss";
            $date_due2 = "1995-12-11";
            $time_due2 = "06:34:00";
            $test_task2 = new Task($id, $description2, $date_due2, $time_due2, $test_category_id);
            $test_task2->save();

            //Act
            $result = $test_category->getTasks();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function test_save()
        {
            $name = "Work stuff";
            $test_Category = new Category($name);
            $test_Category->save();

            $result = Category::getAll();

            $this->assertEquals($test_Category, $result[0]);
        }

        function test_getAll()
        {
            $name = "Work stuff";
            $name2 = "Home stuff";
            $test_Category = new Category($name);

            $test_Category->save();
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            $result = Category::getAll();

            $this->assertEquals([$test_Category, $test_Category2], $result);
        }

        function test_deleteAll()
        {
            $name = "Wash the dog";
            $name2 = "Home stuff";
            $test_Category = new Category($name);
            $test_Category->save();
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            Category::deleteAll();
            $result = Category::getAll();

            $this->assertEquals([], $result);
        }


            function testUpdate()
        {
            //Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $new_name = "Home stuff";

            //Act
            $test_category->update($new_name);

            //Assert
            $this->assertEquals("Home stuff", $test_category->getName());
        }

        function test_find()
        {
            $name = "Wash the dog";
            $name2 = "Home stuff";
            $test_Category = new Category($name);
            $test_Category->save();
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            $result = Category::find($test_Category->getId());

            $this->assertEquals($test_Category, $result);
        }

        function testDelete()
        {
            //Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Home stuff";
            $test_category2 = new Category($name2, $id);
            $test_category2->save();


            //Act
            $test_category->delete();

            //Assert
            $this->assertEquals([$test_category2], Category::getAll());
        }

        function testDeleteCategoryTasks()
        {
            //Arrange
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Build website";
            $category_id = $test_category->getId();
            $test_task = new task($description, $id, $category_id);
            $test_task->save();


            //Act
            $test_category->delete();

            //Assert
            $this->assertEquals([], Task::getAll());
        }

    }
?>
