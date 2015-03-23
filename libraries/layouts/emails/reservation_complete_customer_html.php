<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }
$checkin = new DateTime($displayData['reservation']->checkin, $displayData['timezone']);
$checkout = new DateTime($displayData['reservation']->checkout, $displayData['timezone']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title><?php echo $displayData['asset']->name ?></title>
	<style>
	/**********************************************
	* Ink v1.0.5 - Copyright 2013 ZURB Inc        *
	**********************************************/

	/* Client-specific Styles & Reset */

	#outlook a {
		padding:0;
	}

	body{
		width:100% !important;
		min-width: 100%;
		-webkit-text-size-adjust:100%;
		-ms-text-size-adjust:100%;
		margin:0;
		padding:0;
	}

	.ExternalClass {
		width:100%;
	}

	.ExternalClass,
	.ExternalClass p,
	.ExternalClass span,
	.ExternalClass font,
	.ExternalClass td,
	.ExternalClass div {
		line-height: 100%;
	}

	#backgroundTable {
		margin:0;
		padding:0;
		width:100% !important;
		line-height: 100% !important;
	}

	img {
		outline:none;
		text-decoration:none;
		-ms-interpolation-mode: bicubic;
		width: auto;
		max-width: 100%;
		float: left;
		clear: both;
		display: block; /* this line make img not showed in pdf*/
	}

	center {
		width: 100%;
		min-width: 580px;
	}

	a img {
		border: none;
	}

	p {
		margin: 0 0 0 10px;
	}

	table {
		border-spacing: 0;
		border-collapse: collapse;
	}

	td {
		word-break: break-word;
		-webkit-hyphens: auto;
		-moz-hyphens: auto;
		hyphens: auto;
		border-collapse: collapse !important;
	}

	table, tr, td {
		padding: 0;
		vertical-align: top;
		text-align: left;
	}

	hr {
		color: #d9d9d9;
		background-color: #d9d9d9;
		height: 1px;
		border: none;
	}

	/* Responsive Grid */

	table.body {
		height: 100%; /* this rule cause blank page */
		width: 100%; /* this rule cause blank page */
	}

	table.container {
		width: 580px;
		margin: 0 auto;
		text-align: inherit;
	}

	table.row {
		padding: 0px;
		width: 100%;
		position: relative;
	}

	table.container table.row {
		display: block; /* this line make dompdf throws warning */
	}

	td.wrapper {
		padding: 10px 20px 0px 0px;
		position: relative;
	}

	table.columns,
	table.column {
		margin: 0 auto;
	}

	table.columns td,
	table.column td {
		padding: 0px 0px 10px;
	}

	table.columns td.sub-columns,
	table.column td.sub-columns,
	table.columns td.sub-column,
	table.column td.sub-column {
		padding-right: 10px;
	}

	td.sub-column, td.sub-columns {
		min-width: 0px;
	}

	table.row td.last,
	table.container td.last {
		padding-right: 0px;
	}

	table.one { width: 30px; }
	table.two { width: 80px; }
	table.three { width: 130px; }
	table.four { width: 180px; }
	table.five { width: 230px; }
	table.six { width: 280px; }
	table.seven { width: 330px; }
	table.eight { width: 380px; }
	table.nine { width: 430px; }
	table.ten { width: 480px; }
	table.eleven { width: 530px; }
	table.twelve { width: 580px; }

	table.one center { min-width: 30px; }
	table.two center { min-width: 80px; }
	table.three center { min-width: 130px; }
	table.four center { min-width: 180px; }
	table.five center { min-width: 230px; }
	table.six center { min-width: 280px; }
	table.seven center { min-width: 330px; }
	table.eight center { min-width: 380px; }
	table.nine center { min-width: 430px; }
	table.ten center { min-width: 480px; }
	table.eleven center { min-width: 530px; }
	table.twelve center { min-width: 580px; }

	table.one .panel center { min-width: 10px; }
	table.two .panel center { min-width: 60px; }
	table.three .panel center { min-width: 110px; }
	table.four .panel center { min-width: 160px; }
	table.five .panel center { min-width: 210px; }
	table.six .panel center { min-width: 260px; }
	table.seven .panel center { min-width: 310px; }
	table.eight .panel center { min-width: 360px; }
	table.nine .panel center { min-width: 410px; }
	table.ten .panel center { min-width: 460px; }
	table.eleven .panel center { min-width: 510px; }
	table.twelve .panel center { min-width: 560px; }

	.body .columns td.one,
	.body .column td.one { width: 8.333333%; }
	.body .columns td.two,
	.body .column td.two { width: 16.666666%; }
	.body .columns td.three,
	.body .column td.three { width: 25%; }
	.body .columns td.four,
	.body .column td.four { width: 33.333333%; }
	.body .columns td.five,
	.body .column td.five { width: 41.666666%; }
	.body .columns td.six,
	.body .column td.six { width: 50%; }
	.body .columns td.seven,
	.body .column td.seven { width: 58.333333%; }
	.body .columns td.eight,
	.body .column td.eight { width: 66.666666%; }
	.body .columns td.nine,
	.body .column td.nine { width: 75%; }
	.body .columns td.ten,
	.body .column td.ten { width: 83.333333%; }
	.body .columns td.eleven,
	.body .column td.eleven { width: 91.666666%; }
	.body .columns td.twelve,
	.body .column td.twelve { width: 100%; }

	td.offset-by-one { padding-left: 50px; }
	td.offset-by-two { padding-left: 100px; }
	td.offset-by-three { padding-left: 150px; }
	td.offset-by-four { padding-left: 200px; }
	td.offset-by-five { padding-left: 250px; }
	td.offset-by-six { padding-left: 300px; }
	td.offset-by-seven { padding-left: 350px; }
	td.offset-by-eight { padding-left: 400px; }
	td.offset-by-nine { padding-left: 450px; }
	td.offset-by-ten { padding-left: 500px; }
	td.offset-by-eleven { padding-left: 550px; }

	td.expander {
		visibility: hidden;
		width: 0px;
		padding: 0 !important;
	}

	table.columns .text-pad,
	table.column .text-pad {
		padding-left: 10px;
		padding-right: 10px;
	}

	table.columns .left-text-pad,
	table.columns .text-pad-left,
	table.column .left-text-pad,
	table.column .text-pad-left {
		padding-left: 10px;
	}

	table.columns .right-text-pad,
	table.columns .text-pad-right,
	table.column .right-text-pad,
	table.column .text-pad-right {
		padding-right: 10px;
	}

	/* Block Grid */

	.block-grid {
		width: 100%;
		max-width: 580px;
	}

	.block-grid td {
		display: inline-block;
		padding:10px;
	}

	.two-up td {
		width:270px;
	}

	.three-up td {
		width:173px;
	}

	.four-up td {
		width:125px;
	}

	.five-up td {
		width:96px;
	}

	.six-up td {
		width:76px;
	}

	.seven-up td {
		width:62px;
	}

	.eight-up td {
		width:52px;
	}

	/* Alignment & Visibility Classes */

	table.center, td.center {
		text-align: center;
	}

	h1.center,
	h2.center,
	h3.center,
	h4.center,
	h5.center,
	h6.center {
		text-align: center;
	}

	span.center {
		display: block;
		width: 100%;
		text-align: center;
	}

	img.center {
		margin: 0 auto;
		float: none;
	}

	.show-for-small,
	.hide-for-desktop {
		display: none;
	}

	/* Typography */

	body, table.body, h1, h2, h3, h4, h5, h6, p, td {
		color: #222222;
		font-family: "Helvetica", "Arial", sans-serif;
		font-weight: normal;
		padding:0;
		margin: 0;
		text-align: left;
		line-height: 1.3;
	}

	h1, h2, h3, h4, h5, h6 {
		word-break: normal;
	}

	h1 {font-size: 40px;}
	h2 {font-size: 36px;}
	h3 {font-size: 32px;}
	h4 {font-size: 28px;}
	h5 {font-size: 24px;}
	h6 {font-size: 20px;}
	body, table.body, p, td {font-size: 14px;line-height:19px;}

	p.lead, p.lede, p.leed {
		font-size: 18px;
		line-height:21px;
	}

	p {
		margin-bottom: 10px;
	}

	small {
		font-size: 10px;
	}

	a {
		color: #2ba6cb;
		text-decoration: none;
	}

	a:hover {
		color: #2795b6 !important;
	}

	a:active {
		color: #2795b6 !important;
	}

	a:visited {
		color: #2ba6cb !important;
	}

	h1 a,
	h2 a,
	h3 a,
	h4 a,
	h5 a,
	h6 a {
		color: #2ba6cb;
	}

	h1 a:active,
	h2 a:active,
	h3 a:active,
	h4 a:active,
	h5 a:active,
	h6 a:active {
		color: #2ba6cb !important;
	}

	h1 a:visited,
	h2 a:visited,
	h3 a:visited,
	h4 a:visited,
	h5 a:visited,
	h6 a:visited {
		color: #2ba6cb !important;
	}

	/* Panels */

	.panel {
		background: #f2f2f2;
		border: 1px solid #d9d9d9;
		padding: 10px !important;
	}

	.sub-grid table {
		width: 100%;
	}

	.sub-grid td.sub-columns {
		padding-bottom: 0;
	}

	/* Buttons */

	table.button,
	table.tiny-button,
	table.small-button,
	table.medium-button,
	table.large-button {
		width: 100%;
		overflow: hidden;
	}

	table.button td,
	table.tiny-button td,
	table.small-button td,
	table.medium-button td,
	table.large-button td {
		display: block;
		width: auto !important;
		text-align: center;
		background: #2ba6cb;
		border: 1px solid #2284a1;
		color: #ffffff;
		padding: 8px 0;
	}

	table.tiny-button td {
		padding: 5px 0 4px;
	}

	table.small-button td {
		padding: 8px 0 7px;
	}

	table.medium-button td {
		padding: 12px 0 10px;
	}

	table.large-button td {
		padding: 21px 0 18px;
	}

	table.button td a,
	table.tiny-button td a,
	table.small-button td a,
	table.medium-button td a,
	table.large-button td a {
		font-weight: bold;
		text-decoration: none;
		font-family: Helvetica, Arial, sans-serif;
		color: #ffffff;
		font-size: 16px;
	}

	table.tiny-button td a {
		font-size: 12px;
		font-weight: normal;
	}

	table.small-button td a {
		font-size: 16px;
	}

	table.medium-button td a {
		font-size: 20px;
	}

	table.large-button td a {
		font-size: 24px;
	}

	table.button:hover td,
	table.button:visited td,
	table.button:active td {
		background: #2795b6 !important;
	}

	table.button:hover td a,
	table.button:visited td a,
	table.button:active td a {
		color: #fff !important;
	}

	table.button:hover td,
	table.tiny-button:hover td,
	table.small-button:hover td,
	table.medium-button:hover td,
	table.large-button:hover td {
		background: #2795b6 !important;
	}

	table.button:hover td a,
	table.button:active td a,
	table.button td a:visited,
	table.tiny-button:hover td a,
	table.tiny-button:active td a,
	table.tiny-button td a:visited,
	table.small-button:hover td a,
	table.small-button:active td a,
	table.small-button td a:visited,
	table.medium-button:hover td a,
	table.medium-button:active td a,
	table.medium-button td a:visited,
	table.large-button:hover td a,
	table.large-button:active td a,
	table.large-button td a:visited {
		color: #ffffff !important;
	}

	table.secondary td {
		background: #e9e9e9;
		border-color: #d0d0d0;
		color: #555;
	}

	table.secondary td a {
		color: #555;
	}

	table.secondary:hover td {
		background: #d0d0d0 !important;
		color: #555;
	}

	table.secondary:hover td a,
	table.secondary td a:visited,
	table.secondary:active td a {
		color: #555 !important;
	}

	table.success td {
		background: #5da423;
		border-color: #457a1a;
	}

	table.success:hover td {
		background: #457a1a !important;
	}

	table.alert td {
		background: #c60f13;
		border-color: #970b0e;
	}

	table.alert:hover td {
		background: #970b0e !important;
	}

	table.radius td {
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
	}

	table.round td {
		-webkit-border-radius: 500px;
		-moz-border-radius: 500px;
		border-radius: 500px;
	}

	/* Outlook First */

	body.outlook p {
		display: inline !important;
	}

	/*  Media Queries */

	@media only screen and (max-width: 600px) {

		table[class="body"] img {
			width: auto !important;
			height: auto !important;
		}

		table[class="body"] center {
			min-width: 0 !important;
		}

		table[class="body"] .container {
			width: 95% !important;
		}

		table[class="body"] .row {
			width: 100% !important;
			display: block !important;
		}

		table[class="body"] .wrapper {
			display: block !important;
			padding-right: 0 !important;
		}

		table[class="body"] .columns,
		table[class="body"] .column {
			table-layout: fixed !important;
			float: none !important;
			width: 100% !important;
			padding-right: 0px !important;
			padding-left: 0px !important;
			display: block !important;
		}

		table[class="body"] .wrapper.first .columns,
		table[class="body"] .wrapper.first .column {
			display: table !important;
		}

		table[class="body"] table.columns td,
		table[class="body"] table.column td {
			width: 100% !important;
		}

		table[class="body"] .columns td.one,
		table[class="body"] .column td.one { width: 8.333333% !important; }
		table[class="body"] .columns td.two,
		table[class="body"] .column td.two { width: 16.666666% !important; }
		table[class="body"] .columns td.three,
		table[class="body"] .column td.three { width: 25% !important; }
		table[class="body"] .columns td.four,
		table[class="body"] .column td.four { width: 33.333333% !important; }
		table[class="body"] .columns td.five,
		table[class="body"] .column td.five { width: 41.666666% !important; }
		table[class="body"] .columns td.six,
		table[class="body"] .column td.six { width: 50% !important; }
		table[class="body"] .columns td.seven,
		table[class="body"] .column td.seven { width: 58.333333% !important; }
		table[class="body"] .columns td.eight,
		table[class="body"] .column td.eight { width: 66.666666% !important; }
		table[class="body"] .columns td.nine,
		table[class="body"] .column td.nine { width: 75% !important; }
		table[class="body"] .columns td.ten,
		table[class="body"] .column td.ten { width: 83.333333% !important; }
		table[class="body"] .columns td.eleven,
		table[class="body"] .column td.eleven { width: 91.666666% !important; }
		table[class="body"] .columns td.twelve,
		table[class="body"] .column td.twelve { width: 100% !important; }

		table[class="body"] td.offset-by-one,
		table[class="body"] td.offset-by-two,
		table[class="body"] td.offset-by-three,
		table[class="body"] td.offset-by-four,
		table[class="body"] td.offset-by-five,
		table[class="body"] td.offset-by-six,
		table[class="body"] td.offset-by-seven,
		table[class="body"] td.offset-by-eight,
		table[class="body"] td.offset-by-nine,
		table[class="body"] td.offset-by-ten,
		table[class="body"] td.offset-by-eleven {
			padding-left: 0 !important;
		}

		table[class="body"] table.columns td.expander {
			width: 1px !important;
		}

		table[class="body"] .right-text-pad,
		table[class="body"] .text-pad-right {
			padding-left: 10px !important;
		}

		table[class="body"] .left-text-pad,
		table[class="body"] .text-pad-left {
			padding-right: 10px !important;
		}

		table[class="body"] .hide-for-small,
		table[class="body"] .show-for-desktop {
			display: none !important;
		}

		table[class="body"] .show-for-small,
		table[class="body"] .hide-for-desktop {
			display: inherit !important;
		}
	}

	</style>
	<style>

		table.facebook td {
			background: #3b5998;
			border-color: #2d4473;
		}

		table.facebook:hover td {
			background: #2d4473 !important;
		}

		table.twitter td {
			background: #00acee;
			border-color: #0087bb;
		}

		table.twitter:hover td {
			background: #0087bb !important;
		}

		table.google-plus td {
			background-color: #DB4A39;
			border-color: #CC0000;
		}

		table.google-plus:hover td {
			background: #CC0000 !important;
		}

		.template-label {
			color: #ffffff;
			font-weight: bold;
			font-size: 11px;
		}

		.callout .wrapper {
			padding-bottom: 20px;
		}

		.callout .panel {
			background: #ECF8FF;
			border-color: #b9e5ff;
		}

		.header {
			background: #999999;
		}

		.footer .wrapper {
			background: #ebebeb;
		}

		.footer h5 {
			padding-bottom: 10px;
		}

		table.columns .text-pad {
			padding-left: 10px;
			padding-right: 10px;
		}

		table.columns .left-text-pad {
			padding-left: 10px;
		}

		table.columns .right-text-pad {
			padding-right: 10px;
		}

		@media only screen and (max-width: 600px) {

			table[class="body"] .right-text-pad {
				padding-left: 10px !important;
			}

			table[class="body"] .left-text-pad {
				padding-right: 10px !important;
			}
		}

		.email_heading {
			background: #f2f2f2;
			border: 1px solid #d9d9d9;
			padding: 5px
		}

		.email_roomtype_name {
			border-bottom: 1px solid #CCC;
			margin-top: 10px;
			margin-bottom: 5px;
			font-weight: bold;
		}

	</style>
</head>
<body>

<table class="body">
	<tr>
		<td class="center" align="center" valign="top">
			<center>

				<!-- Begin email header -->
				<table class="row header">
					<tr>
						<td class="center" align="center">
							<center>

								<table class="container">
									<tr>
										<td class="wrapper last">

											<table class="twelve columns">
												<tr>
													<td class="six sub-columns">
														<?php $param = json_decode( $displayData['asset']->params, true );
														if ( isset( $param['logo'] ) ) : ?>
															<img src="<?php echo $param['logo']; ?>" alt="logo" />
														<?php endif ?>
													</td>
													<td class="six sub-columns last" style="text-align:right; vertical-align:middle;">
														<span class="template-label"><?php _e( 'Reservation confirmation', 'solidres' ) ?></span><br />
														<span class="template-label"><?php printf( __( 'Reference ID: %s', 'solidres' ), $displayData['reservation']->code ) ?></span>
													</td>
													<td class="expander"></td>
												</tr>
											</table>

										</td>
									</tr>
								</table>

							</center>
						</td>
					</tr>
				</table>
				<!-- End of email header -->

				<!-- Begin of email body -->
				<table class="container">
					<tr>
						<td>

							<table class="row callout">
								<tr>
									<td class="wrapper last">

										<table class="twelve columns">
											<tr>
												<td>
													<h3><?php printf( __( 'Dear %s %s %s', 'solidres' ), $displayData['reservation']->customer_firstname, $displayData['reservation']->customer_middlename, $displayData['reservation']->customer_lastname)  ?></h3>

													<p>&nbsp;</p>

													<p>
														<?php printf( __( 'Thank you for your reservation at %s. Should you have any further information, please do not hesitate to contact us at any time.', 'solidres'), $displayData['asset']->name) ?>
													</p>

													<p>
														<?php _e( 'We are pleased to confirm your reservation as follows:', 'solidres' ) ?>
													</p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>

							<h5 class="email_heading"><?php _e( 'General info', 'solidres' ) ?></h5>

							<table class="row">
								<tr>
									<td class="wrapper">

										<table class="six columns">
											<tr>
												<td>
													<p><?php echo __( 'Checkin: ', 'solidres' ) . $checkin->format($displayData['date_format']) ?></p>
													<p><?php echo __( 'Checkout: ', 'solidres' ) . $checkout->format($displayData['date_format']) ?></p>
													<p><?php echo __( 'Payment method: ', 'solidres' ) . __( $displayData['reservation']->payment_method_id, 'solidres' ) ?></p>
													<p><?php echo __( 'Email: ', 'solidres') . $displayData['reservation']->customer_email ?></p>
													<p><?php echo __( 'Number of nights: ', 'solidres') . $displayData['number_of_nights'] ?></p>
													<p><?php echo __( 'Note: ', 'solidres') . $displayData['reservation']->note ?></p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
									<td class="wrapper last">

										<table class="six columns">
											<tr>
												<td>
													<p><?php echo __( 'Room cost (excl tax): ', 'solidres' ) . $displayData['sub_total'] ?></p>
													<p><?php echo __( 'Room cost tax: ', 'solidres' ) . $displayData['tax'] ?></p>
													<p><?php echo __( 'Extra cost (exl tax): ', 'solidres' ) . $displayData['total_extra_price_tax_excl'] ?></p>
													<p><?php echo __( 'Extra tax: ', 'solidres' ) . $displayData['extra_tax'] ?></p>
													<p><?php echo __( 'Grand total: ', 'solidres' ) . $displayData['grand_total'] ?></p>
													<p><?php echo __( 'Deposit Amount: ', 'solidres' ) . $displayData['deposit_amount'] ?></p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>

							<?php if (!empty($displayData['bankwire_instructions'])) : ?>
							<h5 class="email_heading"><?php _e( 'Bankwire info', 'solidres') ?></h5>

							<table class="row">
								<tr>
									<td class="wrapper last">

										<table class="twelve columns">
											<tr>
												<td>
													<p>
														<?php
														echo $displayData['bankwire_instructions']['account_name'];
														?>
													</p>
													<p>
														<?php
														echo $displayData['bankwire_instructions']['account_details'];
														?>
													</p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>

							<?php endif ?>

							<h5 class="email_heading"><?php _e( 'Room/Extra info', 'solidres' ) ?></h5>

							<?php foreach($displayData['reserved_room_details'] as $room) : ?>
							<p class="email_roomtype_name">
								<?php echo $room->room_type_name ?>
							</p>

							<table class="row">
								<tr>
									<td class="wrapper">

										<table class="six columns">
											<tr>
												<td>
													<p>
														<?php echo __( 'Guest fullname', 'solidres' ) . ': '. $room->guest_fullname ?>
													</p>
													<p>
														<?php foreach ($room->other_info as $info) : if (substr($info->key, 0, 7) == 'smoking') : ?>
															<?php echo __( $info->key, 'solidres' ) . ': ' . ($info->value == '' ? __( 'No preferences', 'solidres' ) : ($info->value == 1 ? __( 'Yes', 'solidres' ): __( 'No', 'solidres' ) ) ) ; ?>
														<?php endif; endforeach; ?>
													</p>
													<p>
														<?php echo __( 'Adult number', 'solidres' ) . ': '. $room->adults_number ?>
													</p>
													<p>
														<?php echo __( 'Child number', 'solidres' ) . ': '. $room->children_number ?>
													</p>
													<?php foreach ($room->other_info as $info) : ?>
														<ul>
															<?php if (substr($info->key, 0, 5) == 'child') : ?>
																<li>
																	<?php echo __( $info->key, 'solidres' ) . ': ' . sprintf( _n( '%s year old', '%s years old', $info->value, 'solidres' ), $info->value ) ?>
																</li>
															<?php endif; ?>
														</ul>
													<?php endforeach; ?>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
									<td class="wrapper last">

										<table class="six columns">
											<tr>
												<td>
													<?php if ( isset($room->extras) && is_array($room->extras)) : ?>
													<p><?php _e( 'Extras items: ', 'solidres' ) ?></p>
													<?php foreach($room->extras as $extra) : ?>

													<dl>
														<dt>
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
													</dl>
													<?php endforeach; ?>
													<?php endif; ?>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>
							<?php endforeach; ?>

							<h5 class="email_heading"><?php _e( 'Other info', 'solidres' ) ?></h5>

							<table class="row">
								<tr>
									<td class="wrapper last">

										<table class="twelve columns">
											<tr>
												<td>
													<dl>
														<?php
														if (isset($displayData['reserved_extras']) && is_array($displayData['reserved_extras'])) :
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
														<?php
															endforeach;
														endif;
														?>
													</dl>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>

							<table class="row footer">
								<tr>
									<td class="wrapper">

										<table class="six columns">
											<tr>
												<td class="left-text-pad">

													<h5><?php _e( 'Connect With Us: ', 'solidres' ) ?></h5>

													<?php
													if (!empty($displayData['asset_custom_fields']['socialnetworks'])) :
														foreach ($displayData['asset_custom_fields']['socialnetworks'] as $network) :
															if ( ! empty( $network[1] ) ) :
																$network_parts = explode( '.', $network[0] );
																if ( in_array( $network_parts[2], array('facebook', 'twitter', 'gplus'))) : ?>
													<table class="tiny-button <?php echo esc_attr( $network_parts[2] )?>">
														<tr>
															<td>
																<a href="<?php echo esc_url( $network[1] );?>"><?php echo esc_attr( $network_parts[2] )?></a>
															</td>
														</tr>
													</table>

													<br>
													<?php		endif;
															endif;
														endforeach;
													endif; ?>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
									<td class="wrapper last">

										<table class="six columns">
											<tr>
												<td class="last right-text-pad">
													<h5><?php _e( 'Contact Info: ', 'solidres' ) ?></h5>
													<p>
														<?php
															echo __( 'Address: ', 'solidres' ) . $displayData['asset']->address_1 . ', ' . $displayData['asset']->postcode . ', ' . $displayData['asset']->city
														?>
													</p>
													<p><?php _e( 'Phone: ', 'solidres' ) ?><?php echo $displayData['asset']->phone ?></p>
													<p><?php _e( 'Email: ', 'solidres' ) ?><a href="mailto:<?php echo $displayData['asset']->email ?>"><?php echo $displayData['asset']->email ?></a></p>
												</td>
												<td class="expander"></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>

							<!-- container end below -->
						</td>
					</tr>
				</table>
				<!-- End of email body -->

			</center>
		</td>
	</tr>
</table>
</body>
</html>