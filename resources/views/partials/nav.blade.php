<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">
				@include('admin::partials.nav-logo')
			</a>
		</div>

		<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-left">
				<li class="{{ $menu->activeClassForUrl(URL::to('admin')) }}">
					<a href="{!! URL::to('admin') !!}">
						<i class="fa fa-home"></i>
						Home
					</a>
				</li>
				@foreach($menu->getTopLevelItems() as $item => $items)
					<li class="dropdown {{ $menu->activeClassForUrls($items) }}">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-file-text"></i>
							{{ $item }}
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							@foreach ($items as $item => $link)
								<li class="{{ $menu->activeClassForPartialUrl($link) }}">{!! link_to($link, $item) !!}</li>
							@endforeach
						</ul>
					</li>
				@endforeach
				@if ($menu->gate('view_users'))
					<li class="{{ $menu->activeClassForPartialUrl(url('admin/users')) }}">
						<a href="{{ url('admin/users') }}">
							<i class="fa fa-user"></i>
							Users
						</a>
					</li>
					<li class="{{ $menu->activeClassForPartialUrl(URL::route('admin.permissions.index')) }}">
						<a href="{{ URL::route('admin.permissions.index') }}">
							<i class="fa fa-cogs"></i>
							Permissions
						</a>
					</li>
				@endif
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i>Logged in as {{ $user->first_name }} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						@if (Bozboz\Permissions\RuleStack::with('edit_profile', $user->id)->then('edit_anything')->isAllowed())
							<li><a href="/admin/users/{{ $user->id }}/edit"><i class="fa fa-wrench"></i> Edit Profile</a></li>
						@endif
						<li><a href="/"><i class="fa fa-desktop"></i> View Website</a></li>
						<li class="divider"></li>
						<li><a href="/admin/logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
