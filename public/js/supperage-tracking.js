// (function(window) {
//     'use strict';
    
//     const SupperageTracking = {
//         adId: null,
//         apiUrl: '',
        
//         /**
//          * Initialize tracking
//          */
//         init: function(adId) {
//             this.adId = adId;
//             this.apiUrl = window.location.origin + '/advertising/' + adId + '/action';
//             console.log('Supperage Tracking initialized for Ad #' + adId);
//         },
        
//         /**
//          * Track a conversion action
//          */
//         track: function(actionType, value, metaData) {
//             if (!this.adId) {
//                 console.error('Supperage Tracking not initialized. Call init() first.');
//                 return;
//             }
            
//             fetch(this.apiUrl, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'Accept': 'application/json',
//                 },
//                 body: JSON.stringify({
//                     action_type: actionType,
//                     value: value || 0,
//                     meta_data: metaData || {},
//                 })
//             })
//             .then(response => response.json())
//             .then(data => {
//                 console.log('✅ Supperage conversion tracked:', actionType, data);
//             })
//             .catch(error => {
//                 console.error('❌ Supperage tracking failed:', error);
//             });
//         }
//     };
    
//     // Expose to global scope
//     window.SupperageTracking = SupperageTracking;
    
// })(window);



/**
 * Supperage Ad Conversion Tracking
 * Place this file in: public/js/supperage-tracking.js
 * 
 * Advertisers include this on their website to track conversions
 */

(function(window) {
    'use strict';
    
    const SupperageTracking = {
        adId: null,
        baseUrl: window.location.origin,
        
        /**
         * Initialize tracking with ad ID
         * @param {string|number} adId - The advertisement ID
         */
        init: function(adId) {
            this.adId = adId;
            console.log('✅ Supperage Tracking initialized for Ad #' + adId);
            
            // Auto-detect base URL if script is loaded from different domain
            if (document.currentScript) {
                const scriptSrc = document.currentScript.src;
                const url = new URL(scriptSrc);
                this.baseUrl = url.origin;
            }
        },
        
        /**
         * Track a conversion action
         * @param {string} actionType - Type: signup, purchase, download, form_submit, contact, lead, trial, other
         * @param {number} value - Optional monetary value (default: 0)
         * @param {object} metaData - Optional extra data (default: {})
         * @returns {Promise}
         */
        track: function(actionType, value, metaData) {
            // Validation
            if (!this.adId) {
                console.error('❌ Supperage Tracking Error: Not initialized. Call SupperageTracking.init(adId) first.');
                return Promise.reject('Not initialized');
            }
            
            if (!actionType) {
                console.error('❌ Supperage Tracking Error: actionType is required.');
                return Promise.reject('Missing actionType');
            }
            
            // Build tracking URL
            const trackingUrl = this.baseUrl + '/advertising/' + this.adId + '/action';
            
            // Prepare data
            const data = {
                action_type: actionType,
                value: value || 0,
                meta_data: metaData || {},
                timestamp: new Date().toISOString()
            };
            
            console.log('📊 Tracking conversion:', data);
            
            // Send tracking request
            return fetch(trackingUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            })
            .then(result => {
                console.log('✅ Supperage conversion tracked successfully:', result);
                return result;
            })
            .catch(error => {
                console.error('❌ Supperage tracking failed:', error);
                throw error;
            });
        },
        
        /**
         * Track signup conversion
         * @param {object} metaData - Optional extra data
         */
        trackSignup: function(metaData) {
            return this.track('signup', 0, metaData);
        },
        
        /**
         * Track purchase conversion
         * @param {number} amount - Purchase amount
         * @param {object} metaData - Optional extra data
         */
        trackPurchase: function(amount, metaData) {
            return this.track('purchase', amount, metaData);
        },
        
        /**
         * Track download conversion
         * @param {object} metaData - Optional extra data
         */
        trackDownload: function(metaData) {
            return this.track('download', 0, metaData);
        },
        
        /**
         * Track form submission
         * @param {object} metaData - Optional extra data
         */
        trackFormSubmit: function(metaData) {
            return this.track('form_submit', 0, metaData);
        },
        
        /**
         * Track contact action
         * @param {object} metaData - Optional extra data
         */
        trackContact: function(metaData) {
            return this.track('contact', 0, metaData);
        },
        
        /**
         * Track lead generation
         * @param {object} metaData - Optional extra data
         */
        trackLead: function(metaData) {
            return this.track('lead', 0, metaData);
        },
        
        /**
         * Track trial signup
         * @param {object} metaData - Optional extra data
         */
        trackTrial: function(metaData) {
            return this.track('trial', 0, metaData);
        }
    };
    
    // Expose to global scope
    window.SupperageTracking = SupperageTracking;
    
    // Auto-initialize if data-ad-id attribute exists on script tag
    if (document.currentScript) {
        const adId = document.currentScript.getAttribute('data-ad-id');
        if (adId) {
            SupperageTracking.init(adId);
        }
    }
    
})(window);