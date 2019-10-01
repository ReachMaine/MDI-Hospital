<?php
/* code for bill pay s.t. wordpress stops stripping stuff out. */
add_shortcode('mdih_billpay', 'mdih_billpay_form');

function mdih_billpay_form() {

	     $form = '<form class="mdih-billpay-form" action="https://hosted.transactionexpress.com/Transaction/Transaction/Index" method="POST" target="_blank" oninput="CustRefID.value = CustName.value.toUpperCase() + 	&#39;/INV#&#39; + CustInvoice.value">';
				//$form .= '<form class="mdih-billpay-form" action="https://hosted.transactionexpress.com/Transaction/Transaction/Index" method="POST" target="_blank">';
				$form .= '<input id="HostedKey" name="HostedKey" type="hidden" value="f1dc2cf8-a28b-47ce-8133-d379430bc096" />';
				$form .= '<input id="Gateway_ID" name="Gateway_ID" type="hidden" value="9007813296" /><input id="IndustryCode" name="IndustryCode" type="hidden" value="2" />';
	      $form .= '<input id="RecurringType" name="RecurringType" type="hidden" value="N" />';
				$form .= '<input id="RecurringAmount" name="RecurringAmount" type="hidden" value="" />';
				$form .= '<input id="RURL" name="RURL" type="hidden" value="https://www.mdihospital.org/thanks/" />';
				$form .= '<input id="CURL" name="CURL" type="hidden" value="https://www.mdihospital.org/insurance-and-billing/" />';
				$form .= '<input id="AVSRequired" name="AVSRequired" type="hidden" value="Y" />';
				$form .= '<input id="CVV2Required" name="CVV2Required" type="hidden" value="Y" /><input id="EmailRequired" name="EmailRequired" type="hidden" value="Y" /><input id="PostRspMsg" name="PostRspMsg" type="hidden" value="N" />';
				$form .= '<input id="SECCode" name="SECCode" type="hidden" value="2" />';
				$form .= '<input id="Descriptor" name="Descriptor" type="hidden" value="Doctor" />';
				$form .= '<input name="CustRefID" type="hidden" />';
				// visible form fields
	      $form .= '<div class="mdih-billpay-field"><label>Patient Name:</label><input class="paynow" maxlength="25" name="CustName" required="" type="text" value="" /></div>';
				$form .= '<div class="mdih-billpay-field"><label>Account Number:</label><input class="paynow" maxlength="15" name="CustInvoice" required="" type="text" value="" /></div>';
				$form .= '<div class="mdih-billpay-field"><label>Your Email: </label><input id="Email" name="Email" required="" type="text" value="" placeholder="your@email.com" /></div>';
				$form .= '<div class="mdih-billpay-field"><label>Phone number:</label><input id="PhoneNumber" name="PhoneNumber" required="" type="text" value="" placeholder="123-123-1234" pattern = "^\d{3}-\d{3}-\d{4}$" />';
					$form .= '<div class="mdih-billpay-instr">Please use phone number with dashes like 207-288-5081.</div>';
				$form .= '</div>';
				$form .= '<div class="mdih-billpay-field"><label>Amount:</label> <input id="Amount" name="Amount" type="Text" value=""
pattern = "^\d{1,6}\.\d{2}?$"/>';
					$form .= '<div class="mdih-billpay-instr">Please include 2 decimal places in your payment amount.</div>';
				$form .= '</div>';
				$form .= '<input id="Submit" class="mdih-billpay-submit" name="Submit" type="submit" value="Pay Now" />';
			$form .= '</form>';
	    return $form;

}
