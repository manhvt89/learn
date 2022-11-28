<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->lang->line('items_generate_barcodes'); ?></title>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>dist/barcode_font.css" />
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
	.print-barcode_2{
		width: 50mm;
		transform: rotate(180deg);
		padding-bottom: 6px;
		border-spacing: 1px;
	}
	.print-barcode_1{
		width: 50mm;
		outline: 1px dashed;
		border-spacing: 1px;
		height: 20mm;
	}
	.print-page-barcode{
		width: 105mm;
	}
	.category-barcode{
		transform: rotate(90deg);
	}
	@media print {
    .pagebreak {
        clear: both;
        page-break-after: always;
    }
}
</style>

<body class=<?php echo "font_".$this->barcode_lib->get_font_name($barcode_config['barcode_font']); ?> 
      style="font-size:<?php echo $barcode_config['barcode_font_size']; ?>px">
	  <div class="print-page-barcode">
		<?php 
		$count = 0;
	  foreach($items as $item)
			{ 
				if ($count % $barcode_config['barcode_num_in_row'] == 0 and $count != 0)
				{
					?>
					<div class="pagebreak"></div>
					<?php
				}
				?>
		<div class="2" style=" width: 50mm; text-align: center;float: left; margin:0px; margin-top:8px">
				<?php echo $this->barcode_lib->_display_barcode_lens($item, $barcode_config); ?>
		</div>
	 <?php ++$count; } ?>
	
	  </div>
</body>

</html>
