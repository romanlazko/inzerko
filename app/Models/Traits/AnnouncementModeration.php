<?php

namespace App\Models\Traits;

use App\Enums\Status;
use App\Jobs\PublishAnnouncementJob;
use App\Jobs\PublishAnnouncementOnAllTelegramChannelsJob;
use App\Jobs\PublishAnnouncementOnTelegramChannel;
use App\Jobs\TranslateAnnouncement;

trait AnnouncementModeration
{
    public function succesfyCreated(array|\Throwable|\Error $info = [])
    {
        return $this->updateStatus(Status::created, $info);
    }

    public function moderate(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::await_moderation, $info);

        return $result;
    }

    public function moderationFailed(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::moderation_failed, $info);

        return $result;
    }

    public function moderationNotPassed(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::moderation_not_passed, $info);

        // ModerationNotPassedAnnouncement::dispatch($this)->delay(now()->addMinutes(5));

        return $result;
    }

    public function reject(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::rejected, $info);

        // RejectedAnnouncement::dispatch($this)->delay(now()->addMinutes(5));

        return $result;
    }

    public function approve(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::approved, $info);

        if ($result) {
            $this->translate($info);
        }

        return $result;
    }
    
    public function translate(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::await_translation, $info);

        if ($result) {
            TranslateAnnouncement::dispatch($this->id);
            // ->delay(now()->addMinutes(5))
        }

        return $result;
    }

    public function translationFailed(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::translation_failed, $info);

        return $result;
    }

    public function translated(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::translated, $info);

        if ($result) {
            $this->publish();
        }

        return $result;
    }

    public function publish(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::await_publication, $info);
        
        if ($result) {
            $this->publishOnTelegram($info);
        }
        
        return $result;
    }

    public function publishOnTelegram(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::await_telegram_publication, $info);

        if ($result) {
            PublishAnnouncementOnAllTelegramChannelsJob::dispatch($this->id);
        }

        return $result;
    }

    public function publishedOnTelegram(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::published_on_telegram, $info);

        if ($result) {
            $this->published($info);
        }

        return $result;
    }

    public function publishIfAllChannelsIsPublished()
    {
        $publishedChannels = $this->channels->filter(function ($channel) {
            return $channel->status?->isPublished();
        });

        if ($this->channels->count() == $publishedChannels->count()) {
            return $this->published();
        }
    }

    public function publishingFailed(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::publishing_failed, $info);

        return $result;
    }

    public function published(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::published, $info);

        // PublishedAnnouncement::dispatch($this)->delay(now()->addMinutes(5));
        
        return $result;
    }

    public function sold(array|\Throwable|\Error $info = [])
    {
        $result = $this->updateStatus(Status::sold, $info);

        return $result;
    }

    public function indicateAvailability()
    {
        if ($this->status == Status::sold) {
            return $this->publish();
        }

        return false;
    }
}
