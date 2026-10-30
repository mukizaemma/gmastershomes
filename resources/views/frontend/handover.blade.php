@push('head')
<style>
    .handover-doc { max-width: 900px; margin-left: auto; margin-right: auto; }
    .handover-doc h2 { margin-top: 0; font-size: clamp(1.35rem, 2.5vw, 1.6rem); }
    .handover-doc h3 { margin-top: 1.5rem; font-size: 1.1rem; font-weight: 600; }
    .handover-doc .handover-lead { font-size: 1.05rem; line-height: 1.65; }
    .handover-doc table { font-size: 0.95rem; }
    .handover-doc .callout-training {
        border-left: 4px solid #0356b7;
        background: #f0f6fc;
        padding: 1rem 1.25rem;
        border-radius: 0 8px 8px 0;
    }
    .handover-doc code { font-size: 0.88em; }
    .handover-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .handover-tabs button {
        border: 1px solid #d7dde5;
        background: #fff;
        color: #1a1a1a;
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        font-size: 0.92rem;
        line-height: 1.2;
    }
    .handover-tabs button.is-active {
        background: #111;
        border-color: #111;
        color: #fff;
    }
    .handover-panel { padding-top: 0.25rem; }
    @media print {
        .handover-no-print { display: none !important; }
        .handover-doc { font-size: 11pt; max-width: 100%; }
        .rts__section { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        .handover-panel,
        .handover-panel[hidden] { display: block !important; }
        .handover-panel { break-inside: avoid; margin-bottom: 1.5rem; }
        .handover-panel h2 { margin-top: 1.25rem; }
    }
</style>
@endpush

<div class="public-livewire-page">
<div class="rts__section section__padding handover-doc text-start">
    <div class="container py-3 py-lg-4">
        <div class="handover-no-print d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <p class="small text-muted mb-0">GMasters Homes · Website handover</p>
                <p class="small mb-0"><strong>Ireme Technologies</strong></p>
            </div>
            <button type="button" class="theme-btn btn-style sm-btn fill" onclick="window.print()">
                <span><i class="fa-solid fa-print me-1" aria-hidden="true"></i> Print / Save as PDF</span>
            </button>
        </div>

        <header class="mb-4">
            <h1 class="mb-3" style="font-size: clamp(1.5rem, 3vw, 2rem);">Website handover and admin guide</h1>
            <p class="handover-lead text-muted mb-2">What this site is for, what guests can do, how to get admin access, and how hosting invoices work.</p>
            <p class="small text-muted mb-0">Prepared for <strong>GMasters Homes</strong> · 30 October 2026 · <strong>Ireme Technologies</strong></p>
        </header>

        <div class="handover-tabs handover-no-print" role="tablist">
            <button type="button" class="is-active" data-handover-tab="website" role="tab" aria-selected="true">The website</button>
            <button type="button" data-handover-tab="guests" role="tab" aria-selected="false">Guests</button>
            <button type="button" data-handover-tab="access" role="tab" aria-selected="false">Admin access</button>
            <button type="button" data-handover-tab="guide" role="tab" aria-selected="false">Admin guide</button>
            <button type="button" data-handover-tab="security" role="tab" aria-selected="false">Security</button>
            <button type="button" data-handover-tab="hosting" role="tab" aria-selected="false">Hosting</button>
        </div>

        <section class="handover-panel" data-handover-panel="website" role="tabpanel">
            <h2>What the website is for</h2>
            <p>GMasters Homes is a guest house in Kabeza, Kigali. The public site shows the house, the rooms, and tours and safaris in Rwanda and Uganda. Guests ask for a stay or a tour itinerary. Tour prices are sent after the team has the dates, group size, accommodation preference, destinations, and transport preference. The site is in English and French.</p>
            <p>The menu guests see is Home, Accommodation, Tours &amp; Safaris, Gallery, and Contact.</p>
            <h3>What has been done</h3>
            <ul>
                <li class="mb-2">Public pages for the guest house, accommodation, tours and safaris, gallery, and contact.</li>
                <li class="mb-2">Four safari packages with day-by-day itineraries and a photo gallery on each tour.</li>
                <li class="mb-2">Room requests and tour itinerary requests saved for the admin, with email to the guest and the team when mail is configured.</li>
                <li class="mb-2">Manage and cancel links in the guest email. Cancel asks for a reason and a second confirmation, and it stays open until the day before the start date.</li>
                <li class="mb-2">A check when the same email already sent a room or tour request in the last 7 days.</li>
                <li class="mb-2">Tour requests can be sent by email, or opened in WhatsApp with the details the guest typed.</li>
                <li class="mb-0">Annual hosting invoices in the admin, and this handover for the team.</li>
            </ul>
        </section>

        <section class="handover-panel" data-handover-panel="guests" role="tabpanel" hidden>
            <h2>What guests can see and do</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>Area</th>
                            <th>Path</th>
                            <th>What a guest can do</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Home</td>
                            <td><code>/</code></td>
                            <td>See the guest house and move to rooms, tours, or contact.</td>
                        </tr>
                        <tr>
                            <td>Accommodation</td>
                            <td><code>/our-rooms</code></td>
                            <td>Read each room and start a stay request.</td>
                        </tr>
                        <tr>
                            <td>Book a stay</td>
                            <td><code>/book-now</code></td>
                            <td>Send dates, guests, and contact details. The team confirms from the admin.</td>
                        </tr>
                        <tr>
                            <td>Tours &amp; Safaris</td>
                            <td><code>/tours-and-safaris</code></td>
                            <td>Read the itineraries and open a tour.</td>
                        </tr>
                        <tr>
                            <td>Tour request</td>
                            <td><code>/tours-and-safaris/{slug}</code></td>
                            <td>Send accommodation, destinations, transport, dates, number of people, and the tours. The price is sent after review.</td>
                        </tr>
                        <tr>
                            <td>Gallery</td>
                            <td><code>/gallery</code></td>
                            <td>Look at photos of the guest house.</td>
                        </tr>
                        <tr>
                            <td>Contact</td>
                            <td><code>/contact</code></td>
                            <td>Write to the house.</td>
                        </tr>
                        <tr>
                            <td>Manage a request</td>
                            <td><code>/request/{token}</code></td>
                            <td>Open the link from the email and update the same request.</td>
                        </tr>
                        <tr>
                            <td>Cancel a request</td>
                            <td><code>/request/{token}/cancel</code></td>
                            <td>Give a reason, confirm a second time, and cancel before the start date.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mb-0">French pages use the same paths with <code>/fr</code> in front. On a tour page, <strong>Submit via Email</strong> is shown when email delivery is configured, beside <strong>Submit via WhatsApp</strong>. WhatsApp opens in a new tab with the details they entered.</p>
        </section>

        <section class="handover-panel" data-handover-panel="access" role="tabpanel" hidden>
            <h2>Get admin access first</h2>
            <p>Create your account before you manage the website. Register with the email address that should receive admin access:</p>
            <p><a href="{{ url('/register') }}">{{ url('/register') }}</a></p>
            <div class="callout-training mb-4">
                <p class="mb-2"><strong>Share that email with the developer</strong></p>
                <p class="mb-0">Send the email you used to register to Ireme Technologies at <a href="mailto:{{ config('hosting.issuer_email') }}">{{ config('hosting.issuer_email') }}</a>. The developer grants admin access for that address. Wait until that access is in place, then sign in and continue with the website.</p>
            </div>
            <p>After access is granted, sign in at <a href="{{ url('/login') }}">{{ url('/login') }}</a>. Super Admins and Content Managers land on the content dashboard.</p>
            <h3>What the admin can do</h3>
            <ul class="mb-0">
                <li class="mb-2"><strong>Reservations</strong> — read room requests and tour itinerary requests.</li>
                <li class="mb-2"><strong>Rooms and discounts</strong> — edit suites, prices, and offers.</li>
                <li class="mb-2"><strong>Tours &amp; Safaris</strong> — edit the text, the cover, and the image gallery for each trip.</li>
                <li class="mb-2"><strong>Gallery, slideshow, and page backgrounds</strong> — photos for the public site.</li>
                <li class="mb-2"><strong>Settings</strong> — contact email, phone (this number is the WhatsApp link), logo, about text, and the description shown when a link to the site is shared.</li>
                <li class="mb-2"><strong>Users</strong> — Super Admin only.</li>
                <li class="mb-2"><strong>Hosting</strong> — a table of invoices. An admin can view one. A super admin can view, edit, and delete, and can open Settings for the exchange rate and the next invoice amounts.</li>
                <li class="mb-0"><strong>Handover guide</strong> — opens this page.</li>
            </ul>
        </section>

        <section class="handover-panel" data-handover-panel="guide" role="tabpanel" hidden>
            <h2>Admin user guide</h2>
            <h3>1. Register and wait for access</h3>
            <ol>
                <li class="mb-2">Open <a href="{{ url('/register') }}">{{ url('/register') }}</a> and create an account.</li>
                <li class="mb-2">Email that address to <a href="mailto:{{ config('hosting.issuer_email') }}">{{ config('hosting.issuer_email') }}</a> so the developer can grant admin access.</li>
                <li class="mb-0">Sign in at <a href="{{ url('/login') }}">{{ url('/login') }}</a> only after that access is in place. Use the left menu from the dashboard.</li>
            </ol>
            <h3>Update a room</h3>
            <ol>
                <li class="mb-2">Open <strong>Rooms</strong>.</li>
                <li class="mb-2">Edit the name, description, price, and photos, then save.</li>
                <li class="mb-0">Open the public Accommodation page and confirm the room reads the way you expect.</li>
            </ol>
            <h3>Update a tour and its gallery</h3>
            <ol>
                <li class="mb-2">Open <strong>Tours &amp; Safaris</strong>.</li>
                <li class="mb-2">Edit the summary, duration, and itinerary. Add or replace gallery images for that tour.</li>
                <li class="mb-0">The public address stays <code>/tours-and-safaris/{slug}</code>.</li>
            </ol>
            <h3>Read a guest request</h3>
            <ol>
                <li class="mb-2">Open <strong>Reservations</strong>.</li>
                <li class="mb-2">A room request shows the dates and the quoted stay. A tour request shows the tours, accommodation, transport, and destinations.</li>
                <li class="mb-0">Reply from the reservation when you are ready.</li>
            </ol>
            <h3>WhatsApp and email on tour requests</h3>
            <p class="mb-0">Put the house phone, including the country code, in <strong>Settings</strong>. Guests who choose WhatsApp open a chat to that number with their form answers filled in.</p>
        </section>

        <section class="handover-panel" data-handover-panel="security" role="tabpanel" hidden>
            <h2>How it is secured</h2>
            <ul class="mb-0">
                <li class="mb-2">The admin area asks for a signed-in Super Admin or Content Manager. Registering an account does not open the admin by itself. The developer grants that access after you share the email.</li>
                <li class="mb-2">Passwords are stored hashed. Admin forms use a CSRF token.</li>
                <li class="mb-2">A guest manages or cancels a request with a long random link from their email.</li>
                <li class="mb-2">Cancellation asks for a reason and a second confirmation. It closes on the start date, after check-in, and after the request is already cancelled.</li>
                <li class="mb-2">Email addresses are checked for a valid format and a mail server before a request is accepted.</li>
                <li class="mb-2">The same email address is stopped from quietly creating another room or tour request within 7 days.</li>
                <li class="mb-2">Team and guest mail goes out through Resend when the API key is set.</li>
                <li class="mb-0">This handover page is kept out of search results. Share the link with the team: <a href="{{ url('/handover') }}">{{ url('/handover') }}</a>.</li>
            </ul>
        </section>

        <section class="handover-panel" data-handover-panel="hosting" role="tabpanel" hidden>
            <h2>Annual hosting</h2>
            <p>Open <strong>Hosting</strong> in the admin menu. The page is a table of invoices. An admin can <strong>View</strong> an invoice. A super admin can <strong>View</strong>, <strong>Edit</strong>, and <strong>Delete</strong>. The <strong>Settings</strong> button is for the super admin and holds the exchange rate and the service amounts for the next invoice.</p>
            <p>An invoice is created every <strong>1 October</strong> for <strong>1 October through 30 September</strong>. Each one lists domain registration, hosting at <strong>$80.00</strong> a year, SSL, and support. Domain, SSL, and support stay at $0.00 until a super admin sets a cost in Settings. The invoice shows US dollars and Rwandan francs. The rate starts at <strong>1 USD = 1,530 RWF</strong>. Only <strong>admin@iremetech.com</strong> can change that rate, from Settings. A change applies to the next invoice.</p>
            <p>The current year, <strong>1 October 2026 to 30 September 2027</strong>, is invoice <strong>IREME/gmasters/PH001/2026</strong> and is already <strong>Paid</strong>. Later invoices are created unpaid. A super admin marks one paid after the transfer is received, and can send a payment reminder if payment is delayed.</p>
            <p>The team is emailed, and a notice stays on the dashboard, 30 days before the year ends, 2 days before, on the ending day, and after it has expired.</p>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100 bg-white">
                        <strong>Bank Transfer</strong>
                        <div>Account No: 4491274576</div>
                        <div>Bank: BPR</div>
                        <div>Names: Ireme Technologies</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100 bg-white">
                        <strong>MoMo Pay</strong>
                        <div>20033</div>
                        <div>Ireme Technologies</div>
                    </div>
                </div>
            </div>
            <p class="mb-0">Reminders also run daily at 08:00 Kigali time when the server runs <code>php artisan schedule:run</code> every minute. Opening the admin runs the same check.</p>
        </section>
    </div>
</div>
</div>

@push('scripts')
<script>
(function () {
    var root = document.querySelector('.handover-doc');
    if (!root || root.dataset.tabsBound) {
        return;
    }
    root.dataset.tabsBound = '1';
    var tabs = root.querySelectorAll('[data-handover-tab]');
    var panels = root.querySelectorAll('[data-handover-panel]');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var id = tab.getAttribute('data-handover-tab');
            tabs.forEach(function (item) {
                var on = item === tab;
                item.classList.toggle('is-active', on);
                item.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            panels.forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-handover-panel') !== id;
            });
        });
    });
})();
</script>
@endpush
