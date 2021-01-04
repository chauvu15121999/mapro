@extends('backend.layout.index')
@section('content')
	<?php
		print_r($board['members']); 
	?>
{{-- 	<?php  $board =  json_encode($board); ?>
	<board
	 :user="{{Auth()->user()}}"  
	:board="{{$board}}"
	/> --}}
@endsection 