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
         //
         protected function tearDown()
         {
             Task::deleteAll();
             Category::deleteAll();
         }

         function test_save()
         {
             //Arrange
             $description = "Wash the dog";
             $date_due = "1990-12-11";
             $time_due = "09:00:00";
             $id = 1;
             $test_task = new Task($id, $description, $date_due, $time_due);

             //Act
             $test_task->save();

             //Assert
             $result = Task::getAll();
             $this->assertEquals($test_task, $result[0]);
         }


              function testUpdate()
          {
              //Arrange
              $description = "Work stuff";
              $id = 1;
              $date_due = "1990-12-11";
              $time_due = "09:00:00";
              $test_task = new Task ($description, $id, $date_due, $time_due);
              $test_task->save();

              $new_description = "Home stuff";

              //Act
              $test_task->update($new_description);

              //Assert
              $this->assertEquals("Home stuff", $test_task->getDescription());
          }




         function test_getAll()
         {
              //Arrance
             $description = "Home stuff";
             $id = 1;
             $date_due = "1990-12-11";
             $time_due = "09:00:00";
             $test_task = new Task($description, $id, $date_due, $time_due);
             $test_task->save();

             $description2 = "Water the lawn";
              $id2 = 2;
              $date_due2 = "1990-11-11";
              $time_due2 = "09:01:00";
              $test_task2 = new Task($description2, $id2, $date_due2, $time_due2);
              $test_task2->save();


              //act
             $result = Task::getAll();
             var_dump($result);

             //assert
             $this->assertEquals([$test_task, $test_task2], $result);
         }

         function test_deleteAll()
         {
           //arrange
            $description = "Wash the dog";
             $id = 1;
             $date_due = "1990-12-11";
             $time_due = "09:00:00";
             $test_task = new Task($description, $id, $date_due, $time_due);
             $test_task->save();

             $description2 = "Water the lawn";
           $id2 = 2;
           $date_due2 = "1990-11-11";
           $time_due2 = "09:01:00";
           $test_task2 = new Task($description2, $id2, $date_due2, $time_due2);
           $test_task2->save();


           //act
             Task::deleteAll();

             //assert
             $result = Task::getAll();
             $this->assertEquals([], $result);
         }

         function test_getId()
         {
           //Arrange
             $id = 1;
             $description = "Wash the dog";
             $date_due = "1990-12-11";
             $time_due = "09:00:00";
             $test_task = new Task($description, $id, $date_due, $time_due);

             //Act
             $result = $test_task->getId();

             //Assert
             $this->assertEquals(1, $result);
         }




         function test_find()
         {
           //Arrange
          $description = "Wash the dog";
          $id = 1;
          $date_due = "1990-12-11";
          $time_due = "09:00:00";
          $test_task = new Task($description, $id, $date_due, $time_due);
          $test_task->save();

          $description2 = "Water the lawn";
          $id2 = 2;
          $date_due2 = "1990-11-11";
          $time_due2 = "09:01:00";
          $test_task2 = new Task($description2, $id2, $date_due2, $time_due2);
          $test_task2->save();

          //Act
          $result = Task::find($test_task->getId());

          //Assert
          $this->assertEquals($test_task, $result);
         }




         function testDeleteTask()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $date_due = "1990-12-11";
            $time_due = "09:00:00";
            $test_task = new Task($description, $id, $date_due, $time_due);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $date_due = "1990-11-11";
            $time_due = "09:01:00";
            $test_task2 = new Task($description2, $id2, $date_due2, $time_due2);
            $test_task2->save();


            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], Task::getAll());
        }

     }

?>
