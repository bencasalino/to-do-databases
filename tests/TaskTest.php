<?php
     /**
     * @backupGlobals disabled
     * @backupStaticAttributes disabled
     */

     require_once "src/Task.php";
     require_once "src/Category.php";

     $server = 'mysql:host=localhost;dbname=to_do_test';
     $username = 'root';
     $password = 'root';
     $DB = new PDO($server, $username, $password);

     class TaskTest extends PHPUnit_Framework_TestCase
     {

         protected function tearDown()
         {
             Task::deleteAll();
             Category::deleteAll();
         }

         function test_save()
         {
             //Arrange
             $name = "Home stuff";
             $id = null;
             $test_category = new Category ($name, $id);
             $test_category->save();

             $description = "Wash the dog";
             $date_due = "9/25/1990";
             $time_due = "9:00am";
             $category_id = $test_category->getId();
             $test_task = new Task($id, $description, $date_due, $time_due, $category_id);

             //Act
             $test_task->save();

             //Assert
             $result = Task::getAll();
             $this->assertEquals($test_task, $result[0]);
         }


              function testUpdate()
          {
              //Arrange
              $name = "Work stuff";
              $id = null;
              $test_category = new Category( $id, $descrption, $date_due, $time_due,  $category_id, );
              $test_category->save();

              $new_name = "Home stuff";

              //Act
              $test_category->update($new_name);

              //Assert
              $this->assertEquals("Home stuff", $test_category->getName());
          }




         function test_getAll()
         {
             $name = "Home stuff";
             $id = null;
             $test_category = new Category ($name, $id);
             $test_category->save();

             $description = "Wash the dog";
             $date_due = "9/25/1990";
             $time_due = "9:00am";
             $category_id = $test_category->getId();
             $test_task = new Task($id, $description, $date_due, $time_due, $category_id);
             $test_task-> save();

             $description2 = "Water the Lawn";
             $date_due2 = "3/28/2000";
             $time_due2 = "10:00am";
             $test_task2 = new Task($id, $description2, $date_due2, $time_due2, $category_id);
             $test_task2->save();

             $result = Task::getAll();

             $this->assertEquals([$test_task, $test_task2], $result);
         }

         function test_deleteAll()
         {
             $name = "Home stuff";
             $id = null;
             $test_category = new Category ($name, $id);
             $test_category->save();

             $description = "Wash the dog";
             $date_due = "9/25/1990";
             $time_due = "9:00am";
             $category_id = $test_category->getId();
             $test_task = new Task($id, $description, $date_due, $time_due, $category_id);
             $test_task->save();

             $description2 = "Water the Lawn";
             $date_due2 = "3/28/2000";
             $time_due2 = "10:00am";
             $test_task2 = new Task($id, $description2, $date_due2, $time_due2, $category_id);
             $test_task2->save();

             Task::deleteAll();

             $result = Task::getAll();
             $this->assertEquals([], $result);
         }

         function test_getId()
         {
             //Arrange
             $name = "Home stuff";
             $id = null;
             $test_category = new Category ($name, $id);
             $test_category->save();

             $description = "Wash the dog";
             $date_due = "9/25/1990";
             $time_due = "9:00am";
             $category_id = $test_category->getId();
             $test_task = new Task($id, $description, $date_due, $time_due, $category_id);
             $test_task->save();

             //Act
             $result = $test_task->getId();
             //Assert

             $this->assertEquals(true, is_numeric($result));
         }

         function test_find()
         {
             //Arrange
             $name = "Home stuff";
             $id = null;
             $test_category = new Category ($name, $id);
             $test_category->save();

             $description = "Wash the dog";
             $date_due = "9/25/1990";
             $time_due = "9:00am";
             $category_id = $test_category->getId();
             $test_task = new Task($id, $description, $date_due, $time_due, $category_id);
             $test_task->save();

             $description2 = "Water the Lawn";
             $date_due2 = "3/28/2000";
             $time_due2 = "10:00am";
             $test_task2 = new Task($id, $description2, $date_due2, $time_due2, $category_id);
             $test_task2->save();

             $result = Task::find($test_task->getId());

             $this->assertEquals($test_task, $result);
         }
     }

?>
