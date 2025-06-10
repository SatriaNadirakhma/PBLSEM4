<style>
  .swal2-popup.tiny-swal {
    width: 350px ;
    max-width: 250px ;
    padding: 0.5rem 0.8rem ;
    font-size: 0.75rem;
    min-height: unset ;
    box-sizing: border-box;
  }

  .swal2-title {
    font-size: 0.95rem ;
    margin: 0 0 0.5rem ;
    line-height: 1.2;
  }

  .swal2-icon {
    width: 3em ;
    height: 3em ;
    margin: 0 auto 0.4rem ;
  }

  .swal2-icon .swal2-icon-content {
    font-size: 1.4em ;
  }

  .swal2-actions {
    justify-content: center ;
    gap: 0.5rem;
  }

  .swal2-actions button {
    padding: 0.5rem 0.9rem ;
    font-size: 0.8rem;
  }
</style>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="/dashboard" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <!-- <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a> -->
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown">
      <!-- <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a> -->
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Brad Diesel
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Call me whenever you can...</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                John Pierce
                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">I got your message bro</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Nora Silvester
                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">The subject goes here</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
   <!-- User profile dropdown -->
    <li class="nav-item dropdown user-menu" style="position: relative;">
      <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown"
        style="padding: 0.5rem 1rem;">
        <img
          src="{{ auth()->user()->profile ? asset('storage/' . auth()->user()->profile) : asset('img/default-profile.png') }}"
          class="user-image img-circle elevation-1" alt="Profile Picture"
          style="width: 32px; height: 32px; object-fit: cover; margin-right: 8px;">
        <span class="d-none d-md-inline" style="line-height: 32px;">{{ auth()->user()->nama }}</span>
        <i class="fas fa-chevron-down ml-2"></i>
      </a>

      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class="user-header bg-primary">
          <img
            src="{{ auth()->user()->profile ? asset('storage/' . auth()->user()->profile) : asset('img/default-profile.png') }}"
            class="img-circle elevation-2" alt="Profile Picture"
            style="width: 100px; height: 100px; object-fit: cover;">
          <p>
            {{ auth()->user()->nama }}
            <small class="text-capitalize">{{ auth()->user()->role }}</small>
          </p>
        </li>



        <!-- Menu Footer-->
        <li class="user-footer d-flex justify-content-between">
          <a href="{{ url('/profile') }}" class="btn btn-default btn-flat">
            <i class="fas fa-user mr-1"></i> Profile
          </a>
          <a href="#" class="btn btn-default btn-flat"
            onclick="confirmLogout(event)">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>

        <!-- Logout confirmation -->
        <script>
          function confirmLogout(event) {
            event.preventDefault();

            Swal.fire({
              title: 'Yakin logout?',
              icon: 'warning',
              position: 'top-end',
              showCancelButton: true,
              confirmButtonText: 'Ya',
              cancelButtonText: 'Batal',
              showCloseButton: true,
              customClass: {
                popup: 'tiny-swal'
              }
            }).then((result) => {
              if (result.isConfirmed) {
                document.getElementById("logout-form").submit();
              }
            });

          }
        </script>

       


      </ul>
    </li>
  </ul>
</nav>