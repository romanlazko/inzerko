<?php

namespace App\Bots\pozor_baraholka_bot\Http\Controllers;

use App\Bots\pozor_baraholka_bot\Http\DataTransferObjects\Announcement;
use App\Bots\pozor_baraholka_bot\Http\Services\BaraholkaAnnouncementService;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncementPhoto;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaCategory;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaSubcategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Romanlazko\Telegram\App\Facades\Telegram;
use Romanlazko\Telegram\Exceptions\TelegramException;

class BaraholkaAnnouncementController extends Controller
{
    private $telegram; 

    private $baraholkaAnnouncementService;

    public function __construct()
    {
        
        $this->baraholkaAnnouncementService = new BaraholkaAnnouncementService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search     = strtolower($request->search);
        $type       = strtolower($request->type);
        $city       = strtolower($request->city);

        $announcements = BaraholkaAnnouncement::where('status', 'published')
            ->when(empty($request->input('sort')), function($query) {
                return $query->orderByDesc('created_at');
            })
            ->when(!empty($request->input('sort')), function($query) use($request) {
                $sort = explode('/', $request->sort);
                return $query->orderBy($sort[0], $sort[1]);
            })
            ->when(!empty($request->input('search')), function($query) use($search) {
                return $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(title) LIKE ?', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(caption) LIKE ?', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(category) LIKE ?', ['%' . $search . '%']);
                });
            })
            ->when(!empty($request->input('type')), function($query) use($type) {
                return $query->where(function ($query) use ($type) {
                    $query->whereRaw('LOWER(type) LIKE ?', ['%' . $type . '%']);
                });
            })
            ->when(!empty($request->input('city')), function($query) use($city) {
                return $query->where(function ($query) use ($city) {
                    $query->whereRaw('LOWER(city) LIKE ?', ['%' . $city . '%']);
                });
            })
            ->when(!empty($request->input('category')), function($query) use($request) {
                return $query->where('category_id', $request->category);
            })
            ->when(!empty($request->input('subcategory')), function($query) use($request) {
                return $query->where('subcategory_id', $request->subcategory);
            })
            ->when(!empty($request->input('minCost')), function($query) use($request) {
                return $query->where('cost', ">=", $request->minCost);
            })
            ->when(!empty($request->input('maxCost')), function($query) use($request) {
                return $query->where('cost', "<=", $request->maxCost);
            })
            ->with('photos')
            ->paginate(42);

        $photoUrls = [];

        foreach ($announcements as $announcement) {
            $announcement->main_photo = $announcement->photos->first();

            if ($announcement->main_photo AND (!$announcement->main_photo->url OR !file_exists(public_path($announcement->main_photo->url)))) {
                $photoUrl = Telegram::getPhoto(['file_id' => $announcement->main_photo->file_id]);
                $photoUrls[] = [
                    'id'  => $announcement->main_photo->id,
                    'url' => $this->baraholkaAnnouncementService->uploadAnnouncementPhoto($photoUrl)
                ];
                $announcement->main_photo->url = asset($photoUrl);
            }
        }

        if (!empty($photoUrls)) {
            BaraholkaAnnouncementPhoto::upsert($photoUrls, ['id'], ['url']);
        }

        $categories = BaraholkaCategory::all();

        $subcategories = BaraholkaSubcategory::when(!empty($request->input('category')), function($query) use($request) {
            return $query->where('baraholka_category_id', $request->category);
        })?->limit(10)->get();

        return view('pozor_baraholka_bot::baraholka.index', compact(
            'announcements',
            'categories',
            'subcategories'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BaraholkaCategory::all();

        return view('pozor_baraholka_bot::baraholka.create', compact(
            'categories',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->baraholkaAnnouncementService->storeAnnouncement(
            Announcement::fromRequest($request)
        );

        return redirect()->route('pozor_baraholka_bot.announcement.index')->with([
            'ok' => true,
            'description' => "Announcement succesfuly created"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BaraholkaAnnouncement $announcement)
    {
        $announcement->photos->map(function ($photo) {
            if (!$photo->url OR !file_exists(public_path($photo->url))) {
                $photoUrl = Telegram::getPhoto(['file_id' => $photo->file_id]);
                $photo->update([
                    'url' => $this->baraholkaAnnouncementService->uploadAnnouncementPhoto($photoUrl)
                ]);
            }

            return $photo->url = asset($photo->url);
        });

        $announcement->chat->photo = Telegram::getPhoto(['file_id' => $announcement->chat->photo]);

        return view('pozor_baraholka_bot::baraholka.show', compact(
            'announcement',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BaraholkaAnnouncement $baraholkaAnnouncement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BaraholkaAnnouncement $baraholkaAnnouncement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BaraholkaAnnouncement $baraholkaAnnouncement)
    {
        //
    }

    public function sendMessage(Request $request, BaraholkaAnnouncement $announcement)
    {
        // dd($announcement);
        try {
            if ($announcement->chat) {
                $response = Telegram::sendMessage([
                    'chat_id' => '544883527',
                    'text' => $request->message
                ]);
                return back()->with([
                    'ok' => $response->getOk(), 
                    'description' => "Message successfully sent"
                ]);
            }
            return back();
        }
        catch (TelegramException $e){
            return back()->with([
                'ok' => false, 
                'description' => $e->getMessage()
            ]);
        }
    }

}
