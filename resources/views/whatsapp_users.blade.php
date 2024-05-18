@foreach($users as $user)
	<tr>
		<td>{{$user->name}}</td>
		<td>{{$user->phone}}</td>
		<td>{{$user->section}}</td>
		<td colspan="5">
			<div class="d-flex align-items-center gap-3">
				<button type="button" class="deleteRow btn btn-danger bg-transparent"><i class="fa-solid fa-trash text-danger"></i></button>
			</div>
		</td>
	</tr>
@endforeach