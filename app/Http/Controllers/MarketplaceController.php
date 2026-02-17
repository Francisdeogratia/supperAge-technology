<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\MarketplaceStore;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceStoreView;
use App\Models\WalletTransaction;
use App\Models\Notification;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    /**
     * Show marketplace homepage with all stores
     */
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Get all active stores with product counts
        $stores = MarketplaceStore::with(['owner', 'products'])
            ->where('status', 'active')
            ->withCount(['products as active_products' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Check if user has a store
        $userStore = MarketplaceStore::where('owner_id', $userId)->first();
        
        // Get unread notifications count for user's store
        $storeNotifications = 0;
        if ($userStore) {
            $storeNotifications = Notification::where('notification_reciever_id', $userId)
                ->where('type', 'marketplace_order')
                ->where('read_notification', 'no')
                ->count();
        }
        
        return view('marketplace.index', compact('user', 'stores', 'userStore', 'storeNotifications'));
    }

    /**
     * Show store creation payment page
     */
    /**
 * Updated showCreateStore to show correct balance
 * Replace your existing showCreateStore method with this
 */
public function showCreateStore()
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }
    
    $user = UserRecord::find($userId);
    
    // Check if user already has a store
    $existingStore = MarketplaceStore::where('owner_id', $userId)->first();
    
    if ($existingStore) {
        return redirect()->route('marketplace.my-store')->with('info', 'You already have a store.');
    }
    
    // Get user's wallet balances per currency
    $balances = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('status', 'successful')
        ->select('currency', DB::raw('SUM(amount) as total'))
        ->groupBy('currency')
        ->pluck('total', 'currency')
        ->toArray();
    
    return view('marketplace.create-payment', compact('user', 'balances'));
}
    /**
     * Process store creation payment
     */
    public function processStorePayment(Request $request)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Please log in first.']);
        }

        // Check if already has store
        if (MarketplaceStore::where('owner_id', $userId)->exists()) {
            return redirect()->route('marketplace.my-store')->with('info', 'You already have a store.');
        }

        // Base fee in NGN
        $baseFeeNgn = 2500;

        // Detect user country
        $userCountry = $user->country ?? 'NG';
        $defaultCurrency = match($userCountry) {
            'NG' => 'NGN',
            'US' => 'USD',
            'GB' => 'GBP',
            'FR','DE','IT','ES' => 'EUR',
            default => 'NGN'
        };

        // Allow override from dropdown
        $selectedCurrency = $request->input('currency', $defaultCurrency);

        // Fetch live rates from Flutterwave
        $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get('https://api.flutterwave.com/v3/rates', [
                'from' => 'NGN',
                'to'   => $selectedCurrency,
                'amount' => $baseFeeNgn
            ])
            ->json();

        if (!isset($rateResponse['status']) || $rateResponse['status'] !== 'success') {
            return redirect()->back()->withErrors(['error' => 'Unable to fetch exchange rates.']);
        }

        // Convert NGN fee into selected currency
        $convertedAmount = $rateResponse['data']['rate'] 
            ? round($baseFeeNgn * $rateResponse['data']['rate'], 2)
            : $baseFeeNgn;

        // Prepare payload for Flutterwave
        $payload = [
            'tx_ref'       => 'STORE_' . uniqid(),
            'amount'       => $convertedAmount,
            'currency'     => $selectedCurrency,
            'redirect_url' => route('marketplace.payment-success', ['currency' => $selectedCurrency]),
            'customer'     => [
                'email' => $user->email,
                'name'  => $user->name,
            ],
            'customizations' => [
                'title'       => 'Marketplace Store Creation',
                'description' => "{$selectedCurrency} {$convertedAmount} store setup fee",
            ],
        ];

        // Call Flutterwave API
        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', $payload)
            ->json();

        if (!isset($response['data']['link'])) {
            return redirect()->back()->withErrors(['error' => 'Unable to start payment.']);
        }

        // Redirect to Flutterwave checkout
        return redirect($response['data']['link']);
    }

    /**
     * Payment success callback
     */
    public function paymentSuccess(Request $request)
    {
        $status        = $request->query('status');
        $txRef         = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');
        $currency      = $request->query('currency', 'NGN');

        if (!$transactionId) {
            return redirect()->route('marketplace.index')->with('error', 'No transaction ID found.');
        }

        // Verify payment with Flutterwave
        $verifyResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify")
            ->json();

        if (
            isset($verifyResponse['status']) &&
            $verifyResponse['status'] === 'success' &&
            isset($verifyResponse['data']['status']) &&
            $verifyResponse['data']['status'] === 'successful' &&
            $verifyResponse['data']['currency'] === $currency
        ) {
            $userId = Session::get('id');
            $user = UserRecord::find($userId);

            if ($user) {
                // Check if user already has a store
                $existingStore = MarketplaceStore::where('owner_id', $userId)->first();
                
                if (!$existingStore) {
                    // Redirect to store setup form
                    return redirect()->route('marketplace.setup-store')->with('success', 'Payment successful! Now set up your store.');
                }

                return redirect()->route('marketplace.my-store')->with('info', 'You already have a store.');
            }

            return redirect()->route('marketplace.index')->with('error', 'User not found.');
        }

        return redirect()->route('marketplace.index')->with('error', 'Payment verification failed.');
    }

    /**
     * Pay from wallet
     */
    /**
 * Updated payFromWallet method - Compatible with your wallet system
 * Replace your existing payFromWallet method with this
 */
public function payFromWallet(Request $request)
{
    $userId = Session::get('id');
    $user = UserRecord::find($userId);

    if (!$user) {
        return redirect()->back()->withErrors(['error' => 'Please log in first.']);
    }

    // Check if already has store
    if (MarketplaceStore::where('owner_id', $userId)->exists()) {
        return redirect()->route('marketplace.my-store')->with('info', 'You already have a store.');
    }

    // Base fee in NGN
    $baseFeeNgn = 2500;
    $selectedCurrency = $request->input('currency', 'NGN');

    // Get user's balance in the selected currency
    $userBalance = WalletTransaction::where('wallet_owner_id', $user->id)
        ->where('status', 'successful')
        ->where('currency', $selectedCurrency)
        ->sum('amount');

    // Convert base fee if needed
    $deductionAmount = $baseFeeNgn;
    if ($selectedCurrency !== 'NGN') {
        $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get('https://api.flutterwave.com/v3/rates', [
                'from' => 'NGN',
                'to'   => $selectedCurrency,
                'amount' => $baseFeeNgn
            ])
            ->json();

        if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
            $deductionAmount = round($rateResponse['data']['rate'] * $baseFeeNgn, 2);
        }
    }

    // Check wallet balance
    if ($userBalance < $deductionAmount) {
        return redirect()->back()->withErrors([
            'error' => "Insufficient {$selectedCurrency} balance. You need {$selectedCurrency} " . number_format($deductionAmount, 2) . " but have {$selectedCurrency} " . number_format($userBalance, 2)
        ]);
    }

    DB::beginTransaction();
    try {
        // Create debit transaction
        WalletTransaction::create([
            'wallet_owner_id' => $user->id,
            'payer_id'        => $user->id,
            'transaction_id'  => 'STORE_' . uniqid(),
            'tx_ref'          => 'STORE_SETUP_' . time(),
            'amount'          => -$deductionAmount, // Negative for debit
            'type'            => 'debit',
            'currency'        => $selectedCurrency,
            'status'          => 'successful',
            'description'     => "Marketplace Store Creation Fee ({$selectedCurrency})",
        ]);

        DB::commit();

        return redirect()->route('marketplace.setup-store')->with('success', 'Payment successful! Now set up your store.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
    }
}

    /**
     * Show store setup form
     */
    public function setupStore()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Check if already has store
        $existingStore = MarketplaceStore::where('owner_id', $userId)->first();
        
        if ($existingStore) {
            return redirect()->route('marketplace.my-store');
        }
        
        return view('marketplace.setup-store', compact('user'));
    }

    /**
     * Create the store
     */
    /**
 * Updated createStore method - Add subscription initialization
 * Replace your existing createStore method with this
 */
/**
 * Updated createStore method - Add subscription initialization
 * Replace your existing createStore method with this
 */
public function createStore(Request $request)
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }

    // Check if already has store
    if (MarketplaceStore::where('owner_id', $userId)->exists()) {
        return response()->json(['error' => 'You already have a store'], 400);
    }

    $request->validate([
        'store_name' => 'required|string|max:200|unique:marketplace_stores,store_name',
        'description' => 'nullable|string|max:1000',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:100',
        'address' => 'required|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'country' => 'required|string|max:50',
        'logo' => 'nullable|image|max:2048',
        'banner' => 'nullable|image|max:2048'
    ]);

    DB::beginTransaction();
    try {
        $storeData = [
            'owner_id' => $userId,
            'store_name' => $request->store_name,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'status' => 'active',
            'subscription_started_at' => now(),
            'subscription_expires_at' => now()->addDays(30),
            'subscription_status' => 'active'
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $uploadPath = public_path('uploads/stores');
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $logo = $request->file('logo');
            $logoName = 'store_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            
            // Log for debugging
            \Log::info('Uploading logo', [
                'filename' => $logoName,
                'path' => $uploadPath,
                'full_path' => $uploadPath . '/' . $logoName
            ]);
            
            $logo->move($uploadPath, $logoName);
            $storeData['logo'] = 'uploads/stores/' . $logoName;
            
            // Verify file was created
            if (!file_exists($uploadPath . '/' . $logoName)) {
                throw new \Exception('Logo file was not created');
            }
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $uploadPath = public_path('uploads/stores');
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $banner = $request->file('banner');
            $bannerName = 'store_banner_' . time() . '.' . $banner->getClientOriginalExtension();
            
            // Log for debugging
            \Log::info('Uploading banner', [
                'filename' => $bannerName,
                'path' => $uploadPath,
                'full_path' => $uploadPath . '/' . $bannerName
            ]);
            
            $banner->move($uploadPath, $bannerName);
            $storeData['banner'] = 'uploads/stores/' . $bannerName;
            
            // Verify file was created
            if (!file_exists($uploadPath . '/' . $bannerName)) {
                throw new \Exception('Banner file was not created');
            }
        }

        $store = MarketplaceStore::create($storeData);

        // Log created store data
        \Log::info('Store created', [
            'store_id' => $store->id,
            'logo' => $store->logo,
            'banner' => $store->banner
        ]);

        // Create notification
        Notification::create([
            'user_id' => $userId,
            'message' => "ðŸŽ‰ Your store '{$store->store_name}' has been created successfully!",
            'link' => route('marketplace.my-store'),
            'notification_reciever_id' => $userId,
            'read_notification' => 'no',
            'type' => 'marketplace_store',
            'notifiable_type' => MarketplaceStore::class,
            'notifiable_id' => $store->id,
            'data' => json_encode([
                'store_id' => $store->id,
                'store_name' => $store->store_name
            ])
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Store created successfully!',
            'redirect' => route('marketplace.my-store')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Store creation failed', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return response()->json(['error' => 'Failed to create store: ' . $e->getMessage()], 500);
    }
}

    /**
     * Show user's store dashboard
     */
    public function myStore()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $store = MarketplaceStore::where('owner_id', $userId)->first();
        
        if (!$store) {
            return redirect()->route('marketplace.show-create-store')->with('error', 'You need to create a store first.');
        }

        // Get store statistics
        $totalProducts = MarketplaceProduct::where('store_id', $store->id)->count();
        $activeProducts = MarketplaceProduct::where('store_id', $store->id)->where('status', 'active')->count();
        $totalOrders = MarketplaceOrder::where('store_id', $store->id)->count();
        $pendingOrders = MarketplaceOrder::where('store_id', $store->id)->where('status', 'pending')->count();
        $totalRevenue = MarketplaceOrder::where('store_id', $store->id)
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->sum('total_amount');
        $totalViews = MarketplaceStoreView::where('store_id', $store->id)->count();

        // Get recent orders
        $recentOrders = MarketplaceOrder::with(['product', 'buyer'])
            ->where('store_id', $store->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get products
        $products = MarketplaceProduct::where('store_id', $store->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Unread order notifications
        $unreadOrders = Notification::where('notification_reciever_id', $userId)
            ->where('type', 'marketplace_order')
            ->where('read_notification', 'no')
            ->count();

        return view('marketplace.my-store', compact(
            'user',
            'store',
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'totalViews',
            'recentOrders',
            'products',
            'unreadOrders'
        ));
    }

    /**
     * Add/Edit Product
     */
    public function addProduct(Request $request)
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return response()->json(['error' => 'Not logged in'], 401);
    }

    $store = MarketplaceStore::where('owner_id', $userId)->first();
    
    if (!$store) {
        return response()->json(['error' => 'Store not found'], 404);
    }

    // Check subscription
    if (method_exists($store, 'isSubscriptionExpired') && $store->isSubscriptionExpired()) {
        return response()->json(['error' => 'Your store subscription has expired. Please renew.'], 403);
    }

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'type' => 'required|in:product,service',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'stock' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors()
        ], 422);
    }

    DB::beginTransaction();
    try {
        // Handle image uploads FIRST
        $images = [];
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/products');
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $imageName = 'product_' . time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);
                    $images[] = 'uploads/products/' . $imageName;
                }
            }
        }

        // Create product data
        $productData = [
            'store_id' => $store->id,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price,
            'currency' => $request->currency,
            'stock' => $request->stock ?? 0,
            'category' => $request->category,
            'status' => 'active',
            'images' => $images, // Model will auto-cast to JSON
            'views' => 0,
            'orders' => 0
        ];

        $product = MarketplaceProduct::create($productData);

        // Update store product count
        $store->increment('total_products');

        // Notify followers about new product
        $this->notifyFollowersNewProduct($userId, $store, $product);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'product' => $product
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Log the full error for debugging
        \Log::error('Add Product Error:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Failed to add product: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Update Product
     */
    public function updateProduct(Request $request, $productId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $store = MarketplaceStore::where('owner_id', $userId)->first();
        
        if (!$store) {
            return response()->json(['error' => 'Store not found'], 404);
        }

        $product = MarketplaceProduct::where('id', $productId)
            ->where('store_id', $store->id)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'type' => 'required|in:product,service',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,draft,out_of_stock'
        ]);

        $product->update($request->only([
            'name', 'description', 'type', 'price', 'stock', 'category', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    /**
     * Delete Product
     */
    public function deleteProduct($productId)
    {
        $userId = Session::get('id');
        $store = MarketplaceStore::where('owner_id', $userId)->first();
        
        if (!$store) {
            return response()->json(['error' => 'Store not found'], 404);
        }

        $product = MarketplaceProduct::where('id', $productId)
            ->where('store_id', $store->id)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();
        $store->decrement('total_products');

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }

    /**
     * View single product (tracks views)
     */
    public function viewProduct($productSlug)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $product = MarketplaceProduct::with('store.owner')->where('slug', $productSlug)->firstOrFail();

        // Track view (only once per user per product)
        $existingView = MarketplaceStoreView::where('product_id', $product->id)
            ->where('viewer_id', $userId)
            ->first();

        if (!$existingView) {
            MarketplaceStoreView::create([
                'store_id' => $product->store_id,
                'product_id' => $product->id,
                'viewer_id' => $userId,
                'ip_address' => request()->ip()
            ]);

            $product->increment('views');
            $product->store->increment('total_views');
        }

        // Get related products from same store
        $relatedProducts = MarketplaceProduct::where('store_id', $product->store_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('marketplace.product', compact('user', 'product', 'relatedProducts'));
    }

    /**
     * Place an order
     */
    public function placeOrder(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:marketplace_products,id',
            'quantity' => 'required|integer|min:1',
            'buyer_name' => 'required|string|max:200',
            'buyer_email' => 'required|email|max:100',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'required|string|max:500',
            'buyer_city' => 'nullable|string|max:100',
            'buyer_state' => 'nullable|string|max:100',
            'buyer_country' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000'
        ]);

        $product = MarketplaceProduct::with('store')->findOrFail($request->product_id);

        // Check stock
        if ($product->type === 'product' && $product->stock < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        DB::beginTransaction();
        try {
            $totalAmount = $product->price * $request->quantity;

            $order = MarketplaceOrder::create([
                'store_id' => $product->store_id,
                'product_id' => $product->id,
                'buyer_id' => $userId,
                'buyer_name' => $request->buyer_name,
                'buyer_email' => $request->buyer_email,
                'buyer_phone' => $request->buyer_phone,
                'buyer_address' => $request->buyer_address,
                'buyer_city' => $request->buyer_city,
                'buyer_state' => $request->buyer_state,
                'buyer_country' => $request->buyer_country,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'total_amount' => $totalAmount,
                'currency' => $product->currency,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Reduce stock
            if ($product->type === 'product') {
                $product->decrement('stock', $request->quantity);
                if ($product->stock <= 0) {
                    $product->update(['status' => 'out_of_stock']);
                }
            }

            // Update counters
            $product->increment('orders');
            $product->store->increment('total_orders');

            // 2. In placeOrder() method - Notify seller:
                Notification::create([
                    'user_id' => $userId,
                    'message' => "Ã°Å¸â€ºâ€™ New order #{$order->order_number} for {$product->name} from {$request->buyer_name}",
                    'link' => route('marketplace.order-details', $order->order_number),
                    'notification_reciever_id' => $product->store->owner_id,
                    'read_notification' => 'no',
                    'type' => 'marketplace_order',
                    'notifiable_type' => MarketplaceOrder::class,
                    'notifiable_id' => $order->id,
                    'data' => json_encode([
                        'order_number' => $order->order_number,
                        'product_name' => $product->name,
                        'buyer_name' => $request->buyer_name,
                        'total_amount' => $totalAmount,
                        'currency' => $product->currency
                    ])
                ]);

            // 3. In placeOrder() method - Notify buyer:
                    Notification::create([
                        'user_id' => $product->store->owner_id,
                        'message' => "Ã¢Å“â€¦ Your order #{$order->order_number} has been placed successfully!",
                        'link' => route('marketplace.my-orders'),
                        'notification_reciever_id' => $userId,
                        'read_notification' => 'no',
                        'type' => 'marketplace_order_placed',
                        'notifiable_type' => MarketplaceOrder::class,
                        'notifiable_id' => $order->id,
                        'data' => json_encode([
                            'order_number' => $order->order_number,
                            'product_name' => $product->name,
                            'total_amount' => $totalAmount,
                            'currency' => $product->currency
                        ])
                    ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to place order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * View order details
     */
    public function orderDetails($orderNumber)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        $order = MarketplaceOrder::with(['store', 'product', 'buyer'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check authorization (buyer or seller)
        if ($order->buyer_id !== $userId && $order->store->owner_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        return view('marketplace.order-details', compact('user', 'order'));
    }

    /**
     * Update order status (seller only)
     */
    public function updateOrderStatus(Request $request, $orderNumber)
    {
        $userId = Session::get('id');
        
        $order = MarketplaceOrder::with('store')->where('order_number', $orderNumber)->firstOrFail();

        // Check if user is the seller
        if ($order->store->owner_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Update timestamps
        if ($request->status === 'confirmed' && !$order->confirmed_at) {
            $order->update(['confirmed_at' => now()]);
        } elseif ($request->status === 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        } elseif ($request->status === 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
            
            // Add to revenue
            $order->store->increment('total_revenue', $order->total_amount);
        }

        // Notify buyer of status change
        // 4. In updateOrderStatus() method:
                    Notification::create([
                        'user_id' => $userId,
                        'message' => "Ã°Å¸â€œÂ¦ Order #{$order->order_number} status updated: {$request->status}",
                        'link' => route('marketplace.order-details', $order->order_number),
                        'notification_reciever_id' => $order->buyer_id,
                        'read_notification' => 'no',
                        'type' => 'marketplace_order_update',
                        'notifiable_type' => MarketplaceOrder::class,
                        'notifiable_id' => $order->id,
                        'data' => json_encode([
                            'order_number' => $order->order_number,
                            'old_status' => $oldStatus,
                            'new_status' => $request->status
                        ])
                    ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!'
        ]);
    }

    /**
     * My orders (buyer view)
     */
    public function myOrders()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        $orders = MarketplaceOrder::with(['store', 'product'])
            ->where('buyer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('marketplace.my-orders', compact('user', 'orders'));
    }

    /**
     * Store analytics/views
     */
    public function storeAnalytics()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $store = MarketplaceStore::where('owner_id', $userId)->firstOrFail();

        // Get product views
        $productViews = MarketplaceStoreView::with(['product', 'viewer'])
            ->where('store_id', $store->id)
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Get top viewed products
        $topProducts = MarketplaceProduct::where('store_id', $store->id)
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        // Views by date (last 30 days)
        $viewsByDate = MarketplaceStoreView::where('store_id', $store->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('marketplace.analytics', compact('user', 'store', 'productViews', 'topProducts', 'viewsByDate'));
    }

    /**
     * Renew subscription
     */
    public function renewSubscription()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $store = MarketplaceStore::where('owner_id', $userId)->firstOrFail();

        return view('marketplace.renew-subscription', compact('user', 'store'));
    }

    /**
     * Process subscription renewal
     */
    public function processRenewal(Request $request)
    {
        $userId = Session::get('id');
        $user = UserRecord::find($userId);
        $store = MarketplaceStore::where('owner_id', $userId)->firstOrFail();

        // Base fee in NGN
        $baseFeeNgn = 2500;
        $selectedCurrency = $request->input('currency', 'NGN');

        // Convert if needed
        $convertedAmount = $baseFeeNgn;
        if ($selectedCurrency !== 'NGN') {
            $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
                ->get('https://api.flutterwave.com/v3/rates', [
                    'from' => 'NGN',
                    'to'   => $selectedCurrency,
                    'amount' => $baseFeeNgn
                ])
                ->json();

            if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
                $convertedAmount = round($rateResponse['data']['rate'] * $baseFeeNgn, 2);
            }
        }

        // Prepare Flutterwave payload
        $payload = [
            'tx_ref'       => 'RENEWAL_' . $store->id . '_' . time(),
            'amount'       => $convertedAmount,
            'currency'     => $selectedCurrency,
            'redirect_url' => route('marketplace.renewal-success', ['currency' => $selectedCurrency]),
            'customer'     => [
                'email' => $user->email,
                'name'  => $user->name,
            ],
            'customizations' => [
                'title'       => 'Store Subscription Renewal',
                'description' => "Monthly subscription for {$store->store_name}",
            ],
        ];

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', $payload)
            ->json();

        if (!isset($response['data']['link'])) {
            return redirect()->back()->withErrors(['error' => 'Unable to start payment.']);
        }

        return redirect($response['data']['link']);
    }

    /**
     * Renewal success
     */
    public function renewalSuccess(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        $currency      = $request->query('currency', 'NGN');

        if (!$transactionId) {
            return redirect()->route('marketplace.my-store')->with('error', 'No transaction ID found.');
        }

        $verifyResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify")
            ->json();

        if (
            isset($verifyResponse['status']) &&
            $verifyResponse['status'] === 'success' &&
            isset($verifyResponse['data']['status']) &&
            $verifyResponse['data']['status'] === 'successful'
        ) {
            $userId = Session::get('id');
            $store = MarketplaceStore::where('owner_id', $userId)->first();

            if ($store) {
                // Extend subscription by 30 days
                $newExpiry = $store->subscription_expires_at && $store->subscription_expires_at->isFuture()
                    ? $store->subscription_expires_at->addDays(30)
                    : now()->addDays(30);

                $store->update([
                    'subscription_expires_at' => $newExpiry,
                    'subscription_status' => 'active'
                ]);

                return redirect()->route('marketplace.my-store')->with('success', 'Subscription renewed successfully! Valid until ' . $newExpiry->format('M d, Y'));
            }
        }

        return redirect()->route('marketplace.my-store')->with('error', 'Payment verification failed.');
    }

    /**
     * Notify followers about new product
     */
    private function notifyFollowersNewProduct($userId, $store, $product)
{
    try {
        $followerIds = DB::table('follow_tbl')
            ->where('receiver_id', $userId)
            ->pluck('sender_id');

        foreach ($followerIds as $followerId) {
            Notification::create([
                'user_id' => $userId,
                'message' => "{$store->store_name} added a new product: {$product->name}",
                'link' => route('marketplace.view-product', $product->slug),
                'notification_reciever_id' => $followerId,
                'read_notification' => 'no',
                'type' => 'marketplace_new_product',
                'notifiable_type' => MarketplaceProduct::class,
                'notifiable_id' => $product->id,
                'data' => json_encode([
                    'store_id' => $store->id,
                    'store_name' => $store->store_name,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_slug' => $product->slug  // Ã°Å¸â€˜Ë† ADD THIS LINE
                ])
            ]);
        }
    } catch (\Exception $e) {
        \Log::warning('Failed to notify followers: ' . $e->getMessage());
    }
}

    /**
     * Get conversion rate
     */
    public function getConversionRate(Request $request)
    {
        $currency = $request->query('currency', 'NGN');
        $baseFeeNgn = 2500;

        if ($currency === 'NGN') {
            return response()->json([
                'success' => true,
                'convertedAmount' => $baseFeeNgn
            ]);
        }

        $rateResponse = Http::withToken(env('FLW_SECRET_KEY'))
            ->get('https://api.flutterwave.com/v3/rates', [
                'from' => 'NGN',
                'to'   => $currency,
                'amount' => $baseFeeNgn
            ])
            ->json();

        if (isset($rateResponse['status']) && $rateResponse['status'] === 'success') {
            return response()->json([
                'success' => true,
                'convertedAmount' => round($rateResponse['data']['rate'] * $baseFeeNgn, 2)
            ]);
        }

        return response()->json(['success' => false]);
    }

    /**
 * View a single store (public view)
 */
public function viewStore($storeSlug)
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }
    
    $user = UserRecord::find($userId);
    
    // Get store with owner information
    $store = MarketplaceStore::with('owner')
        ->where('store_slug', $storeSlug)
        ->where('status', 'active')
        ->firstOrFail();
    
    // Track store view (only once per user)
    $existingView = MarketplaceStoreView::where('store_id', $store->id)
        ->where('viewer_id', $userId)
        ->whereNull('product_id')
        ->first();
    
    if (!$existingView) {
        MarketplaceStoreView::create([
            'store_id' => $store->id,
            'product_id' => null,
            'viewer_id' => $userId,
            'ip_address' => request()->ip()
        ]);
        
        $store->increment('total_views');
    }
    
    // Get all active products
    $products = MarketplaceProduct::where('store_id', $store->id)
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->paginate(12);
    
    // Get unique categories
    $categories = MarketplaceProduct::where('store_id', $store->id)
        ->where('status', 'active')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category')
        ->filter();
    
    return view('marketplace.view-store', compact('user', 'store', 'products', 'categories'));
   }


public function updateStore(Request $request)
{
    $userId = Session::get('id');
    $store = MarketplaceStore::where('owner_id', $userId)->firstOrFail();
    
    $request->validate([
        'store_name' => 'required|string|max:200',
        'description' => 'nullable|string|max:1000',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:100',
        'address' => 'required|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'country' => 'required|string|max:50',
        'currency' => 'nullable|string|max:3',
        'logo' => 'nullable|image|max:2048',
        'banner' => 'nullable|image|max:2048'
    ]);
    
    DB::beginTransaction();
    try {
        // Update text fields
        $store->update([
            'store_name' => $request->store_name,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'currency' => $request->currency ?? $store->currency
        ]);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $uploadPath = public_path('uploads/stores');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $logo = $request->file('logo');
            $logoName = 'store_logo_' . time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $logo->move($uploadPath, $logoName);
            
            // Delete old logo
            if ($store->logo && file_exists(public_path($store->logo))) {
                unlink(public_path($store->logo));
            }
            
            $store->logo = 'uploads/stores/' . $logoName;
        }
        
        // Handle banner upload
        if ($request->hasFile('banner')) {
            $uploadPath = public_path('uploads/stores');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $banner = $request->file('banner');
            $bannerName = 'store_banner_' . time() . '_' . uniqid() . '.' . $banner->getClientOriginalExtension();
            $banner->move($uploadPath, $bannerName);
            
            // Delete old banner
            if ($store->banner && file_exists(public_path($store->banner))) {
                unlink(public_path($store->banner));
            }
            
            $store->banner = 'uploads/stores/' . $bannerName;
        }
        
        $store->save();
        
        DB::commit();
        
        return response()->json([
            'success' => true, 
            'message' => 'Store updated successfully!'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Store update failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'error' => 'Failed to update store: ' . $e->getMessage()
        ], 500);
    }
}


}