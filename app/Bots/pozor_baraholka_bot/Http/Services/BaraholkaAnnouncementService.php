<?php 

namespace App\Bots\pozor_baraholka_bot\Http\Services;

use App\Bots\pozor_baraholka_bot\Http\DataTransferObjects\Announcement;
use App\Bots\pozor_baraholka_bot\Http\DataTransferObjects\AnnouncementDTO;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Romanlazko\Telegram\App\Facades\TelegramFacade;
use Romanlazko\Telegram\App\Telegram;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class BaraholkaAnnouncementService
{
    // public function __construct(private TelegramFacade $telegram) {}

    public function storeAnnouncement(Announcement $announcement): BaraholkaAnnouncement
    {
        $baraholkaAnnouncement = BaraholkaAnnouncement::updateOrCreate([
            'user_id'           => $announcement->user_id ?? null,
            'telegram_chat_id'  => $announcement->chat ?? null,
            'city'              => $announcement->city ?? null,
            'type'              => $announcement->type ?? null,
            'title'             => $announcement->title ?? null,
            'caption'           => $announcement->caption ?? null,
            'cost'              => $announcement->cost ?? null,
            'category_id'       => $announcement->category_id ?? null,
            'subcategory_id'    => $announcement->subcategory_id ?? null,
            'condition'         => $announcement->condition ?? null,
            'status'            => 'new',
        ]);

        if ($announcement->photos) {
            foreach ($announcement->photos as $photo) {
                $photoDatas[] = $this->getPhotoData($photo);

                // if ($photoData) {
                //     $baraholkaAnnouncement->photos()->updateOrCreate($photoData);

                // }
            }

            $baraholkaAnnouncement->photos()->upsert($photoDatas, ['id'], );
        }

        return $baraholkaAnnouncement;
    }

    public function getPhotoData(UploadedFile|string|null $photo): ?array
    {
        if (is_null($photo)) {
            return null;
        }

        if ($photo instanceof UploadedFile) {
            $photoUrl = $photo->getPathname();
        } else {
            $photoUrl = TelegramFacade::getPhoto(['file_id' => $photo]);
        }

        $photoPath = $this->uploadAnnouncementPhoto($photoUrl);

        return [
            'file_id' => $photo,
            'url' => $photoPath,
        ];
    }

    public function uploadAnnouncementPhoto(string $photoUrl): string
    {
        $photoData  = file_get_contents($photoUrl);

        if ($photoData === false) {
            return null;
        }

        $photoHash = md5($photoData);

        $photoName = $photoHash. '.' .$this->getFileExtension($photoUrl);

        $filePath = 'announcements/baraholka/' . substr($photoHash, 0, 2) . '/' . substr($photoHash, 2, 2) . '/' . $photoName;
        
        if (Storage::disk('public')->put($filePath, $photoData)) {
            $filePath = Storage::url($filePath);

            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize(public_path($filePath));

            return $filePath;
        }

        return null;
    }

    public function getFileExtension(string $filePath): ?string
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($extension === '') {
            return 'jpg';
        }
        return $extension;
    }
}
