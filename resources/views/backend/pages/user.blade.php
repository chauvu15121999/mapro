@extends('backend.layout.index')
@section('content')
 <user 
 :user="{{Auth()->user()}}"
 >
 </user>
@endsection 
{{-- @section('script')

@endsection     --}}     