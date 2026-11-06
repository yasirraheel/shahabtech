/* ShahabTech location based pricing and wording.
 * Detects the visitor country from their IP address and shows prices in
 * the local currency, plus the country name and flag in the hero, header,
 * About and testimonial sections. No API key is needed.
 * The lookup is best effort. If it fails, the page keeps the server
 * rendered USD prices and the country lines stay hidden.
 * Fixed conversion rates from USD, as of 7 Nov 2026. PKR uses the State
 * Bank of Pakistan interbank rate, 1 USD = 276.66 PKR. AED uses its
 * official peg, 1 USD = 3.6725 AED, and SAR uses its peg, 1 USD = 3.75 SAR.
 * Other rates are approximate market rates.
 */
(function () {
    'use strict';

    var RATES = {
        AE: { code: 'AED', rate: 3.6725 },
        SA: { code: 'SAR', rate: 3.75 },
        QA: { code: 'QAR', rate: 3.64 },
        KW: { code: 'KWD', rate: 0.31 },
        BH: { code: 'BHD', rate: 0.38 },
        OM: { code: 'OMR', rate: 0.39 },
        PK: { code: 'PKR', rate: 276.66 },
        US: { code: 'USD', rate: 1 },
        GB: { code: 'GBP', rate: 0.76 },
        IN: { code: 'INR', rate: 88.5 },
        CA: { code: 'CAD', rate: 1.4 },
        AU: { code: 'AUD', rate: 1.53 }
    };

    /* Eurozone country codes, all priced in EUR at 0.86 per USD. */
    var EUROZONE = ['AT', 'BE', 'CY', 'DE', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PT', 'SI', 'SK'];

    var CACHE_KEY = 'shahabtech_visitor_location';

    function currencyFor(code) {
        if (RATES[code]) {
            return RATES[code];
        }
        if (EUROZONE.indexOf(code) !== -1) {
            return { code: 'EUR', rate: 0.86 };
        }
        return { code: 'USD', rate: 1 };
    }

    function formatAmount(value) {
        return Math.round(value).toLocaleString('en-US');
    }

    function applyLocation(countryCode, countryName) {
        var currency = currencyFor(countryCode);

        /* Prices: every element carrying a USD base price converts. */
        var priceEls = document.querySelectorAll('[data-price-usd]');
        for (var i = 0; i < priceEls.length; i++) {
            var el = priceEls[i];
            var usd = parseFloat(el.getAttribute('data-price-usd'));
            if (isNaN(usd)) {
                continue;
            }
            el.setAttribute('title', 'USD ' + formatAmount(usd));
            if (currency.code === 'USD') {
                el.textContent = 'USD ' + formatAmount(usd);
            } else {
                el.textContent = currency.code + ' ' + formatAmount(usd * currency.rate);
            }
        }

        /* Country name lines: hero, About and testimonial sections. */
        var nameEls = document.querySelectorAll('.js-country-name');
        for (var j = 0; j < nameEls.length; j++) {
            nameEls[j].textContent = countryName;
        }
        var lines = document.querySelectorAll('.js-country-line');
        for (var k = 0; k < lines.length; k++) {
            lines[k].hidden = false;
        }

        /* Header flag next to the logo. Hide it completely if the flag
           image fails to load, instead of showing a stretched bar. */
        var flagWrap = document.querySelector('.js-country-flag');
        var flagImg = document.querySelector('.js-flag-img');
        if (flagWrap && flagImg) {
            flagImg.onerror = function () { flagWrap.hidden = true; };
            flagImg.src = 'https://flagcdn.com/w40/' + countryCode.toLowerCase() + '.png';
            flagImg.alt = countryName;
            flagWrap.hidden = false;
        }
    }

    function readCache() {
        try {
            var raw = window.sessionStorage.getItem(CACHE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function writeCache(data) {
        try {
            window.sessionStorage.setItem(CACHE_KEY, JSON.stringify(data));
        } catch (e) {
            /* Storage unavailable, the lookup simply runs again next page. */
        }
    }

    function fetchWithTimeout(url, ms) {
        var controller = new AbortController();
        var timer = setTimeout(function () { controller.abort(); }, ms);
        return fetch(url, { signal: controller.signal })
            .then(function (response) {
                clearTimeout(timer);
                if (!response.ok) {
                    throw new Error('Lookup failed');
                }
                return response.json();
            })
            .catch(function (err) {
                clearTimeout(timer);
                throw err;
            });
    }

    function detectCountry() {
        /* Primary lookup, free and no key needed. */
        return fetchWithTimeout('https://ipapi.co/json/', 4000)
            .then(function (data) {
                var code = (data.country_code || '').toUpperCase();
                var name = data.country_name || '';
                if (!code || !name) {
                    throw new Error('Incomplete lookup result');
                }
                return { code: code, name: name };
            })
            .catch(function () {
                /* Fallback lookup, free and no key needed. */
                return fetchWithTimeout('https://ip-api.com/json/', 4000)
                    .then(function (data) {
                        var code = (data.countryCode || '').toUpperCase();
                        var name = data.country || '';
                        if (!code || !name) {
                            throw new Error('Incomplete lookup result');
                        }
                        return { code: code, name: name };
                    });
            });
    }

    function init() {
        var cached = readCache();
        if (cached && cached.code && cached.name) {
            applyLocation(cached.code, cached.name);
            return;
        }
        detectCountry()
            .then(function (location) {
                writeCache(location);
                applyLocation(location.code, location.name);
            })
            .catch(function () {
                /* Both lookups failed. Prices stay in USD and the
                   country lines and flag stay hidden. */
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
