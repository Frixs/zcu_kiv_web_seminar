@section('server-group-settings')
@php
    $userCurrentGroups = instance('Server')::getUserGroups($thisserver->id, instance('Auth')::id());
@endphp

<div class="server-group-settings-wrapper">
@foreach (instance('Server')::getMembers($thisserver->id) as $user)
    @php
        $userGroups = instance('Server')::getUserGroups($thisserver->id, $user->id);
    @endphp
    <div class="user-wrapper">
        <div class="col-xs-6 name-box gc-col-nosp">
        {{ $user->username }}
        </div>
        <div class="col-xs-6 group-box gc-col-nosp">
            @if ($userCurrentGroups[0]->priority_max -10 >= $userGroups[0]->priority_max)
                <select class="form-control" data-uid="{{ $user->id }}">
                @foreach (instance('Group')::getAllServerGroups(true) as $groupKey => $group)
                    @if ($userCurrentGroups[0]->priority_max >= $group['priority'])
                        <option style="color:#{{ $group['color'] }};" @if ($userGroups[0]->id == $groupKey) selected="selected" @endif>{{ $group['name'] }}</option>
                    @endif
                @endforeach
                </select>
            @else
                <span style="color:#{{ $userGroups[0]->color }};">{{ $userGroups[0]->name }}</span>
            @endif
        </div>
        <div class="gc-cleaner"></div>
    </div>
@endforeach
</div>

@endsection