<?php $this->load->view("partial/header"); ?>

<div id="page_title"><?php echo $this->lang->line('reports_report_input'); ?></div>

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

	<?php
	echo form_button(array(
		'name'=>'generate_report',
		'id'=>'generate_report',
		'content'=>$this->lang->line('common_submit'),
		'class'=>'btn btn-primary btn-sm')
	);
	?>
<?php echo form_close(); ?>
<div id="view_report_lens_category">

</div>
<div id="table_holder">
	<div id="toolbar">
		<div class="pull-left form-inline" role="toolbar">
			<!--
			<button id="delete" class="btn btn-default btn-sm print_hide">
				<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
			</button>
			-->
			<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
		
		</div>
	</div>	
	<table id="table"></table>
</div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript">
	$(document).ready(function()
	{
		<?php $this->load->view('partial/daterangepicker'); ?>

		$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {

			var csrf_ospos_v3 = csrf_token();
			var location_id = $('#location_id').val();
			var _strDate = $("#daterangepicker").val();
			var _aDates = _strDate.split(" - ");			
			var fromDate = _aDates[0];
			var toDate = _aDates[1];

			$.ajax({
				method: "POST",
				url: "<?php echo site_url('reports/ajax_inventory_sun_glasses')?>",
				data: { location_id: location_id, fromDate:fromDate,toDate:toDate ,csrf_ospos_v3: csrf_ospos_v3 },
				dataType: 'json'
			})
				.done(function( msg ) {
					if(msg.result == 1)
					{

						//var detail_data = msg.data.details_data;
						var header_summary = msg.data.headers_summary;
						var summary_data = msg.data.summary_data;
						var header_details = msg.data.headers_details;

						var init_dialog = function()
						{

						};
						//$('#table').bootstrapTable('refresh');
						$('#table').bootstrapTable({
							columns: header_summary,
							pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
							striped: true,
							pagination: true,
							sortable: true,
							showColumns: true,
							uniqueId: 'id',
							showExport: true,
							data: summary_data,
							iconSize: 'sm',
							paginationVAlign: 'bottom',
							detailView: true,
							uniqueId: 'id',
							escape: false,
							onExpandRow: function (index, row, $detail) {
								//alert(JSON.stringify(header_details));
								console.log(row);
								$detail.html('Đang cập nhật...');
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('reports/ajax_inventory_sun_glasses_by_cat')?>",
									data: { location_id: location_id, fromDate:fromDate,toDate:toDate ,csrf_ospos_v3: csrf_ospos_v3, cat: row.cat },
									dataType: 'json',
									success: function (result) {
										
										if(result.result == 1)
										{
											detail_data = result.data;
											$detail.html('<table></table>').find('table').bootstrapTable({
											columns:  header_details,
											data: detail_data,
											sortable: true,
											showExport: true,
											});
										} else {
											$detail.html('Không có sản phẩm nào ở đây');
										}
										/*
										$detail.html('<table></table>').find("table").bootstrapTable({
											columns: header_details,
											data: detail_data[row.id],
											sortable: true,
											showExport: true,
										}); */
									}
								});
							}
							
						});
						$('#table').bootstrapTable('load',{data: summary_data});
					}else{
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}

				});

		});
		<?php $this->load->view('partial/bootstrap_tables_locale'); ?>
		$("#generate_report").click(function()
		{
			var csrf_ospos_v3 = csrf_token();
			var location_id = $('#location_id').val();
			//var category = $('#category').val();
			var _strDate = $("#daterangepicker").val();
			var _aDates = _strDate.split(" - ");			
			var fromDate = _aDates[0];
			var toDate = _aDates[1];
			$.ajax({
				method: "POST",
				url: "<?php echo site_url('reports/ajax_inventory_sun_glasses')?>",
				//data: { location_id: location_id, category: category, csrf_ospos_v3: csrf_ospos_v3 },
				data: { location_id: location_id, fromDate:fromDate,toDate:toDate ,csrf_ospos_v3: csrf_ospos_v3 },
				dataType: 'json'
			})
				.done(function( msg ) {
					if(msg.result == 1)
					{

						//var detail_data = msg.data.details_data;
						var header_summary = msg.data.headers_summary;
						var summary_data = msg.data.summary_data;
						var header_details = msg.data.headers_details;

						var init_dialog = function()
						{

						};

						$('#table').bootstrapTable({
							columns: header_summary,
							pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
							striped: true,
							pagination: true,
							sortable: true,
							showColumns: true,
							uniqueId: 'id',
							showExport: true,
							data: summary_data,
							iconSize: 'sm',
							paginationVAlign: 'bottom',
							detailView: true,
							uniqueId: 'id',
							escape: false,
							onPageChange: init_dialog,
							onPostBody: function() {
								dialog_support.init("a.modal-dlg");
							},
							onExpandRow: function (index, row, $detail) {
								//alert(JSON.stringify(header_details));
								console.log(row);
								$detail.html('Đang cập nhật...');
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('reports/ajax_inventory_sun_glasses_by_cat')?>",
									data: { location_id: location_id, fromDate:fromDate,toDate:toDate ,csrf_ospos_v3: csrf_ospos_v3, cat: row.cat },
									dataType: 'json',
									success: function (result) {
										
										if(result.result == 1)
										{
											detail_data = result.data;
											$detail.html('<table></table>').find('table').bootstrapTable({
											columns:  header_details,
											data: detail_data,
											sortable: true,
											showExport: true,
											});
										} else {
											$detail.html('Không có sản phẩm nào ở đây');
										}
										/*
										$detail.html('<table></table>').find("table").bootstrapTable({
											columns: header_details,
											data: detail_data[row.id],
											sortable: true,
											showExport: true,
										}); */
									}
								});
							}
						});

						init_dialog();


					}else{
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}

				});
		});

	});
</script>