@php
	$topservers = instance('Server')::getTopServers();
    $i = 0;
@endphp

<div class="structures my-servers">
	{{-- FILTER INPUT --}}
	<input type="text" name="filter-my-servers" value="" class="form-control filter-input" data-filter="#search-servers-filter" placeholder="{{ lang('structures.community-box.filter_ph') }}"
	 maxlenght="100" tabindex="1" autocomplete="off">
	<hr>

	<div id="search-servers-filter">
	</div>
    <hr>

	@if ($topservers)
		@foreach ($topservers as $server)
			<a href="#" class="server-box" data-server-id="{{ $server->id }}">
                <div class="rank-badge">{{ ++$i }}</div>
				@if ($server->has_background_box) <img src="images/structure/bg_server_default.jpg" alt="" draggable="false" tabindex="-1"> @else <img src="images/structure/bg_server_default.jpg" alt="" draggable="false" tabindex="-1"> @endif
				<div class="title">
					{{ $server->name }}
				</div>
				<div class="info small">
					<div class="left">
{{-- TODO BUTTONS TO JOIN SERVER --}}
						@if ($server->is_private) <i class="fa fa-lock" aria-hidden="true"></i> {{ lang('structures.community-box.private') }} @else <i class="fa fa-unlock" aria-hidden="true"></i> {{ lang('structures.community-box.public') }} @endif
					</div>
					<div class="right">
						<i class="fa fa-users" aria-hidden="true"></i> {{ $server->user_count }}
					</div>
					<div class="gc-cleaner"></div>
				</div>
			</a>
		@endforeach
	@else
		<div class="text-center small no-results">
			{{ lang('validation.no_results_found') }}
		</div>
	@endif
</div>