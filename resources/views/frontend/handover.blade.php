@push('head')
<style>
    .handover-doc { max-width: 900px; margin-left: auto; margin-right: auto; }
    .handover-doc h2 { margin-top: 2rem; font-size: clamp(1.35rem, 2.5vw, 1.6rem); }
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
    @media print {
        .handover-no-print { display: none !important; }
        .handover-doc { font-size: 11pt; max-width: 100%; }
        .rts__section { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
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
            <p class="handover-lead text-muted mb-2">What this site is for, what guests can do, what the admin can do, how access is protected, and how the annual hosting invoice works.</p>
            <p class="small text-muted mb-0">Prepared for <strong>GMasters Homes</strong> · 30 October 2026 · <strong>Ireme Technologies</strong></p>
        </header>

        <section class="mb-4 p-3 p-lg-4 rounded-3 border bg-light">
            <h2 class="h5 mb-2">About this project</h2>
            <p class="mb-0">GMasters Homes is a guest house in Kabeza, Kigali. The website presents the house and its rooms, and it takes requests for stays and for tours and safaris in Rwanda and Uganda. Ireme Technologies built the site, looks after hosting, and remains available for support.</p>
        </section>

        <h2>1. What the website is for</h2>
        <p>Guests use the site to look at the guest house, choose a room, and ask for a stay. They also use it to choose a tour or safari. Tour prices are sent after the team has the guest’s dates, group size, accommodation preference, destinations, and transport preference. The public site is available in English and French.</p>
        <p>The admin area is where the team updates rooms, photos, tour text, and replies to requests. Hosting for the year is recorded there as an invoice.</p>

        <h2>2. What has been done</h2>
        <ul>
            <li class="mb-2">Public pages for the guest house, rooms, gallery, contact, dining, team, and updates.</li>
            <li class="mb-2">A Tours &amp; Safaris section with four packages, day-by-day itineraries, and a photo gallery on each tour.</li>
            <li class="mb-2">Room requests and tour requests saved for the admin, with an email to the guest and the team when mail is configured.</li>
            <li class="mb-2">A manage link and a cancel link in the guest email. Cancel asks for a reason and a second confirmation, and it stays open until the day before the start date.</li>
            <li class="mb-2">A check that tells a guest when the same email address already sent a room or tour request in the last 7 days, and offers a new request or a fresh manage link.</li>
            <li class="mb-2">Tour requests can be sent by email, or opened in WhatsApp with the details the guest typed.</li>
            <li class="mb-2">An annual hosting invoice in the admin, created every 1 October, with download and renewal reminders.</li>
            <li class="mb-0">This handover page for the team.</li>
        </ul>

        <h2>3. What guests can see and do</h2>
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
                        <td>Read the four itineraries and open a tour.</td>
                    </tr>
                    <tr>
                        <td>Tour request</td>
                        <td><code>/tours-and-safaris/{slug}</code></td>
                        <td>Send accommodation preference, destinations, transport, dates, number of people, and the tours they want. The price is sent after review.</td>
                    </tr>
                    <tr>
                        <td>Gallery, contact, updates</td>
                        <td><code>/gallery</code>, <code>/contact</code>, <code>/our-updates</code></td>
                        <td>Look at photos, write to the house, and read news.</td>
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
        <p>French pages use the same paths with <code>/fr</code> in front. A guest who already sent a request in the past 7 days is asked either to submit a new one or to have the manage link emailed again. On a tour page, <strong>Submit via Email</strong> is shown when email delivery is configured, beside <strong>Submit via WhatsApp</strong>. WhatsApp opens in a new tab and includes the details they entered, using the phone number saved in Settings.</p>

        <h2>4. What the admin can do</h2>
        <p>Sign in at <a href="{{ url('/login') }}">{{ url('/login') }}</a>. Super Admins and Content Managers land on the content dashboard. The left menu is the day-to-day tool.</p>
        <ul>
            <li class="mb-2"><strong>Reservations</strong> — read room requests and tour requests, including accommodation, transport, destinations, and the tours chosen.</li>
            <li class="mb-2"><strong>Rooms and discounts</strong> — edit suites, prices, and offers.</li>
            <li class="mb-2"><strong>Tours &amp; Safaris</strong> — edit the text, the cover, and the image gallery for each trip.</li>
            <li class="mb-2"><strong>Gallery, slideshow, and page backgrounds</strong> — photos for the public site.</li>
            <li class="mb-2"><strong>Settings</strong> — contact email, phone (this number is the WhatsApp link), logo, and about text.</li>
            <li class="mb-2"><strong>Users</strong> — Super Admin only. Add or remove people who can sign in.</li>
            <li class="mb-2"><strong>Hosting</strong> — the annual payment request, in dollars and francs, with download of the current and past invoices. A Super Admin marks an invoice paid and can send a reminder. The exchange rate is changed only by admin@iremetech.com.</li>
            <li class="mb-0"><strong>Handover guide</strong> — opens this page.</li>
        </ul>

        <h2>5. How it is secured</h2>
        <ul>
            <li class="mb-2">The admin area asks for a signed-in Super Admin or Content Manager. A Content Manager does not manage user accounts.</li>
            <li class="mb-2">Passwords are stored hashed. Admin forms use a CSRF token.</li>
            <li class="mb-2">A guest manages or cancels a request with a long random link from their email. The link is not a public account number.</li>
            <li class="mb-2">Cancellation asks for a reason and a second confirmation. It closes on the start date, after check-in, and after the request is already cancelled.</li>
            <li class="mb-2">Email addresses are checked for a valid format and a mail server before a request is accepted.</li>
            <li class="mb-2">The same email address is stopped from quietly creating another room or tour request within 7 days.</li>
            <li class="mb-2">Team and guest mail goes out through Resend when the API key is set.</li>
            <li class="mb-0">This handover page is kept out of search results. Share the link with the team: <a href="{{ url('/handover') }}">{{ url('/handover') }}</a>.</li>
        </ul>

        <h2>6. Admin user guide</h2>

        <h3>Sign in</h3>
        <ol>
            <li class="mb-2">Open <a href="{{ url('/login') }}">{{ url('/login') }}</a>.</li>
            <li class="mb-2">Use the email and password for your Super Admin or Content Manager account.</li>
            <li class="mb-0">You arrive on the content dashboard. Use the left menu from there.</li>
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
            <li class="mb-0">The public address stays <code>/tours-and-safaris/{slug}</code>. Saving a gallery does not change that address.</li>
        </ol>

        <h3>Read a guest request</h3>
        <ol>
            <li class="mb-2">Open <strong>Reservations</strong>.</li>
            <li class="mb-2">A room request shows the dates and the quoted stay. A tour request shows the tours, accommodation, transport, and destinations, and the price is filled in after you review it.</li>
            <li class="mb-0">Reply from the reservation when you are ready. The guest already has a manage link in the email they received.</li>
        </ol>

        <h3>WhatsApp and email on tour requests</h3>
        <p>Put the house phone, including the country code, in <strong>Settings</strong>. Guests who choose WhatsApp open a chat to that number with their form answers filled in. Guests who choose email receive a copy, and the team receives one too, when the Resend key is set.</p>

        <div class="callout-training mb-4">
            <p class="mb-2"><strong>If several people will update the site</strong></p>
            <p class="mb-0 small">Ask a Super Admin to create each person as a Content Manager. Walk through Rooms, Tours &amp; Safaris, Reservations, and Hosting once together so everyone can find a request and the hosting invoice.</p>
        </div>

        <h2>7. Annual hosting renewal</h2>
        <p>Hosting is billed once a year. On <strong>1 October</strong> the admin creates an invoice for <strong>1 October through 30 September</strong> of the following year. Open it from <strong>Hosting</strong> in the admin menu.</p>
        <p>Each invoice lists four services:</p>
        <ul>
            <li class="mb-2"><strong>Domain registration</strong> — included at $0.00 unless a super admin sets a cost for a later invoice.</li>
            <li class="mb-2"><strong>Hosting</strong> — $80.00 per year.</li>
            <li class="mb-2"><strong>SSL</strong> — included at $0.00 unless a super admin sets a cost for a later invoice.</li>
            <li class="mb-0"><strong>Support</strong> — included at $0.00. A super admin can add a support cost later, and that cost is used on the next invoice.</li>
        </ul>
        <p>Each invoice shows the amount in US dollars and in Rwandan francs. The rate starts at <strong>1 USD = 1,530 RWF</strong>. Only the Ireme super admin signed in as <strong>admin@iremetech.com</strong> can change that rate. A change applies to the next invoice. Invoices already issued keep the rate printed on them.</p>
        <p>The current year, <strong>1 October 2026 to 30 September 2027</strong>, is invoice <strong>IREME/gmasters/PH001/2026</strong> and is already marked <strong>Paid</strong>. Later invoices are created unpaid. A super admin marks one paid after the transfer is received, and can send a payment reminder by hand if payment is delayed. Every admin can open Hosting and view or download the current invoice and every past invoice.</p>
        <p>The team is emailed, and a notice stays on the dashboard, at these moments:</p>
        <ul>
            <li class="mb-2">30 days before the hosting year ends</li>
            <li class="mb-2">2 days before it ends</li>
            <li class="mb-2">on the day it ends</li>
            <li class="mb-0">after it has expired, until the new invoice is marked paid</li>
        </ul>
        <p>Each email is sent once for that stage. The notice on the dashboard remains so someone can still act if they missed the email. Pay with either method below. The same details are printed on the invoice.</p>
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
        <p class="mb-0">Changing domain, SSL, or support amounts applies to the next 1 October invoice. The invoice already issued keeps the amounts printed on it.</p>

        <h2>8. Keeping reminders running</h2>
        <p>The renewal check runs when an admin opens the dashboard, and it also runs on a daily schedule at 08:00 Kigali time. On the server, run the scheduler every minute:</p>
        <p><code>* * * * * php /path/to/artisan schedule:run</code></p>
        <p class="mb-0">That schedule issues the 1 October invoice and sends the 30-day, 2-day, expiry-day, and expired emails. Opening the admin still does the same check, so a reminder is not lost when someone signs in on that day.</p>
    </div>
</div>
</div>
