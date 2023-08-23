<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->lang->line('items_generate_barcodes'); ?></title>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>dist/barcode_font.css" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>dist/barcode_print.css" />
</head>
<style>
	.barcode-print-area {
			background-color: transparent;
			outline: 1px dashed;
			width: 188.9px;
			height: 151.2px;
			margin: 0px 3px 0px 3px;
			/*transform: rotate(45deg);*/
		}

		.print-barcode_2 {
			width: 48mm;
			transform: rotate(180deg);
			padding-bottom: 0px;
			border-spacing: 1px;
			margin: 0 auto;
			height: 20mm;
			border: green 1px solid;
		}

		.print-barcode_1 {
			width: 48mm;
			/*outline: 1px dashed;*/
			border-spacing: 1px;
			height: 20mm;
			text-align: center;
			margin: 0 auto;
			border: green 1px solid;
		}

		.print-page-barcode {
			width: 105mm;
			/*
outline: 1px dashed;
border-spacing: 1px;
*/
			/*width: 420px;*/
			margin: auto;
		}

		.category-barcode {
			transform: rotate(90deg);
		}

		.buttonpr {
			width: 105mm;
			margin: auto;
			padding: 25px;
			text-align: center;
		}

		.bt-print-barcode {
			width: 40mm;
			margin: auto;
			height: 12mm;
			font-size: 25px;
			background-color: gray;
			font-family: <?=$this->barcode_lib->get_font_name($barcode_config['barcode_font'])?>;
		}

		.store_name{
			font-size: <?php echo $barcode_config['barcode_font_size']; ?>px;
			font-family: <?=$this->barcode_lib->get_font_name($barcode_config['barcode_font']) ?>;
			padding-top: 1mm;
		}
		.barcode-item-name{
			font-family: <?=$this->barcode_lib->get_font_name($barcode_config['barcode_font'])?>;
			text-transform: uppercase;
			font-size: <?php echo ($barcode_config['barcode_font_size'] - 2); ?>px;
			padding-top:5px;
			padding-left: 0px;
			width:48mm;
			text-align: center;
		}
		.barcode-item-unit_price{
			font-family: <?=$this->barcode_lib->get_font_name($barcode_config['barcode_font'])?>;
			text-transform: uppercase;
			font-size: <?php echo ($barcode_config['barcode_font_size'] - 2); ?>px;
			padding-top:10px;
			padding-left: 0px;
			font-weight: bold;
			text-align: center;
		}

		.store_address{
			font-size:8px;
		}
		.barcode-item-item_code{
			font-family: <?=$this->barcode_lib->get_font_name($barcode_config['barcode_font'])?>;
			text-transform: uppercase;
		}
		.LibreBarcode128{
			font-size: 10mm;
			padding-bottom: 1mm;
		}

		@media print {
			.pagebreak {
				clear: both;
				page-break-after: always;
			}
			.buttonpr {
				display: none;
			}
			body {
				margin: 0;
			}
			#register_wrapper {
				display: none;
			}
		}
</style>

<body class=<?php echo "font_".$this->barcode_lib->get_font_name($barcode_config['barcode_font']); ?> 
      style="font-size:<?php echo $barcode_config['barcode_font_size']; ?>px">
	  <div class="buttonpr no-print">
				<button onclick="window.print()" class="bt-print-barcode">Print</button>
	  </div>
	  <?php print_barcode($items,$this->config->item('GBarcode')['template'],$barcode_config);?>
</body>

</html>