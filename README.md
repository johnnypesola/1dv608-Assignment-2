# Assignment 2 Login - PHP course 1DV608 on Linnaeus University

This is my repository for the login assignment. It uses a mysql database as backend.

#### It fulfills the following requirements: 

-  [Requirement specification UC1, UC2, UC3](https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_2/Assignment2_Use_Cases.md)
-  [Requirement specification UC4](https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_4/UC4.md)

#### It has been tested with the following testcases:

-  [Test cases 1.1 - 2.4](https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_2/Assignment2_Test_Cases_Mandatory.md)
-  [Test cases 3.1 - 3.8](https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_2/Assignment2_Extra_Test_cases.md)
-  [Test cases 4.1 - 4.10](https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_4/TestCases.md)
   
It also has some flooding protection, so thats it's only possible to register a certain number of new users per hour. And also checks that not too many login attempts are made with the same username.
