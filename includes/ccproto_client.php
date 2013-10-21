<?php

require_once( 'ccproto.inc.php' );
require_once( 'api_consts.inc.php' );

ini_set( 'max_execution_time', 0 );

define(	'sCCC_INIT',		1		);		//	initial status, ready to issue LOGIN on client
define(	'sCCC_LOGIN',		2		);		//	LOGIN is sent, waiting for RAND (login accepted) or CLOSE CONNECTION (login is unknown)	
define(	'sCCC_HASH',		3		);		//	HASH is sent, server may CLOSE CONNECTION (hash is not recognized)
define(	'sCCC_PICTURE',		4		);

/**
 *	CC protocol class
 */
class ccproto {
	var	$status;
	var	$s;

	/**
	 *
	 */
	function init() {
		$this->status = sCCC_INIT;
	} // init()

	/**
	 *
	 */
	function login( $hostname, $port, $login, $pwd ) {
		$this->status = sCCC_INIT;

		$errnum = 0;
		$errstr = '';

		if(( $this->s = @fsockopen( $hostname, $port, $errnum, $errstr )) === FALSE ) {
			//print( 'We have an fsockopen() error: ' . $errstr . ' (' . $errnum . ')' );
			return ccERR_NET_ERROR;
		}

		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );

		$pack->setCmd( cmdCC_LOGIN );
		$pack->setSize( strlen( $login ) );
		$pack->setData( $login );

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		if( $pack->unpackFrom( $this->s, cmdCC_RAND, CC_RAND_SIZE ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		$shabuf = NULL;
		$shabuf .= $pack->getData();
		$shabuf .= md5( $pwd );
		$shabuf .= $login;
		
		$pack->setCmd( cmdCC_HASH );
		$pack->setSize( CC_HASH_SIZE );
		$pack->setData( hash( 'sha256', $shabuf, TRUE ) );
		
		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}
		
		if( $pack->unpackFrom( $this->s, cmdCC_OK ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		$this->status = sCCC_PICTURE;

		return ccERR_OK;
	} // login()

	/**
	 *
	 */
	function picture2( 
		$pict,				//	IN		picture binary data
		&$pict_to, 			//	IN/OUT	timeout specifier to be used, on return - really used specifier, see ptoXXX constants, ptoDEFAULT in case of unrecognizable
		&$pict_type, 		//	IN/OUT	type specifier to be used, on return - really used specifier, see ptXXX constants, ptUNSPECIFIED in case of unrecognizable
		&$text,				//	OUT	text
		&$major_id = NULL,	//	OUT	OPTIONAL	major part of the picture ID
		&$minor_id = NULL	//	OUT OPTIONAL	minor part of the picture ID
	) {
		if( $this->status != sCCC_PICTURE )
			return ccERR_STATUS;

		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );
		$pack->setCmd( cmdCC_PICTURE2 );

		$desc = new cc_pict_descr();
		$desc->setTimeout( ptoDEFAULT );
		$desc->setType( $pict_type );
		$desc->setMajorID( 0 );
		$desc->setMinorID( 0 );
		$desc->setData( $pict );
		$desc->calcSize();
		
		$pack->setData( $desc->pack() );
		$pack->calcSize();

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		if( $pack->unpackFrom( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}
		
		switch( $pack->getCmd() ) {
			case cmdCC_TEXT2:
				$desc->unpack( $pack->getData() );
				$pict_to	= $desc->getTimeout();
				$pict_type	= $desc->getType();
				$text		= $desc->getData();
				if( isset( $major_id ) )
					$major_id	= $desc->getMajorID();
				if( isset( $minor_id ) )
					$minor_id	= $desc->getMinorID();
				return ccERR_OK;

			case cmdCC_BALANCE:
				// balance depleted
				return ccERR_BALANCE;
			
			case cmdCC_OVERLOAD:
				// server's busy
				return ccERR_OVERLOAD;
			
			case cmdCC_TIMEOUT:
				// picture timed out
				return ccERR_TIMEOUT;
			
			case cmdCC_FAILED:
				// server's error
				return ccERR_GENERAL;
			
			default:
				// unknown error
				return ccERR_UNKNOWN;
		}
	} // picture2()

	/**
	 *
	 */
	function picture_multipart( 
		$pics,				//	IN array of pictures binary data
		&$pict_to, 			//	IN/OUT	timeout specifier to be used, on return - really used specifier, see ptoXXX constants, ptoDEFAULT in case of unrecognizable
		&$pict_type, 		//	IN/OUT	type specifier to be used, on return - really used specifier, see ptXXX constants, ptUNSPECIFIED in case of unrecognizable
		&$text,				//	OUT	text
		&$major_id = NULL,	//	OUT	OPTIONAL	major part of the picture ID
		&$minor_id = NULL	//	OUT OPTIONAL	minor part of the picture ID
	) {
	
		if( !isset( $pics ) || !is_array( $pics ) ) {
			// $pics - must be an array of pictures
			return ccERR_BAD_PARAMS;
		}
	
		switch( $pict_type ) {
		
			case ptASIRRA:
				// ASIRRA must have ptASIRRA_PICS_NUM pictures
				if( count( $pics ) != ptASIRRA_PICS_NUM ) {
					return ccERR_BAD_PARAMS;
				}
				break;
				
			default:
				// we serve only ASIRRA multipart pictures so far
				return ccERR_BAD_PARAMS;
				break;
		} // switch( pict_type )
	
		$pict = "";
		
		// combine all images into one bunch
		foreach( $pics as &$pic ) {
			$pict .= pack( "V", strlen( $pic ) );
			$pict .= $pic;
		}
	
		return $this->picture2( $pict, $pict_to, $pict_type, $text, $major_id, $minor_id );
	} // picture_asirra()

	/**
	 *
	 */
	function picture_bad2( $major_id, $minor_id ) {
		$pack = new cc_packet();

		$pack->setVer( CC_PROTO_VER );
		$pack->setCmd( cmdCC_PICTUREFL );

		$desc = new cc_pict_descr();
		$desc->setTimeout( ptoDEFAULT );
		$desc->setType( ptUNSPECIFIED );
		$desc->setMajorID( $major_id );
		$desc->setMinorID( $minor_id );
		$desc->calcSize();
		
		$pack->setData( $desc->pack() );
		$pack->calcSize();

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		return ccERR_NET_ERROR;
	} // picture_bad2()
	
	/**
	 *
	 */
	function balance( &$balance ) {
		if( $this->status != sCCC_PICTURE )
			return ccERR_STATUS;

		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );
		$pack->setCmd( cmdCC_BALANCE );
		$pack->setSize( 0 );

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		if( $pack->unpackFrom( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}
		
		switch( $pack->getCmd() ) {
			case cmdCC_BALANCE:
				$balance = $pack->getData();
				return ccERR_OK;

			default:
				// unknown error
				return ccERR_UNKNOWN;
		}
	} // balance()

	/**
	 *
	 */
	function system_load( &$system_load ) {
		if( $this->status != sCCC_PICTURE )
			return ccERR_STATUS;

		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );
		$pack->setCmd( cmdCC_SYSTEM_LOAD );
		$pack->setSize( 0 );

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		if( $pack->unpackFrom( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}
	
		if( $pack->getSize() != 1 ) {
			return ccERR_UNKNOWN;
		}

		switch( $pack->getCmd() ) {
			case cmdCC_SYSTEM_LOAD:
				$arr = unpack( 'Csysload', $pack->getData() );
				$system_load = $arr['sysload'];
				return ccERR_OK;

			default:
				// unknown error
				return ccERR_UNKNOWN;
		}
	} // system_load()

	/**
	 *
	 */
	function close() {
		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );

		$pack->setCmd( cmdCC_BYE );
		$pack->setSize( 0 );

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		fclose( $this->s );
		$this->status = sCCC_INIT;

		return ccERR_NET_ERROR;
	} // close()

	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////

	/**
	 *	deprecated functions section. still operational, but better not be used
	 */

	/**
	 *
	 */
	function picture( $pict, &$text ) {
		if( $this->status != sCCC_PICTURE )
			return ccERR_STATUS;

		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );

		$pack->setCmd( cmdCC_PICTURE );
		$pack->setSize( strlen( $pict ) );
		$pack->setData( $pict );

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		if( $pack->unpackFrom( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}
		
		switch( $pack->getCmd() ) {
			case cmdCC_TEXT:
				$text = $pack->getData();
				return ccERR_OK;

			case cmdCC_BALANCE:
				// balance depleted
				return ccERR_BALANCE;
			
			case cmdCC_OVERLOAD:
				// server's busy
				return ccERR_OVERLOAD;
			
			case cmdCC_TIMEOUT:
				// picture timed out
				return ccERR_TIMEOUT;
			
			case cmdCC_FAILED:
				// server's error
				return ccERR_GENERAL;
			
			default:
				// unknown error
				return ccERR_UNKNOWN;
		}
	} // picture()

	/**
	 *
	 */
	function picture_bad() {
		$pack = new cc_packet();
		$pack->setVer( CC_PROTO_VER );

		$pack->setCmd( cmdCC_FAILED );
		$pack->setSize( 0 );

		if( $pack->packTo( $this->s ) === FALSE ) {
			return ccERR_NET_ERROR;
		}

		return ccERR_NET_ERROR;
	} // picture_bad()

}

?>