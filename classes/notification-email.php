<?php
/**
 * Class for our email notification type.
 *
 * @package     Ninja Forms
 * @subpackage  Classes/Notifications
 * @copyright   Copyright (c) 2014, WPNINJAS
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.8
*/

class NF_Notification_Email extends NF_Notification_Base_Type
{

	/**
	 * Get things rolling
	 */
	function __construct() {

	}

	/**
	 * Output our edit screen
	 * 
	 * @access public
	 * @since 2.8
	 * @return void
	 */
	public function edit_screen( $id = '' ) {
		$form_id = ( '' != $id ) ? Ninja_Forms()->notification( $id )->form_id : '';

		if ( $id == '' ) {
			$email_format = 'html';
			$from_name = '';
			$from_address = '';
			$reply_to = '';
			$to = '';
			$cc = '';
			$bcc = '';
			$email_subject = '';
			$email_message = '';
			$attach_csv = '';
		} else {
			$email_format = Ninja_Forms()->notification( $id )->get_setting( 'email_format' );
			$from_name = Ninja_Forms()->notification( $id )->get_setting( 'from_name' );
			$from_address = Ninja_Forms()->notification( $id )->get_setting( 'from_address' );
			$reply_to = Ninja_Forms()->notification( $id )->get_setting( 'reply_to' );
			$to = Ninja_Forms()->notification( $id )->get_setting( 'to' );
			$cc = Ninja_Forms()->notification( $id )->get_setting( 'cc' );
			$bcc = Ninja_Forms()->notification( $id )->get_setting( 'bcc' );
			$email_subject = Ninja_Forms()->notification( $id )->get_setting( 'email_subject' );
			$email_message = Ninja_Forms()->notification( $id )->get_setting( 'email_message' );
			$attach_csv = Ninja_Forms()->notification( $id )->get_setting( 'attach_csv' );			
		}

		
		?>
		<tr>
			<th scope="row"><label for="settings-email_format"><?php _e( 'Format', 'ninja-forms' ); ?></label></th>
			<td>
				<select name="settings[email_format]" id="settings-email_format" data-key="email_format"/>
					<option value="html" <?php selected( $email_format, 'html' ); ?>><?php _e( 'HTML', 'ninja-forms' ); ?></option>
					<option value="plain" <?php selected( $email_format, 'plain' ); ?>><?php _e( 'Plain Text', 'ninja-forms' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-attach_csv"><?php _e( 'Attach CSV', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[attach_csv]" type="hidden" value="0">
				<input name="settings[attach_csv]" type="checkbox" id="settings-attach_csv" value="1" <?php checked( $attach_csv, 1 ); ?>/>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-from_name"><?php _e( 'From Name', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[from_name]" type="text" id="settings-from_name" value="<?php echo $from_name; ?>" class="nf-tokenize" placeholder="Name or fields" data-token-limit="0" data-key="from_name" data-type="name" />
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="settings-from_address"><?php _e( 'From Address', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[from_address]" type="text" id="settings-from_address" value="<?php echo $from_address; ?>" class="nf-tokenize" placeholder="One email address or field" data-token-limit="1" data-key="from_address" data-type="email" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-reply_to"><?php _e( 'Reply To', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[reply_to]" type="text" id="settings-reply_to" value="<?php echo $reply_to; ?>" class="nf-tokenize" placeholder="One email address or field" data-token-limit="1" data-key="reply_to" data-type="email" />
			</td>
		</tr>			
		<tr>
			<th scope="row"><label for="settings-to"><?php _e( 'To', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[to]" type="text" id="settings-to" value="<?php echo $to; ?>" class="nf-tokenize" placeholder="Email addresses or search for a field" data-token-limit="0" data-key="to" data-type="email" />
			</td>
		</tr>		
		<tr>
			<th scope="row"><label for="settings-cc"><?php _e( 'Cc', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[cc]" type="text" id="settings-cc" value="<?php echo $cc; ?>" class="nf-tokenize" placeholder="Email addresses or search for a field" data-token-limit="0" data-key="cc" data-type="email" />
			</td>
		</tr>		
		<tr>
			<th scope="row"><label for="settings-bcc"><?php _e( 'Bcc', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[bcc]" type="text" id="settings-bcc" value="<?php echo $bcc; ?>" class="nf-tokenize" placeholder="Email addresses or search for a field" data-token-limit="0" data-key="bcc" data-type="email" />
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="settings-email_subject"><?php _e( 'Subject', 'ninja-forms' ); ?></label></th>
			<td>
				<input name="settings[email_subject]" type="text" id="settings-email_subject" value="<?php echo $email_subject; ?>" class="nf-tokenize" placeholder="Subject Text or search for a field" data-token-limit="0" data-key="email_subject" data-type="all" />
			</td>
		</tr>		
		<tr>
			<th scope="row"><label for="settings-email_message"><?php _e( 'Email Message', 'ninja-forms' ); ?></label></th>
			<td>
				<?php
				$settings = array(
					'textarea_name' => 'settings[email_message]',
				);
				wp_editor( $email_message, 'email_message', $settings ); 
				?>
			</td>
		</tr>

		<?php

		do_action( 'nf_email_notification_after_settings', $id );

		if ( '' != $form_id ) {
			$from_name = $this->get_value( $id, 'from_name', $form_id );
			$from_address = $this->get_value( $id, 'from_address', $form_id );
			$reply_to = $this->get_value( $id, 'reply_to', $form_id );
			$to = $this->get_value( $id, 'to', $form_id );
			$cc = $this->get_value( $id, 'cc', $form_id );
			$bcc = $this->get_value( $id, 'bcc', $form_id );			
			$email_subject = $this->get_value( $id, 'email_subject', $form_id );			
		} else {
			$from_name = '';
			$from_address = '';
			$reply_to = '';
			$to = '';
			$cc = '';
			$bcc = '';
			$email_subject = '';
		}

		?>

		<script type="text/javascript">
				nf_notifications.tokens['from_name'] = <?php echo json_encode( $from_name ); ?>;
				nf_notifications.tokens['from_address'] = <?php echo json_encode( $from_address ); ?>;
				nf_notifications.tokens['reply_to'] = <?php echo json_encode( $reply_to ); ?>;
				nf_notifications.tokens['to'] = <?php echo json_encode( $to ); ?>;
				nf_notifications.tokens['cc'] = <?php echo json_encode( $cc ); ?>;
				nf_notifications.tokens['bcc'] = <?php echo json_encode( $bcc ); ?>;
				nf_notifications.tokens['email_subject'] = <?php echo json_encode( $email_subject ); ?>;
		</script>

		<?php
	}

	/**
	 * Get our input value labels
	 * 
	 * @access public
	 * @since 2.8
	 * @return string $label
	 */
	public function get_value( $id, $meta_key, $form_id ) {
		$meta_value = nf_get_object_meta_value( $id, $meta_key );
		$meta_value = explode( '`', $meta_value );

		$return = array();
		foreach( $meta_value as $val ) {
			if ( is_numeric( $val ) ) {
				$label = nf_get_field_admin_label( $val, $form_id );
				if ( strlen( $label ) > 30 ) {
					$label = substr( $label, 0, 30 );
				}

				$return[] = array( 'value' => $val, 'label' => $label . ' - ID: ' . $val );
			} else {
				$return[] = array( 'value' => $val, 'label' => $val );
			}
		}

		return $return;
	}

	/**
	 * Process our Email notification
	 * 
	 * @access public
	 * @since 2.8
	 * @return void
	 */
	public function process( $id ) {
		global $ninja_forms_processing;

		$form_id 		= $ninja_forms_processing->get_form_ID();
		$form_title 	= $ninja_forms_processing->get_form_setting( 'form_title' );

		$email_format 	= Ninja_Forms()->notification( $id )->get_setting( 'email_format' );
		$attach_csv 	= Ninja_Forms()->notification( $id )->get_setting( 'attach_csv' );

		$from_name 		= $this->process_setting( $id, 'from_name' );
		$from_name 		= implode( ' ', $from_name );

		$from_address 	= $this->process_setting( $id, 'from_address' );
		$from_address 	= $from_address[0];

		$reply_to 		= $this->process_setting( $id, 'reply_to' );
		$reply_to 		= $reply_to[0];		
		$to 			= $this->process_setting( $id, 'to' );
		$cc 			= $this->process_setting( $id, 'cc' );
		$bcc 			= $this->process_setting( $id, 'bcc' );

		$email_from 	= $from_name.' <'.$from_address.'>';

		$subject 		= $this->process_setting( $id, 'email_subject' );
		$subject 		= implode( '', $subject );
		if ( empty( $subject ) ) {
			$subject = $form_title;
		}

		$message 		= $this->process_setting( $id, 'email_message' );
		$message 		= $message[0];

		if ( $email_format != 'plain' ) {
			$message = apply_filters( 'ninja_forms_admin_email_message_wpautop', wpautop( $message ) );
		}

		$headers = array();
		$headers[] = 'From: ' . $email_from;

		if( ! empty( $reply_to ) ) {
			$headers[] = 'Reply-To: ' . $reply_to;
		}
		$headers[] = 'Content-Type: text/' . $email_format;
		$headers[] = 'charset=utf-8';

		if ( ! empty( $cc ) ) {
			foreach ( $cc as $address ) {
				$headers[] = 'Cc: ' . $address;				
			}
		}

		if ( ! empty( $bcc ) ) {
			foreach ( $bcc as $address ) {
				$headers[] = 'Bcc: ' . $address;				
			}
		}

		$csv_attachment = '';
		$attachments = array();

		if ( $ninja_forms_processing->get_form_setting( 'email_attachments' ) ) {
			$attachments = $ninja_forms_processing->get_form_setting( 'email_attachments' );
		}

		// Check to see if we need to attach a CSV
		if ( 1 == $attach_csv ) {
			// Create our attachment

			// Get our submission ID
			$sub_id = $ninja_forms_processing->get_form_setting( 'sub_id' );

			// create CSV content
			$csv_content = Ninja_Forms()->sub( $sub_id )->export( true );
			
			$upload_dir = wp_upload_dir();
			$path = trailingslashit( $upload_dir['path'] );

			// create temporary file
			$path = tempnam( $path, 'Sub' );
			$temp_file = fopen( $path, 'r+' );
			
			// write to temp file
			fwrite( $temp_file, $csv_content );
			fclose( $temp_file );
			
			// find the directory we will be using for the final file
			$path = pathinfo( $path );
			$dir = $path['dirname'];
			$basename = $path['basename'];
			
			// create name for file
			$new_name = apply_filters( 'ninja_forms_submission_csv_name', 'ninja-forms-submission' );
			
			// remove a file if it already exists
			if( file_exists( $dir.'/'.$new_name.'.csv' ) ) {
				unlink( $dir.'/'.$new_name.'.csv' );
			}
			
			// move file
			rename( $dir.'/'.$basename, $dir.'/'.$new_name.'.csv' );
			$csv_attachment = $dir.'/'.$new_name.'.csv';

			$attachments[] = $csv_attachment;
		}

		if ( is_array( $to ) AND !empty( $to ) ){
			foreach( $to as $to ) {
				wp_mail( $to, $subject, $message, $headers, $attachments );
			}
		}

		// Delete our admin CSV if one is present.
		if ( file_exists( $csv_attachment ) ) {
			//unlink ( $csv_attachment );
		}

	}

	/**
	 * Explode our settings by ` and extract each value.
	 * Check to see if the setting is a field; if it is, assign the value.
	 * Run shortcodes and return the result.
	 * 
	 * @access public
	 * @since 2.8
	 * @return array $setting
	 */
	public function process_setting( $id, $setting ) {
		global $ninja_forms_processing;

		$setting = explode( '`', Ninja_Forms()->notification( $id )->get_setting( $setting ) );

		for ( $x = 0; $x <= count ( $setting ) - 1; $x++ ) { 
			if ( $ninja_forms_processing->get_field_value( $setting[ $x ] ) ) {
				$setting[ $x ] = $ninja_forms_processing->get_field_value( $setting[ $x ] );
			}
			$setting[ $x ] = do_shortcode( $setting[ $x ] );
		}

		return $setting;
	}

}