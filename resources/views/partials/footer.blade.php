<!-- Footer Section -->
<footer class="aces-footer text-white py-5">
    <div class="container">
        <div class="row align-items-start">

            <!-- Column 1: Contact (Left side) -->
            <div class="col-md-4 mb-4 mb-md-0 text-center text-md-start">
                <h5 class="fw-bold mb-3">Have a Question? Call Us</h5>
                <p class="mb-0 fw-semibold">888-501-4999</p>
            </div>

            <!-- Right Side Wrapper -->
            <div
                class="col-md-8 d-flex flex-column flex-md-row justify-content-md-end text-center text-md-start gap-5 move-left">

                <!-- Column 2: About -->
                <div>
                    <h5 class="fw-bold mb-3">About</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL('/pages/mission') }}" class="footer-link">Mission & History</a></li>
                        <li><a href="{{ URL('/pages/coaching-staff') }}" class="footer-link">Coaching Staff</a></li>
                        <li><a href="{{ URL('/pages/aces-in-college') }}" class="footer-link">ACES Playing In
                                College</a></li>
                        <li><a href="{{ URL('/pages/testimonials') }}" class="footer-link">Testimonials</a></li>
                        <li><a href="{{ URL('/pages/championships') }}" class="footer-link">Championships</a></li>
                    </ul>
                </div>

                <!-- Column 3: Support -->
                <div>
                    <h5 class="fw-bold mb-3">Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ URL('/pages/contact-us') }}" class="footer-link">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="footer-divider my-4" />

        <div class="text-center small">
            © 2026, Powered by <span class="fw-semibold">ENCORE LACROSSE</span>
        </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="bi bi-chevron-up" style="font-size: 15px;"></i>
    </a>
</footer>

@if(session()->has('em_customer_id'))
    @php
        $linkedParentCartCount = 0;
        try {
            $linkedParentCustomer = \App\Models\EMCustomer::query()
                ->whereKey((int) session('em_customer_id'))
                ->where('active', 1)
                ->first();
            if ($linkedParentCustomer) {
                $linkedParentIds = app(\App\Services\Training\TrainingFamilyService::class)->memberIds($linkedParentCustomer);
                $linkedParentCartCount = (int) \App\Models\EMCustomerPackageCart::query()
                    ->whereIn('customer_id', $linkedParentIds)
                    ->sum('quantity');
            }
        } catch (\Throwable $e) {
            $linkedParentCartCount = 0;
        }
    @endphp
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var count = {{ $linkedParentCartCount }};
        document.querySelectorAll('a[aria-label="Training cart"]').forEach(function (cartLink) {
            var badge = cartLink.querySelector('.header-cart-badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'header-cart-badge';
                    cartLink.appendChild(badge);
                }
                badge.textContent = count > 99 ? '99+' : String(count);
            } else if (badge) {
                badge.remove();
            }
        });

        function cleanPendingCalendarText(root) {
            (root || document).querySelectorAll('a.aces-family-pending').forEach(function (link) {
                if ((link.textContent || '').trim().toUpperCase() === 'IN FAMILY CART') {
                    link.textContent = 'IN CART';
                }
            });
        }

        cleanPendingCalendarText(document);
        var calendar = document.getElementById('trainingCalendarGrid');
        if (calendar) {
            new MutationObserver(function () {
                cleanPendingCalendarText(calendar);
            }).observe(calendar, {childList:true, subtree:true, characterData:true});
        }
    });
    </script>
@endif
