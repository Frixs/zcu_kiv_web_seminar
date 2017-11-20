@php
	$myservers = instance('UserGroup')::getUserServers(instance('Auth')::id());
@endphp

<div class="structures my-servers">
	{{-- FILTER INPUT --}}
	<input type="text" name="filter-my-servers" value="" class="form-control filter-input" data-filter="#my-events-filter" placeholder="{{ lang('structures.my-servers.filter_ph') }}"
	 maxlenght="100" tabindex="1" autocomplete="off">
	<hr>

	@if ($myservers)
		<div id="my-events-filter">
		@foreach ($myservers as $server)
			<a href="#" class="server-box" data-server-id="" data-filter-searchable>
				@if ($server->has_background_box) <img src="images/structure/bg_server_default.jpg" alt="" draggable="false" tabindex="-1"> @else <img src="images/structure/bg_server_default.jpg" alt="" draggable="false" tabindex="-1"> @endif
				<div class="title">
					{{ $server->name }}
				</div>
				<div class="info small">
					<div class="left">
						@if ($server->is_private) <i class="fa fa-lock" aria-hidden="true"></i> {{ lang('structures.my-servers.private') }} @else <i class="fa fa-unlock" aria-hidden="true"></i> {{ lang('structures.my-servers.public') }} @endif
					</div>
					<div class="right">
						<i class="fa fa-users" aria-hidden="true"></i> {{ $server->user_count }}
					</div>
					<div class="gc-cleaner"></div>
				</div>
			</a>
		@endforeach
		</div>
	@else
		<p>nothing here</p>
	@endif
</div>