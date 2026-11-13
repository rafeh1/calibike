<!-- Hero Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center gap-6">
      <div class="flex-1">
        <div class="text-xs font-mono text-cyan-400 tracking-widest mb-2">CALIBIKE &middot; FIRST RUN</div>
        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">40C3</h1>
        <p class="text-xl text-slate-300 mt-2">40 lb &middot; 40 mph &middot; 40 mi range</p>
        <p class="text-slate-400 mt-3 max-w-xl">
          2500 W MAC motor &middot; 52 V FOC vector control &middot; ultra-light frame carrying 200+ lb load
        </p>
      </div>
      <div class="w-32 md:w-40 flex-shrink-0">
        <img src="CaliBike_logo_full.svg" alt="CaliBike Logo" class="w-full h-auto opacity-90">
      </div>
    </div>

    <!-- Cover Image from LinkedIn Article -->
    <div class="mb-10 rounded-2xl overflow-hidden border border-brand-border glow-cyan">
      <img
        src="https://media.licdn.com/dms/image/v2/D5612AQHYAG_TAiokmQ/article-cover_image-shrink_720_1280/B56aAhbfROIcAU-/0/1787267249531?e=1795651200&v=beta&t=rbV8r2rhw0yhzf76EBRShqXfWZHj_SV6nXD4LnYryrw"
        alt="2500w motor on 40lb ebike"
        class="w-full h-auto object-cover">
    </div>

    <!-- Intro Text & Hyper-Bike Positioning -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <h2 class="text-lg font-bold text-white mb-3">The Stealth Hyper-Bike Positioning</h2>
      <p class="text-slate-300 leading-relaxed mb-4">
        A true <strong>37 mph on throttle alone</strong>—powered by a 2,500W peak motor—places the CaliBike 40C3 into hyper-e-bike territory, completely separating it from standard Class 1/2/3 commuters (which top out at 20–28 mph).
      </p>
      <p class="text-slate-300 leading-relaxed">
        Most 40+ mph e-bikes or heavy moped-style builds weigh 70 to 120+ lbs. The 40C3 delivers class-leading speed and agility in an ultra-lightweight ~40 lb chassis carrying a 200+ lb payload—providing raw power without hauling a motorcycle-weight frame up stairs or onto vehicle racks.
      </p>
    </div>

    <!-- Main First Run Video -->
    <div class="mb-10">
      <h2 class="text-sm font-mono text-cyan-400 mb-3 uppercase tracking-wider">Main First Run Video</h2>
      <div class="rounded-2xl overflow-hidden border border-brand-border glow-cyan">
        <div class="relative w-full" style="padding-bottom: 56.25%;">
          <iframe class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/ppuPU-8cVbA"
            title="40C3 First Run"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400">40</div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">lb total weight</div>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400">37 / 43</div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">mph (throttle/pedal)</div>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400">~40</div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">mi range</div>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400">2500</div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">W MAC motor</div>
      </div>
    </div>

    <!-- FUNCTIONAL DESCRIPTION: Technical Specs & Performance Pillars -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">FUNCTIONAL DESCRIPTION</div>
      <h2 class="text-xl font-bold text-white mb-4">Raw Power Meets Silk-Smooth Control</h2>
      
      <p class="text-slate-300 leading-relaxed mb-6">
        Dumping 2,500W into a 40 lb bicycle using standard square-wave or trapezoidal controllers causes abrupt, noisy launches and dangerous wheel spinouts. The 40C3 utilizes <strong>Field Oriented Control (FOC) Sine-Wave Vector Control</strong> to continuously read rotor position, converting 2.5 kW of peak torque into linear, whisper-quiet forward acceleration.
      </p>

      <div class="grid md:grid-cols-3 gap-6 mb-6">
        <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-xl">
          <h3 class="font-bold text-cyan-400 text-sm mb-2">Zero Spinouts & Launch Control</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            Vector control precision prevents sudden RPM torque spikes. Delivers maximum smooth acceleration from 0 mph to 37 mph throttle output without burning rubber.
          </p>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-xl">
          <h3 class="font-bold text-cyan-400 text-sm mb-2">Whisper-Quiet Operation</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            Pure sinusoidal current curves eliminate motor buzz and high-pitched electrical whine under load, creating a stealthy, refined ride feel.
          </p>
        </div>
        <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-xl">
          <h3 class="font-bold text-cyan-400 text-sm mb-2">Thermal Efficiency & Range</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            Sinusoidal commutation minimizes heat loss in motor windings, stretching the 52V 17Ah (~884 Wh) battery pack to achieve a full 40-mile range.
          </p>
        </div>
      </div>

      <div class="border-t border-slate-800 pt-4">
        <h3 class="text-sm font-bold text-white mb-2">Riding & Compliance Modes</h3>
        <ul class="text-xs text-slate-300 space-y-2 list-disc list-inside">
          <li><strong>Street-Legal Mode:</strong> Programmable speed cap (Class 2 / Class 3) for multi-use paths and urban bike lanes.</li>
          <li><strong>Off-Road / Unlimited Mode:</strong> Unlocks full 2,500W output for 37 mph throttle / 43 mph pedal-assist on open roads or private tracks.</li>
        </ul>
      </div>
    </div>

    <!-- NavMap Mobile Cellular Display Image -->
    <div class="mb-10">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-sm font-mono text-cyan-400 uppercase tracking-wider">NavMap Mobile &amp; Cellular Display Interface</h2>
        <span class="text-xs font-mono text-slate-500">navmap_cell.png</span>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-4 glow-cyan">
        <div class="rounded-xl overflow-hidden border border-slate-800 bg-black flex justify-center">
          <img src="navmap_cell.png" alt="NavMap Cellular Display Interface" class="w-full h-auto max-h-[500px] object-contain" onerror="this.src='navmap.jpg'">
        </div>
      </div>
    </div>

    <!-- World's Most Efficient Transportation System Table -->
    <div class="bg-brand-card border border-cyan-500/30 rounded-2xl p-6 mb-10 glow-cyan">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1">ENGINEERING ANALYSIS</div>
      <h2 class="text-xl font-bold text-white mb-4">World's Most Efficient Transportation System</h2>
      <p class="text-slate-300 leading-relaxed mb-6">
        By evaluating payload-to-vehicle weight ratio and energy draw per mile, the 40C3 establishes a high-efficiency transport standard—moving riders with 100x less dead weight than an electric passenger car.
      </p>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-mono border-collapse">
          <thead>
            <tr class="border-b border-brand-border text-cyan-400">
              <th class="py-3 px-4">Metric</th>
              <th class="py-3 px-4 text-cyan-300 bg-cyan-950/30 border-x border-brand-border">CaliBike 40C3</th>
              <th class="py-3 px-4 text-slate-400">Typical Speed E-Bike</th>
              <th class="py-3 px-4 text-slate-400">Electric Car (EV)</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-brand-border text-slate-300">
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Vehicle Weight</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">40 lbs</td>
              <td class="py-3 px-4">85+ lbs</td>
              <td class="py-3 px-4">4,000 lbs</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Payload-to-Weight Ratio (200lb rider)</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">5.0 : 1</td>
              <td class="py-3 px-4">2.3 : 1</td>
              <td class="py-3 px-4">0.05 : 1</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Energy Draw</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">~22 Wh / mi</td>
              <td class="py-3 px-4">35+ Wh / mi</td>
              <td class="py-3 px-4">~300 Wh / mi</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Gas Equivalent Efficiency</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">1,500+ MPGe</td>
              <td class="py-3 px-4">~950 MPGe</td>
              <td class="py-3 px-4">~115 MPGe</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Commutation / Delivery</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">FOC Vector (Sine-wave)</td>
              <td class="py-3 px-4">Square / Trapezoidal</td>
              <td class="py-3 px-4">Vector Drive</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Additional Clip 1 -->
    <div class="mb-10">
      <h2 class="text-sm font-mono text-cyan-400 mb-3">Additional Clip 1</h2>
      <div class="rounded-2xl overflow-hidden border border-brand-border">
        <div class="relative w-full" style="padding-bottom: 56.25%;">
          <iframe class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/JeEFvR9cpxw"
            title="40C3 Clip 1"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </div>

    <!-- Tuning Notes -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <h2 class="text-lg font-bold text-white mb-3">Tuning Notes</h2>
      <p class="text-slate-300 leading-relaxed mb-4">
        I initially had it severely underpowered by restricting battery current to 45 A.
        Power fluctuations caused the VESC to shut power very early &mdash; high speed but no torque,
        so no real power on hills.
      </p>
      <p class="text-slate-300 leading-relaxed">
        AI suggested trying higher power. I raised the max amp limit to <strong class="text-white">65 A</strong>.
        That restored usable torque while keeping the smooth FOC delivery.
      </p>
    </div>

    <!-- Additional Clip 2 (Embedded with start=320 to fix YouTube connection error) -->
    <div class="mb-10">
      <h2 class="text-sm font-mono text-cyan-400 mb-3">Additional Clip 2 (Test Run &amp; Telemetry)</h2>
      <div class="rounded-2xl overflow-hidden border border-brand-border">
        <div class="relative w-full" style="padding-bottom: 56.25%;">
          <iframe class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/SADQOCrYULg?start=320"
            title="40C3 Clip 2"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </div>

    <!-- Drivetrain Upgrade: 65T vs 58T Chainring -->
    <div class="bg-brand-card border border-cyan-500/30 rounded-2xl p-6 mb-10 glow-cyan">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">DRIVETRAIN &amp; RIDER LOAD</div>
      <h2 class="text-xl font-bold text-white mb-4">65T Chainring Upgrade to Eliminate High-Speed Air-Pedaling</h2>

      <div class="grid md:grid-cols-2 gap-6 items-center mb-6">
        <div class="space-y-4 text-slate-300 leading-relaxed">
          <p>
            With 40C3 being significantly faster, it provides less load on the user. The 33C3 has a <strong class="text-white">58T front chainring</strong> with a <strong class="text-white">12T rear gear</strong>, so at 35 to 40 mph I am air-pedaling and minimal load is on the user.
          </p>
          <p>
            The smartwatch confirms this: it shows the 40C3 taking <strong class="text-cyan-400">45 Kcal/mile</strong>, whereas the 33C3 used to load <strong class="text-white">65 to 90 Kcal/mile</strong>.
          </p>
          <p>
            To fix this, I added a <strong class="text-cyan-400">65T front chainring</strong>—it looks huge. Tomorrow I will check the Kcal with the 65T front chainring paired with the 12T rear gear.
          </p>
        </div>

        <div class="rounded-xl overflow-hidden border border-slate-800 bg-black">
          <img src="65t_chainring1.jpg" alt="65T Front Chainring Installed on CaliBike 40C3" class="w-full h-auto object-cover">
          <div class="px-3 py-2 bg-slate-900/90 text-xs font-mono text-slate-400 flex justify-between">
            <span>65T Front Chainring &middot; 12T Rear Cog</span>
            <span>65t_chainring1.jpg</span>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-mono border-collapse">
          <thead>
            <tr class="border-b border-brand-border text-cyan-400">
              <th class="py-3 px-4">Drivetrain Metric</th>
              <th class="py-3 px-4 text-slate-400">58T Chainring (Baseline / 33C3)</th>
              <th class="py-3 px-4 text-cyan-300 bg-cyan-950/30 border-x border-brand-border">65T Chainring (40C3 Upgrade)</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-brand-border text-slate-300">
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Front / Rear Teeth</td>
              <td class="py-3 px-4">58T / 12T</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">65T / 12T</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Gear Ratio</td>
              <td class="py-3 px-4">4.83 : 1</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">5.42 : 1 (+12.1% taller)</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Feel at 35–40 mph</td>
              <td class="py-3 px-4">Spinning out / air-pedaling</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">Firm resistance &amp; positive engagement</td>
            </tr>
            <tr>
              <td class="py-3 px-4 font-semibold text-white">Rider Energy Load</td>
              <td class="py-3 px-4">45 Kcal/mi on 40C3 (65–90 Kcal/mi on 33C3)</td>
              <td class="py-3 px-4 text-cyan-400 bg-cyan-950/20 border-x border-brand-border font-bold">Pending next run telemetry</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Summary Box & CaliBike Logo -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10 flex flex-col md:flex-row gap-6 items-center">
      <img src="CaliBike_logo_medium.jpg" alt="CaliBike" class="w-28 h-auto rounded-lg opacity-90">
      <div>
        <h2 class="text-lg font-bold text-white mb-2">Summary</h2>
        <p class="text-slate-300 leading-relaxed">
          40 lb frame &middot; 37 mph throttle / 43 mph pedal &middot; ~40 mi range &middot; 2500 W MAC motor &middot; 52 V 17Ah pack &middot; FOC Sine-Wave.
          Smooth Tesla-style power delivery in a package most high-speed bikes can't match on weight.
        </p>
      </div>
    </div>

    <!-- Links -->
    <div class="flex flex-wrap gap-4 mb-12">
      <a href="https://www.youtube.com/watch?v=ppuPU-8cVbA" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-950/60 border border-red-700/50 text-red-300 rounded-lg text-sm font-medium hover:bg-red-900/40 transition">
        Main Video &rarr;
      </a>
      <a href="https://www.strava.com/activities/19759363348" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-950/60 border border-orange-700/50 text-orange-300 rounded-lg text-sm font-medium hover:bg-orange-900/40 transition">
        Strava Activity &rarr;
      </a>
      <a href="https://www.linkedin.com/pulse/first-run-40c3-40lb-40mph-40-mi-range-2500w-mac-motor-rafe-husain-rfjec/" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-950/60 border border-blue-700/50 text-blue-300 rounded-lg text-sm font-medium hover:bg-blue-900/40 transition">
        LinkedIn Article &rarr;
      </a>
      <a href="/40c3/dashboard.php"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-950/60 border border-emerald-700/50 text-emerald-300 rounded-lg text-sm font-medium hover:bg-emerald-900/40 transition">
        Live Dashboard &rarr;
      </a>
      <a href="/" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-slate-600 text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
        &larr; Back to iproto.com
      </a>
    </div>