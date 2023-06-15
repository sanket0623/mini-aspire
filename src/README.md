## How to run the App

1. After taking the pull of the project from github
2. Run below commands in order to setup 
      - composer require laravel/sail --dev
      - php artisan sail:install and install mysql
     - ./vendor/bin/sail up -d to up the environment then check http://localhost/
3. Connect the database and check if all the tables available. If not then take the tables schema from database/mysql/miniaspire_table.sql
4. Please use below query to create a user 
    - insert into users (name, email) values ('xxxxx', 'xxxxxx@xxxx.com');



## Postman collection
Please get the json from below link and import the same.
https://jsoneditoronline.org/#right=local.punola&left=cloud.d64e302436cd42c8bcd54368eef709ec 

Following functionality is supported by this application.

1. Create loan(User):-
   This API will create a loan for the user with pre-payment according to the term.
   Endpoint:- /user/v1/createLoan 

2. View loan(User):-
   User can view the loan list and check the status of loan and pre-payment. It also support the pagination
   Endpoint:- /user/v1/viewLoanList?page=1

3. View loan(Admin):-
   Admin can view the whole loan list and check the status of loan and pre-payment. It also support the pagination
   Endpoint:- /admin/v1/viewLoanList?page=1

4. Update loan status(Admin):-
   Admin can REJECTED/APPROVED the loan
   Endpoint:- /admin/v1/updateLoanStatus

5. User loan pre-payment(User):-
   This API will help the user to do payment of the loan.
   Endpoint:- /user/v1/loanPrepayment


## To run testcases
Go to the project directory and run testcases
 - php artisan test