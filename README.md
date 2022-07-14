# profile-app is login, register and view profile web app. the front end is built using vueJs and the backend is built using slim 3.0 framework.

for the front-end directory run the two commands below
npm install
npm run dev

for the backend run
enter the src directory and run "composer upgrade"

FRONT-END

I implemented the front-end using vuejs 3.o as I was instructed, where I created a project using the npm init vue@3.0. I came up with three components/pages inside the subdirectory called pages which is located inside the src directory.

src contains Login.vue, NotFound.vue, Profile.vue, Register.vue


BACKEND-FUNCTIONALITY 

I used a php backend framework called slim (3.o) to implement the backend of the application. 
I installed slim using composer require slim/slim:3.0. I required the composer autoloader in my index.php script inorder to start using slim.
In index.php I created a new Slim app called app where I also implemented the api endpoints.

This section is explained in detail under the API section.

DATA BASE

I installed SQLite Database on my windows machine inorder to get the privilege of using SQlite database.

I used SQLite database as my backend data store as instructed and did the necessary configuration in index.php such as requiring the db.php file to have access to it. In db.php, i created different tables based on different conditions such as CREATE TABLE IF NOT EXISTS app_users, CREATE TABLE IF NOT EXISTS oauth_clients, CREATE TABLE IF NOT EXISTS oauth_access_tokens, CREATE TABLE IF NOT EXISTS oauth_authorization_codes, and others.



RESTFUL APIs

I built the APIs using Slim 3.o with three endpoints.

Register endpoint (/register)

This endpoint has a getParsedBody() function which accesses the body of the being created user instance and also checks if the first name, last name, phone number and password are set. And if they are set, it trims or removes the unnecessary spaces and characters from the body of the respective passed values. It adds the user to the database, unsets the password field and returns json data.


Login endpoint (/login)

Inside this method, a variable which contains a secret code or key or an authorization code is declared. This code will be combined with other parameters to create an access token which is contained in the returned JSON by this post() method. This post() method also has a getParsedBody() function which accesses the data of the created user instance attempting to login. It checks if the phone number and password field contains values, if they do, it cleans the data through the trim() method. If the fields are empty or the password doesn’t match the one in the database, a message is displayed saying invalid phone number.


If the information is valid, a json response is returned with an access token.

Profile endpoint (/profile/{phoneNumber})

This basically returns the phone number, first name and last name of the user in JSON format. This is where Oauth 2 comes into play. I will explain the details in the next section.


Oauth 2 Authentication.

Oauth 2 is a third party authentication.

NB: Oauth 2 has token authentication which I used. It also has other types of authentication.

During the login process as explained earlier, a json response is returned which contains an access token which is used for authentication before accessing the profile page.

The endpoint “/profile/{phoneNumber}”  has a phone number and an access token in its body which the getParsedBody method accesses. Initially the function’ logic checks if the phone number and token are available. If they are available, the token is decoded and a new object of the key is created.
The phone number is also decoded to see if it matches the one in args parameter.

If the token is expired, invalid, or missing, the user will just receive a message informing them about their particular condition.

If the token and phone number are correctly verified, the user is able to view the profile page.

UNIT TESTS


