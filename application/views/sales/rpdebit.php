<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{

	// when any filter is clicked and the dropdown window is closed

	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>
    // set the beginning of time as starting date
    $('#daterangepicker').data('daterangepicker').setStartDate("<?php echo date($this->config->item('dateformat'), mktime(0,0,0,01,01,2010));?>");
	// update the hidden inputs with the selected dates before submitting the search data
    //var start_date = "<?php echo date('Y-m-d', mktime(0,0,0,01,01,2010));?>";
	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
        table_support.refresh();
    });

    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    var csrf_ospos_v3 = csrf_token();
	//var location_id = $('#location_id').val();
	//var category = $('#category').val();
	//var _strDate = $("#daterangepicker").val();
	//var _aDates = _strDate.split(" - ");			
	//var fromDate = _aDates[0];
	//var toDate = _aDates[1];
	var customer_uuid = $('#customer_uuid').val();
	$.ajax({
		method: "POST",
		url: "<?php echo site_url('sales/ajax_rp_debits')?>",
		//data: { location_id: location_id, category: category, csrf_ospos_v3: csrf_ospos_v3 },
		//data: { location_id: location_id, fromDate:fromDate,toDate:toDate ,csrf_ospos_v3: csrf_ospos_v3 },
		data: { csrf_ospos_v3: csrf_ospos_v3, customer_uuid: customer_uuid },
		dataType: 'json'
	})
		.done(function( msg ) {
			//console.log(msg);
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
					uniqueId: 'sale_id',
					showExport: true,
					data: summary_data,
					iconSize: 'sm',
					paginationVAlign: 'bottom',
					detailView: true,
					escape: false,
					onPageChange: init_dialog,
					onPostBody: function() {
						dialog_support.init("a.modal-dlg");
					},
					onExpandRow: function (index, row, $detail) {
						//alert(JSON.stringify(header_details));
						console.log(detail_data[row.id]);
						$detail.html('<table></table>').find("table").bootstrapTable({
							columns: header_details,
							data: detail_data[row.id],
							sortable: true,
							showExport: false,
						});
					} 
				});

				init_dialog();


			}else{
				$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
			}

		});
});
</script>

<div id="page_title">Thông tin công nợ của khách hàng</div>

<?php
if(isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}
?>
<div class="customer_information">
	<div class="row" id="customer_name">
		<div class="col-xs-6">
			Họ và tên:
		</div>
		<div class="col-xs-6">
			<?=$customer_name?>
		</div>
	</div>
	<div class="row" id="customer_phone">
		<div class="col-xs-6">
			Điện thoại:
		</div>
		<div class="col-xs-6">
		<?=$customer_phone?>
		</div>
	</div>
	<div class="row" id="customer_address">
		<div class="col-xs-6">
			Địa chỉ:
		</div>
		<div class="col-xs-6">
		<?=$customer_address?>
		</div>
	</div>
</div>

<div id="view_report_lens_category">
	<input type="hidden" name="customer_uuid" id="customer_uuid" value="<?=$customer_uuid?>"/>
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