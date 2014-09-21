<?php
require_once 'smppclient.class.php';
require_once 'gsmencoder.class.php';
require_once 'sockettransport.class.php';

// Construct transport and client
$transport = new SocketTransport(array('127.0.0.1'),2775);
$transport->setRecvTimeout(10000);
$smpp = new SmppClient($transport);

// Activate binary hex-output of server interaction
$smpp->debug = true;
$transport->debug = true;

// Open the connection
$transport->open();
$smpp->bindTransmitter("test","pass");

// Optional connection specific overrides
//SmppClient::$sms_null_terminate_octetstrings = false;
//SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
//SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;

// Prepare message
$from = new SmppAddress('Demo',SMPP::TON_ALPHANUMERIC);
$to = new SmppAddress(919942012345,SMPP::TON_INTERNATIONAL,SMPP::NPI_E164);
for($i=0;$i<10;$i++) {
  $message = 'Demo Text ABC '.$i;
  $encodedMessage = GsmEncoder::utf8_to_gsm0338($message);

  // Send
  $msgid = $smpp->sendSMS($from,$to,$encodedMessage,$tags);
  print 'message ref id: '.$msgid;
}

// Close connection
$smpp->close();
