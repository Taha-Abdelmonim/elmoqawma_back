@extends('layouts/layoutMaster')

@section('title', trans("global.setting_show_value"))

@section('content')
  @php
    $links = [
      "start" => trans("global.settings"),
      "/" => trans("global.dashboard"),
      "/settings" => trans("global.all_settings"),
      "end" => trans("global.setting_show_value"),
  ]
  @endphp
  @include("layouts.breadcrumbs")
  <div class="row">
    <!-- Headings -->
    <div class="col-lg">
      <div class="card mb-4">
       <h5 class="card-header">@lang("global.setting_show_value")</h5>
        <table class="table table-borderless">
          <tbody>
          <tr>
            <td class="py-3">
              <h5 class="mb-0">
                @php
                  echo $value
                @endphp
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
