<?php
require_once( 'api_consts.inc.php' );

	function poster_curl(
		$host,					// host where to connect to
		$port,					// port to connect to
		$login,					// your login
		$password,				// your password
		$pic_file_names,		// array of file names of pictures to be processed
		$questions,				// array of questions related to pictures
		$pic_type = 0,			// picture type
		$print_format = 'line',	// output print format either 'line' or 'table'
		$use_ssl = FALSE
	)
	{
		$return = 0;

		$ch = curl_init();

		/*
		 * nCURLOPT_URL: URL to fetch.
		 * CURLOPT_POST: TRUE to regular HTML POST.
		 * CURLOPT_RETURNTRANSFER: TRUE to return the transfer as a string of the return value of “curl_exec” instead of outputting it out directly.
		 * CURLOPT_POSTFIELDS: The full data to post in a HTTP “POST” operation.
		 */

		$url =  '';
		$port = (int)$port;
		if( $port == 80 ) {
			// skip default port
			$url = "http://$host";
		} else if( $use_ssl && ($port == 443) ) {
			$url = "https://$host";
		} else if( $use_ssl ) {
			$url = "https://$host:$port";
		} else {
			$url = "http://$host:$port";
		}

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_VERBOSE, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)" );

		// suppress Expect header.
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Expect:') );

		if( $use_ssl ) {
			// simple way is to accept all certificates
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

//			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, TRUE );
			//	0: Don’t check the common name (CN) attribute
			//	1: Check that the common name attribute at least exists
			//	2: Check that the common name exists and that it matches the host name of the server
//			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
			// There is a CURLOPT_CAPATH option that allows you to specify a directory
			// that holds multiple CA certificates to trust
//			curl_setopt( $ch, CURLOPT_CAINFO, "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt" );

		} // if( use_ssl )

		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 300 );

		// common MANDATORY part or POST request
		$post = array(
			'function'		=> 'picture2',
			'username'		=> $login,
			'password'		=> $password,
		);

		// now let's build rest of POST fields
		if( is_array( $pic_file_names ) ) {
			// we have set of images
			if( count( $pic_file_names ) > ptMULTIPART_PICS_NUM ) {
				return FALSE;
			}

			$pic_index = 1;
			// same as <input type="file" name="pict">
			foreach( $pic_file_names as $pfn ) {
				$post['pict'.$pic_index] = "@$pfn";
				$pic_index++;
			}
		} else {
			// single image
			$post['pict'] = "@$pic_file_names";
		}

		if( is_array( $questions ) ) {
			// we have set of questions
			if( count( $questions ) > ptMULTIPART_PICS_NUM ) {
				return FALSE;
			}

			$text_index = 1;
			// same as <input type="file" name="pict">
			foreach( $questions as $question ) {
				$post['text'.$text_index] = "$question";
				$text_index++;
			}
		} else {
			// single question
			$post['text1'] = "$questions";
		}

		$post['pict_to'] = "0";

		if( isset( $pic_type ) ) {
			$post['pict_type'] = "$pic_type";
		}

		if( isset( $print_format ) ) {
			$post['print_format'] = $print_format;
		}

		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );

		$response = curl_exec( $ch );
		if( $response === FALSE ) {
			$error = curl_error( $ch );
		}
		$curl_info = curl_getinfo( $ch );
		curl_close( $ch );

		if( $response === FALSE ) {
			// cURL call error
			echo "curl error=$error";
			return FALSE;
		} else if( $curl_info['http_code'] != 200 ) {
			// HTTP error
			return (int)$curl_info['http_code'];
		} else {
			// HTTP 200
			if( strlen( $response ) == 0 ) {
				// HTTP 200 with 0-length respose, am I correct with my settings?
				return 0;
			}

			// response

			if( $print_format == 'table' ) {
				$start		= '<table><tr><td>';
				$separator	= '</td><td>';
				$end		= '</td></tr></table>';

				if( strlen( $response ) < strlen( $start ) + strlen( $end ) ) {
					// not sufficient length
					return 1;
				}

				if( substr( $response, 0, strlen( $start ) ) != $start ) {
					// where is the start section?
					return 2;
				}

				// <table><tr><td>0</td><td>1</td><td>2</td><td>0</td><td>0</td><td>the text</td></tr></table>

				// cut off start portion
				$response = substr( $response, strlen( $start ) );

				if( substr( $response, -strlen( $end ) ) != $end ) {
					// where is the end section?
					return 3;
				}

				// 0</td><td>1</td><td>2</td><td>0</td><td>0</td><td>the text</td></tr></table>

				// cut off end portion
				$response = substr( $response, 0, -strlen( $end ) );

				// 0</td><td>1</td><td>2</td><td>0</td><td>0</td><td>the text

				// split the rest into parts
				$parts = @explode( $separator, $response );
			} else {
				// line
				// split answer into parts
				$parts = @explode( '|', $response );
			}

			$error_code = $parts[0];

			if( $error_code === '0' ) {
				// error code 0 - OK
				// ResultCode|MajorID|MinorID|Type|Timeout|Text
				$error_code	= $parts[0];
				$major_id	= $parts[1];
				$minor_id	= $parts[2];
				// 3 - type
				// 4 - timeout
				$return		= $parts[5];
			} else {
				// error
				$return = (int)$error_code;
			}
		}


		return $return;
	} // function
