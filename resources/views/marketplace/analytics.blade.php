
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P7ZNRWKS7Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-P7ZNRWKS7Z');
    </script>

    <meta charset="utf-8">
    <meta name="author" content="omoha Ekenedilichukwu Francis">
    <meta name="description" content="SupperAge is the social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money.">
    <meta name="keywords" content="SupperAge, social financial app, earn money online, chat and earn, online marketplace, digital wallet, social networking, e-commerce platform">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="IWdPOFToacXu8eoMwOYWPxqja5IAyAd_cQSBAILNfWo" />

    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SupperAge Analytics page </title>

<!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <!-- Stylesheets -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Font Awesome 4.7 (matches your fa fa-user-circle class) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/finebtn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobilenavbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/searchuser.css') }}">
</head>
<body>
<!-- Your notify  navbar content  -->
    @include('layouts.navbar')

@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0"><i class="fas fa-chart-line"></i> Store Analytics</h2>
            <p class="text-muted">{{ $store->store_name }}</p>
        </div>
        <a href="{{ route('marketplace.my-store') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Store
        </a>
    </div>

    <!-- Summary Stats -->
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card text-center h-100 bg-primary bg-gradient text-white">
            <div class="card-body">
                <i class="fas fa-eye fa-2x mb-2"></i>
                <h3 class="fw-bold mb-0">{{ number_format($store->total_views) }}</h3>
                <p class="mb-0">Total Views</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card text-center h-100 bg-success bg-gradient text-white">
            <div class="card-body">
                <i class="fas fa-box fa-2x mb-2"></i>
                <h3 class="fw-bold mb-0">{{ number_format($store->total_products) }}</h3>
                <p class="mb-0">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card text-center h-100 bg-warning bg-gradient text-white">
            <div class="card-body">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <h3 class="fw-bold mb-0">{{ number_format($store->total_orders) }}</h3>
                <p class="mb-0">Total Orders</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3 mb-md-0">
        <div class="card text-center h-100 bg-info bg-gradient text-white">
            <div class="card-body">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $productViews->unique('viewer_id')->count() }}</h3>
                <p class="mb-0">Unique Visitors</p>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <!-- Views Chart -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> Views Over Last 30 Days</h5>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Top Viewed Products</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $index => $product)
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'primary') }} rounded-circle" 
                                          style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset($product->images[0]) }}" class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $product->name }}">
                                @else
                                    <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small">{{ Str::limit($product->name, 30) }}</h6>
                                    <small class="text-muted">{{ number_format($product->views) }} views</small>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">
                                No products yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Views Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Recent Product Views</h5>
        </div>
        <div class="card-body">
            @if($productViews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Viewer</th>
                                <th>IP Address</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productViews as $view)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($view->product && $view->product->images && count($view->product->images) > 0)
                                                <img src="{{ asset($view->product->images[0]) }}" class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $view->product->name }}">
                                            @else
                                                <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image text-white small"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $view->product->name ?? 'Unknown Product' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $view->product->category ?? 'Uncategorized' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($view->viewer)
                                            <strong>{{ $view->viewer->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $view->viewer->email }}</small>
                                        @else
                                            <span class="text-muted">Anonymous</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $view->ip_address }}</code></td>
                                    <td>
                                        {{ $view->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $view->created_at->format('h:i A') }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $productViews->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No views yet</h5>
                    <p class="text-secondary">Views will appear here when customers browse your products</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


<script src="{{ asset('myjs/bar.js') }}"></script> <!-- this should come last -->
<script src="{{ asset('myjs/more_lesstext.js') }}"></script>
<script src="{{ asset('myjs/menu_pop_up_post.js') }}"></script>
<script src="{{ asset('myjs/allpost.js') }}"></script>
<script src="{{ asset('myjs/tales.js') }}"></script>
<script src="{{ asset('myjs/mobilenavbar.js') }}"></script>
<script src="{{ asset('myjs/searchuser.js') }}"></script>

<script>
// Prepare data for chart
const viewsData = @json($viewsByDate);
const labels = viewsData.map(item => {
    const date = new Date(item.date);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}).reverse();
const data = viewsData.map(item => item.count).reverse();

// Create chart
const ctx = document.getElementById('viewsChart').getContext('2d');
const viewsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Views',
            data: data,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});
</script>
@endsection

</body>
</html>