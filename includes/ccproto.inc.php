<?php

define(	'CC_PROTO_VER',			1		);		//	protocol version
define(	'CC_RAND_SIZE',			256		);		//	size of the random sequence for authentication procedure
define(	'CC_MAX_TEXT_SIZE',		100		);		//	maximum characters in returned text for picture
define(	'CC_MAX_LOGIN_SIZE',	100		);		//	maximum characters in login string
define(	'CC_MAX_PICTURE_SIZE',	200000	);		//	200 K bytes for picture seems sufficient for all purposes
define(	'CC_HASH_SIZE',			32		);

define( 'cmdCC_UNUSED',			0		);
define(	'cmdCC_LOGIN',			1		);		//	login
define(	'cmdCC_BYE',			2		);		//	end of session
define(	'cmdCC_RAND',			3		);		//	random data for making hash with login+password
define(	'cmdCC_HASH',			4		);		//	hash data
define(	'cmdCC_PICTURE',		5		);		//	picture data, deprecated
define(	'cmdCC_TEXT',			6		);		//	text data, deprecated
define(	'cmdCC_OK',				7		);		//
define(	'cmdCC_FAILED',			8		);		//
define(	'cmdCC_OVERLOAD',		9		);		//
define(	'cmdCC_BALANCE',		10		);		//	zero balance
define(	'cmdCC_TIMEOUT',		11		);		//	time out occured
define( 'cmdCC_PICTURE2',		12		);		//	picture data
define( 'cmdCC_PICTUREFL',		13		);		//	picture failure
define( 'cmdCC_TEXT2',			14		);		//	text data
define( 'cmdCC_SYSTEM_LOAD',		15		);		//	system load

define( 'SIZEOF_CC_PACKET',		6		);

define( 'SIZEOF_CC_PICT_DESCR',	20		);

require_once( 'api_consts.inc.php' );

/**
 *	packet class
 */
class cc_packet {

	var	$ver	= CC_PROTO_VER;	//	version of the protocol
	var	$cmd	= cmdCC_BYE;	//	command, see cc_cmd_t
	var	$size	= 0;			//	data size in consequent bytes 
	var	$data	= '';			//	packet payload

	/**
	 *
	 */
	function checkPackHdr( $cmd = NULL, $size = NULL ) {
		if( $this->ver != CC_PROTO_VER )
			return FALSE;
		if( isset( $cmd ) && ($this->cmd != $cmd) )
			return FALSE;
		if( isset( $size ) && ($this->size != $size) )
			return FALSE;

		return TRUE;
	}

	/**
	 *
	 */
	function pack() {
		return pack( 'CCV', $this->ver, $this->cmd, $this->size ) . $this->data;
	}

	/**
	 *
	 */
	function packTo( $handle ) {
		return fwrite( $handle, $this->pack(), SIZEOF_CC_PACKET + strlen( $this->data ) );
	}

	/**
	 *
	 */
	function unpackHeader( $bin ) {
		$arr = unpack( 'Cver/Ccmd/Vsize', $bin );
		$this->ver	= $arr['ver'];
		$this->cmd	= $arr['cmd'];
		$this->size	= $arr['size'];
	}

	/**
	 *
	 */
	function unpackFrom( $handle, $cmd = NULL, $size = NULL ) {
		if( ($bin = stream_get_contents( $handle, SIZEOF_CC_PACKET )) === FALSE ) {
			return FALSE;
		}
		$this->unpackHeader( $bin );

		if( $this->checkPackHdr( $cmd, $size ) === FALSE )
			return FALSE;

		if( $this->size > 0 ) {
			if( ($bin = stream_get_contents( $handle, $this->size )) === FALSE ) {
				return FALSE;
			}
			$this->data = $bin;
		}
		return TRUE;
	}

	/**
	 *
	 */
	function setVer( $ver ) {
		$this->ver = $ver;
	}

	/**
	 *
	 */
	function getVer() {
		return $this->ver;
	}

	/**
	 *
	 */
	function setCmd( $cmd ) {
		$this->cmd = $cmd;
	}

	/**
	 *
	 */
	function getCmd() {
		return $this->cmd;
	}

	/**
	 *
	 */
	function setSize( $size ) {
		$this->size = $size;
	}

	/**
	 *
	 */
	function getSize() {
		return $this->size;
	}

	/**
	 *
	 */
	function calcSize() {
		$this->size = strlen( $this->data );
		return $this->size;
	}

	/**
	 *
	 */
	function getFullSize() {
		return SIZEOF_CC_PACKET + $this->size;
	}

	/**
	 *
	 */
	function setData( $data ) {
		$this->data = $data;
	}

	/**
	 *
	 */
	function getData() {
		return $this->data;
	}
}

/**
 *	picture description class
 */
class cc_pict_descr {
	var	$timeout	= ptoDEFAULT;
	var	$type		= ptUNSPECIFIED;
	var	$size		= 0;
	var	$major_id	= 0;
	var	$minor_id	= 0;
	var $data		= NULL;

	/**
	 *
	 */
	function pack() {
		return pack( 'VVVVV', $this->timeout, $this->type, $this->size, $this->major_id, $this->minor_id ) . $this->data;
	}

	/**
	 *
	 */
	function unpack( $bin ) {
		$arr = unpack( 'Vtimeout/Vtype/Vsize/Vmajor_id/Vminor_id', $bin );
		$this->timeout	= $arr['timeout'];
		$this->type		= $arr['type'];
		$this->size		= $arr['size'];
		$this->major_id	= $arr['major_id'];
		$this->minor_id	= $arr['minor_id'];
		$this->data		= substr( $bin, SIZEOF_CC_PICT_DESCR );
	}

	/**
	 *
	 */
	function setTimeout( $to ) {
		$this->timeout = $to;
	}

	/**
	 *
	 */
	function getTimeout() {
		return $this->timeout;
	}

	/**
	 *
	 */
	function setType( $type ) {
		$this->type = $type;
	}

	/**
	 *
	 */
	function getType() {
		return $this->type;
	}

	/**
	 *
	 */
	function setSize( $size ) {
		$this->size = $size;
	}

	/**
	 *
	 */
	function getSize() {
		return $this->size;
	}

	/**
	 *
	 */
	function calcSize() {
		$this->size = strlen( $this->data );
		return $this->size;
	}

	/**
	 *
	 */
	function getFullSize() {
		return SIZEOF_CC_PICT_DESCR + $this->size;
	}

	/**
	 *
	 */
	function setMajorID( $major_id ) {
		$this->major_id = $major_id;
	}

	/**
	 *
	 */
	function getMajorID() {
		return $this->major_id;
	}

	/**
	 *
	 */
	function setMinorID( $minor_id ) {
		$this->minor_id = $minor_id;
	}

	/**
	 *
	 */
	function getMinorID() {
		return $this->minor_id;
	}

	/**
	 *
	 */
	function setData( $data ) {
		$this->data = $data;
	}

	/**
	 *
	 */
	function getData() {
		return $this->data;
	}
}
?>