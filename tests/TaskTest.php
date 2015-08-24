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
             $id = 1;
             $description = "Wash the dog";

             $test_task = new Task($id, $description);

             //Act
             $test_task->save();

             //Assert
             $result = Task::getAll();
             $this->assertEquals($test_task, $result[0]);
         }


              function test_Update()
          {
              //Arrange
              $id = 1;
             $description = "Wash the dog";
             $date_due = "1990-12-11";
             $time_due = "09:00:00";
             $test_task = new Task($id, $description, $date_due, $time_due);
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
             $id = 4;
             $description = "Home stuff";
             $test_task = new Task($id, $description);
             $test_task->save();



              $id2 = 2;
              $description2 = "Water the lawn";

              $test_task2 = new Task($id2, $description2);
              $test_task2->save();



              //act
             $result = Task::getAll();


             //assert
             $this->assertEquals([$test_task, $test_task2], $result);
         }

         function test_deleteAll()
         {

           //Arrange
           $id = 1;
          $description = "Wash the dog";
          $date_due = "1990-12-11";
          $time_due = "09:00:00";
          $test_task = new Task($id, $description, $date_due, $time_due);
          $test_task->save();

          $id2 = 2;
          $description2 = "Water the lawn";
          $date_due2 = "1990-11-11";
          $time_due2 = "09:01:00";
          $test_task2 = new Task($id2, $description2, $date_due2, $time_due2);
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

             $test_task = new Task($id, $description);

             //Act
             $result = $test_task->getId();

             //Assert
             $this->assertEquals(1, $result);
         }




         function test_find()
         {
           //Arrange
           $id = 1;
          $description = "Wash the dog";
          $date_due = "1990-12-11";
          $time_due = "09:00:00";
          $test_task = new Task($id, $description, $date_due, $time_due);
          $test_task->save();

          $id2 = 2;
          $description2 = "Water the lawn";
          $date_due2 = "1990-11-11";
          $time_due2 = "09:01:00";
          $test_task2 = new Task($id2, $description2, $date_due2, $time_due2);
          $test_task2->save();

          //Act
          $result = Task::find($test_task->getId());

          //Assert
          $this->assertEquals($test_task, $result);
         }




         function testDeleteTask()
        {
            //Arrange
            $id = 1;
           $description = "Wash the dog";
           $date_due = "1990-12-11";
           $time_due = "09:00:00";
           $test_task = new Task($id, $description, $date_due, $time_due);
           $test_task->save();

           $id2 = 2;
           $description2 = "Water the lawn";
           $date_due2 = "1990-11-11";
           $time_due2 = "09:01:00";
           $test_task2 = new Task($id2, $description2, $date_due2, $time_due2);
           $test_task2->save();



            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], Task::getAll());
        }

        function testAddCategory()
{
    //Arrange
    $name = "Work stuff";
    $id = 1;
    $test_category = new Category($name, $id);
    $test_category->save();

    $description = "File reports";
    $id2 = 2;
    $test_task = new Task($description, $id2);
    $test_task->save();

    //Act
    $test_task->addCategory($test_category);

    //Assert
    $this->assertEquals($test_task->getCategories(), [$test_category]);
}

    function testGetCategories()
    {
        //Arrange
        $name = "Work stuff";
        $id = 1;
        $test_category = new Category($name, $id);
        $test_category->save();

        $name2 = "Volunteer stuff";
        $id2 = 2;
        $test_category2 = new Category($name2, $id2);
        $test_category2->save();

        $description = "File reports";
        $id3 = 3;
        $test_task = new Task($description, $id3);
        $test_task->save();

        //Act
        $test_task->addCategory($test_category);
        $test_task->addCategory($test_category2);

        //Assert
        $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
    }

        function testDelete()
    {
        //Arrange
        $name = "Work stuff";
        $id = 1;
        $test_category = new Category($name, $id);
        $test_category->save();

        $description = "File reports";
        $id2 = 2;
        $test_task = new Task($description, $id2);
        $test_task->save();

        //Act
        $test_task->addCategory($test_category);
        $test_task->delete();

        //Assert
        $this->assertEquals([], $test_category->getTasks());
    }


     }

?>
