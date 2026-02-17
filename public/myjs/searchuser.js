// ========================================
// USER SEARCH FUNCTIONALITY
// ========================================

$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('.search_user');
    const searchContainer = searchInput.closest('.fb-left');
    
    // Create dropdown element if it doesn't exist
    if (!$('#searchResultsDropdown').length) {
        searchContainer.append('<div id="searchResultsDropdown" class="search-results-dropdown"></div>');
    }
    
    const searchDropdown = $('#searchResultsDropdown');
    
    // Handle search input
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // If query is empty, hide dropdown
        if (query.length === 0) {
            searchDropdown.removeClass('show').empty();
            return;
        }
        
        // If query is less than 2 characters, show message
        if (query.length < 2) {
            searchDropdown.addClass('show').html(`
                <div class="search-no-results">
                    <i class="fa fa-search"></i>
                    <p>Type at least 2 characters to search</p>
                </div>
            `);
            return;
        }
        
        // Show loading state
        searchDropdown.addClass('show').html(`
            <div class="search-loading">
                <i class="fa fa-spinner fa-spin"></i>
                <p>Searching...</p>
            </div>
        `);
        
        // Delay search by 300ms to avoid too many requests
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    });
    
    // Perform the search
    function performSearch(query) {
        $.ajax({
            url: '/search/users',
            method: 'GET',
            data: { query: query },
            success: function(response) {
                if (response.success && response.users.length > 0) {
                    displayResults(response.users);
                } else {
                    showNoResults(query);
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                searchDropdown.html(`
                    <div class="search-no-results">
                        <i class="fa fa-exclamation-triangle"></i>
                        <p>Error searching users. Please try again.</p>
                    </div>
                `);
            }
        });
    }
    
    // Display search results
    function displayResults(users) {
        let html = '';
        
        users.forEach(function(user) {
            html += `
                <a href="${user.profile_url}" class="search-result-item">
                    <div class="search-result-avatar">
                        ${user.profileimg 
                            ? `<img src="${user.profileimg}" alt="${user.name}">` 
                            : `<div class="default-avatar"><i class="fa fa-user"></i></div>`
                        }
                        ${user.badge_status 
                            ? `<img src="${user.badge_status}" class="search-result-badge" alt="Verified">` 
                            : ''
                        }
                    </div>
                    <div class="search-result-info">
                        <p class="search-result-name">
                            ${escapeHtml(user.name)}
                        </p>
                        <p class="search-result-username">@${escapeHtml(user.username)}</p>
                        ${user.bio ? `<p class="search-result-bio">${escapeHtml(user.bio)}</p>` : ''}
                        ${user.location ? `<p class="search-result-location"><i class="fa fa-map-marker"></i> ${escapeHtml(user.location)}</p>` : ''}
                    </div>
                </a>
            `;
        });
        
        searchDropdown.html(html);
    }
    
    // Show no results message
    function showNoResults(query) {
        searchDropdown.html(`
            <div class="search-no-results">
                <i class="fa fa-search"></i>
                <p>No users found for "<strong>${escapeHtml(query)}</strong>"</p>
                <small>Try searching by name, username, or email</small>
            </div>
        `);
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container, .fb-left').length) {
            searchDropdown.removeClass('show');
        }
    });
    
    // Close dropdown on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            searchDropdown.removeClass('show');
            searchInput.blur();
        }
    });
    
    // Handle keyboard navigation (Arrow keys)
    searchInput.on('keydown', function(e) {
        const items = searchDropdown.find('.search-result-item');
        const currentActive = searchDropdown.find('.search-result-item.active');
        let index = currentActive.length ? items.index(currentActive) : -1;
        
        // Arrow Down
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            index = (index + 1) % items.length;
            items.removeClass('active');
            items.eq(index).addClass('active');
        }
        
        // Arrow Up
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            index = index <= 0 ? items.length - 1 : index - 1;
            items.removeClass('active');
            items.eq(index).addClass('active');
        }
        
        // Enter
        if (e.key === 'Enter' && currentActive.length) {
            e.preventDefault();
            window.location.href = currentActive.attr('href');
        }
    });
    
    // Add active state styling
    $(document).on('mouseenter', '.search-result-item', function() {
        $('.search-result-item').removeClass('active');
        $(this).addClass('active');
    });
});