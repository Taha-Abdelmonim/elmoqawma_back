@extends('layouts/layoutMaster')

@section('title', trans("global.show_comment"))

@section('content')
	@php
		$links = [
			"start" => trans("global.comments"),
			"/" => trans("global.dashboard"),
			"/comments" => trans("global.all_comments"),
			"end" => trans("global.show_comment"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="row">
		<!-- Headings -->
		<div class="col-lg">
			<div class="card mb-4">
				<a href="{{route('user_show', $comment->user->id)}}">
					<h5 class="card-header">{{$comment->user->name()}}</h5>
				</a>
				<table class="table table-borderless">
					<tbody>
					<tr>
						<td class="py-3">
							<h5 class="mb-0">
								@php
									echo $comment->comment
								@endphp
							</h5>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
