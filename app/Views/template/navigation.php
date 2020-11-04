<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
	<!-- <div class="sidebar-brand-icon rotate-n-15">
	  <i class="fas fa-laugh-wink"></i>
	</div> -->
	<div class="sidebar-brand-text mx-3">LiveSympo</div>
	<!-- <img src="/images/logo/logo_type1.png" style="width: 100%;" /> -->
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item dashboard">

<?php
    // lvl 2(데이터관리자)는 안보이도록
    if ($lvl != 2) {
?>
    <a class="nav-link" href="/dashboard">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
<?php
    }
?>

  <!-- Heading -->
  <div class="sidebar-heading">
	Project
  </div>

  <!-- Nav Item -->
  <li class="nav-item project">
	<a class="nav-link collapsed" href="/project">
	  <i class="fas fa-fw fa-cog"></i>
	  <span>Project</span>
	</a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">


<?php
    // lvl 2(데이터관리자)는 안보이도록
    if ($lvl != 2) {
?>
  <!-- Heading -->
  <div class="sidebar-heading">
	Admin
  </div>

  <!-- Nav Item -->
  <li class="nav-item admin">
	<a class="nav-link collapsed" href="/admin">
	  <i class="fas fa-fw fa-cog"></i>
	  <span>Admin</span>
	</a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">
<?php
    }
?>

</ul>
<!-- End of Sidebar -->
