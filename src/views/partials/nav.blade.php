
<nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active">{{ link_to('admin', "Admin home") }}</li>
        <li>{{ HTML::link('','Settings') }}</li>
      </ul>

        <ul class="nav navbar-nav">
        @foreach ($menu->getAttributes() as $item => $link)
            <li>{{ link_to($link, $item) }}</li>
        @endforeach
        </ul>

      <ul class="nav navbar-nav navbar-right">
        <li>{{link_to('admin/logout', "Logout");}}</li>
       
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>