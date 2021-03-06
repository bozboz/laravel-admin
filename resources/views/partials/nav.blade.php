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
					@if (count($items)>1)
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
					@else
						<li class="{{ $menu->activeClassForUrl(reset($items)) }}">
							<a href="{!! reset($items) !!}">
								<i class="fa fa-file-text"></i>
								{{ $item }}
							</a>
						</li>
					@endif
				@endforeach
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i>Logged in as {{ $user->first_name }} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						@if (Bozboz\Permissions\RuleStack::with('edit_profile', $user)->then('edit_anything')->isAllowed())
							<li><a href="/admin/users/{{ $user->id }}/edit"><i class="fa fa-wrench"></i> Edit Profile</a></li>
						@endif
						<li><a href="/"><i class="fa fa-desktop"></i> View Website</a></li>
						<li><a href="/admin/versions"><i class="fa fa-code-fork"></i> View Package Versions</a></li>
						@if(Route::has('styleguide'))
							<li><a href="{{ route('styleguide') }}"><i class="fa fa-book"></i> View Style Guide</a></li>
						@endif
						@if (Session::has('previous_user'))
							<li class="divider"></li>
							<li>
								{{ Form::open(['route' => 'admin.previous-user']) }}
									<button type="submit"><i class="fa fa-undo"></i> Switch back to previous user</button>
								{{ Form::close() }}
							</li>
						@endif
						<li class="divider"></li>
						<li><a href="/admin/logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
