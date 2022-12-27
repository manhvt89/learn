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
<div id="table_holder"><table id="table"></table></div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript">
	$(document).ready(function()
	{
		<?php $this->load->view('partial/bootstrap_tables_locale'); ?>
		$("#generate_report").click(function()
		{
			var csrf_ospos_v3 = csrf_token();
			var location_id = $('#location_id').val();
			//var category = $('#category').val();
			$.ajax({
				method: "POST",
				url: "<?php echo site_url('reports/ajax_inventory_lens')?>",
				data: { location_id: location_id, csrf_ospos_v3: csrf_ospos_v3 },
				dataType: 'json'
			})
				.done(function( msg ) {
					if(msg.result == 1)
					{

						var detail_data = msg.data.details_data;
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
							detailView: false,
							uniqueId: 'id',
							escape: false
							
						});
					}else{
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}

				});
		});

	});
</script>