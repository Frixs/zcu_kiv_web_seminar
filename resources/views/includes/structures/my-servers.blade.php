@php
	$myservers = instance('UserGroup')::getUserServers(instance('Auth')::id());
@endphp

<div class="structures my-servers">
	{{-- FILTER INPUT --}}
	<div class="col-xs-10 gc-col-nosp-leftm">
		<input type="text" name="filter-my-servers" value="" class="form-control filter-input" data-filter="#my-events-filter" placeholder="{{ lang('structures.my-servers.filter_ph') }}"
	 maxlenght="100" tabindex="1" autocomplete="off">
	</div>
	<div class="col-xs-2 gc-col-nosp-rightm new-server-btn-wrapper">
		<a href="server/create" class="btn btn-primary ttip --tt-darkest --tt-fat" data-toggle="tooltip" data-placement="left auto" title="{{ lang('structures.my-servers.new_server_ph') }}">
			<i class="fa fa-plus" aria-hidden="true"></i>
		</a>
	</div>
	<div class="gc-cleaner"></div>
	<hr>

	@if ($myservers)
		<div id="my-events-filter">
		@foreach ($myservers as $server)
			<a href="server/server:{{ $server->server_id }}" class="server-box" data-server-id="{{ $server->server_id }}" data-filter-searchable>
				@if ($server->has_background_placeholder) <img src="{{ instance('Config')::get('app.root_server_uploads_rel') }}/{{ $server->server_id }}_background_placeholder.jpg" alt="" draggable="false" tabindex="-1"> @else <img src="images/structure/server_background_placeholder_default.jpg" alt="" draggable="false" tabindex="-1"> @endif
				<div class="title">
					{{ $server->name }}
				</div>
				<div class="info small">
					<div class="left">
						@if ($server->access_type == 0)
							<i class="fa fa-unlock" aria-hidden="true"></i> {{ lang('structures.my-servers.public') }}
						@elseif ($server->access_type == 1)
							<i class="fa fa-unlock-alt" aria-hidden="true"></i> {{ lang('structures.my-servers.protected') }}
						@else
							<i class="fa fa-lock" aria-hidden="true"></i> {{ lang('structures.my-servers.private') }}
						@endif
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
		<div class="text-center small">
			{{ lang('validation.no_results_found') }}
		</div>
	@endif
</div>