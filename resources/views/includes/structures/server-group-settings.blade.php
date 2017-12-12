@section('server-group-settings')
@php
    $userCurrentGroups = instance('Server')::getUserGroups($thisserver->id, instance('Auth')::id());
    $isServerOwner = instance('User')::isServerOwner(instance('Server')::getServerID());
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
        <div class="col-xs-6 group-box gc-col-nosp" data-serverid="{{  $thisserver->id }}">
            @if ($userCurrentGroups[0]->priority_max >= $userGroups[0]->priority_max || $isServerOwner)
                <select class="form-control" data-uid="{{ $user->id }}">
                @php $isSelected = false; @endphp
                @foreach (instance('Group')::getAllServerGroups(true) as $groupKey => $group)
                    @if ($userCurrentGroups[0]->priority_max >= $group['priority'] || $isServerOwner)
                        <option value="{{ $groupKey }}" style="color:#{{ $group['color'] }};" @if ($userGroups[0]->id == $groupKey && !$isSelected) selected="selected" @php $isSelected = true; @endphp @endif>{{ $group['name'] }}</option>
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