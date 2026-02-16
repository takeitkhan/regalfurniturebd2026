<?php


$MerchantID = '';
$OrderID = '';
$SessionID = '';

function PostQW($data)
{
    $hostname = '127.0.0.1'; // Address of the server with servlet used to work with orders
    $port = "743"; // Port

    $path = '/Exec';
    $content = '';

    global $MerchantID, $OrderID, $SessionID;

    // Establish a connection to the $hostname server
    $fp = fsockopen($hostname, $port, $errno, $errstr, 30);
    //dd($fp);
    // Check if the connection is successfully established
    if (!$fp) die('<p>' . $errstr . ' (' . $errno . ')</p>');

    // HTTP request header
    $headers = 'POST ' . $path . " HTTP/1.0\r\n";
    $headers .= 'Host: ' . $hostname . "\r\n";
    $headers .= "Content-type: application/x-www-form-urlencoded\r\n";
    $headers .= 'Content-Length: ' . strlen($data) . "\r\n\r\n";

    // Send HTTP request to the server
    fwrite($fp, $headers . $data);

    // Receive response
    while (!feof($fp)) {
        $inStr = fgets($fp, 1024);
        $content .= $inStr;
    }
    fclose($fp);



    // Cut the HTTP response headers. The string can be commented out if it is necessary to parse the header
    // In this case it is necessary to cut the response
    $content = substr($content, strpos($content, "<TKKPG>"));

    //dd($content);
    // To parse the response, use the simplexml library
    // Documentation on simplexml - http://us3.php.net/manual/ru/book.simplexml.php
    $xml = simplexml_load_string($content);
    return ($xml);
}