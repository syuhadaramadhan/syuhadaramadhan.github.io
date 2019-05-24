<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
  <div class="row">
  	<div class="col-lg-6">
		<!-- Munculkan Alert Bila Data Tidak Valid Atau Gagal -->
		<?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>') ?>
		<!-- Munculkan Alert Bila Berhasil -->
		<?= $this->session->flashdata('message'); ?>
		<!-- Tabel Data Menu -->
  		<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNewMenuModal">Add New Menu</a>
  		<table class="table table-hover">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Menu</th>
		      <th scope="col">Action</th>
		    </tr>
		  </thead>
		  <tbody>
	    	<!-- Looping Menu -->
	    	<?php $i = 1; ?>
	    	<?php foreach($menu as $m) : ?>
	    		<tr>
			      	<th scope="row"><?= $i; ?></th>
			      	<td><?= $m['menu']; ?></td>
			      	<td>
						<a href="" class="badge badge-success">Edit</a>
						<a href="" class="badge badge-danger">Delete</a>
				    </td>
			    </tr>
			<?php $i++ ?>
			<?php endforeach; ?>
		  </tbody>
		</table>
  	</div>
  </div>
</div>
<!-- /.container-fluid -->

<!-- Code Modal Add New Menu -->
<!-- Modal (Data Targetnya Harus Sama Dengan Button Add New "data-toggle="modal" data-target="#addNewMenuModal"-->
<div class="modal fade" id="addNewMenuModal" tabindex="-1" role="dialog" aria-labelledby="addNewMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewMenuModalLabel">Add New Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu'); ?>" method="post">
      	<!-- Isi Modal -->
	    <div class="modal-body">
	        <div class="form-group">
			    <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu Name">
		  	</div>
	    </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Add</button>
	      </div>
      </form>
    </div>
  </div>
</div>