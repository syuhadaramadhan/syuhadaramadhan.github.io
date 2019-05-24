    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-mobile-alt"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Syuhada</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider">

      
      <!-- query menu -->
      <?php
        // Deklaraikan Mendapatkan Role Id Dari Session.
        $role_id = $this->session->userdata('role_id');
        // Query Join Tabel User Menu Dan User Acces Menu.
        $queryMenu =  "SELECT `user_menu`.`id`, `menu`
                      FROM `user_menu` JOIN `user_access_menu` 
                          ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                      WHERE `user_access_menu`.`role_id` = $role_id 
                      ORDER BY `user_access_menu`.`menu_id` ASC";
        // Dapatkan Hasil Query Dalam Bentuk Array.
        $menu = $this->db->query($queryMenu)->result_array();
      ?>

      <!-- Looping Menu -->
      <?php foreach($menu as $m) : ?>
        <div class="sidebar-heading">
          <?= $m['menu']; ?> 
        </div>
        <!-- siapkan sub menu sesuai menu -->
        <?php 
          // Deklaraikan Mendapatkan Menu Id Dari $querymenu.
          $menuId = $m['id'];
          // Query Join Tabel User Menu Dan User Sub Menu.
          $querySubMenu = "SELECT *
                          FROM `user_sub_menu` JOIN `user_menu` 
                          ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                          WHERE `user_sub_menu`.`menu_id` = $menuId
                          AND `user_sub_menu`.`is_active` = 1";
          // Dapatkan Hasil Query Dalam Bentuk Array.
          $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>
        <!-- Looping Sub Menu -->
        <?php foreach($subMenu as $sm) : ?>
          <!-- Kondisi Untuk Menandakan Menu Sedang Aktif -->
          <?php if ($title == $sm['title']) : ?>
            <li class="nav-item active">
          <?php else : ?>
            <li class="nav-item">
          <?php endif; ?>  
            <a class="nav-link" href="<?= base_url($sm['url']); ?>">
            <i class="<?= $sm['icon']; ?>"></i>
            <span><?= $sm['title']; ?></span></a>
          </li>
        <?php endforeach; ?>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <?php endforeach; ?>

      <!-- Heading -->
      <div class="sidebar-heading">
        Logout
      </div>

      <!-- Nav Item - My Profile -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->