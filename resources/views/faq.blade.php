@extends('layouts.app')

@section('title', 'FAQ - SupperAge')
@section('seo_title', 'FAQ - SupperAge Help Center')
@section('seo_description', 'Find answers to frequently asked questions about SupperAge. Learn how to use the platform, manage your account, earn rewards, and more.')

@section('content')
<div class="faq-container">
    <div class="faq-header">
        <h1>Frequently Asked Questions</h1>
        <p>Find quick answers to common questions about SupperAge</p>
    </div>

    <div class="faq-search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="faqSearch" placeholder="Search for answers..." class="search-input">
    </div>

    <div class="faq-categories">
        <button class="category-btn active" data-category="all">
            <i class="fas fa-th-large"></i> All Questions
        </button>
        <button class="category-btn" data-category="general">
            <i class="fas fa-info-circle"></i> General
        </button>
        <button class="category-btn" data-category="earning">
            <i class="fas fa-coins"></i> Earning
        </button>
        <button class="category-btn" data-category="wallet">
            <i class="fas fa-wallet"></i> Wallet & Payments
        </button>
        <button class="category-btn" data-category="marketplace">
            <i class="fas fa-store"></i> Marketplace
        </button>
        <button class="category-btn" data-category="security">
            <i class="fas fa-shield-alt"></i> Security
        </button>
        <button class="category-btn" data-category="advertising">
            <i class="fas fa-ad"></i> Advertising
        </button>
    </div>

    <div class="faq-content">
        <div class="faq-item" data-category="general">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    What is SupperAge?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p>SupperAge is a social-financial app where you can chat, share, earn, shop, create stores, fund wallets, and withdraw money. It's a complete ecosystem that combines social networking with financial services and e-commerce.</p>
            </div>
        </div>

        <div class="faq-item" data-category="earning">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    How do I earn money on SupperAge?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p>There are many ways to earn on SupperAge:</p>
                <ul>
                    <li><strong>Completing tasks</strong> - Like, share, comment, follow other users</li>
                    <li><strong>Joining groups</strong> - Get rewarded for community participation</li>
                    <li><strong>Referrals</strong> - Invite friends and earn bonuses</li>
                    <li><strong>Selling in the marketplace</strong> - Create a store and sell products or services</li>
                    <li><strong>Receiving funding</strong> - Other users can fund your wallet directly</li>
                    <li><strong>Live stream support</strong> - Receive gifts and tips during live broadcasts</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" data-category="wallet">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    How do I fund my wallet?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p>You can fund your wallet using several methods:</p>
                <ul>
                    <li><strong>Bank transfer</strong> - Direct transfer from your bank account</li>
                    <li><strong>Card payments</strong> - Debit or credit cards</li>
                    <li><strong>Mobile money</strong> - Available in supported countries</li>
                    <li><strong>From another SupperAge user</strong> - Receive funds directly from other users</li>
                </ul>
                <p>Simply go to your Wallet section and choose your preferred funding method.</p>
            </div>
        </div>

        <div class="faq-item" data-category="wallet">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    How do I withdraw money?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p>Withdrawing money is simple:</p>
                <ol>
                    <li>Go to <strong>Wallet → Withdraw → Bank Account</strong></li>
                    <li>Choose the amount you want to withdraw</li>
                    <li>Confirm the transaction</li>
                    <li>Money will be sent to your registered bank account</li>
                </ol>
                <p class="note"><i class="fas fa-info-circle"></i> Withdrawal fees may apply depending on your location and amount.</p>
            </div>
        </div>

        <div class="faq-item" data-category="marketplace">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    How can I sell products or services?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p>Starting your store is easy:</p>
                <ol>
                    <li>Go to <strong>Marketplace → Create Store</strong></li>
                    <li>Upload your items or services</li>
                    <li>Set your prices</li>
                    <li>Start selling!</li>
                </ol>
                <p>You can sell physical products, digital products, or services. Payments go directly to your SupperAge wallet.</p>
            </div>
        </div>

        <div class="faq-item" data-category="security">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    Are my chats and data secure?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p><strong>Yes, absolutely!</strong> We take security seriously and use:</p>
                <ul>
                    <li><strong>Encryption</strong> - All data is encrypted in transit and at rest</li>
                    <li><strong>Secure servers</strong> - Industry-standard security infrastructure</li>
                    <li><strong>Fraud prevention</strong> - Advanced monitoring systems</li>
                    <li><strong>Active monitoring</strong> - 24/7 security team</li>
                    <li><strong>Two-factor authentication</strong> - Optional extra security layer</li>
                </ul>
                <p>Your privacy and security are our top priorities.</p>
            </div>
        </div>

        <div class="faq-item" data-category="advertising">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    Can I advertise on SupperAge?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p><strong>Yes!</strong> Users can purchase various advertising options:</p>
                <ul>
                    <li><strong>Sponsored posts</strong> - Promote your content to a wider audience</li>
                    <li><strong>Story ads</strong> - Appear in users' story feeds</li>
                    <li><strong>Banner ads</strong> - Prominent placement on the platform</li>
                    <li><strong>Profile boosts</strong> - Increase your profile visibility</li>
                </ul>
                <p>Visit our Advertising section to view pricing and create your first campaign.</p>
            </div>
        </div>

        <div class="faq-item" data-category="general">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    Is SupperAge free?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p><strong>Yes!</strong> All basic features are completely free, including:</p>
                <ul>
                    <li>Creating an account</li>
                    <li>Chatting with friends</li>
                    <li>Posting content and stories</li>
                    <li>Completing tasks to earn</li>
                    <li>Creating a marketplace store</li>
                    <li>Sending and receiving funds</li>
                </ul>
                <p><strong>Optional paid features:</strong></p>
                <ul>
                    <li>Advertising campaigns</li>
                    <li>Marketplace transaction fees (seller fees)</li>
                    <li>Wallet withdrawal fees (varies by location)</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" data-category="general">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    Can SupperAge be used worldwide?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p><strong>Yes, absolutely!</strong> SupperAge is a global platform with:</p>
                <ul>
                    <li><strong>Multi-currency support</strong> - Send and receive in various currencies</li>
                    <li><strong>International payments</strong> - Transfer money across borders</li>
                    <li><strong>Global marketplace</strong> - Buy and sell worldwide</li>
                    <li><strong>Multi-language support</strong> - Connect with users everywhere</li>
                </ul>
                <p>Anyone, anywhere can join SupperAge and connect with the world.</p>
            </div>
        </div>

        <div class="faq-item" data-category="general">
            <div class="faq-question">
                <h3>
                    <i class="fas fa-question-circle"></i>
                    How do I contact support?
                </h3>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="faq-answer">
                <p>We're here to help! You can reach us through:</p>
                <div class="contact-methods">
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong>
                            <p><a href="mailto:info@supperage.com">info@supperage.com</a></p>
                        </div>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-comments"></i>
                        <div>
                            <strong>Contact Form</strong>
                            <p><a href="{{ route('contact') }}">Submit a request</a></p>
                        </div>
                    </div>
                </div>
                <p class="response-time"><i class="fas fa-clock"></i> We typically respond within 24-48 hours</p>
            </div>
        </div>
    </div>

    <div class="still-need-help">
        <div class="help-card">
            <i class="fas fa-life-ring"></i>
            <h3>Still need help?</h3>
            <p>Can't find the answer you're looking for? Our support team is ready to assist you.</p>
            <a href="{{ route('contact') }}" class="contact-btn">
                <i class="fas fa-paper-plane"></i> Contact Support
            </a>
        </div>
    </div>
</div>

<style>
.faq-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.faq-header {
    text-align: center;
    margin-bottom: 40px;
}

.faq-header h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.faq-header p {
    font-size: 1.1em;
    color: #666;
}

.faq-search-box {
    position: relative;
    margin-bottom: 30px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.faq-search-box i {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 1.2em;
}

.search-input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: 2px solid #e0e0e0;
    border-radius: 50px;
    font-size: 1em;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #1DA1F2;
    box-shadow: 0 0 15px rgba(29, 161, 242, 0.2);
}

.faq-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 40px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
}

.category-btn {
    padding: 10px 20px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    color: #2c3e50;
    font-size: 0.95em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.category-btn:hover {
    border-color: #1DA1F2;
    color: #1DA1F2;
    transform: translateY(-2px);
}

.category-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.faq-content {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.faq-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.faq-item.hidden {
    display: none;
}

.faq-question {
    padding: 20px 25px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    transition: background 0.3s ease;
}

.faq-question:hover {
    background: #e9ecef;
}

.faq-question h3 {
    font-size: 1.15em;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.faq-question i.fa-question-circle {
    color: #1DA1F2;
    font-size: 1.2em;
}

.toggle-icon {
    color: #666;
    transition: transform 0.3s ease;
    font-size: 1em;
}

.faq-item.active .toggle-icon {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.faq-item.active .faq-answer {
    max-height: 1000px;
}

.faq-answer > div {
    padding: 25px;
}

.faq-answer p {
    font-size: 1.05em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 15px;
}

.faq-answer ul, .faq-answer ol {
    margin: 15px 0;
    padding-left: 30px;
}

.faq-answer li {
    font-size: 1.05em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 10px;
}

.faq-answer strong {
    color: #2c3e50;
}

.note {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 12px 15px;
    border-radius: 5px;
    margin-top: 15px;
}

.note i {
    color: #ffc107;
    margin-right: 8px;
}

.contact-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.contact-method i {
    font-size: 2em;
    color: #1DA1F2;
}

.contact-method strong {
    display: block;
    margin-bottom: 5px;
}

.contact-method p {
    margin: 0;
}

.contact-method a {
    color: #1DA1F2;
    text-decoration: none;
    font-weight: 600;
}

.contact-method a:hover {
    text-decoration: underline;
}

.response-time {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-style: italic;
}

.response-time i {
    color: #1DA1F2;
    margin-right: 5px;
}

.still-need-help {
    margin-top: 50px;
}

.help-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    color: white;
}

.help-card i {
    font-size: 3em;
    margin-bottom: 20px;
    opacity: 0.9;
}

.help-card h3 {
    font-size: 1.8em;
    margin-bottom: 15px;
}

.help-card p {
    font-size: 1.1em;
    margin-bottom: 25px;
    opacity: 0.95;
}

.contact-btn {
    display: inline-block;
    padding: 15px 35px;
    background: white;
    color: #667eea;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1em;
    transition: all 0.3s ease;
}

.contact-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.contact-btn i {
    margin-right: 8px;
}

@media (max-width: 768px) {
    .faq-container {
        padding: 20px 10px;
    }

    .faq-header h1 {
        font-size: 2em;
    }

    .category-btn {
        font-size: 0.85em;
        padding: 8px 15px;
    }

    .faq-question h3 {
        font-size: 1em;
    }

    .contact-methods {
        grid-template-columns: 1fr;
    }

    .help-card {
        padding: 30px 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    const categoryBtns = document.querySelectorAll('.category-btn');
    const searchInput = document.getElementById('faqSearch');

    // Toggle FAQ answers
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            item.classList.toggle('active');
        });
    });

    // Category filter
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            faqItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.classList.remove('hidden');
                if (searchTerm.length > 2) {
                    item.classList.add('active');
                }
            } else {
                item.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection