# OpenRadioManager

OpenRadioManager is an open source tool to help online radio stations manage the basic functions of running a radio station.
View a live demo at http://scrillas.com/projects/OpenRadioManager/

Features include

Web Portal
* Provides a ready-made web page for your station
* Station name, timezone and request-page content can be controlled through Manage Station screen

Request management
* Songs can be requested by any visitor
* Requests can be Approved or Rejected by Supervisor, Manager, and Owner roles
* Approved requests are added to a Request List where last request date and number of times requested can be viewed

Staff management
* Four levels of user access
* * DJ can view requests
* * Supervisor can manage requests
* * Manager can manage requests and staff
* * Owner can manage requests, staff, and station details
* Users with insufficient permissions are blocked from accessing screens above their level, even when going to the URL directly
* Users with Staff Management ability can only edit or delete accounts of their level and below
* Password-protected logins are generated automatically when adding staff
* * Credentials are immediately hashed and are never transmitted via email or stored in plaintext
* Forgot Password feature can be used if a user is unable to login. If the account exists, they will receive an email with a reset link
* User accounts automatically become locked after 3 failed login attempts. Unlock must be performed by Manager or Owner roles
* * Password reset requests automatically expire after 24 hours

Installation
* Installation is completed by using the Installation Wizard
* * Download and unzip
* * Upload all files to your web server
* * Using your web browser, navigate to https://yoursite.com/install.php
* * The Installation Wizard will guide you through entering all required details and will generate all required database tables
* * Admin account will be set up during installation
* * Once completed, the Installation Wizard will automatically delete itself

Requirements:
* Web server must have PHP and MySQL installed
