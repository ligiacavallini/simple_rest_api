#Here is the instructions to test this simple example:

I’m using MySql database with PHP PDO extension.
The script to create the table is simplesrestapi.sql. 
The app is in the folder /api
You can change the database access on simplerestapi/api/App/Core/Config.php
There is a code called test.php in the folder simplerestapi/. It consists in a simple stream_context_create, in case you need.
The URI are:

To see all entries - to use GET method: /api/address
To see one specific entry - to use GET method: /api/address/[the_ID]
To insert a row - to use POST method: /api/address
To update a row - to use PUT or POST method: /api/address/[the_ID]
TO delete a row - to use DELETE method: /api/address/[the_ID]

ON CRUD
If some error occur, it will be returned like this:

{	
	"status":"error",
	"message":"[THE ERROR MESSAGE]"
}
The specific http request will be provided.

if there is no error, than the return will be like this:

{
	"status":"ok",
	"message":"[TEXT WITH THE ACTION DONE]"
}

Status API Training Shop Blog About Pricing
