<?php

//file: config/constants.php

/**
 * Constants for application.
 * 
 * @since	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
 
 return [
 	"IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/',
 	"API_ERROR" => 'FAIL',
 	"API_SUCCESS" => 'OK',
 	"API_LISTING_IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/listing/',
 	"API_GALLERY_IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/gallery/',
 	"API_MOBILE_IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/mobile/',
 	"NEXT_RESERVATION_TIME_RANGE_LIMIT" => 2, //hours after or before an existing reservation, next reservation is allowed to a user for same date
 	"SERVER_TIME_CUTOFF_FOR_RESERVATION" => '20:30:00', //time upto which server will accept the reservation requests 
 ];
