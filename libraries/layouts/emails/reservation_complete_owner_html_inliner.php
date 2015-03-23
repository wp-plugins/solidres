<!-- Inliner Build Version 4380b7741bb759d6cb997545f3add21ad48f010b -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php /*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

$editLink = get_admin_url() . 'admin.php?page=sr-reservations&action=edit&id='.$displayData['reservation']->id;
$checkin = new DateTime($displayData['reservation']->checkin, $displayData['timezone']);
$checkout = new DateTime($displayData['reservation']->checkout, $displayData['timezone']);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<body style="width: 100% !important; min-width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0; padding: 0;"><style type="text/css">
a:hover {
	color: #2795b6 !important;
}
a:active {
	color: #2795b6 !important;
}
a:visited {
	color: #2ba6cb !important;
}
h1 a:active {
	color: #2ba6cb !important;
}
h2 a:active {
	color: #2ba6cb !important;
}
h3 a:active {
	color: #2ba6cb !important;
}
h4 a:active {
	color: #2ba6cb !important;
}
h5 a:active {
	color: #2ba6cb !important;
}
h6 a:active {
	color: #2ba6cb !important;
}
h1 a:visited {
	color: #2ba6cb !important;
}
h2 a:visited {
	color: #2ba6cb !important;
}
h3 a:visited {
	color: #2ba6cb !important;
}
h4 a:visited {
	color: #2ba6cb !important;
}
h5 a:visited {
	color: #2ba6cb !important;
}
h6 a:visited {
	color: #2ba6cb !important;
}
table.button:hover td {
	background: #2795b6 !important;
}
table.button:visited td {
	background: #2795b6 !important;
}
table.button:active td {
	background: #2795b6 !important;
}
table.button:hover td a {
	color: #fff !important;
}
table.button:visited td a {
	color: #fff !important;
}
table.button:active td a {
	color: #fff !important;
}
table.button:hover td {
	background: #2795b6 !important;
}
table.tiny-button:hover td {
	background: #2795b6 !important;
}
table.small-button:hover td {
	background: #2795b6 !important;
}
table.medium-button:hover td {
	background: #2795b6 !important;
}
table.large-button:hover td {
	background: #2795b6 !important;
}
table.button:hover td a {
	color: #ffffff !important;
}
table.button:active td a {
	color: #ffffff !important;
}
table.button td a:visited {
	color: #ffffff !important;
}
table.tiny-button:hover td a {
	color: #ffffff !important;
}
table.tiny-button:active td a {
	color: #ffffff !important;
}
table.tiny-button td a:visited {
	color: #ffffff !important;
}
table.small-button:hover td a {
	color: #ffffff !important;
}
table.small-button:active td a {
	color: #ffffff !important;
}
table.small-button td a:visited {
	color: #ffffff !important;
}
table.medium-button:hover td a {
	color: #ffffff !important;
}
table.medium-button:active td a {
	color: #ffffff !important;
}
table.medium-button td a:visited {
	color: #ffffff !important;
}
table.large-button:hover td a {
	color: #ffffff !important;
}
table.large-button:active td a {
	color: #ffffff !important;
}
table.large-button td a:visited {
	color: #ffffff !important;
}
table.secondary:hover td {
	background: #d0d0d0 !important; color: #555;
}
table.secondary:hover td a {
	color: #555 !important;
}
table.secondary td a:visited {
	color: #555 !important;
}
table.secondary:active td a {
	color: #555 !important;
}
table.success:hover td {
	background: #457a1a !important;
}
table.alert:hover td {
	background: #970b0e !important;
}
table.facebook:hover td {
	background: #2d4473 !important;
}
table.twitter:hover td {
	background: #0087bb !important;
}
table.google-plus:hover td {
	background: #CC0000 !important;
}
@media only screen and (max-width: 600px) {
	table[class="body"] img {
		width: auto !important; height: auto !important;
	}
	table[class="body"] center {
		min-width: 0 !important;
	}
	table[class="body"] .container {
		width: 95% !important;
	}
	table[class="body"] .row {
		width: 100% !important; display: block !important;
	}
	table[class="body"] .wrapper {
		display: block !important; padding-right: 0 !important;
	}
	table[class="body"] .columns {
		table-layout: fixed !important; float: none !important; width: 100% !important; padding-right: 0px !important; padding-left: 0px !important; display: block !important;
	}
	table[class="body"] .column {
		table-layout: fixed !important; float: none !important; width: 100% !important; padding-right: 0px !important; padding-left: 0px !important; display: block !important;
	}
	table[class="body"] .wrapper.first .columns {
		display: table !important;
	}
	table[class="body"] .wrapper.first .column {
		display: table !important;
	}
	table[class="body"] table.columns td {
		width: 100% !important;
	}
	table[class="body"] table.column td {
		width: 100% !important;
	}
	table[class="body"] .columns td.one {
		width: 8.333333% !important;
	}
	table[class="body"] .column td.one {
		width: 8.333333% !important;
	}
	table[class="body"] .columns td.two {
		width: 16.666666% !important;
	}
	table[class="body"] .column td.two {
		width: 16.666666% !important;
	}
	table[class="body"] .columns td.three {
		width: 25% !important;
	}
	table[class="body"] .column td.three {
		width: 25% !important;
	}
	table[class="body"] .columns td.four {
		width: 33.333333% !important;
	}
	table[class="body"] .column td.four {
		width: 33.333333% !important;
	}
	table[class="body"] .columns td.five {
		width: 41.666666% !important;
	}
	table[class="body"] .column td.five {
		width: 41.666666% !important;
	}
	table[class="body"] .columns td.six {
		width: 50% !important;
	}
	table[class="body"] .column td.six {
		width: 50% !important;
	}
	table[class="body"] .columns td.seven {
		width: 58.333333% !important;
	}
	table[class="body"] .column td.seven {
		width: 58.333333% !important;
	}
	table[class="body"] .columns td.eight {
		width: 66.666666% !important;
	}
	table[class="body"] .column td.eight {
		width: 66.666666% !important;
	}
	table[class="body"] .columns td.nine {
		width: 75% !important;
	}
	table[class="body"] .column td.nine {
		width: 75% !important;
	}
	table[class="body"] .columns td.ten {
		width: 83.333333% !important;
	}
	table[class="body"] .column td.ten {
		width: 83.333333% !important;
	}
	table[class="body"] .columns td.eleven {
		width: 91.666666% !important;
	}
	table[class="body"] .column td.eleven {
		width: 91.666666% !important;
	}
	table[class="body"] .columns td.twelve {
		width: 100% !important;
	}
	table[class="body"] .column td.twelve {
		width: 100% !important;
	}
	table[class="body"] td.offset-by-one {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-two {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-three {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-four {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-five {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-six {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-seven {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-eight {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-nine {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-ten {
		padding-left: 0 !important;
	}
	table[class="body"] td.offset-by-eleven {
		padding-left: 0 !important;
	}
	table[class="body"] table.columns td.expander {
		width: 1px !important;
	}
	table[class="body"] .right-text-pad {
		padding-left: 10px !important;
	}
	table[class="body"] .text-pad-right {
		padding-left: 10px !important;
	}
	table[class="body"] .left-text-pad {
		padding-right: 10px !important;
	}
	table[class="body"] .text-pad-left {
		padding-right: 10px !important;
	}
	table[class="body"] .hide-for-small {
		display: none !important;
	}
	table[class="body"] .show-for-desktop {
		display: none !important;
	}
	table[class="body"] .show-for-small {
		display: inherit !important;
	}
	table[class="body"] .hide-for-desktop {
		display: inherit !important;
	}
	table[class="body"] .right-text-pad {
		padding-left: 10px !important;
	}
	table[class="body"] .left-text-pad {
		padding-right: 10px !important;
	}
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width" /><title><?php echo $displayData['asset']->name ?></title><table class="body" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; height: 100%; width: 100%; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="center" align="center" valign="top" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;">
			<center style="width: 100%; min-width: 580px;">

				<!-- Begin email header -->
				<table class="row header" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; background: #999999; padding: 0px;" bgcolor="#999999"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="center" align="center" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" valign="top">
							<center style="width: 100%; min-width: 580px;">

								<table class="container" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: inherit; width: 580px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="left" valign="top">

											<table class="twelve columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="six sub-columns" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; min-width: 0px; width: 50%; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 10px 10px 0px;" align="left" valign="top">
														<?php if ( isset( $param['logo'] ) ) : ?>
														<img src="<?php echo $param['logo']; ?>" alt="logo" style="outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; width: auto; max-width: 100%; float: left; clear: both; display: block;" align="left" /><?php endif ?></td>
													<td class="six sub-columns last" style="text-align: right; vertical-align: middle; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; min-width: 0px; width: 50%; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="right" valign="middle">
														<span class="template-label" style="color: #ffffff; font-weight: bold; font-size: 11px;"><?php _e( 'Reservation confirmation', 'solidres' ) ?></span><br /><span class="template-label" style="color: #ffffff; font-weight: bold; font-size: 11px;">
															<a href="<?php echo $editLink ?>" target="_blank" style="color: #2ba6cb; text-decoration: none;">
																<?php printf( __( 'Reference ID: %s', 'solidres' ), $displayData['reservation']->code ) ?>
															</a>
														</span>
													</td>
													<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
												</tr></table></td>
									</tr></table></center>
						</td>
					</tr></table><!-- End of email header --><!-- Begin of email body --><table class="container" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: inherit; width: 580px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top">

							<table class="row callout" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 20px;" align="left" valign="top">

										<table class="twelve columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<h3 style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 32px; margin: 0; padding: 0;" align="left"><?php _e( 'Hello,', 'solidres' ) ?></h3>

													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"> </p>

													<p><?php printf( __( 'A new reservation has been made, please check details below or <a href="%s" target="_blank">click here</a> to view it:', 'solidres' ), $editLink) ?></p></td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
								</tr></table><h5 class="email_heading" style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 24px; background: #f2f2f2; margin: 0; padding: 5px; border: 1px solid #d9d9d9;" align="left"><?php _e( 'General info', 'solidres' ) ?></h5>

							<table class="row" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 20px 0px 0px;" align="left" valign="top">

										<table class="six columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Checkin: ', 'solidres' ) . $checkin->format($displayData['date_format']) ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Checkout: ', 'solidres' ) . $checkout->format($displayData['date_format']) ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Payment method: ', 'solidres' ) . __( $displayData['reservation']->payment_method_id, 'solidres' ) ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Email: ', 'solidres') . $displayData['reservation']->customer_email ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Number of nights: ', 'solidres') . $displayData['number_of_nights'] ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Note: ', 'solidres') . $displayData['reservation']->note ?></p>
												</td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
									<td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="left" valign="top">

										<table class="six columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Room cost (excl tax): ', 'solidres' ) . $displayData['sub_total'] ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Room cost tax: ', 'solidres' ) . $displayData['tax'] ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Extra cost (exl tax): ', 'solidres' ) . $displayData['total_extra_price_tax_excl'] ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Extra tax: ', 'solidres' ) . $displayData['extra_tax'] ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Grand total: ', 'solidres' ) . $displayData['grand_total'] ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php echo __( 'Deposit Amount: ', 'solidres' ) . $displayData['deposit_amount'] ?></p>
												</td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
								</tr></table><?php if (!empty($displayData['bankwire_instructions'])) : ?><h5 class="email_heading" style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 24px; background: #f2f2f2; margin: 0; padding: 5px; border: 1px solid #d9d9d9;" align="left"><?php _e( 'Bankwire info', 'solidres') ?></h5>

							<table class="row" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="left" valign="top">

										<table class="twelve columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php echo $displayData['bankwire_instructions']['account_name'];
														?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php echo $displayData['bankwire_instructions']['account_details'];
														?></p>
												</td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
								</tr></table><?php endif ?><h5 class="email_heading" style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 24px; background: #f2f2f2; margin: 0; padding: 5px; border: 1px solid #d9d9d9;" align="left"><?php _e( 'Room/Extra info', 'solidres' ) ?></h5>

							<?php foreach($displayData['reserved_room_details'] as $room) : ?>
							<p class="email_roomtype_name" style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: bold; text-align: left; line-height: 19px; font-size: 14px; border-bottom-style: solid; border-bottom-color: #CCC; border-bottom-width: 1px; margin: 10px 0 5px; padding: 0;" align="left">
								<?php echo $room->room_type_name ?>
							</p>

							<table class="row" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 20px 0px 0px;" align="left" valign="top">

										<table class="six columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php echo __( 'Guest fullname', 'solidres' ) . ': '. $room->guest_fullname ?>
													</p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php foreach ($room->other_info as $info) : if (substr($info->key, 0, 7) == 'smoking') : ?>
															<?php echo __( $info->key, 'solidres' ) . ': ' . ($info->value == '' ? __( 'No preferences', 'solidres' ) : ($info->value == 1 ? __( 'Yes', 'solidres' ): __( 'No', 'solidres' ) ) ) ; ?>
														<?php endif; endforeach; ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php echo __( 'Adult number', 'solidres' ) . ': '. $room->adults_number ?>
													</p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php echo __( 'Child number', 'solidres' ) . ': '. $room->children_number ?>
													</p>
													<?php foreach ($room->other_info as $info) : ?>
													<ul><?php if (substr($info->key, 0, 5) == 'child') : ?>
														<li>
															<?php echo __( $info->key, 'solidres' ) . ': ' . sprintf( _n( '%s year old', '%s years old', $info->value, 'solidres' ), $info->value ) ?>
														</li>
														<?php endif; ?></ul><?php endforeach; ?></td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
									<td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="left" valign="top">

										<table class="six columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<?php if ( isset($room->extras) && is_array($room->extras)) : ?>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php _e( 'Extras items: ', 'solidres' ) ?></p>
													<?php foreach($room->extras as $extra) : ?>

													<dl><dt>
															<?php echo $extra->extra_name ?>
														</dt>
														<dd>
															<?php echo __( 'Quantity: ', 'solidres' ) . $extra->extra_quantity ?>
														</dd>
														<dd>
															<?php
															$roomExtraCurrency = clone $displayData['base_currency'];
															$roomExtraCurrency->setValue($extra->extra_price);
															echo __( 'Price: ', 'solidres' ) . $roomExtraCurrency->format()
															?>
														</dd>
													</dl><?php endforeach; ?><?php endif; ?></td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
								</tr></table><?php endforeach; ?><h5 class="email_heading" style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 24px; background: #f2f2f2; margin: 0; padding: 5px; border: 1px solid #d9d9d9;" align="left"><?php _e( 'Other info', 'solidres' ) ?></h5>

							<table class="row" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 10px 0px 0px;" align="left" valign="top">

										<table class="twelve columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 580px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<dl><?php if (isset($displayData['reserved_extras']) && is_array($displayData['reserved_extras'])) :
														foreach($displayData['reserved_extras'] as $extra) : ?>
														<dt>
															<?php echo $extra->extra_name ?>
														</dt>
														<dd>
															<?php echo __( 'Quantity: ', 'solidres' ) . $extra->extra_quantity ?>
														</dd>
														<dd>
															<?php
															$bookingExtraCurrency = clone $displayData['base_currency'];
															$bookingExtraCurrency->setValue($extra->extra_price);
															echo __( 'Price: ', 'solidres' ) . $bookingExtraCurrency->format()
															?>
														</dd>
														<?php endforeach;
														endif;
														?></dl></td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
								</tr></table><table class="row footer" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; position: relative; display: block; padding: 0px;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="wrapper" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; background: #ebebeb; margin: 0; padding: 10px 20px 0px 0px;" align="left" bgcolor="#ebebeb" valign="top">

										<table class="six columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="left-text-pad" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px 10px;" align="left" valign="top">

													<h5 style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 24px; margin: 0; padding: 0 0 10px;" align="left"><?php _e( 'Connect With Us: ', 'solidres' ) ?></h5>

													<?php if (!empty($displayData['asset_custom_fields']['socialnetworks'])) :
														foreach ($displayData['asset_custom_fields']['socialnetworks'] as $network) :
															if ( ! empty( $network[1] ) ) :
																$network_parts = explode( '.', $network[0] );
																if ( in_array( $network_parts[2], array('facebook', 'twitter', 'gplus'))) : ?><table class="tiny-button <?php echo esc_attr( $network_parts[2] )?>" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 100%; overflow: hidden; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: center; color: #ffffff; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; display: block; width: auto !important; background: #2ba6cb; margin: 0; padding: 5px 0 4px; border: 1px solid #2284a1;" align="center" bgcolor="#2ba6cb" valign="top">
																			<a href="<?php echo esc_url( $network[1] );?>" style="color: #ffffff; text-decoration: none; font-weight: normal; font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><?php echo esc_attr( $network_parts[2] )?></a>
																		</td>
																	</tr></table><br /><?php endif;
															endif;
														endforeach;
													endif; ?>

															</td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
									<td class="wrapper last" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; position: relative; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; background: #ebebeb; margin: 0; padding: 10px 0px 0px;" align="left" bgcolor="#ebebeb" valign="top">

										<table class="six columns" style="border-spacing: 0; border-collapse: collapse; vertical-align: top; text-align: left; width: 280px; margin: 0 auto; padding: 0;"><tr style="vertical-align: top; text-align: left; padding: 0;" align="left"><td class="last right-text-pad" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0px 0px 10px;" align="left" valign="top">
													<h5 style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 1.3; word-break: normal; font-size: 24px; margin: 0; padding: 0 0 10px;" align="left"><?php _e( 'Contact Info: ', 'solidres' ) ?></h5>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left">
														<?php echo __( 'Address: ', 'solidres' ) . $displayData['asset']->address_1 . ', ' . $displayData['asset']->postcode . ', ' . $displayData['asset']->city
														?>
													</p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php _e( 'Phone: ', 'solidres' ) ?><?php echo $displayData['asset']->phone ?></p>
													<p style="color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; text-align: left; line-height: 19px; font-size: 14px; margin: 0 0 10px; padding: 0;" align="left"><?php _e( 'Email: ', 'solidres' ) ?><a href="mailto:<?php echo $displayData['asset']->email ?>"><?php echo $displayData['asset']->email ?></a></p>
												</td>
												<td class="expander" style="word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; text-align: left; visibility: hidden; width: 0px; color: #222222; font-family: 'Helvetica', 'Arial', sans-serif; font-weight: normal; line-height: 19px; font-size: 14px; margin: 0; padding: 0;" align="left" valign="top"></td>
											</tr></table></td>
								</tr></table><!-- container end below --></td>
					</tr></table><!-- End of email body --></center>
		</td>
	</tr></table></body>
</html>
