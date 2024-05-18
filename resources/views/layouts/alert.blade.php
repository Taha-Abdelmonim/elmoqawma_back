<style>
    .toast-container {
        top: 80px;
        z-index: 10000;
    }
    html[dir=rtl] .toast-container {
        left: 20px;
    }
    html[dir=ltr] .toast-container {
        right: 20px;
    }
</style>
@if(Session::get("success") || Session::get("error"))
  @if(Session::get("success"))
    <div class="toast-container position-fixed">
      <div class="bs-toast toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <div class="me-auto fw-semibold text-success">@lang("global.success")</div>
          <i class="ti ti-check ti-xs me-2 text-success"></i>
          <button type="button" id="btn-close-alert" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-success ms-auto">
          {{Session::pull("success")}}
        </div>
      </div>
    </div>
  @endif
  @if(Session::get("error"))
    <div class="toast-container">
      <div class="bs-toast toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <i class="ti ti-check ti-xs me-2 text-danger"></i>
          <div class="me-auto fw-semibold text-danger">@lang("global.error")</div>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-danger ms-auto fs-6">
          @php
            echo Session::pull("error")
          @endphp
        </div>
      </div>
    </div>
  @endif
@endif
<div class="toast-container position-fixed container_alert_js" hidden="hidden">
  <div class="bs-toast toast fade show parent_error_message" role="alert" aria-live="assertive" aria-atomic="true" hidden="hidden">
    <div class="toast-header">
      <i class="ti ti-check ti-xs me-2 text-danger"></i>
      <div class="me-auto fw-semibold text-danger">@lang("global.error")</div>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body text-danger error_message">
    </div>
  </div>
  <div class="bs-toast toast fade show parent_success_message" role="alert" aria-live="assertive" aria-atomic="true"  hidden="hidden">
    <div class="toast-header">
      <div class="me-auto fw-semibold text-success">@lang("global.success")</div>
      <i class="ti ti-check ti-xs me-2 text-success"></i>
      <button type="button" id="btn-close-alert" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body text-success ms-auto success_message">
    </div>
  </div>
</div>

@section("my-style")
  <style>
      .toast-container {
          position: fixed;
          top: 80px;
          left: 10px;
          z-index: 10000;
      }
  </style>
@endsection

<script>
  if(document.contains(document.querySelector(".toast-container .btn-close"))) {
    setTimeout(() => {
      $(`.container_alert_js`).attr("hidden", true);
      $(`.toast-container`).attr("hidden", true);
    }, 2000)
    // document.querySelector(".toast-container .btn-close").click()
  }
</script>
@section("my-script")
  <script>
    if(document.contains(document.querySelector(".toast-container .btn-close"))) {
      setTimeout(function () {
        document.querySelector(".toast-container .btn-close").click()
      }, 2000)
    }
  </script>
@endsection
