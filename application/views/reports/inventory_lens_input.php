<?php $this->load->view("partial/header"); ?>

<div id="page_title" class="rp_page_title"><?php echo $this->lang->line('reports_report_input'); ?></div>

<?php
if(isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}
?>

<?php echo form_open('#', array('id'=>'item_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>

	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('reports_stock_location'), 'reports_stock_location_label', array('class'=>'required control-label col-xs-2')); ?>
		<div id='report_stock_location' class="col-xs-3">
			<?php echo form_dropdown('stock_location',$stock_locations,'all','id="location_id" class="form-control"'); ?>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('reports_lens_category'), 'reports_lens_category_label', array('class'=>'required control-label col-xs-2')); ?>
		<div id='report_item_count' class="col-xs-3">
			<?php echo form_dropdown('category',$item_count,'all','id="category" class="form-control"'); ?>
		</div>
	</div>

	<?php
	echo form_button(array(
		'name'=>'generate_report',
		'id'=>'generate_report',
		'content'=>$this->lang->line('common_submit'),
		'class'=>'btn btn-primary btn-sm')
	);
	?>
<?php echo form_close(); ?>
<button id="expExcel" style="display: none;"> Excel</button>
<div id="view_report_lens_category">

</div>

<?php $this->load->view("partial/footer"); ?>
<script src="dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{

	$('#expExcel').click(function(){
		$(".table2excel").table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: "myFileName",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
	});

	$("#generate_report").click(function()
	{
		var csrf_ospos_v3 = csrf_token();
		var location_id = $('#location_id').val();
		var category = $('#category').val();
		$.ajax({
			method: "POST",
			url: "<?php echo site_url('reports/ajax_inventory_summary')?>",
			data: { location_id: location_id, category: category, csrf_ospos_v3: csrf_ospos_v3 },
			dataType: 'json'
		})
			.done(function( msg ) {
				if(category != '1.56 TC') {
					if (msg.result == 1) {
						var html = '';
						var header = '<tr><td colspan="20" class="rp_title">' + msg.data.header.title + '</td></tr>';
						header = header + '<tr><td colspan="3" class="rp_company">Công ty</td>' +
							'<td colspan="4">' + msg.data.header.company_name + '</td>' +
							'<td colspan="2"></td>' +
							'<td colspan="4"></td>' +
							'<td colspan="3">Khách hàng</td>' +
							'<td colspan="4">' + msg.data.header.customer + '</td></tr>';
						header = header + '<tr><td colspan="3" class="rp_des">Mô tả</td>' +
							'<td colspan="4">' + msg.data.header.description + '</td>' +
							'<td colspan="2"></td>' +
							'<td colspan="4"></td>' +
							'<td colspan="3">Ngày đặt hàng</td>' +
							'<td colspan="4">' + msg.data.header.ordered_date + '</td></tr>';
						header = header + '<tr><td colspan="3" class="rp_brand">Thương hiệu</td>' +
							'<td colspan="4">' + msg.data.header.brand + '</td>' +
							'<td colspan="2"></td>' +
							'<td colspan="4"></td>' +
							'<td colspan="3">MS đơn hàng</td>' +
							'<td colspan="4"></td></tr>';

						header = header + '<tr><td colspan="3" class="rp_rmk">RMK</td>' +
							'<td colspan="4">' + '</td>' +
							'<td colspan="2"></td>' +
							'<td colspan="4"></td>' +
							'<td colspan="3">Tổng số lượng (miếng)</td>' +
							'<td colspan="4">' + msg.data.total + '</td></tr>';

						header = header + '<tr><td class="rp_header">SPH</td>' +
							'<td colspan="19">CYL (-)</td>' +
							'</tr>';

						var header_rp = '<tr><td>(-)</td>' +
							'<td>-0.00</td><td>-0.25</td><td>-0.50</td><td>-0.75</td>' +
							'<td>-1.00</td><td>-1.25</td><td>-1.50</td><td>-1.75</td>' +
							'<td>-2.00</td><td>-2.25</td><td>-2.50</td><td>-2.75</td>' +
							'<td>-3.00</td><td>-3.25</td><td>-3.50</td><td>-3.75</td>' +
							'<td>-4.00</td><td colspan="2" rowspan="47"></td></tr>';
						header = header + header_rp;

						var rp_body = '';
						var myopia = msg.data.myopia;
						var hyperopia = msg.data.hyperopia;
						var re_map = msg.data.re_map;
						var sub_myopia = msg.data.sub_myopia;
						var sub_hyperopia = msg.data.sub_hyperopia;
						var _row_index = 0;

						for (i = 1; i < 47; i++) {
							var row = '<tr class="rp_number"><td> -' + re_map[i] + '</td>';
							for (j = 1; j < 18; j++) {
								row = row + '<td>' + myopia[i][j] + '</td>';
							}
							row = row + '</tr>';
							rp_body = rp_body + row;
							_row_index = i; 
						}

						//Next row
						_row_index++;
						var raw43 = '<tr class="rp_number"><td>' + re_map[_row_index] + '</td>';
						for (j = 1; j < 10; j++) {
							raw43 = raw43 + '<td>' + myopia[_row_index][j] + '</td>';
						}
						raw43 = raw43 + '<td>SPH</td><td colspan="9">CYL(+)</td></tr>';
						rp_body = rp_body + raw43;


						//Next row
						_row_index++;
						var raw44 = '<tr class="rp_number"><td>' + re_map[_row_index] + '</td>';
						for (j = 1; j < 10; j++) {
							raw44 = raw44 + '<td>' + myopia[_row_index][j] + '</td>';
						}
						raw44 = raw44 + '<td>(+)</td>';
						for (j = 1; j < 10; j++) {
							raw44 = raw44 + '<td> -' + re_map[j] + '</td>';
						}
						raw44 = raw44 + '</tr>';
						rp_body = rp_body + raw44;

						_row_index++;

						for (i = _row_index; i < 62; i++) {
							var row = '<tr class="rp_number"><td> -' + re_map[i] + '</td>';
							for (j = 1; j < 10; j++) {
								row = row + '<td>' + myopia[i][j] + '</td>';
							}
							row = row + '<td> +' + re_map[i - 44] + '</td>';
							for (k = 1; k < 10; k++) {
								row = row + '<td>' + hyperopia[i - 44][k] + '</td>';
							}
							row = row + '</tr>';
							rp_body = rp_body + row;
						}

						var row57 = '<tr row="57"><td>Sub</td>';
						for (i = 1; i < 10; i++) {
							row57 = row57 + '<td>' + sub_myopia[i] + '</td>';
						}
						row57 = row57 + '<td> +' + re_map[12] + '</td>';
						for (j = 1; j < 10; j++) {
							row57 = row57 + '<td>' + hyperopia[12][j] + '</td>';
						}
						rp_body = rp_body + row57 + '</tr>';


						var row58 = '<tr row="58"><td colspan="4">Group</td><td colspan="2">Số lượng (miếng)</td><td colspan="2">Thành tiền</td><td rowspan="9" colspan="2"></td>';
						row58 = row58 + '<td> +' + re_map[13] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[13][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';


						row58 = '<tr><td colspan="4">-0.00/-0.00 ~ -6.00/-2.00</td><td colspan="2">' + msg.data.sub_group[0] + '</td><td colspan="2"></td></td>';
						row58 = row58 + '<td> +' + re_map[14] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[14][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//--------------------
						row58 = '<tr><td colspan="4">-6.25/-0.00 ~ -8.00/-2.00</td><td colspan="2">' + msg.data.sub_group[1] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[15] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[15][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="4">-8.25/-0.00 ~ -15.00/-2.00</td><td colspan="2">' + msg.data.sub_group[2] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[16] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[16][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="4">+0.00/-0.00 ~ +6.00/-2.00</td><td colspan="2">' + msg.data.sub_group[3] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[17] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[17][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="4">-0.00/-2.25 ~ -10.00/-3.00</td><td colspan="2">' + msg.data.sub_group[4] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[18] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[18][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="4">-0.00/-3.25 ~ -10.00/-4.00</td><td colspan="2">' + msg.data.sub_group[5] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[19] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[19][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="4">+0.25/-0.50 ~ +1.75/-2.00</td><td colspan="2">' + msg.data.sub_group[6] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[20] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[20][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="4">+0.25/-2.25 ~ +1.75/-4.00</td><td colspan="2">' + msg.data.sub_group[7] + '</td><td colspan="2">Thành tiền</td></td>';
						row58 = row58 + '<td> +' + re_map[21] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[21][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//---------------------
						row58 = '<tr><td colspan="4"></td><td colspan="2"></td><td colspan="2"></td><td colspan="2"></td>';
						row58 = row58 + '<td> +' + re_map[22] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[22][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';
						row58 = '<tr><td colspan="4"></td><td colspan="2"></td><td colspan="2"></td><td colspan="2"></td>';
						row58 = row58 + '<td> +' + re_map[23] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[23][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//----------------------

						row58 = '<tr><td colspan="2">Người nhận</td><td colspan="3"></td><td colspan="2">Số ĐT</td><td colspan="3"></td>';
						row58 = row58 + '<td> +' + re_map[24] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[24][j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//---------------------

						row58 = '<tr><td rowspan="2" colspan="2">Địa chỉ</td><td rowspan="2" colspan="8"></td></td>';
						row58 = row58 + '<td> +' + re_map[25] + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + hyperopia[25][j] + '</td>';
						}
						rp_body = rp_body + row58;

						row58 = '<tr>';
						row58 = row58 + '<td> Sub</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + sub_hyperopia[j] + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//--------------------
						var row59 = '<tr><td>SPH</td><td colspan="17">CYL(-)</td><td rowspan="10" colspan="2"></td></tr>';
						row59 = row59 + '<tr><td>(+)</td><td colspan="9" rowspan="9"></td>';
						for (j = 10; j < 18; j++) {
							row59 = row59 + '<td> -' + re_map[j] + '</td>';
						}

						rp_body = rp_body + row59 + '</tr>';

						//next-----------
						for (i = 1; i < 9; i++) {
							var row = '<tr class="pr_number"><td> +' + re_map[i] + '</td>';
							for (j = 10; j < 18; j++) {
								row = row + '<td>' + hyperopia[i][j] + '</td>';
							}
							row = row + '</tr>';
							rp_body = rp_body + row;
						}


						var rp_footer = '<tr><td>Sub</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>' +
							'<td>' + sub_hyperopia[10] + '</td>' +
							'<td>' + sub_hyperopia[11] + '</td>' +
							'<td>' + sub_hyperopia[12] + '</td>' +
							'<td>' + sub_hyperopia[13] + '</td>' +
							'<td>' + sub_hyperopia[14] + '</td>' +
							'<td>' + sub_hyperopia[15] + '</td>' +
							'<td>' + sub_hyperopia[16] + '</td>' +
							'<td>' + sub_hyperopia[17] + '</td>' +
							'<td colspan="2"></td></tr>';
						rp_footer = rp_footer + '<tr><td colspan="2">Minus Low</td><td colspan="2"></td>' +
							'<td colspan="2">Plus</td><td colspan="2"></td>' +
							'<td colspan="2">Mix</td><td colspan="2"></td>' +
							'<td colspan="2">High Cyl</td><td colspan="2"></td></tr>';

						rp_footer = rp_footer + '<tr><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td>' +
							'<td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%"></td><td width="5%">' +
							'</td><td width="5%"></td><td width="5%"></td><td width="5%"></td></tr>';
						html = '<table id="rp_inventory" class="table2excel">' + header + rp_body + rp_footer + '</table>';
						html = html + '<div style="height: 50px;"></div>'
						$('#view_report_lens_category').html(html);
						$('#expExcel').show();
					} else {
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}
				}else{ //display for 1.56 TC
					if (msg.result == 1) {
						var html = '';
						var header = '<tr><td colspan="28" class="rp_title">' + msg.data.header.title + '</td></tr>';
						header = header + '<tr><td colspan="4" class="rp_company">Công ty</td>' +
							'<td colspan="5">' + msg.data.header.company_name + '</td>' +
							'<td colspan="3"></td>' +
							'<td colspan="5"></td>' +
							'<td colspan="4">Khách hàng</td>' +
							'<td colspan="7">' + msg.data.header.customer + '</td></tr>';
						header = header + '<tr><td colspan="4" class="rp_des">Mô tả</td>' +
							'<td colspan="5">' + msg.data.header.description + '</td>' +
							'<td colspan="3"></td>' +
							'<td colspan="5"></td>' +
							'<td colspan="4">Ngày đặt hàng</td>' +
							'<td colspan="7">' + msg.data.header.ordered_date + '</td></tr>';
						header = header + '<tr><td colspan="4" class="rp_brand">Thương hiệu</td>' +
							'<td colspan="5">' + msg.data.header.brand + '</td>' +
							'<td colspan="3"></td>' +
							'<td colspan="5"></td>' +
							'<td colspan="4">MS đơn hàng</td>' +
							'<td colspan="7"></td></tr>';

						header = header + '<tr><td colspan="4" class="rp_rmk">RMK</td>' +
							'<td colspan="5">' + '</td>' +
							'<td colspan="3"></td>' +
							'<td colspan="5"></td>' +
							'<td colspan="4">Tổng số lượng (miếng)</td>' +
							'<td colspan="7">' + msg.data.total + '</td></tr>';

						header = header + '<tr><td class="rp_header">SPH</td>' +
							'<td colspan="25">CYL (-)</td>' +
							'</tr>';

						var header_rp = '<tr><td>(-)</td>' +
							'<td>-0.00</td><td>-0.25</td><td>-0.50</td><td>-0.75</td>' +
							'<td>-1.00</td><td>-1.25</td><td>-1.50</td><td>-1.75</td>' +
							'<td>-2.00</td><td>-2.25</td><td>-2.50</td><td>-2.75</td>' +
							'<td>-3.00</td><td>-3.25</td><td>-3.50</td><td>-3.75</td>' +
							'<td>-4.00</td><td>-4.25</td><td>-4.50</td><td>-4.75</td>' +
							'<td>-5.00</td><td>-5.25</td><td>-5.50</td><td>-5.75</td>' +
							'<td>-6.00</td>'+
							'</tr>';
						header = header + header_rp;
						//header = '' + header_rp;

						var rp_body = '';
						var myopia = msg.data.myopia;
						var hyperopia = msg.data.hyperopia;
						var re_map = msg.data.re_map;
						var sub_myopia = msg.data.sub_myopia;
						var sub_hyperopia = msg.data.sub_hyperopia;

						for (i = 1; i < 56; i++) {
							var row = '<tr class="rp_number"><td> -' + re_map[i] + '</td>';
							for (j = 1; j < 26; j++) {
								row = row + '<td>' + myopia[i][j] + '</td>';
							}
							row = row + '</tr>';
							rp_body = rp_body + row;
						}


						var row57 = '<tr row="57"><td>Sub</td>';
						for (i = 1; i < 26; i++) {
							row57 = row57 + '<td>' + sub_myopia[i] + '</td>';
						}

						rp_body = rp_body + row57 + '</tr>';

                        /*
						var row58 = '<tr row="58"><td colspan="8">Group</td><td colspan="4">Số lượng (miếng)</td><td colspan="6">Thành tiền</td><td rowspan="9" colspan="2"></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';


						row58 = '<tr><td colspan="8">-0.00/-0.00 ~ -6.00/-2.00</td><td colspan="4">' + msg.data.sub_group[0] + '</td><td colspan="6"></td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//--------------------
						row58 = '<tr><td colspan="8">-6.25/-0.00 ~ -8.00/-2.00</td><td colspan="4">' + msg.data.sub_group[1] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="8">-8.25/-0.00 ~ -15.00/-2.00</td><td colspan="4">' + msg.data.sub_group[2] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="8">+0.00/-0.00 ~ +6.00/-2.00</td><td colspan="4">' + msg.data.sub_group[3] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td> ' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="8">-0.00/-2.25 ~ -10.00/-3.00</td><td colspan="4">' + msg.data.sub_group[4] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="8">-0.00/-3.25 ~ -10.00/-4.00</td><td colspan="4">' + msg.data.sub_group[5] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="8">+0.25/-0.50 ~ +1.75/-2.00</td><td colspan="4">' + msg.data.sub_group[6] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						row58 = '<tr><td colspan="8">+0.25/-2.25 ~ +1.75/-4.00</td><td colspan="4">' + msg.data.sub_group[7] + '</td><td colspan="6">Thành tiền</td></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 8; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';

						//---------------------
						row58 = '<tr><td colspan="8"></td><td colspan="4"></td><td colspan="4"></td><td colspan="6"></td>';
						row58 = row58 + '<td>' + '' + '</td>';
						for (j = 1; j < 10; j++) {
							row58 = row58 + '<td>' + '' + '</td>';
						}
						rp_body = rp_body + row58 + '</tr>';
                        */

						html = html + '<table id="rp_inventory" class="table2excel">' + header + rp_body + '</table>';
						html = html + '<div style="height: 50px;"></div>';


                        header = '';
                        header = header + '<tr><td class="rp_header">SPH</td>' +
                            '<td colspan="25">CYL (-)</td>' +
                            '</tr>';


                        header_rp = '<tr><td>(+)</td>' +
                            '<td>-0.00</td><td>-0.25</td><td>-0.50</td><td>-0.75</td>' +
                            '<td>-1.00</td><td>-1.25</td><td>-1.50</td><td>-1.75</td>' +
                            '<td>-2.00</td><td>-2.25</td><td>-2.50</td><td>-2.75</td>' +
                            '<td>-3.00</td><td>-3.25</td><td>-3.50</td><td>-3.75</td>' +
                            '<td>-4.00</td><td>-4.25</td><td>-4.50</td><td>-4.75</td>' +
                            '<td>-5.00</td><td>-5.25</td><td>-5.50</td><td>-5.75</td>' +
                            '<td>-6.00</td>'+
                            '</tr>';
                        header = header + header_rp;
                        rp_body = '';
                        for (i = 1; i < 34; i++) {
                            var row = '<tr class="rp_number"><td> +' + re_map[i] + '</td>';
                            for (j = 1; j < 26; j++) {
                                row = row + '<td>' + hyperopia[i][j] + '</td>';
                            }
                            row = row + '</tr>';
                            rp_body = rp_body + row;
                        }

                        row57 = '<tr row="57"><td>Sub</td>';
                        for (i = 1; i < 26; i++) {
                            row57 = row57 + '<td>' + sub_hyperopia[i] + '</td>';
                        }

                        rp_body = rp_body + row57 + '</tr>';

                        html = html + '<table id="rp_inventory_hyp" class="table2excel">' + header + rp_body + '</table>';
                        html = html + '<div style="height: 50px;"></div>';

						$('#view_report_lens_category').html(html);
						$('#expExcel').show();
					} else {
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}
				}

			});
	});

});
</script>