@extends('backend.layout.index')
@section('content')
	<?php
	for($i = 0 ; $i < count($board['members']) ; $i++){
		 if($board['members'][$i]['user_email'] != Auth::user()->email){
		 	url()->previous();
		 } 
		}
	?>
	<?php  $board =  json_encode($board); ?>
	<board
	 :user="{{Auth()->user()}}"  
	:board="{{$board}}"
	/>
@endsection 