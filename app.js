// Required modules
const express = require("express");
var requestIp = require('request-ip');
var http = require('http');
var url = require('url');
var mysql = require('mysql');
var bodyParser = require('body-parser');
const { lookup } = require('geoip-lite');

// MySQL connection information
var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "trakr"
});





// MySQL connection error catching
con.connect(function(err) {
  if (err) throw err;
  console.log("Connected!");
});





// Function to generate random strings
function Random(min, max) {
  return Math.floor(Math.random() * (max - min + 1) ) + min;
}
  




// New app using express module
const app = express();
app.use(bodyParser.urlencoded({
    extended:true
}));





// app.get is used for when the user makes a GET request, which is what a page load request is
app.get("/", function(req, res) {
  res.send("test");
});




// Test to make sure that Trakr is reading the IP address from clients properly
app.get("/test/ip", function(req, res) {
    try {
    // Getting the users IP address
    var clientIp = requestIp.getClientIp(req);
    // Sending the IP back to the user
    res.send(clientIp);
    res.status(201);
    //Ending
    res.end;
    } catch {
    // Using catch to cancel if there is an error so we dont get a bunch of blank MySQL users
    // Using console.log to visualize errors
    console.log("Error");
    // Error 500 is "internal server error" https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#server_error_responses
    res.status(500);
    res.send('Error');
    // Ending
    res.end;
    }
});




// Test to make sure that Trakr is reading the IP address from clients properly
app.get("/test/country", function(req, res) {
    try {
    // Getting the users IP address
    var clientIp = requestIp.getClientIp(req);
    // Getting the country via Node package
    var country = lookup(clientIp);
    // Sending the country back to the user
    res.send(country);
    res.status(201);
    //Ending
    res.end;
    } catch {
    // Using catch to cancel if there is an error so we dont get a bunch of blank MySQL users
    // Using console.log to visualize errors
    console.log("Error");
    // Error 500 is "internal server error" https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#server_error_responses
    res.status(500);
    res.send('Error');
    // Ending
    res.end;
    }
});





app.get("/trakr", function(req, res) {
    
    try {
    // Getting the users IP address
    var clientIp = requestIp.getClientIp(req);
    // Time in Unix (LOL)
    var time = Date.now();
    // Making random string for identification
    var visitorid = Random(1111111111,9999999999);
    // Getting the users country via Node package (I love Node)
    var country = lookup(clientIp);
    // Preparing MySQL statement, you need to format it this way or else node will just put "username" instead of the username the user submitted
    var sql = "INSERT INTO `visits`(`time`,`IP`, `country`, `visitorid`) VALUES ('"+time+"','"+clientIp+"','"+country+"','"+visitorid+"')";
    // Will make a query to the db using the MySQL driver
    con.query(sql, function (err, result) {
    // Error catching
    if (err) throw err;
    // Logging new visitor
    console.log("1 new visitor with id of: " + visitorid);
    // Sending success to users browser
    res.status(201);
    //Ending
    res.end;
    }); 
    } catch {
    // Using catch to cancel if there is an error so we dont get a bunch of blank MySQL users
    // Using console.log to visualize errors
    console.log("Error");
    // Error 500 is "internal server error" https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#server_error_responses
    res.status(500);
    res.send('Error');
    // Ending
    res.end;
    }
});




  
app.listen(3000, function(){
  console.log("Server is running.");
})