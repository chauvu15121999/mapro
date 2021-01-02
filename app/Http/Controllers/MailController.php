<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   // public function basic_email() {
   //    $data = array('name'=>"Virat Gandhi");
   
   //    Mail::send(['text'=>'mail'], $data, function($message) {
   //       $message->to('abc@gmail.com', 'Tutorials Point')->subject
   //          ('Laravel Basic Testing Mail');
   //       $message->from('xyz@gmail.com','Virat Gandhi');
   //    });
   //    echo "Basic Email Sent. Check your inbox.";
   // }
   public function team_mail($member,$link,$user) {
      $data = array('link' => $link , 'user_invite' => $user);
      Mail::send('Mail.Tmail',compact('data'), function($message) use($member) {
         $message->to($member, 'Tutorials Point')->subject
            ('Mail invete team Mapro');
         $message->from('vuquapro@gmail.com','MAPRO');
      });
      if(count(Mail::failures()) > 0){
         $errors = 'Failed to send password reset email, please try again.';
      }
   }
   public function board_mail($member,$link,$user) {
      $data = array('link' => $link , 'user_invite' => $user);
      Mail::send('Mail.Tmail',compact('data'), function($message) use($member) {
         $message->to($member, 'Tutorials Point')->subject
            ('Mail invete team Mapro');
         $message->from('vuquapro@gmail.com','MAPRO');
      });
     if(count(Mail::failures()) > 0){
         $errors = 'Failed to send password reset email, please try again.';
      }
   }
   
   // public function attachment_email() {
   //    $data = array('name'=>"Virat Gandhi");
   //    Mail::send('mail', $data, function($message) {
   //       $message->to('abc@gmail.com', 'Tutorials Point')->subject
   //          ('Laravel Testing Mail with Attachment');
   //       $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
   //       $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
   //       $message->from('xyz@gmail.com','Virat Gandhi');
   //    });
   //    echo "Email Sent with attachment. Check your inbox.";
   // }
}
