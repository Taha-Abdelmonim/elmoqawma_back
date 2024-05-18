@extends('layouts/layoutMaster')

@section('title', trans("global.show_review"))

@section('content')
	@php
		$links = [
			"start" => trans("global.reviews"),
			"/" => trans("global.dashboard"),
			"/review" => trans("global.all_reviews"),
			"end" => trans("global.show_review"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="row">
		<!-- Headings -->
		<div class="col-lg">
			<div class="card mb-4">
				<a href="{{route('user_show', $review->user->id)}}">
					<h5 class="card-header">{{$review->user->name()}}</h5>
				</a>
				<table class="table table-borderless">
					<tbody>
					<tr>
						<td class="py-3">
							<h5 class="mb-0">
							@php
								echo $review->comment
							@endphp
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
