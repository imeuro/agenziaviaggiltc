<?php
error_reporting(0);
$wpmailup = unserialize(get_option('wpmailup'));
/* exit('<pre>' . print_r($wpmailup, true) . '</pre>'); */
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('placeholder', '//jamesallardice.github.io/Placeholders.js/assets/js/placeholders.min.js');
wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
$text_field_size = 22;
$text_field_maxlength = 80;

if(!function_exists("wpml_translate")){
	function wpml_translate($string, $position){
		# Output
		//WMPL
		/**
		 * retreive translations
		 */
		if (function_exists ( 'icl_translate' )){
			return icl_translate('WP-Mailup', $position, $string);
		}
		//\WMPL
		else{
			return $string;
		}
	}
}
?>
<style type="text/css">

	fieldset.subscribeDataTable label {
		display:block;
		margin-bottom:3px;
	}

	<?php
        switch($wpmailup['cssCombination']){
            case 'style1':
                require(dirname(__FILE__).'/style1.css');
                break;
            case 'style2':
                require(dirname(__FILE__).'/style2.css');
                break;
            case 'style3':
                require(dirname(__FILE__).'/style3.css');
                break;
            case 'style4':
                require(dirname(__FILE__).'/style4.css');
                break;
            case 'style5':
                require(dirname(__FILE__).'/style5.css');
                break;
            case 'style6':
                require(dirname(__FILE__).'/style6.css');
                break;
            case 'style7':
                require(dirname(__FILE__).'/style7.css');
                break;
            default:
                require(dirname(__FILE__).'/default.css');
                break;
        }
    ?>

</style>
<?php /* if($wpmailup['externalCss']): ?>
<link rel="stylesheet" href="<?php echo $wpmailup['externalCss']; ?>" />


<?php endif; */ ?>
<form action="" method="get" name="subscribeForm" class="subscribeForm" onsubmit="return false;">
	<input type="hidden" name="wpmailup-subscribe" class="wpmailup-subscribe" value="subscribe" />
	<fieldset class="subscribeDataTable">
		<?php
		if ( $wpmailup['pluginTitle'] ) {
			echo $before_title . wpml_translate($wpmailup['pluginTitle'], 'pTitle') . $after_title;
		}
		?>
		<p class="muDescription"><?php echo wpml_translate($wpmailup['pluginDescription'], 'pDescription'); ?></p>

		<?php if($wpmailup['emailShow'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['emailDisplayedName'], 'emailName'); ?>:
						<?php if($wpmailup['emailRequired'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-email" class="sub-email" />
				<?php else: ?>
					<input type="text" name="sub-email" class="sub-email" placeholder="<?php echo wpml_translate($wpmailup['emailDisplayedName'], 'emailName'); if($wpmailup['emailRequired'] == 'yes'): echo '*'; endif;?>" />
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if($wpmailup['mobileShow'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['mobileDisplayedName'], 'mobileName'); ?>:
						<?php if($wpmailup['mobileRequired'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-phone" class="sub-phone" maxlength="<?php echo $text_field_maxlength; ?>" />
				<?php else: ?>
					<input type="text" name="sub-phone" class="sub-phone" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['mobileDisplayedName'], 'mobileName'); if($wpmailup['mobileRequired'] == 'yes'): echo '*'; endif; ?>" />
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if($wpmailup['extfield1Show'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['extfield1DisplayedName'], 'field1Name'); ?>:
						<?php if($wpmailup['extfield1Required'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-ext1" class="sub-ext1" maxlength="<?php echo $text_field_maxlength; ?>" />
				<?php else: ?>
					<input type="text" name="sub-ext1" class="sub-ext1" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['extfield1DisplayedName'], 'field1Name'); if($wpmailup['extfield1Required'] == 'yes'): echo '*'; endif; ?>" />
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if($wpmailup['extfield2Show'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['extfield2DisplayedName'], 'field2Name'); ?>:
						<?php if($wpmailup['extfield2Required'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-ext2" class="sub-ext2" maxlength="<?php echo $text_field_maxlength; ?>" />
				<?php else: ?>
					<input type="text" name="sub-ext2" class="sub-ext2" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['extfield2DisplayedName'], 'field2Name'); if($wpmailup['extfield2Required'] == 'yes'): echo '*'; endif; ?>" />
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if($wpmailup['extfield3Show'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['extfield3DisplayedName'], 'field3Name'); ?>:
						<?php if($wpmailup['extfield3Required'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-ext3" class="sub-ext3" maxlength="<?php echo $text_field_maxlength; ?>" />
				<?php else: ?>
					<input type="text" name="sub-ext3" class="sub-ext3" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['extfield3DisplayedName'], 'field3Name'); if($wpmailup['extfield3Required'] == 'yes'): echo '*'; endif; ?>" />
				<?php endif; ?>

			</p>
		<?php endif; ?>

		<?php if($wpmailup['extfield4Show'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['extfield4DisplayedName'], 'field4Name'); ?>:
						<?php if($wpmailup['extfield4Required'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-ext4" class="sub-ext4" maxlength="<?php echo $text_field_maxlength; ?>" />
				<?php else: ?>
					<input type="text" name="sub-ext4" class="sub-ext4" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['extfield4DisplayedName'], 'field4Name'); if($wpmailup['extfield4Required'] == 'yes'): echo '*'; endif; ?>" />
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if($wpmailup['extfield5Show'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['extfield5DisplayedName'], 'field5Name'); ?>:
						<?php if($wpmailup['extfield5Required'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input type="text" name="sub-ext5" class="sub-ext5" maxlength="<?php echo $text_field_maxlength; ?>" />
				<?php else: ?>
					<input type="text" name="sub-ext5" class="sub-ext5" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['extfield5DisplayedName'], 'field5Name'); if($wpmailup['extfield5Required'] == 'yes'): echo '*'; endif; ?>" />
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if($wpmailup['dateShow'] == 'yes'): ?>
			<p class="muField">
				<?php if($wpmailup['textInside'] != 'yes'): ?>
					<label>
						<?php echo wpml_translate($wpmailup['dateDisplayedName'], 'dateName'); ?>:
						<?php if($wpmailup['dateRequired'] == 'yes'): ?>
							<span style="color:#FF0000;">*</span>
						<?php endif; ?>
					</label>
					<input  type="text" name="sub-date" class="sub-date" maxlength="<?php echo $text_field_maxlength;?>">
				<?php else: ?>
					<input  type="text" name="sub-date" class="sub-date" maxlength="<?php echo $text_field_maxlength; ?>" placeholder="<?php echo wpml_translate($wpmailup['dateDisplayedName'], 'dateName'); if($wpmailup['dateRequired'] == 'yes'): echo '*'; endif; ?>" >
				<?php endif; ?>
			</p>
		<?php endif; ?>


		<?php if($wpmailup['termsConfirm'] == 'yes'): ?>
			<p class="muTerms">
				<?php echo wpml_translate(stripslashes($wpmailup['termsNcon']), 'terms-n-con'); ?>
			<p>
			<p class="muTermsCheckbox">
				<label><input name="terms-confirm" class="terms-confirm" type="checkbox" value="yes" /> <?php echo wpml_translate($wpmailup['acceptanceMsg'], 'acceptance-msg'); ?></label>
			</p>
		<?php endif; ?>
		<?php if($wpmailup['terms2Confirm'] == 'yes'): ?>
			<p class="muTerms2">
				<?php echo wpml_translate(stripslashes($wpmailup['terms2Ncon']), 'terms2-n-con'); ?>
			<p>
			<p class="muTerms2Checkbox">
				<label><input name="terms2-confirm" class="terms2-confirm" type="checkbox" value="yes" /> <?php echo wpml_translate($wpmailup['acceptance2Msg'], 'acceptance2-msg'); ?></label>
			</p>
		<?php endif; ?>
		
		<img class="loading-img" style="display:none;vertical-align:middle;background:none;padding: 5px 3px;" src="<?php echo WP_PLUGIN_URL . '/wp-mailup/images/indicator.white.gif'; ?>" border="0" /><span class="show-response"><noscript><?php _e('Please enable javascript to work with this subscription form.'); ?></noscript></span><p class="muSubmit"><input type="submit" name="submit" value="<?php echo wpml_translate($wpmailup['submitButton'], 'sButton'); ?>" /></p>
	</fieldset>
</form>
<script type="text/javascript">

	var jQ = jQuery.noConflict();

	jQ(document).ready(function(){

		<?php if($wpmailup['dateShow'] == 'yes'): ?>
		jQ(".sub-date").datepicker();
		<?php endif; ?>

		function viewInfoIcon(status, form)
		{
			switch(String(status))
			{
				case 'loading':
					form.find('.loading-img').attr('src', '<?php echo WP_PLUGIN_URL . '/wp-mailup/images/indicator.white.gif'; ?>');
					form.find('.loading-img').css('display', '');
					break;
				case 'info':
					form.find('.loading-img').attr('src', '<?php echo WP_PLUGIN_URL . '/wp-mailup/images/question.gif'; ?>');
					form.find('.loading-img').css('display', '');
					break;
				default:
			}
		}

		jQ("body").on("submit", "form.subscribeForm", function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			var selectedForm = jQuery(this);
			var token = selectedForm.find('.wpmailup-subscribe').val();
			var sub_email = selectedForm.find('.sub-email').val();
			var sub_phone = selectedForm.find('.sub-phone').val();

			var sub_date = '';
			<?php if($wpmailup['dateShow'] == 'yes'): ?>
			sub_date = selectedForm.find('.sub-date').val();
			<?php endif; ?>

			var sub_ext1 = '';
			<?php if($wpmailup['extfield1Show'] == 'yes'): ?>
			sub_ext1 = selectedForm.find('.sub-ext1').val();
			<?php endif; ?>

			var sub_ext2 = '';
			<?php if($wpmailup['extfield2Show'] == 'yes'): ?>
			sub_ext2 = selectedForm.find('.sub-ext2').val();
			<?php endif; ?>

			var sub_ext3 = '';
			<?php if($wpmailup['extfield3Show'] == 'yes'): ?>
			sub_ext3 = selectedForm.find('.sub-ext3').val();
			<?php endif; ?>

			var sub_ext4 = '';
			<?php if($wpmailup['extfield4Show'] == 'yes'): ?>
			sub_ext4 = selectedForm.find('.sub-ext4').val();
			<?php endif; ?>

			var sub_ext5 = '';
			<?php if($wpmailup['extfield5Show'] == 'yes'): ?>
			sub_ext5 = selectedForm.find('.sub-ext5').val();
			<?php endif; ?>

			var csvFldValues = '';
			<?php
			if($wpmailup['dateShow']):
			$csvFldNames = $wpmailup['dateFieldcode']; ?>
			csvFldValues = sub_date;
			<?php
			endif;
			if($wpmailup['extfield1Show']):
			if(empty($csvFldNames)):
			$csvFldNames = $wpmailup['extfield1Fieldcode']; ?>
			csvFldValues = sub_ext1;
			<?php
			else:
			$csvFldNames = $csvFldNames . ";" . $wpmailup['extfield1Fieldcode']; ?>
			csvFldValues = csvFldValues +';' +sub_ext1;
			<?php
			endif;
			endif;
			if($wpmailup['extfield2Show']):
			if(empty($csvFldNames)):
			$csvFldNames = $wpmailup['extfield2Fieldcode']; ?>
			csvFldValues = sub_ext2;
			<?php
			else:
			$csvFldNames = $csvFldNames . ";" . $wpmailup['extfield2Fieldcode']; ?>
			csvFldValues = csvFldValues +';' +sub_ext2;
			<?php
			endif;
			endif;
			if($wpmailup['extfield3Show']):
			if(empty($csvFldNames)):
			$csvFldNames = $wpmailup['extfield3Fieldcode']; ?>
			csvFldValues = sub_ext3;
			<?php
			else:
			$csvFldNames = $csvFldNames . ";" . $wpmailup['extfield3Fieldcode'];  ?>
			csvFldValues = csvFldValues +';' +sub_ext3;
			<?php
			endif;
			endif;
			if($wpmailup['extfield4Show']):
			if(empty($csvFldNames)):
			$csvFldNames = $wpmailup['extfield4Fieldcode']; ?>
			csvFldValues = sub_ext4;
			<?php
			else:
			$csvFldNames = $csvFldNames . ";" . $wpmailup['extfield4Fieldcode']; ?>
			csvFldValues = csvFldValues +';' +sub_ext4;
			<?php
			endif;
			endif;
			if($wpmailup['extfield5Show']):
			if(empty($csvFldNames)):
			$csvFldNames = $wpmailup['extfield5Fieldcode']; ?>
			csvFldValues = sub_ext5;
			<?php
			else:
			$csvFldNames = $csvFldNames . ";" . $wpmailup['extfield5Fieldcode']; ?>
			csvFldValues = csvFldValues +';' +sub_ext5;
			<?php
			endif;
			endif;
			?>
			var csvFldNames = '<?php echo $csvFldNames; ?>';

			//var csvFldValues = '';
			/*if(sub_ext1 && sub_ext2)
			 {*/
			//csvFldValues = sub_ext1 + ';' + sub_ext2 + ';' + sub_ext3 + ';' + sub_ext4 + ';' + sub_ext5;
			/*}
			 else if(sub_ext1)
			 {
			 csvFldValues = sub_ext1;
			 }
			 else
			 {
			 csvFldValues = sub_ext2;
			 }*/


			var listId = '<?php echo $wpmailup['listId']; ?>';
			var groupId = '<?php echo $wpmailup['groupId']; ?>';
			var confirmReq = '<?php echo ($wpmailup['requestConfirm'] == 'yes')?'true':'false'; ?>';
			var subUrl = '<?php echo $wpmailup['consoleHost'] . $wpmailup['subscribePath']. '/?source=generic'; ?>';

			/*
			 validate form
			 */
			<?php if(($wpmailup['emailRequired'] == 'yes') && ($wpmailup['emailShow'] == 'yes')): ?>


			if(!(sub_email.match(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,63})$/)))
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['invalidAddress']), 'invalid-address'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>
			<?php if(($wpmailup['mobileRequired'] == 'yes') && ($wpmailup['mobileShow'] == 'yes')): ?>
			//if(jQ.trim(sub_phone) == "")
			if((jQ.trim(sub_phone) == "")||(!(sub_phone.match((/^(\+?\-? *[0-9]+)([,0-9 ]*)([0-9 ]){6,20}$/)))))
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['invalidPhone']), 'invalid-phone'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			else
			{
				// REMOVE '+' AND SPACES, WHICH ARE NOT SUPPORTED BY XmlSubscribe.aspx
				sub_phone = sub_phone.replace("+","00");
				sub_phone = sub_phone.replace(/\s/g, '');
				sub_phone = sub_phone.replace("-","");
			}
			<?php endif; ?>

			<?php if(($wpmailup['dateShow'] == 'yes') && ($wpmailup['dateRequired'] == 'yes')): ?>
			if(jQ.trim(sub_date) == '')
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['dateDisplayedName']), 'dateName').' '.wpml_translate(addslashes($wpmailup['fieldRequired']), 'field-required'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>


			<?php if(($wpmailup['extfield1Show'] == 'yes') && ($wpmailup['extfield1Required'] == 'yes')): ?>
			if(jQ.trim(sub_ext1) == '')
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['extfield1DisplayedName']), 'field1Name').' '.wpml_translate(addslashes($wpmailup['fieldRequired']), 'field-required'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>

			<?php if(($wpmailup['extfield2Show'] == 'yes') && ($wpmailup['extfield2Required'] == 'yes')): ?>
			if(jQ.trim(sub_ext2) == '')
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['extfield2DisplayedName']), 'field2Name').' '.wpml_translate(addslashes($wpmailup['fieldRequired']), 'field-required'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>

			<?php if(($wpmailup['extfield3Show'] == 'yes') && ($wpmailup['extfield3Required'] == 'yes')): ?>
			if(jQ.trim(sub_ext3) == '')
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['extfield3DisplayedName']), 'field3Name').' '.wpml_translate(addslashes($wpmailup['fieldRequired']), 'field-required'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>

			<?php if(($wpmailup['extfield4Show'] == 'yes') && ($wpmailup['extfield4Required'] == 'yes')): ?>
			if(jQ.trim(sub_ext4) == '')
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['extfield4DisplayedName']), 'field4Name').' '.wpml_translate(addslashes($wpmailup['fieldRequired']), 'field-required'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>

			<?php if(($wpmailup['extfield5Show'] == 'yes') && ($wpmailup['extfield5Required'] == 'yes')): ?>
			if(jQ.trim(sub_ext5) == '')
			{
				selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['extfield5DisplayedName']), 'field5Name').' '.wpml_translate(addslashes($wpmailup['fieldRequired']), 'field-required'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			<?php endif; ?>

			/*
			 Check terms and conditions have been checked
			 */
			var termsAccept = '';
			<?php if($wpmailup['termsConfirm'] == 'yes'): ?>
			if(jQ('.terms-confirm').is(':checked') == false)
			{
				selectedForm.find('.show-response').html('<?php  echo wpml_translate(addslashes($wpmailup['termsNotAgreed']), 'terms-not-agreed'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			else
			{
				termsAccept = jQ('.terms-confirm').val();
			}
			<?php else: ?>
			termsAccept = 'yes';
			<?php endif; ?>
			/*
			 Check other terms and conditions have been checked
			 */
			var terms2Accept = '';
			<?php if($wpmailup['terms2Confirm'] == 'yes'): ?>
			if(jQ('.terms2-confirm').is(':checked') == false)
			{
				selectedForm.find('.show-response').html('<?php  echo wpml_translate(addslashes($wpmailup['terms2NotAgreed']), 'terms2-not-agreed'); ?>');
				viewInfoIcon('info', selectedForm);
				return false;
			}
			else
			{
				terms2Accept = jQ('.terms2-confirm').val();
			}
			<?php else: ?>
			terms2Accept = 'yes';
			<?php endif; ?>

			var form_values = {
				"Email":sub_email,
				"List":listId,
				"sms":sub_phone,
				"Group":groupId,
				"Confirm":confirmReq,
				"csvFldNames":csvFldNames,
				"csvFldValues":csvFldValues,
				"retCode":"1",
				"token":token,
				"subsUrl":subUrl,
				"termsAccept":termsAccept,
				"terms2Accept":terms2Accept
			}

			selectedForm.find('.loading-img').css('display', '');
			viewInfoIcon('loading', selectedForm);
			selectedForm.find('.show-response').html('<?php _e('Sending request...'); ?>');
			jQ.post('<?php echo plugin_dir_url(__FILE__) . 'subscribe.php'; ?>', form_values, function(returned_data){
				switch(Number(returned_data))
				{
					case 0:
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['successMessage']), 'success-message'); ?>');
						break;
					case 1:
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['genericError']), 'generic-error'); ?>');
						break;
					case 2:
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['invalidAddress']), 'invalid-address'); ?>');
						break;
					case 3:
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['alreadyPresent']), 'already-present'); ?>');
						break;
					case 10:
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['termsNotAgreed']), 'terms-not-agreed'); ?>');
						break;
					case 11:
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['terms2NotAgreed']), 'terms2-not-agreed'); ?>');
						break;	
					case -1011:
						/* here generic message is displayed instead of a detailed message, which can be misleading for subscribers */
						selectedForm.find('.show-response').html('<?php echo wpml_translate(addslashes($wpmailup['genericError']), 'generic-error'); ?>');
						/*jQ('.show-response').html('<?php echo 'IP address validation is required. Please check this <a href="http://help.mailup.com/display/mailupUserGuide/WordPress#WordPress-authorizing" target="_blank">page</a>'; ?>');*/

						break;
					default:
						break;
				}
				viewInfoIcon('info', selectedForm);
			});
			return false;
		});
	});

</script>
