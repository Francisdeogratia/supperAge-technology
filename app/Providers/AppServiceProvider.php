<?php
namespace App\Providers;

use App\Models\Message;
use App\Models\FileMessage;
use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session; // 🔥 ADD THIS LINE
use App\Helpers\GeoHelper;
// Add these two imports:
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type; // Import the Type class


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        // Share these variables with ALL views
        View::composer('*', function ($view) {
            $unseenMessageCount = 0;
            $unseenFileCount = 0;
            $totalNotifications = 0;

            // 🔥 CHECK FOR YOUR CUSTOM SESSION KEY INSTEAD OF auth()->check()
            if (Session::has('id')) {
                // 🔥 GET USER ID DIRECTLY FROM SESSION
                $userId = Session::get('id');

                // Count unseen messages
                // NOTE: This assumes you have fixed the 'seen' column database error
                $unseenMessageCount = Message::where('receiver_id', $userId)
                                             ->where('seen', false)
                                             ->count();

                // Count unseen file messages (still commented out, which is good)
                // $unseenFileCount = FileMessage::where('receiver_id', $userId)
                //                               ->where('seen', false)
                //                               ->count();

                // Count unread notifications
                // NOTE: This assumes you have fixed the 'read' vs 'read_notification' column name
                $totalNotifications = Notification::where('user_id', $userId)
                                                   ->where('read_notification', 'no')
                                                   ->count();
            }

            $view->with(compact('unseenMessageCount', 'unseenFileCount', 'totalNotifications'));
        });


        // === FIX FOR DOCTRINE ENUM ERROR ===
        Schema::defaultStringLength(191); // Recommended Laravel default if not set

        if (class_exists(Type::class)) {
            // Check if 'enum' type is not already registered before adding it
            if (! Type::hasType('enum')) {
                Type::addType('enum', \Doctrine\DBAL\Types\StringType::class);
            }
        }

    }

}