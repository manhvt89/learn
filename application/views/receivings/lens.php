<?php $this->load->view("partial/header"); ?>
<div id="page_title" class="rp_page_title" style="text-align: center;"><?php echo $page_title; ?></div>

<div style="color:red; align-content: center"><?php echo validation_errors(); ?></div>
<?php echo form_open('receivings/lens', array('id'=>'target')); ?>
<div class="form-group form-group-sm">
	<?php echo form_label($this->lang->line('reports_lens_category'), 'reports_lens_category_label', array('class'=>'required control-label col-xs-2')); ?>
	<div id='report_item_count' class="col-xs-3">
		<?php echo form_dropdown('category',$item_count,'all','id="category" class="form-control"'); ?>
	</div>
</div>

<div id="view_report_lens_category12">
	<input id="hhmyo" name="hhmyo" type="hidden" value="" />
	<input id="error" name="error" type="hidden" value="0" />
	<table id="input_grid_data_myo" width="100%">
		<thead>
			<tr id="_row_m_1">
				<td style="padding: 0 9px 0 9px;">SPH</td>
				<td colspan="25">CYL(-)</td>
			</tr>
			<tr id="_row_m_2">
				<?php
					$i = 0;
					foreach($cyls  as $cyl):
						?>
					<td id='col-<?=$i?>'><?php echo $cyl ?></td>
						<?php
						$i++;
					endforeach;
				?>

			</tr>
		</thead>
		<tbody>
			<?php
				foreach($mysphs as $key=>$sph):
					if($key > 0)
					{
						$tr = '<tr id="_row_myo_'.$key.'">';
						$tr = $tr . '<td>'.$sph.'</td>';
						foreach($cyls as $k=>$cyl):
							if($k > 0)
							{
								if($k < 10)
								{
									$k = '0'.$k;
								}
								//$tr = $tr . '<td>'.'<input type="text" name="myo'.$key.$k.'" value="'.set_value('myo'.$key.$k).'">'.'</td>';
								$tr = $tr . '<td></td>';
							}
						endforeach;
						$tr = $tr . '</tr>';
						echo $tr;
					}
				endforeach;
			?>
		<tbody>
		</table>
		<input id="hhhyo" name="hhhyo" type="hidden" value="" />
	<table id="input_grid_data_hyo" class="" width="100%">
		<thead>
			<tr id="_row_m_1"><td style="padding: 0 9px 0 9px;">SPH</td><td colspan="25">CYL(-)</td></tr>
			<tr id="_row_h_2">
				<td>+</td>
				<?php
					foreach($cyls  as $k=>$cyl):
						if($k > 0):
						?>
							<td><?php echo $cyl ?></td>
						<?php
						endif;
					endforeach;
				?>

			</tr>
		</thead>
	<tbody>
	<?php
		foreach($hysphs as $key=>$sph):
			if($key > 0)
			{
				$tr = '<tr id="_row_hyo_'.$key.'">';
				$tr = $tr . '<td>'.$sph.'</td>';
				foreach($cyls as $k=>$cyl):
					if($k > 0)
					{
						if($k < 10)
						{
							$k = '0'.$k;
						}
						$tr = $tr . '<td></td>';
						//$tr = $tr . '<td>'.'<input type="text" name="hyo'.$key.$k.'"  value="'.set_value('hyo'.$key.$k).'">'.'</td>';
					}
				endforeach;
				$tr = $tr . '</tr>';
				echo $tr;
			}
		endforeach;
	?>
	</tbody>
</table>
</div>
<div id="view_report_lens_category">

</div>

<?php
	echo form_button(array(
		'name'=>'generate_report',
		'id'=>'generate_report',
		'data-submitting'=>'false',
		'content'=>$this->lang->line('common_submit'),
		'class'=>'btn btn-primary btn-sm')
	);
?>
<?php echo form_close(); ?>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#generate_report').click(function()
		{
			if (this.getAttribute('data-submitting') === 'true') {
				// Đã submit rồi, không cho phép duplicate click
				return;
			}

			// Đánh dấu trạng thái submitting
			this.setAttribute('data-submitting', 'true');

			// Lấy tất cả các ô dữ liệu trong bảng
			const datamyo = myo.getData();
			const datahyo = hyo.getData();
			// Kiểm tra tính hợp lệ của dữ liệu
			let isDataValidMyo = validateData(datamyo);
			let isDataValidHyo = validateData(datahyo);

			if (isDataValidMyo['result'] && isDataValidHyo['result']) {
				// Dữ liệu hợp lệ, tiến hành gửi yêu cầu đến máy chủ
				$('#hhmyo').val(JSON.stringify(myo.getJson()));
				$('#hhhyo').val(JSON.stringify(hyo.getJson()));
				$( "#target" ).submit();
				// ...
				// document.getElementById('form').submit(); // Gửi yêu cầu đến máy chủ
			} else {
				// Hiển thị thông báo yêu cầu sửa dữ liệu
				let message_error = 'Vui lòng sửa dữ liệu để đảm bảo các ô là số nguyên. '
				if(isDataValidMyo['message'] != '')
				{
					message_error = message_error + ' Cận ' + isDataValidMyo['message'];
				}

				if(isDataValidHyo['message'] != '')
				{
					message_error = message_error + ' Viễn ' + isDataValidHyo['message'];
				}
				
				alert(message_error);
			}

			// Đánh dấu trạng thái kết thúc submit
			this.setAttribute('data-submitting', 'false');
			
		});

	});

	myo = jspreadsheet(document.getElementById('input_grid_data_myo'),{
		onbeforeinsertrow: function(){
			return false;

		},
		onbeforeinsertcolumn: function (){
			return false;
		},
		updateTable: function(el, cell, x, y, source, value, id) {
			if (x == 0) {
				cell.classList.add('readonly');
				$(cell).css('font-weight','bold');
				$(cell).css('color','black');
				$(cell).css('background-color', '#dcdcdc');
			}
			if (y % 2) {
				if(x != 0)
				{
            		$(cell).css('background-color', '#edf3ff');
				}
        	}

		},
		/*
		onpaste: function (el,data) {
			cleanPasteData(data,myo);
			//console.log(data);
		},
		*/

	});
	hyo = jspreadsheet(document.getElementById('input_grid_data_hyo'),{
		onbeforeinsertrow: function(){
			return false;

		},
		onbeforeinsertcolumn: function (){
			return false;
		},
		updateTable: function(el, cell, x, y, source, value, id) {
			if (x == 0) {
				cell.classList.add('readonly');
				$(cell).css('font-weight','bold');
				$(cell).css('color','black');
				$(cell).css('background-color', '#dcdcdc');
			}
			if (y % 2) {
				if(x != 0)
				{
            		$(cell).css('background-color', '#edf3ff');
				}
        	}

		},

	});
	console.log(myo.getJson());

	function validateData(data) {
		var _aReturn = new Array();
		for (let i = 0; i < data.length; i++) {
			for (let j = 1; j < data[i].length; j++) {
				let sInput = data[i][j];
				const cellValue = sInput.trim();
				let _index = i +1;
				if(cellValue != '')
				{
					if (!Number.isInteger(Number(cellValue))) {
						console.log(i + "|"+cellValue + "|"+j);
						_aReturn['result']=false;
						_aReturn['message'] = 'vị trí dòng: '+_index+' cột: '+j;
						return _aReturn;
					}
				}
			}
		}
		_aReturn['result']=true;
		_aReturn['message'] ='';
		return _aReturn;
	}

	// Hàm làm sạch dữ liệu dán vào
	function cleanPasteData1(str,delimiter) {
		 // Remove last line break
		 str = str.replace(/\r?\n$|\r$|\n$/g, "");
            // Last caracter is the delimiter
            if (str.charCodeAt(str.length-1) == 9) {
                str += "\0";
            }
            // user-supplied delimeter or default comma
            delimiter = (delimiter || ",");

            var arr = [];
            var quote = false;  // true means we're inside a quoted field
            // iterate over each character, keep track of current row and column (of the returned array)
            for (var row = 0, col = 0, c = 0; c < str.length; c++) {
                var cc = str[c], nc = str[c+1];
                arr[row] = arr[row] || [];
                arr[row][col] = arr[row][col] || '';

                // If the current character is a quotation mark, and we're inside a quoted field, and the next character is also a quotation mark, add a quotation mark to the current column and skip the next character
                if (cc == '"' && quote && nc == '"') { arr[row][col] += cc; ++c; continue; }

                // If it's just one quotation mark, begin/end quoted field
                if (cc == '"') { quote = !quote; continue; }

                // If it's a comma and we're not in a quoted field, move on to the next column
                if (cc == delimiter && !quote) { ++col; continue; }

                // If it's a newline (CRLF) and we're not in a quoted field, skip the next character and move on to the next row and move to column 0 of that new row
                if (cc == '\r' && nc == '\n' && !quote) { ++row; col = 0; ++c; continue; }

                // If it's a newline (LF or CR) and we're not in a quoted field, move on to the next row and move to column 0 of that new row
                if (cc == '\n' && !quote) { ++row; col = 0; continue; }
                if (cc == '\r' && !quote) { ++row; col = 0; continue; }

                // Otherwise, append the current character to the current column
                arr[row][col] += cleanCellValue(cc);
            }
            return arr;
	}

	function cleanPasteData(data, spreadsheet) {
		let _maxI = data.length-1;
		let _maxJ = data[_maxI].length - 1;
		for (let i = 0; i < data.length; i++) {
			for (let j = 0; j < data[i].length; j++) {
			const cellValue = data[i][j];

			// Xử lý và làm sạch giá trị paste vào
			const cleanedCellValue = cleanCellValue(cellValue);

			// Cập nhật giá trị đã được làm sạch vào ô tương ứng trong Jspreadsheet
			spreadsheet.setValue(j, i, cleanedCellValue);
			}
		}

		let lastCellValue = data[_maxI][_maxJ];
		if(containsNullCharacter(lastCellValue))
		{
			spreadsheet.setValue(_maxJ, _maxI, "E");
		}
		
	}

	// Hàm làm sạch giá trị của mỗi ô
	function cleanCellValue(value) {
		// Xử lý và làm sạch giá trị ở đây, ví dụ:
		const cleanedValue = value.trim(); // Xóa khoảng trắng ở đầu và cuối chuỗi
		//console.log(cleanedValue);
		if (!Number.isInteger(Number(cleanedValue))) {
			return 'E';	
		}
		
		return cleanedValue;
	}

	function containsNullCharacter(str) {
		const regex = /\u0000/;
		return regex.test(str);
	}
</script>
<?php $this->load->view("partial/footer"); ?>
