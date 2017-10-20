@extends('layouts.plain')

@section('title', 'Login')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
<button class="btn btn-hg btn-primary">
  Boss Button
</button>
	<h2>{{ $variable_name }}</h2>
	<p>Some text here</p>
	<form class="form-horizontal" action="_request/login-form" method="post">
		<div class="form-group">
			<label class="control-label col-sm-2" for="email">Email:</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="pwd">Password:</label>
			<div class="col-sm-10">          
				<input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<label class="checkbox" for="checkbox1">
					<input type="checkbox" name="remember" value="" id="checkbox1" data-toggle="checkbox">
					Checkbox
        		</label>
				<script>
					$(':checkbox').radiocheck();
				</script>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Submit</button>
			</div>
		</div>
	</form>
@endsection