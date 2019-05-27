    <!-- sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- sidebar - brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-mobile-alt"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Syuhada</div>
      </a>

      <!-- divider -->
      <hr class="sidebar-divider">

      
      <!-- query menu -->
      <?php
        // deklaraikan mendapatkan role id dari session.
        $role_id = $this->session->userdata('role_id');
        // query join tabel user menu dan user acces menu.
        $queryMenu =  "SELECT `user_menu`.`id`, `menu`
                      FROM `user_menu` JOIN `user_access_menu` 
                          ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                      WHERE `user_access_menu`.`role_id` = $role_id 
                      ORDER BY `user_access_menu`.`menu_id` ASC";
        // dapatkan hasil query dalam bentuk array.
        $menu = $this->db->query($queryMenu)->result_array();
      ?>

      <!-- looping menu -->
      <?php foreach($menu as $m) : ?>
        <div class="sidebar-heading">
          <?= $m['menu']; ?> 
        </div>
        <!-- siapkan sub menu sesuai menu -->
        <?php 
          // deklaraikan mendapatkan menu id dari $querymenu.
          $menuId = $m['id'];
          // query join tabel user menu dan user sub menu.
          $querySubMenu = "SELECT *
                          FROM `user_sub_menu` JOIN `user_menu` 
                          ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                          WHERE `user_sub_menu`.`menu_id` = $menuId
                          AND `user_sub_menu`.`is_active` = 1";
          // dapatkan hasil query dalam bentuk array.
          $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>
        <!-- looping sub menu -->
        <?php foreach($subMenu as $sm) : ?>
          <!-- kondisi untuk menandakan menu sedang aktif -->
          <?php if ($title == $sm['title']) : ?>
            <li class="nav-item active">
          <?php else : ?>
            <li class="nav-item">
          <?php endif; ?>  
            <a class="nav-link pb-0" href="<?= base_url($sm['url']); ?>">
            <i class="<?= $sm['icon']; ?>"></i>
            <span><?= $sm['title']; ?></span></a>
          </li>
        <?php endforeach; ?>
      <!-- divider -->
      <hr class="sidebar-divider mt-3">

      <?php endforeach; ?>

      <!-- heading -->
      <div class="sidebar-heading">
        Logout
      </div>

      <!-- nav item - my profile -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>

      <!-- sidebar toggler (sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- end of sidebar -->