@extends('backend.layout.index')
@section('content')
	<?php
	$test = false ; 
	for($i = 0 ; $i < count($board['members']) ; $i++){
		 if($board['members'][$i]['user_email'] == Auth::user()->email){
		 	$test = true;
		 } 
		}
		if($test == false){
			echo '<script>
		 		window.history.back();
		 	</script>';
		}
	?>
	<?php  $board =  json_encode($board); ?>
	<board
	 :user="{{Auth()->user()}}"  
	:board="{{$board}}"
	/>

@endsection 