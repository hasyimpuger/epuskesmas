<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>


<section class="content">
<form action="<?php echo base_url()?>mst/inv_ruangan/{action}/{code}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-footer pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>mst/inv_ruangan'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Id</label>
              <input type="text" class="form-control" name="id_mst_inv_ruangan" placeholder="Id" readonly value="<?php 
                if(set_value('id_mst_inv_ruangan')=="" && isset($id_mst_inv_ruangan)){
                  echo $id_mst_inv_ruangan;
                }else{
                  echo  set_value('id_mst_inv_ruangan');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Nama Ruangan</label>
              <input type="text" class="form-control" name="nama_ruangan" placeholder="Nama Ruangan" value="<?php 
                if(set_value('nama_ruangan')=="" && isset($nama_ruangan)){
                  echo $nama_ruangan;
                }else{
                  echo  set_value('nama_ruangan');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Keterangan</label>
              <input type="text" class="form-control" name="keterangan" placeholder="Keterangan" value="<?php 
                if(set_value('keterangan')=="" && isset($keterangan)){
                  echo $keterangan;
                }else{
                  echo  set_value('keterangan');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Kode</label>
              <input type="text" class="form-control" name="code_cl_phc" placeholder="Kode" readonly value="<?php 
                if(set_value('code')=="" && isset($code)){
                  echo $code;
                }else{
                  echo  set_value('code');
                }
                ?>">
            </div>
          </div>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
    $("#menu_mst_inv_ruangan").addClass("active");
    $("#menu_parameter").addClass("active");
	});
</script>