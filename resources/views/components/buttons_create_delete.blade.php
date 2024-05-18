<div class="card-header border-bottom">
	<div class="d-flex align-items-center justify-content-between mb-4">
		<h5 class="card-title mb-3">{{trans("global.search_all_fields")}}</h5>
		<div class="d-flex align-items-center">
			<div class="demo-inline-spacing  me-3">
				<div class="btn-group mt-0">
					<button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown">{{trans("global.action_select")}}</button>
					<ul class="dropdown-menu">
						<li>
							<form @if(isset($route)) action="{{route($route)}}" @else action="{{route('delete.all', ucfirst(substr($name, 0, -1)))}}" @endif  method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer">
								<i class="ti ti-trash ti-sm me-2"></i> @csrf
								<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
							</form>
						</li>
						{{--<li>
							<a class="dropdown-item" href="javascript:void(0);"><i
									class="ti ti-printer me-2"></i>{{trans("global.print")}}</a>
						</li>--}}
					</ul>
				</div>
			</div>

			<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route("$name.create", $params ?? [])}}">
				<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
					<span class="d-none d-sm-inline-block">{{trans("global.create")}} </span>
				</span>
			</a>
			@yield("all-buttons")
		</div>
	</div>

</div>