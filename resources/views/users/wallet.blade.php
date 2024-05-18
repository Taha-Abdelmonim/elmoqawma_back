<div class="modal fade" id="wallet" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-simple modal-edit-user">
		<div class="modal-content p-3 p-md-5">
			<div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<form class="row g-3" id="form_wallet" method="POST">
					@csrf
					<div class="col-12">
						<label class="form-label" for="code">@lang('global.code')</label>
						<input type="text" name="code" id="code" placeholder="@lang('global.code')" class="form-control" />
					</div>
					<div class="col-12 text-center">
						<button id="save_bank_money" type="submit" class="btn btn-primary me-sm-3 me-1 w-100"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang('global.save')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
