@extends('backend.layout.index')
@section('content')
  <?php 
    $team = json_encode($team);
    $teamtype = json_encode($teamtype)
  ?>
  <team
  :user="{{Auth()->user()}}" 
  :teamtype="{{$teamtype}}"
  :team="{{$team}}">  
  </team>
@endsection 
{{-- @section('script')

@endsection     --}}     