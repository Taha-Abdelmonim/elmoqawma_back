@extends('layouts/layoutMaster')

@section('title', trans("global.edit_subscription"))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection
@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.subscriptions"),
			"/" => trans("global.dashboard"),
			"/posts" => trans("global.all_subscriptions"),
			"end" => trans("global.edit_subscription"),
		]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('order_update', [$type, $order->id])}}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="col-12">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="card mb-4">
				<h5 class="card-header">@lang("global.edit_subscription")</h5>
				<div class="card-body">
					<a href="{{$order->photo != null || $order->photo != "" ? asset('images/' . $order->photo) : $order->id}}" target="_blank" class="avatar w-px-200 h-px-200 d-block mx-auto mb-4">
						@if($order->photo != null || $order->photo != "")
							<img src="{{asset('images/' . $order->photo)}}" alt="Avatar" class="rounded-circle object-cover">
						@else
							<span>@lang("global.not_photo")</span>
						@endif
					</a>
					<div class="row">
						<div class="col-6">
							<label for="user_id" class="form-label">{{ trans('global.user') }}</label>
							<select required id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
								<option disabled selected>@lang("global.chose_user")</option>
								@foreach ($users as $user)
									<option value="{{ $user->id }}" @if (old('user_id',$order->user_id) == $user->id) selected @endif>{{ $user->name() }}</option>
								@endforeach
							</select>
						</div>
						@if($type === 'course' or $type === 'lecture')
							<div class="col-6">
								<label for="course_id" class="form-label">{{ trans('global.course') }}</label>
								<select required id="course_id" name="course_id" class="select2 form-select form-select-lg course_id  @error('course_id') is-invalid @enderror" data-allow-clear="true">
									<option disabled selected>@lang("global.chose_course")</option>
									@foreach ($courses as $course)
										<option price="{{$course->price}}" currency="{{$course->currency_id}}" value="{{ $course->id }}" @if (old('course_id',$order->course_id) == $course->id) selected @endif>{{ $course->name }}</option>
									@endforeach
								</select>
							</div>
						@endif
						@if($type === 'lecture')
							<div class="col-6 mt-4" id="parent_lectures">
								<label for="lecture_id" class="form-label">{{ trans('global.lecture') }}</label>
								<select required id="lecture_id" name="lecture_id" class="select2 form-select form-select-lg lecture_id  @error('lecture_id') is-invalid @enderror" data-allow-clear="true">
									<option disabled selected>@lang("global.chose_lecture")</option>
									@foreach ($lectures as $lecture)
										<option price="{{$lecture->price}}" currency="1" value="{{ $lecture->id }}" @if (old('lecture_id',$order->lecture_id) == $lecture->id) selected @endif>{{ $lecture->title }}</option>
									@endforeach
								</select>
							</div>
						@endif
						@if($type === 'offer')
							<div class="col-6">
								<label for="offer_id" class="form-label">{{ trans('global.offer') }}</label>
								<select required id="offer_id" name="offer_id" class="select2 form-select form-select-lg offer_id @error('offer_id') is-invalid @enderror" data-allow-clear="true">
									<option disabled selected>@lang("global.chose_offer")</option>
									@foreach ($offers as $offer)
										<option price="{{$offer->price}}" currency="{{$offer->currency_id}}" value="{{ $offer->id }}" @if (old('offer_id',$order->offer_id) == $offer->id) selected @endif>{{ $offer["name_".app()->getLocale()] }}</option>
									@endforeach
								</select>
							</div>
						@endif
						<div class="col-6 mt-4">
							<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
							<select required id="currency_id" name="currency_id" class="select2 form-select form-select-lg @error('currency_id') is-invalid @enderror" data-allow-clear="true" data-style="btn-default">
								@foreach ($currencies as $currency)
									<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id',$order->currency_id) == $currency->id) selected @endif>{{ $currency->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-6 mt-4">
							<label for="card_type" class="form-label">{{ trans('global.card_type') }}</label>
							<select required id="card_type" name="card_type" class="select2 form-select form-select-lg card_type @error('card_type') is-invalid @enderror" data-allow-clear="true">
								<option value="wallet" @if(old('card_type',$order->card_type) === "wallet") selected @endif>@lang("global.wallet")</option>
								<option value="cash" @if(old('card_type',$order->card_type) === "code") selected @endif>@lang("global.code")</option>
								<option value="cash" @if(old('card_type',$order->card_type) === "cash") selected @endif>@lang("global.cash")</option>
								<option value="visa" @if(old('card_type',$order->card_type) === "visa") selected @endif>@lang("global.visa")</option>
							</select>
						</div>
						<div class="col-6 mt-4">
							<label for="price" class="form-label">@lang("global.price")</label>
							<input type="text" value="{{ old("price",$order->price) }}" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="999" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6 mt-4">
							<label for="bank_code" class="form-label">@lang("global.bank_code")</label>
							<input type="text" value="{{ old("bank_code",$order->bank_code) }}" class="form-control @error('bank_code') is-invalid @enderror" id="bank_code" name="bank_code" placeholder="99999" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6 mt-4">
							<label for="code" class="form-label">{{ trans('global.coupon') }}</label>
							<select id="code" name="code" class="select2 form-select form-select-lg @error('code') is-invalid @enderror" data-allow-clear="true" data-style="btn-default">
								<option disabled selected>@lang("global.chose_coupon")</option>
								@foreach ($coupons as $coupon)
									<option value="{{ $coupon->code }}" discount="{{$coupon->discount}}" @if (old('code',$order->code) == $coupon->code) selected @endif>{{ $coupon->code }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-6 mt-4">
							<label for="card_number" class="form-label">@lang("global.card_number")</label>
							<input type="text" value="{{ old("card_number",$order->card_number) }}" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" placeholder="10-13" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6 mt-4">
							<label for="formFile" class="form-label">@lang("global.photo")</label>
							<input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="formFile" accept="image/*" />
						</div>
						<div class="card-body col-6 p-0 mt-4  px-3">
							<label for="customRadioTemp1" class="form-label">@lang("global.status")</label>
							<div class="row">
								<div class="col-md mb-md-0 mb-2">
									<div class="form-check custom-option custom-option-basic">
										<label class="form-check-label custom-option-content pt-1 pb-1" for="customRadioTemp1">
											<input name="status" @if(old('status',$order->status) == "1") checked @endif class="form-check-input" type="radio" value="1" id="customRadioTemp1" checked />
											<span class="custom-option-header">
                        <span class="h6 mb-0">@lang("global.enabled")</span>
                        <i class="fa-solid fa-circle-check text-success"></i>
                      </span>
										</label>
									</div>
								</div>

								<div class="col-md">
									<div class="form-check custom-option custom-option-basic">
										<label class="form-check-label custom-option-content pt-1 pb-1" for="customRadioTemp2">
											<input name="status" @if(old('status',$order->status) == "0") checked @endif class="form-check-input" type="radio" value="0" id="customRadioTemp2" />
											<span class="custom-option-header">
                        <span class="h6 mb-0">@lang("global.not_enabled")</span>
                        <i class="fa-solid fa-unlock text-danger"></i>
                      </span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="pt-4">
						<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection
@section("my-script")
	<script>
    if ("{{$type}}" === "lecture") {
      function getLecture(id) {
        $.ajax({
          type: "get",
          url: `/orders/getLecture/${id}`,
          data: {
            "_token": "{{ csrf_token() }}",
            "id": id
          },
          success: function(data) {
            $("#parent_lectures").html(data);
            $("#parent_lectures").show();
            $("#lecture_id").select2();
          },
          error: function({ responseJSON }) {
            showMessage("error", responseJSON.message);
          }
        });
      }

      $(".course_id").on("change", function() {
        getLecture($(this).val());
      });
    }
    $("body").on("change", ".{{$type}}_id", function() {
      let discount = 0;
      if ($("#code").find("option:selected").attr("discount") != undefined) {
        discount = $("#code").find("option:selected").attr("discount");
      }
      $("#price").val(+$(this).find("option:selected").attr("price") - +discount);
      $("#currency_id").val($(this).find("option:selected").attr("currency")).trigger("change");
    });
    $("#code").on("change", function() {
      if ($(this).val() == null) {
        $("#price").val(+$("#course_id").find("option:selected").attr("price"));
      }
      if ($("#{{$type}}_id").find("option:selected").attr("price") != undefined && $(this).val() != null) {
        let discount = +$(this).find("option:selected").attr("discount");
        let price = +$("#{{$type}}_id").find("option:selected").attr("price");
        $("#price").val(price - discount);
      }
    });
	</script>
@endsection