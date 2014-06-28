<?php
class SwiftMailerHooks {
	public static function UseSwiftMailer ( $headers, $to, $from, $subject, $body ) {
		$message = Swift_Message::newInstance()
				->setSubject( $subject )
				->setFrom( array( $from->address => $from->name ) )
				->setBody( $body );

		$returnPath = $headers['Return-Path'];
		//$message->setReturnPath( $returnPath );

		$transport = self::getSwiftMailer();
		// Create the SwiftMailer::Mailer Object using the above Transport
		$mailer = Swift_Mailer::newInstance( $transport );

		wfDebug( "Sending mail via Swift::Mail\n" );

		foreach ( $to as $recip ) {
			$message->setTo( array( $recip->address => $recip->name) );
			$status = self::sendWithSwift( $mailer, $message );
			if ( !$status->isOK() ) {
				wfDebug( " Error sending mail ");
				return $status;
			}
		}
		return false;
	}

	/**
	 * @return Status|Swift_MailTransport|Swift_SmtpTransport
	 */

	protected static function getSwiftMailer() {
		global $wgSMTP;
		try {
			if ( is_array( $wgSMTP ) ) {
				// Create the Transport with Swift_Message::newInstance() method
				$transport = Swift_SmtpTransport::newInstance( $wgSMTP['host'], $wgSMTP['port'] )
						->setUsername( $wgSMTP['username'] )
						->setPassword( $wgSMTP['password'] );
			} else {
				$transport = Swift_MailTransport::newInstance();
			}
		} catch ( Swift_TransportException $e ) {
			wfDebug( "SWIFT::Mail SMTP configuration failed \n" );
			return Status::newFatal( 'swift-mail-error', $e );
		}
		return $transport;
	}

	/**
	 * @param $mailer
	 * @param $message
	 * @return Status
	 */

	protected static function sendWithSwift( $mailer, $message ) {
		//Create the SwiftMailer message object
		try {
			$mailResult = $mailer->send( $message );
		} catch ( Swit_SwiftException $e ) {
			wfDebug( "Swift Mailer failed: ". $e. "\n" );
			return Status::newFatal( 'swift-mail-error', $e );
		}
		return Status::newGood();
	}


}