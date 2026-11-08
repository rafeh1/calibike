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

  <!-- Navigation Header -->
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
        <a href="/navmap/" class="text-emerald-400 font-semibold">NavMap</a>
      </nav>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-10">

    <!-- Hero Section -->
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

    <!-- Cover Image from LinkedIn Article -->
    <div class="mb-10 rounded-2xl overflow-hidden border border-brand-border glow-cyan">
      <img 
        src="https://media.licdn.com/dms/image/v2/D5612AQHYAG_TAiokmQ/article-cover_image-shrink_720_1280/B56aAhbfROIcAU-/0/1787267249531?e=1795651200&v=beta&t=rbV8r2rhw0yhzf76EBRShqXfWZHj_SV6nXD4LnYryrw" 
        alt="2500w motor on 40lb ebike" 
        class="w-full h-auto object-cover">
    </div>

    <!-- Intro Text -->
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

    <!-- Why 40 lb Matters -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 mb-10">
      <h2 class="text-lg font-bold text-white mb-3">Why 40 lb matters</h2>
      <p class="text-slate-300 leading-relaxed mb-4">
        Most 40+ mph e-bikes or heavy moped-style builds weigh 70 to 100+ lbs. 
        The 40C3 delivers that performance in an ultra-lightweight ~40 lb frame 
        while still carrying a 200+ lb rider + load.
      </p>
      <p class="text-slate-300 leading-relaxed">
        Field Oriented Control gives ultra-smooth, Tesla-like acceleration with zero wheel spinouts.
      </p>
    </div>

    <!-- NavMap Mobile Cellular Display Image (navmap_cell.png) -->
    <div class="mb-10">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-sm font-mono text-cyan-400 uppercase tracking-wider">NavMap Mobile & Cellular Display Interface</h2>
        <span class="text-xs font-mono text-slate-500">navmap_cell.png</span>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-4 glow-cyan">
        <div class="rounded-xl overflow-hidden border border-slate-800 bg-black flex justify-center">
          <img src="navmap_cell.png" alt="NavMap Cellular Display Interface" class="w-full h-auto max-h-[500px] object-contain" onerror="this.src='navmap.jpg'">
        </div>
      </div>
    </div>

    <!-- Engineering Analysis Table -->
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
        Power fluctuations caused the VESC to shut power very early — high speed but no torque, 
        so no real power on hills.
      </p>
      <p class="text-slate-300 leading-relaxed">
        AI suggested trying higher power. I raised the max amp limit to <strong class="text-white">65 A</strong>. 
        That restored usable torque while keeping the smooth FOC delivery.
      </p>
    </div>

    <!-- Additional Clip 2 -->
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

    <!-- Summary Box with Logo -->
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

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-4 mb-12">
      <a href="https://www.youtube.com/watch?v=ppuPU-8cVbA" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-950/60 border border-red-700/50 text-red-300 rounded-lg text-sm font-medium hover:bg-red-900/40 transition">
        Main Video &nearr;
      </a>
      <a href="https://www.strava.com/activities/19759363348" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-950/60 border border-orange-700/50 text-orange-300 rounded-lg text-sm font-medium hover:bg-orange-900/40 transition">
        Strava Activity &nearr;
      </a>
      <a href="https://www.linkedin.com/pulse/first-run-40c3-40lb-40mph-40-mi-range-2500w-mac-motor-rafe-husain-rfjec/" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-950/60 border border-blue-700/50 text-blue-300 rounded-lg text-sm font-medium hover:bg-blue-900/40 transition">
        LinkedIn Article &nearr;
      </a>
      <a href="/40c3/dashboard.php"
         class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-950/60 border border-emerald-700/50 text-emerald-300 rounded-lg text-sm font-medium hover:bg-emerald-900/40 transition">
        Live Dashboard &nearr;
      </a>
      <a href="/" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-slate-600 text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
        &larr; Back to iproto.com
      </a>
    </div>

    <!-- LAYER 1 & LAYER 2 PROTECTED COMMENT SECTION -->
    <div class="bg-brand-card border border-brand-border rounded-2xl p-6 glow-cyan">
      <div class="text-xs font-mono text-cyan-400 tracking-widest mb-1 uppercase">COMMUNITY FEEDBACK</div>
      <h2 class="text-xl font-bold text-white mb-2">Leave a Verified Technical Comment</h2>
      <p class="text-slate-400 text-sm mb-6">Select your identity provider to sign in. External website URLs are automatically blocked to prevent spam.</p>

      <!-- Layer 1: Social Identity Selection -->
      <div id="authSection" class="mb-6 bg-slate-900/80 border border-slate-800 rounded-xl p-4">
        <div class="text-xs font-mono text-slate-400 mb-3 uppercase">Layer 1: Select Identity Provider</div>
        
        <div id="loggedOutState" class="flex flex-wrap gap-3">
          <button onclick="setOAuthProvider('Google')" 
                  class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs rounded-lg border border-slate-700 transition">
            <span class="text-red-400 font-bold">G</span> Google
          </button>
          <button onclick="setOAuthProvider('LinkedIn')" 
                  class="inline-flex items-center gap-2 px-4 py-2 bg-blue-950/80 hover:bg-blue-900/80 text-blue-300 font-mono text-xs rounded-lg border border-blue-800/80 transition">
            <span class="font-bold">in</span> LinkedIn
          </button>
          <button onclick="setOAuthProvider('Facebook')" 
                  class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-950/80 hover:bg-indigo-900/80 text-indigo-300 font-mono text-xs rounded-lg border border-indigo-800/80 transition">
            <span class="font-bold">f</span> Facebook
          </button>
          <button onclick="setOAuthProvider('GitHub')" 
                  class="inline-flex items-center gap-2 px-4 py-2 bg-slate-950 hover:bg-slate-900 text-slate-300 font-mono text-xs rounded-lg border border-slate-800 transition">
            <span class="font-bold">GH</span> GitHub
          </button>
        </div>

        <!-- Selected Provider Indicator -->
        <div id="loggedInState" class="hidden flex items-center justify-between bg-cyan-950/40 border border-cyan-800/60 rounded-lg p-3">
          <div class="flex items-center gap-2 font-mono text-xs">
            <span class="text-slate-400">Authenticated Provider:</span>
            <span id="activeProvider" class="bg-cyan-950 border border-cyan-700 text-cyan-400 px-2.5 py-0.5 rounded text-[11px] font-bold">Google</span>
          </div>
          <button onclick="resetOAuthProvider()" class="text-xs font-mono text-slate-400 hover:text-red-400 underline">Change Provider</button>
        </div>
      </div>

      <!-- Comment Submission Form -->
      <form id="commentForm" onsubmit="submitFilteredComment(event)" class="space-y-4">
        <div>
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Profile Handle / Verified ID</label>
          <input type="text" id="userIdInput" placeholder="e.g. user@gmail.com or linkedin.com/in/profile" required
                 class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600">
        </div>

        <div>
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Comment Body</label>
          <textarea id="commentText" rows="4" placeholder="Share technical feedback on the 40C3 build (Note: External web links are blocked)..." required
                    class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-sm rounded-lg p-2.5 focus:border-cyan-400 focus:outline-none placeholder-slate-600"></textarea>
        </div>

        <div>
          <label class="block text-xs font-mono text-slate-400 mb-1 uppercase">Optional Attachment Image</label>
          <input type="file" id="imageUploadInput" accept="image/*"
                 class="text-xs font-mono text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-mono file:bg-slate-800 file:text-cyan-400 hover:file:bg-slate-700">
        </div>

        <!-- Layer 2 Spam Alert Warning Box -->
        <div id="linkWarning" class="hidden bg-red-950/60 border border-red-800 text-red-300 text-xs font-mono p-3 rounded-lg">
          ?? <strong>Spam Protection Triggered:</strong> External website links or domain references are blocked.
        </div>

        <button type="submit" 
                class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-black font-bold font-mono text-sm rounded-lg transition shadow-lg shadow-cyan-500/20">
          Post Comment
        </button>
      </form>

      <!-- Verified Comments Feed -->
      <div class="mt-8 border-t border-brand-border pt-6">
        <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">Verified Technical Discussion</h3>
        <div id="commentList" class="space-y-3">
          <div class="bg-slate-900/60 border border-slate-800 rounded-lg p-4">
            <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">
              <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">LinkedIn</span>
              <span>rafe-husain</span>
              <span class="text-slate-600">• Admin</span>
            </div>
            <p class="text-slate-300 text-sm">FOC current limit tuned to 65A peak. High torque restored with zero wheel spinouts.</p>
          </div>
        </div>
      </div>
    </div>

  </main>

  <footer class="border-t border-brand-border mt-16 py-6 text-center text-xs text-slate-500 font-mono">
    iproto.com • 40C3 CaliBike prototype
  </footer>

  <script>
    let selectedProvider = "Google"; // Default provider

    function setOAuthProvider(provider) {
      selectedProvider = provider;
      document.getElementById('activeProvider').innerText = provider;
      document.getElementById('loggedOutState').classList.add('hidden');
      document.getElementById('loggedInState').classList.remove('hidden');
    }

    function resetOAuthProvider() {
      document.getElementById('loggedOutState').classList.remove('hidden');
      document.getElementById('loggedInState').classList.add('hidden');
    }

    async function submitFilteredComment(e) {
      e.preventDefault();
      const warning = document.getElementById('linkWarning');
      warning.classList.add('hidden');

      const userId = document.getElementById('userIdInput').value.trim();
      const text = document.getElementById('commentText').value.trim();

      // Layer 2 Client-side Regex Check
      const linkPattern = /(https?:\/\/|www\.|[a-zA-Z0-9-]+\.(com|net|org|xyz|io|co|me|info|biz|tk)|t\.me\/|telegram\.me\/)/i;
      if (linkPattern.test(text)) {
        warning.innerHTML = "?? <strong>Layer 2 Filter Triggered:</strong> External links or URLs are not allowed in comments.";
        warning.classList.remove('hidden');
        return;
      }

      const formData = new FormData();
      formData.append('provider', selectedProvider);
      formData.append('user_id', userId);
      formData.append('comment_text', text);

      const fileInput = document.getElementById('imageUploadInput');
      if (fileInput && fileInput.files[0]) {
        formData.append('comment_image', fileInput.files[0]);
      }

      try {
        const response = await fetch('/40c3/post_comment.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (!result.success) {
          warning.innerHTML = `?? <strong>Error:</strong> ${result.error}`;
          warning.classList.remove('hidden');
          return;
        }

        const c = result.comment;
        const commentList = document.getElementById('commentList');
        const newComment = document.createElement('div');
        newComment.className = "bg-slate-900/60 border border-slate-800 rounded-lg p-4 mb-3";

        let imgHTML = c.image ? `<div class="mt-3 max-w-sm rounded border border-slate-800 overflow-hidden"><img src="${c.image}" class="w-full h-auto"></div>` : '';

        newComment.innerHTML = `
          <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">
            <span class="bg-cyan-950 border border-cyan-800 px-2 py-0.5 rounded text-[10px]">${c.provider}</span>
            <span>${c.user_id}</span>
            <span class="text-slate-600">• Just now</span>
          </div>
          <p class="text-slate-300 text-sm">${c.comment}</p>
          ${imgHTML}
        `;

        commentList.prepend(newComment);
        document.getElementById('commentForm').reset();

      } catch (err) {
        warning.innerHTML = "?? <strong>Server Error:</strong> Could not reach post_comment.php backend.";
        warning.classList.remove('hidden');
      }
    }
  </script>

</body>
</html>