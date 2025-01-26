<?php

namespace App\Bots\inzerko_bot\Http\Controllers\Auth;

use App\Bots\inzerko_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\inzerko_bot\Commands\UserCommands\Profile\Profile;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AuthenticatedSessionController extends Controller
{
    public function auth(Request $request)
    {
        $user = User::findByToken($request->get('token'));

        if (! $user) {
            abort(403, 'Invalid credentials. User not found.');
        }

        $this->deleteLastMessageReplyMarkup($user);

        Auth::login($user);
        
        return redirect()->route('profile.announcement.create');
    }
    
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function deleteLastMessageReplyMarkup($user)
    {
        try {
            $buttons = Inzerko::editMessage([
                [array(CreateAnnouncement::getTitle('ru'), CreateAnnouncement::$command, '')],
                [array(Profile::getTitle('ru'), Profile::$command, '')],
            ]);

            $text = implode("\n", [
                "Привет 👋" ."\n", 
                "Я помогу тебе создать объявление в каналах *Pozor*."."\n",
            ]);

            Inzerko::returnInline([
                'text'          => $text,
                'chat_id'       => $user->chat->chat_id,
                'reply_markup'  => $buttons,
                'parse_mode'    => 'Markdown',
                'message_id'    => $user->chat->latestMessage->message_id,
            ]);
        } catch (\Throwable $th) {
            abort(403, 'Invalid credentials. User not found.');
        }
    }
}
