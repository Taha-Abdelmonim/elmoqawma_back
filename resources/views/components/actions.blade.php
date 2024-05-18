<div class="d-flex align-items-center">
	@isset($show)
		<form method="GET" action="{{ route("$name.show", $params ?? []) }}" class="me-2">
			<button type="submit" class="btn btn-success py-2"><i class="fa-solid fa-eye"></i></button>
		</form>
	@endisset
	@empty($edit)
		<form method="GET" action="{{ route("$name.edit", $params ?? []) }}" class="me-2">
			<button type="submit" class="btn btn-primary py-2"><i class="fa-regular fa-pen-to-square"></i></button>
		</form>
	@endempty
	<form method="POST" action="{{ route("$name.destroy", $params ?? []) }}">
		@csrf @method('DELETE')
		<button type="submit" onclick="return confirm('هل أنت متأكد؟')" class="btn btn-danger py-2"><i class="fa-solid fa-trash-can"></i></button>
	</form>
</div>