<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
   
    $('#table').bootstrapTable();
    $('#table2').bootstrapTable();
  
});
</script>

<div id="title_bar" class="btn-toolbar print_hide">
    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-new='<?php echo $this->lang->line('common_new') ?>' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/view"); ?>'
            title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
        <span class="glyphicon glyphicon-tag">&nbsp</span><?php echo $this->lang->line($controller_name. '_new'); ?>
    </button>
</div>

<div id="toolbar">
   
</div>

<div id="table_holder">
    <table id="table"
    data-toggle="table"
  data-show-columns="true">
        <thead>
            <tr>
                <td data-field="name"></td>
                <td data-field="value"></td>
            </tr>
        </thead>
        <tbody>
    <tr id="tr-id-1" class="tr-class-1" data-title="bootstrap table" data-object='{"key": "value"}'>
      <td id="td-id-1" class="td-class-1" data-title="bootstrap table">
         Tên nhóm quyền
      </td>
      <td data-value="526"><?=$theRole->name?></td>
    </tr>
    <tr id="tr-id-2" class="tr-class-1" data-title="bootstrap table" data-object='{"key": "value"}'>
      <td id="td-id-2" class="td-class-1" data-title="bootstrap table">
         Mã (code)
      </td>
      <td data-value="526"><?=$theRole->code?></td>
    </tr>
    <tr>
      <td colspan="2">
         <table id="table2"
                data-toggle="table"
              data-show-columns="true">
            <thead>
                <tr>
                    <td data-field="stt">STT</td>   
                    <td data-field="name">Tên</td>                    
                    <td data-field="module">Mô đun</td>
                    <td data-field="actions">Action</td>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($permissions)): ?>
                    <tr><td colspan="3"> Chưa được cấp quyền nào</td></tr>
                <?php  else : ?>
                  <?php $i =1; foreach($permissions as $permission): ?>
                    <tr>
                      <td><?=$i?></td>
                      <td><?=$permission->name?></td>
                      <td><?=$permission->module_key?></td>
                      <td><?=$permission->permission_key?></td>
                    </tr>
                    <?php $i++; endforeach; ?>
                <?php endif; ?>
            </tbody>

         </table>
      </td>
    </tr>
  </tbody>

    </table>
</div>

<?php $this->load->view("partial/footer"); ?>
