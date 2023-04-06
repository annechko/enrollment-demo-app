### It is a small web app, providing schools and students help with enrollment process.<br>

This project is a **work in progress**.<br>
I have been working on it for only few days :)


Run `make init` to start the application.

It starts NGINX, PHP-FPM, PostgreSQL, and some more Docker containers, builds assets (bootstrap styles), runs db migrations, starts to listen to connections on http://localhost:8080


App's functionality so far<br>
[![demo video preview](https://img.youtube.com/vi/TMjZpGKBdP4/0.jpg)](https://youtu.be/TMjZpGKBdP4)

You can create registration request as a school on page<br>
http://localhost:8080/school/register


You can create admin user by running command `make admin`<br>
Then you can sign in as that admin user here<br>
http://localhost:8080/admin/login

_Email: admin@admin.admin<br>
Pass: admin_

Then you'll see all schools' applications and you can confirm their registrations<br>
http://localhost:8080/admin/school

After every confirmation an email is sent to the school's email address with an invitation link to set up their password and activate an account.<br>
Invitation link gets expired in 5 days.
