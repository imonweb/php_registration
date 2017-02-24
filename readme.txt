login and registration script with email verification along with forgot password recovery feature using PHP and MySQL

Database And Table

CREATE TABLE IF NOT EXISTS `tbl_users` (
`userID` int(11) NOT NULL AUTO_INCREMENT,
`userName` varchar(100) NOT NULL,
`userEmail` varchar(100) NOT NULL UNIQUE,
`userPass` varchar(100) NOT NULL,
`userStatus` enum('Y','N') NOT NULL DEFAULT 'N',
`tokenCode` varchar(100) NOT NULL,
PRIMARY KEY (`userID`)
)

after database creation we have to create following files which are :
dbconfig.php
class.user.php
index.php
signup.php
verify.php
home.php
fpass.php
resetpass.php
logout.php

Dbconfig.php
In this file we have a simple database connection code using Database class, and one dbConnection function which connects database

Class.user.php
include "dbconfig.php" file at the beginning of this class file to make use of database and within "__construct()" function create new object of "Database()" class as "$db" as shown in this file
- runQuery() : executes a Query.
- lastID() : return a last insert id.
- register() : register new user.
- login() : to login user.
- is_logged_in() : return users session is active or not.
- logout() : to destroy users session.
- send_mail() : to send mail at user registration and send forgot password reset link.
i have used here PHPMailer to send emails using gmail smtp so you can use in your localhost server.

Signup.php | Email Verification
create "signup.php" file and paste following code inside file and include "class.user.php" file at the beginning of this file and create new object to access class files function as shown in the file.
NOTE : I have Skip validation part over here and used only HTML5 validation attributes and used MD5() Password Encryption Function, if you use PHP5.5 then you must use New Password Hashing Functions Here.
after registration a user will get mailed to his mail account to activate and verify his/her account and redirects to the "verify.php" file.
send_mail() function send a confirmation link to the user registered email.

Verify.php
create new file as "verify.php" and paste following code inside this file, after redirecting from email this file will generate a QueryString as "id=something&code=something" and based on activation code the userStatus will update from "N" to "Y" means "inactive" to "active"

Index.php / Login Page
index.php as login page which will take email id and password to access users home page if the details are wrong it will show appropriate message, with forgot password link. only email verified user can log in and access member page.

Home.php / Member Page
after verifying and email confirmation finally user will become verified user of site and can access this member page. this page is for registered members.

Logout.php
simple script and code to logout current logged in user and redirects user to "index.php" login page.

Fpass.php | Forgot Password
if user forgets password then this page will ask verified users email and send password reset link to his email account to reset his password and redirects to "resetpass.php" with QueryString id="something&code=something", this will update tokenCode field in database table and in "resetpass.php" it will match the code then user finally can reset password.

Resetpass.php
this page will be redirected from user email account to reset password, as in the QueryString there is id and code and based on code a user can reset his forgotten password, if a QueryString is set then this page can be opened. after resetting password user will be redirected to the login page.







