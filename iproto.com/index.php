<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CaliBike 40C3 | 40 lb • 40 mph • 40 mi | iproto.com</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              dark: '#0a0d14',
              card: '#121722',
              border: '#1e2638',
              cyan: '#00f0ff'
            }
          }
        }
      }
    }
  </script>
  <style>
    body { background-color: #0a0d14; color: #e2e8f0; }
    .glow-cyan { box-shadow: 0 0 25px rgba(0, 240, 255, 0.12); }
  </style>
</head>
<body class="min-h-screen">

  <header class="border-b border-brand-border bg-brand-dark/90 backdrop-blur sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2">
        <div class="w-7 h-7 rounded bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-black text-sm">i</div>
        <span class="font-mono font-bold tracking-wider">iproto<span class="text-cyan-400">.com</span></span>
      </a>
      <nav class="flex gap-4 text-sm font-mono">
        <a href="/" class="text-slate-400 hover:text-cyan-400">Home</a>
        <a href="/40c3/" class="text-cyan-400 font-semibold">40C3</a>
        <a href="/calidroid1500/" class="text-slate-400 hover:text-cyan-400">Calidroid</a>
      </nav>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-10">

    <!-- Hero -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center gap-6">
      <div class="flex-1">
        <div class="text-xs font-mono text-cyan-400 tracking-widest mb-2">CALIBIKE • FIRST RUN</div>
        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">40C3</h1>
        <p class="text-xl text-slate-300 mt-2">40 lb • 40 mph • 40 mi range</p>
        <p class="text-slate-400 mt-3 max-w-xl">
          2500 W MAC motor • 52 V FOC vector control • ultra-light frame carrying 200+ lb load
        </p>
      </div>
      <div class="w-32 md:w-40 flex-shrink-0">
        <img src="CaliBike_logo_full.svg" alt="CaliBike Logo" class="w-full h-auto opacity-90">
      </div>
    </div>

    <!-- Cover image from LinkedIn article -->
    <div class="mb-10 rounded-2xl overflow-hidden border border-brand-border glow-cyan">
      <img 
        src="https://media.licdn.com/dms/image/v2/D5612AQHYAG_TAiokmQ/article-cover_image-shrink_720_1280/B56aAhbfROIcAU-/0/1787267249531?e=1795651200&v=beta&t=rbV8r2rhw0yhzf76EBRShqXfWZHj_SV6nXD4LnYryrw" 
        alt="2500w motor on 40lb ebike" 
        class="w-full h-auto object-cover">
    </div>

    <!-- Intro text -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <p class="text-slate-300 leading-relaxed">
        First run used FOC and low current to smooth out power delivery. 
        Got to <strong class="text-white">39 mph</strong> before the lights stopped.
        The bike runs a <strong class="text-white">2500 W MAC motor</strong> (only 4.9 kg) 
        and a <strong class="text-white">52 V 17 Ah</strong> pack.
      </p>
    </div>

    <!-- Main Video -->
    <div class="mb-10">
      <h2 class="text-sm font-mono text-cyan-400 mb-3">Main First Run Video</h2>
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

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400">40</div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">lb total</div>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-cyan-400">39</div>
        <div class="text-xs text-slate-400 mt-1 uppercase tracking-wider">mph first run</div>
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

    <!-- Why 40 lb -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <h2 class="text-lg font-bold text-white mb-3">Why 40 lb matters</h2>
      <p class="text-slate-300 leading-relaxed mb-4">
        Most 40+ mph e-bikes or heavy moped-style builds weigh 70 to 100+ lbs. 
        The 40C3 delivers that performance in an ultra-lightweight ~40 lb frame 
        while still carrying a 200+ lb rider + load.
      </p>
      <p class="text-slate-300 leading-relaxed">
        Field Oriented Control gives ultra-smooth, Tesla-like acceleration with zero wheel spinouts. 
        Gemini AI called it a new highly efficient transportation system.
      </p>
    </div>

    <!-- World's Most Efficient Transport Section -->
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

    <!-- Second video -->
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

    <!-- Tuning notes -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <h2 class="text-lg font-bold text-white mb-3">Tuning Notes</h2>
      <p class="text-slate-300 leading-relaxed mb-4">
        I initially had it severely underpowered by restricting battery current to 45 A. 
        Power fluctuations caused the VESC to shut power very early — high speed but no torque, 
        so no real power on hills.
      </p>
      <p class="text-slate-300 leading-relaxed">
        AI suggested trying higher power. I raised the max amp limit to <strong class="text-white">65 A</strong>. 
        That restored usable torque while keeping the smooth FOC delivery.
      </p>
    </div>

    <!-- Third video -->
    <div class="mb-10">
      <h2 class="text-sm font-mono text-cyan-400 mb-3">Additional Clip 2</h2>
      <div class="rounded-2xl overflow-hidden border border-brand-border">
        <div class="relative w-full" style="padding-bottom: 56.25%;">
          <iframe class="absolute top-0 left-0 w-full h-full"
            src="https://www.youtube.com/embed/SADQOCrYULg"
            title="40C3 Clip 2"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </div>

    <!-- Logo + Summary -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10 flex flex-col md:flex-row gap-6 items-center">
      <img src="CaliBike_logo_medium.jpg" alt="CaliBike" class="w-28 h-auto rounded-lg opacity-90">
      <div>
        <h2 class="text-lg font-bold text-white mb-2">Summary</h2>
        <p class="text-slate-300 leading-relaxed">
          40 lb frame • 40 mph capability • ~40 mi range • 2500 W MAC motor • 52 V FOC.  
          Smooth Tesla-style power delivery in a package most high-speed bikes can’t match on weight.
        </p>
      </div>
    </div>

    <!-- Links -->
    <div class="flex flex-wrap gap-4">
      <a href="https://www.youtube.com/watch?v=ppuPU-8cVbA" target="_blank"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-950/60 border border-red-700/50 text-red-300 rounded-lg text-sm font-medium hover:bg-red-900/40 transition">
        Main Video ?
      </a>
      <a href="https://www.strava.com/activities/19759363348" target="_blank"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-950/60 border border-orange-700/50 text-orange-300 rounded-lg text-sm font-medium hover:bg-orange-900/40 transition">
        Strava activity ?
      </a>
      <a href="https://www.linkedin.com/pulse/first-run-40c3-40lb-40mph-40-mi-range-2500w-mac-motor-rafe-husain-rfjec/" target="_blank"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-950/60 border border-blue-700/50 text-blue-300 rounded-lg text-sm font-medium hover:bg-blue-900/40 transition">
        LinkedIn article ?
      </a>
      <a href="/40c3/dashboard.php"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-950/60 border border-emerald-700/50 text-emerald-300 rounded-lg text-sm font-medium hover:bg-emerald-900/40 transition">
        Live Dashboard ?
      </a>
      <a href="/" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-slate-600 text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
        ? Back to iproto.com
      </a>
    </div>

  </main>

  <footer class="border-t border-brand-border mt-16 py-6 text-center text-xs text-slate-500 font-mono">
    iproto.com • 40C3 CaliBike prototype
  </footer>

</body>
</html>