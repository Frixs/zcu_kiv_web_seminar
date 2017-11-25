@php
	$authUID 	= instance('Auth')::id();
	$topservers = instance('Server')::getTopServers();
    $i = 0;
@endphp

<div class="structures community-box">
	{{-- FILTER INPUT --}}
	<input type="text" name="filter-community-box" value="" class="form-control search-input-top-servers" data-search="#search-servers-results" placeholder="{{ lang('structures.community-box.filter_ph') }}"
	 maxlenght="100" tabindex="1" autocomplete="off">
	<hr>

	<div id="search-servers-results">
	</div>

	@if ($topservers)
		@foreach ($topservers as $server)
			<div class="server-box" data-server-id="{{ $server->id }}">
                <div class="rank-badge">{{ ++$i }}</div>
				@if ($server->has_background_placeholder) <img src="{{ instance('Config')::get('app.root_server_uploads_rel') }}/{{ $server->id }}_background_placeholder.jpg" alt="" draggable="false" tabindex="-1"> @else <img src="images/structure/server_background_placeholder_default.jpg" alt="" draggable="false" tabindex="-1"> @endif
				<div class="title">
					{{ $server->name }}
				</div>
				<div class="info small">
					<div class="left">
						@if ($server->access_type == 0)
							@if (instance('User')::hasServerAccess($authUID, $server->id))
								<i class="fa fa-lock" aria-hidden="true"></i> {{ lang('structures.community-box.public') }}
							@else
								<a href="server/join/server:{{ $server->id }}" class="btn btn-primary"><i class="fa fa-unlock" aria-hidden="true"></i> {{ lang('structures.community-box.join') }}</a>
							@endif
						@elseif ($server->access_type == 1)
							@if (instance('User')::hasServerAccess($authUID, $server->id))
								<i class="fa fa-lock" aria-hidden="true"></i> {{ lang('structures.community-box.protected') }}
							@else
								<a href="server/send-request/server:{{ $server->id }}" class="btn btn-primary"><i class="fa fa-unlock-alt" aria-hidden="true"></i> {{ lang('structures.community-box.send_request') }}</a>
							@endif
						@else
							<i class="fa fa-lock" aria-hidden="true"></i> {{ lang('structures.community-box.private') }}
						@endif
					</div>
					<div class="right">
						<i class="fa fa-users" aria-hidden="true"></i> {{ $server->user_count }}
					</div>
					<div class="gc-cleaner"></div>
				</div>
			</div>
		@endforeach
	@else
		<div class="text-center small no-results">
			{{ lang('validation.no_results_found') }}
		</div>
	@endif
</div>