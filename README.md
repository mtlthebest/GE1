(C) MtL 2013

I'm using this repository, "GE1", as a sandbox for a very small Content Management System, tuned for use at my workplace. The source code I've put here is necessary to run a simple web site. I'm using GitHub to have a back-up copy of my source code, to keep track of changes and to organize the code.

I'm trying to run such web site in these two different environments:

  - a L.A.M.P. (Linux, Apache, MySQL, PHP) platform, using Linux Mint on my notebook;
  - a X.A.M.P.P. (Windows, Apache, MySQL, PHP) platform, using a very old notebook
    and Windows XP to run a web-server at work.

Using two different machines to host the same website really forces you to pay attention to these important aspects:

  - Character encoding (I'm trying to use UTF-8 for everything, and that's not so immediate to obtain...);
  - CSS (Cascade Style Sheets become very useful when you want to obtain a uniform
    aspect for your web pages while using different browsers: Internet Explorer, Google Chrome,
    Mozilla Firefox, Opera...).

The source code is written mainly in PHP. Obviously, I'm also using (X)HTML. I want to use XHTML 1.1 for my web pages. The source code here isn't enough to run the website. It's necessary to use an appropriate MySQL database back-end.

Other languages I'm using are:
  - CSS;
  - SQL.

 ##############################
 # FUNCTIONS YET TO IMPLEMENT #
 ##############################

1) Usability:
   - Ability to edit AP, PR and CH records (files to edit: "differiti-editor.php", ...);
   - If I add or modify a record it'd be nice if "differiti-display.php" highlighted the specific row (yellow, for example?). This is just
     to allow people to easily check data...

2) Web pages standardization:
   - MySQL database connection: most .php pages open and close connections (commonality). Should I put that in .inc.php files and avoid repetition?

3) Flexibility:
   - "differiti-home.php" should dynamically draw a table even if i changed/added a new entry in table "table_differititipologie".
     At the moment, that table is hard-coded (veeeeeeeery baaaaaaaaad, any change to that database table now would break the site!);
   - The same reasoning applies to helicopters: it should be an easy task to migrate this site for use with other aircrafts... right now it's
     impossible, because the "summary table" structure in "differiti-home.php" is hard-coded...

4) Security:
   - Authentication system (I'd like to use an existing Exchange system in our Intranet -> PHP + libcurl?);
   - SSL connection (not mandatory, but it should be easy to do with XAMPP. How many people would think to use Wireshark while attached to one
     of our routers?). I'll implement SSL anyway...
