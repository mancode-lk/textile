<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      <ul>
        <?php $pg_name = basename($_SERVER['PHP_SELF']); ?>
        <li class="<?php if($pg_name=='index.php'){echo('active');} ?>" >
          <a href="index.php" ><img src="assets/img/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
        </li>

        
        
        <li class="<?php if($pg_name=='pos.php'){echo('active');} ?>" >
          <a href="pos.php" ><i class="fa fa-desktop"></i><span> POS</span> </a>
        </li>
        
        <li class="<?php if($pg_name=='vendorlist.php'){echo('active');} ?>" >
          <a href="vendor.php" ><i class="fa fa-desktop"></i><span> Vendor Management</span> </a>
        </li>
        <li class="<?php if($pg_name=='sales_report.php'){echo('active');} ?>" >
          <a href="sales_report.php" ><img src="assets/img/icons/sale.svg" alt="img"><span> Sales Report</span> </a>
        </li>
       

     
      

       
        <li class="submenu">
          <a href="javascript:void(0);"><i class="fa fa-user" data-bs-toggle="tooltip" title="" data-bs-original-title="fa fa-anchor" aria-label="fa fa-anchor"></i>
            <span> User Management</span> <span class="menu-arrow"></span></a>
          <ul>
            <li><a class="<?php if($pg_name=='add_user.php'){echo('active');} ?>" href="add_user.php">User List</a></li>
            <li><a class="<?php if($pg_name=='user_access.php'){echo('active');} ?>" href="user_access.php">Manage Users</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</div>
