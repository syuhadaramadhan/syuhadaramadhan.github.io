<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
  <div class="row">
  	<div class="col-lg">
		<!-- Munculkan Alert Bila Data Tidak Valid Atau Gagal -->
		<?php if (validation_errors()): ?>
			<div class="alert alert-danger" role="alert">
				<?= validation_errors(); ?>
			</div>
		<?php endif; ?>
		<!-- Munculkan Alert Bila Berhasil -->
		<?= $this->session->flashdata('message'); ?>
		<!-- Tabel Data Menu -->
		<div class="container">
						<div class="row">
										<div class="col-lg-9">
										<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNewSubmenuModal">Add New Submenu</a>
										</div>
									<div class="col-lg-3 pull-right">
								<div class="form-group">
								<input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Search">
							</div>
								</div>
						</div>
			</div>
  		<table class="table table-hover">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Title</th>
		      <th scope="col">Menu</th>
		      <th scope="col">Url</th>
		      <th scope="col">Icon</th>
		      <th scope="col">Active</th>
		      <th scope="col">Action</th>
		    </tr>
		  </thead>
		  <tbody>
	    	<!-- Looping Menu -->
	    	<?php $i = 1; ?>
	    	<?php foreach($subMenu as $sm) : ?>
	    		<tr>
			      	<th scope="row"><?= $i; ?></th>
			      	<td><?= $sm['title']; ?></td>
			      	<td><?= $sm['menu']; ?></td>
			      	<td><?= $sm['url']; ?></td>
			      	<td><?= $sm['icon']; ?></td>
			      	<td><?= $sm['is_active']; ?></td>
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
<!-- Modal (Data Targetnya Harus Sama Dengan Button Add New "data-toggle="modal" data-target="#addNewSubmenuModal"-->
<div class="modal fade" id="addNewSubmenuModal" tabindex="-1" role="dialog" aria-labelledby="addNewSubmenuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewSubmenuModalLabel">Add New Submenu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu/submenu'); ?>" method="post">
      	<!-- Isi Modal -->
	    <div class="modal-body">
	        <div class="form-group">
			    <input type="text" class="form-control" id="title" name="title" placeholder="Submenu title">
		  	</div>
		  	<div class="form-group">
		  		<select name="menu_id" id="menu_id" class="form-control">
		  			<option value="">Select menu</option>
		  			<!-- Looping Isi Data User Menu -->
		  			<?php foreach($menu as $m): ?>
		  			<option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
		  			<?php endforeach; ?>
		  		</select>
		  	</div>
		  	<div class="form-group">
			    <input type="text" class="form-control" id="url" name="url" placeholder="Submenu url">
		  	</div>
		  	<div class="form-group">
			    <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon">
		  	</div>
		  	<div class="form-group">
		  		<div class="form-check">
				  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
				  <label class="form-check-label" for="is_active">
				    Active?
				  </label>
				</div>
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