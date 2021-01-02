@extends('backend.layout.index')
@section('content')
	<?php  $board =  json_encode($board); ?>
	<board
	 :user="{{Auth()->user()}}"  
	:board="{{$board}}"
	/>
@endsection 