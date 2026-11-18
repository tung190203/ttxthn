<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;

class ProjectUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;
    protected $updateMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, $updateMessage = 'đã có cập nhật mới')
    {
        $this->project = $project;
        $this->updateMessage = $updateMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('project_detail', ['slug' => $this->project->slug ?? 'untitled']);
        
        return (new MailMessage)
            ->subject('Thông báo cập nhật dự án: ' . $this->project->name)
            ->view('emails.project-updated', [
                'project' => $this->project,
                'guest' => $notifiable,
                'updateMessage' => $this->updateMessage,
                'url' => $url
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'message' => 'Dự án "' . $this->project->name . '" ' . $this->updateMessage,
            'url' => route('project_detail', ['slug' => $this->project->slug ?? 'untitled']),
        ];
    }
}
