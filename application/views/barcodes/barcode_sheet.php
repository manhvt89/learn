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
		outline: 2px dashed;
		width: 188px;
  		height: 151px;
		/*transform: rotate(45deg);*/
	}
	.print-barcode_2{
		width: 100%;
		transform: rotate(180deg);
	}
	.print-page-barcode{
		width: 450px;
	}
	.category-barcode{
		transform: rotate(90deg);
	}
</style>

<body class=<?php echo "font_".$this->barcode_lib->get_font_name($barcode_config['barcode_font']); ?> 
      style="font-size:<?php echo $barcode_config['barcode_font_size']; ?>px">
	  <div class="print-page-barcode">
	<table cellspacing=<?php echo $barcode_config['barcode_page_cellspacing']; ?> width='<?php echo $barcode_config['barcode_page_width']."%"; ?>' >
		<tr>
			<?php
			$count = 0;
			foreach($items as $item)
			{
				if ($count % $barcode_config['barcode_num_in_row'] == 0 and $count != 0)
				{
					echo '</tr><tr>';
				}
				echo '<td><div class="barcode-print-area">' . $this->barcode_lib->_display_barcode($item, $barcode_config) . '<div></td>';
				++$count;
			}
			?>
		</tr>
	</table>
	  </div>
</body>

</html>
