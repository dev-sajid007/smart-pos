<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
<title>Smart Account - Best Accounting Software In Bangladesh</title>
<meta name="title" content="Smart Account - Best Accounting Software In Bangladesh" />
<meta property="og:title" content="Best Accounting Software In Bangladesh - Smart Account">
<meta property="og:image" content="/assets/logo.png">
<meta property="og:image:secure_url" content="/assets/logo.png">
<meta property="og:image:alt" content="Smart Account - Best Accounting Software In Bangladesh"">
<meta property="og:image:type" content="image/png">
<meta name="twitter:title" content="Smart Account - Best Accounting Software In Bangladesh">
<meta name="Googlebot" content="all" />
<meta http-equiv="imagetoolbar" content="yes" />
<meta name="Author" content="Smart Software Ltd." />
<meta name="Rating" content="General" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="distribution" content="Global" />
<meta property="article:publisher" content="https://www.facebook.com/smartsoftbd">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:creator" content="@SmartSoftwarebd">

    <!--  favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicons/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/favicons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/favicons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/main.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
     <!-- <title>Login - {{ config('app.project_title') }}</title> -->
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
    
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


      <div class="login-box"> 
        
        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
          
            <h3 class="login-head">
              <img src="{{ asset('assets/logo.png') }}" alt="" width="200px" height="80px">
            </h3>
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input name="email" value="{{ old('email') }}" class="form-control" value="admin@gmail.com" type="text" placeholder="Email" required="required" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input name="password" value="{{ old('password') }}" class="form-control" value="12345678" type="password" required="required" placeholder="Password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
          </div>
        </form>
        
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('assets/admin/js/plugins/pace.min.js') }}"></script>
    <script type="text/javascript">
      // Login Page Flipbox control
      $('.login-content [data-toggle="flip"]').click(function() {
      	$('.login-box').toggleClass('flipped');
      	return false;
      });
    </script>
  </body>
</html>