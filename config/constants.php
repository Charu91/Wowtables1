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
	"REGISTRATION_PAGE_IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/registration/',
	"API_GALLERY_IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/gallery/',
	"API_MOBILE_IMAGE_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/mobile/',
	"LISTPAGE_SIDEBAR_WEB_URL" => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/sidebars/',
	"NEXT_RESERVATION_TIME_RANGE_LIMIT" => 2, //hours after or before an existing reservation, next reservation is allowed to a user for same date
	"SERVER_TIME_CUTOFF_FOR_RESERVATION" => '20:30:00', //time upto which server will accept the reservation requests
	"MIN_SUPPORTED_IOS_VERSION" => '1.1.0',
	"MIN_SUPPORTED_ANDROID_VERSION" => '1.1.7',
	"MIN_SUPPORTED_ANDROID_VERSION_CONCIERGE" => '1.1.1',
	"API_UPDATE" => 'Update',
	"API_NEARBY_DISTANCE" => 15,



	/* DETAILS for PAYU to be used for testing purpose */
	//"PAYU_MERCHANT_ID" => '0MQaQP',//'gtKFFX',
	//"PAYU_SALT"        => '13p0PXZk',//'eCwWELxi',
	/* Details of PAYU to be used for development purpose */
	"PAYU_MERCHANT_ID" => "jvcsd3",
	"PAYU_SALT" => "U09bo7dx",
];
