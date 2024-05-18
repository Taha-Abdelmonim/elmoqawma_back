<?php
namespace App\Notifications;
  
  
  use Illuminate\Bus\Queueable;
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Notifications\Messages\MailMessage;
  use Illuminate\Notifications\Notification;
  
  class CreateCourse extends Notification
  {
    use Queueable;
    
    protected $course;
    public function __construct($course)
    {
      $this->course = $course;
    }
    
    public function via($notifiable)
    {
      return ['database'];
    }
    
    public function toArray($notifiable)
    {
      return [
        'course' => $this->course,
      ];
    }
  }
