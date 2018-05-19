<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="col-md-2">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <br>
            <br>
            <div class="col-sm-10">
              <select name="category_status" id="input-status" class="form-control">
                <?php if ($category_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="col-md-10">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_save_header; ?></h3>
      </div>
      <div class="panel-body">
        <div class="file_in">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Размер Zip файла</th>
              <th>Скачать</th>
              <th>Удалить архив с базами</th>
            </tr>
            </thead>
            <tbody>
            <tr class="td-in info">
              <div >
                <?php if (isset($file_size)){ ?>
                <td><?php echo $file_size ?></td>
                <td><a href="<?php echo $file_link ?>">Скачать бекам sql</a></td>
                <td><a href="<?php echo $action.'&add';?>" class="btn btn-danger">X</a></td>
                <?php } ?>
              </div>
            </tr>
            </tbody>
          </table>
        </div>
        <a href="#" onclick="return false" class="btn btn-success save_sql"> <?php echo (isset($button_save_sql)) ?  $button_save_sql : 'Создать базу' ?> </a>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<script>
  $(function (){
      $('.save_sql').on('click',function () {
          var url =  "'<?php echo $action; ?>'";
          console.log(url);
          $.ajax({
              url: 'index.php?route=extension/module/backup_sql&token='+ '<?php echo $token; ?>',
              method: "POST",
              data : "save_sql='444'"
          }).done(function(smg){
            var dat = JSON.parse(smg);
              $(".td-in").html("<td>"+ dat.size +"</td><td><a href='http://" + dat.link + "'>Скачать бекам sql</a></td> <td><a href='#' class='btn btn-danger'>X</a></td>");
              $('.save_sql').text('Добавить базу в архив');
              console.log(JSON.parse(smg));
                  });
      });
  })
</script>
<?php echo $footer; ?>