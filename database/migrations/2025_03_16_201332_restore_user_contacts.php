<?php

use App\Enums\ContactTypeEnum;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::all()->each(function ($user) {
            $contacts = [];

            foreach ($user->communication_settings ?? [] as $contact => $value) {
                if ($contact === 'contact_phone' AND !is_null($value->phone)) {
                    $contacts[] = [
                        'type'=> ContactTypeEnum::PHONE,
                        'link'=> $value->phone,
                    ];
                }
                
                if ($contact === 'telegram' AND !is_null($value->phone)) {
                    $contacts[] = [
                        'type'=> ContactTypeEnum::PHONE,
                        'link'=> $value->phone,
                    ];
                }

                if ($contact === 'whatsapp' AND !is_null($value->phone)) {
                    $contacts[] = [
                        'type'=> ContactTypeEnum::WHATSAPP,
                        'link'=> $value->phone,
                    ];
                }
            }

            $user->contacts()->createMany($contacts);
        });
    }

    public function down(): void
    {
        User::all()->each(function ($user) {
            $user->contacts()->delete();
        });
    }
};
