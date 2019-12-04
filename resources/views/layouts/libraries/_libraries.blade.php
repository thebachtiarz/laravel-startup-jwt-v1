@if('_lib')
@foreach($_lib as $key => $library)
@include("layouts.libraries.$library")
@endforeach
@endif
