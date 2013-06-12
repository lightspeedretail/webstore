<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
header("Content-type:text/plain; charset:utf-8");
include_once 'sdk.class.php';

$s3 = new AmazonS3();
$bucketname = "empresasctmbucketpruebas";
//CREA UN BUCKET
//$response = $s3->create_bucket($bucketname, $s3::REGION_US_E1);
//print_r($response);

//LISTA BUCKETS
//$response = $s3->get_bucket_list();
//print_r($response);

//UPLOAD
//$response = $s3->create_object($bucketname, "PRUEBA-".date('ljS-FYh:i:sA'),
//        array(
//          'body' => "EMPTY",
//            'contentType' => 'text/plain',
//            'acl' => $s3::ACL_PUBLIC
//        ));
//print_r($response);

$response = $s3->create_object($bucketname, "PRUEBA-".date('ljS-FYh:i:sA').".png",
        array(
          'fileUpload' => "/home/naito/Escritorio/Firsttets.png",
            'acl' => $s3::ACL_PUBLIC
        ));


print_r($response);






?>
