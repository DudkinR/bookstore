<?php
namespace App\Services;

use App\Models\Publisher;

class PublisherService
{
    public function getOrCreatePublisher($name)
    {
        $publisher = Publisher::where('name', $name)->first();

        if ($publisher) {
            return $publisher;
        } else {
            $newPublisher = new Publisher();
            $newPublisher->name = $name;
            $newPublisher->save();
            return $newPublisher;
        }
    }
}
