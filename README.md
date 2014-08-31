Yii-Initial-Setup-with-User-Registration
========================================

###This YII project contains initial user registration functionality.###

#####Basically below modification added to freshly create YII project######

Configure database connection in “config/main.php”  file.

Create Table for user details and generate model based on User table with validation.  

Modify “components/UserIdentity.php” file change for authenticate with Database user data.

Generate RegisterForm.php model with Gii

Create Registration form (views/site/register.php) 

Add Register, Register Verify Email and Register token Verified method for “controllers/SiteController.php”

Add link for registration form in views/layouts/main.php


