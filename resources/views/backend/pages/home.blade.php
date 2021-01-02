@extends('backend.layout.index')
@section('content')
		<?php 
			$teamtype = json_encode($teamtype);
		 ?>
      <homepages
      :teamtype="{{$teamtype}}"
      :user="{{Auth()->user()}}">
      </homepages>
@endsection 
{{-- @section('script')
@endsection     --}}     