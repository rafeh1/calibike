<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CALIDROID 1500 | Autonomous Solar Sea Drone | iproto.com</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              dark: '#070a10',
              card: '#0d131f',
              border: '#1a2436',
              cyan: '#00f0ff',
              blue: '#0088ff'
            }
          }
        }
      }
    }
  </script>
  <style>
    body { background-color: #070a10; color: #e2e8f0; font-family: ui-sans-serif, system-ui, sans-serif; }
    .glow-cyan { box-shadow: 0 0 25px rgba(0, 240, 255, 0.15); }
  </style>
</head>
<body class="min-h-screen">

  <!-- Navigation Bar -->
  <header class="border-b border-brand-border bg-brand-dark/90 backdrop-blur sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2">
        <div class="w-8 h-8 rounded bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-black text-base">i</div>
        <span class="font-mono font-bold tracking-wider text-lg">iproto<span class="text-cyan-400">.com</span></span>
      </a>
      <nav class="flex gap-5 text-sm font-mono">
        <a href="/" class="text-slate-400 hover:text-cyan-400 transition">Home</a>
        <a href="/40c3/" class="text-slate-400 hover:text-cyan-400 transition">40C3 E-Bike</a>
        <a href="/calidroid1500/" class="text-cyan-400 font-bold border-b-2 border-cyan-400 pb-1">Calidroid 1500</a>
      </nav>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-8">

    <!-- Top Hero Section with Integrated Intro Render Banner (R1, R2, R3) -->
    <div class="mb-12 border-b border-brand-border pb-10">
      
      <!-- Headline & Value Pitch -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
          <div class="inline-block text-xs font-mono text-cyan-400 tracking-widest bg-cyan-950/60 border border-cyan-800/60 px-3 py-1 rounded-full mb-3">
            LOW-COST • UNMANNED • COMMON PLATFORM
          </div>
          <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight">CALIDROID 1500</h1>
          <p class="text-xl md:text-2xl text-cyan-400 mt-2 font-mono font-semibold">The Infinite Ocean Sentinel</p>
          <p class="text-slate-300 mt-3 max-w-3xl text-base leading-relaxed">
            A low-cost, ultra-durable ocean drone platform capable of loitering or traveling to designated GPS waypoints[cite: 7]. Powered continuously by a 400W solar array deck and controlled via global cellular SMS or satellite fallback, it eliminates refueling demands and expensive satellite ground stations[cite: 7, 8].
          </p>
        </div>
        <div class="flex flex-col gap-2 w-full md:w-auto font-mono text-xs">
          <a href="#navmap-display" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg text-center transition">Live NavMap Station</a>
          <a href="#hardware-overview" class="px-5 py-2.5 bg-brand-card hover:bg-slate-800 text-slate-300 border border-brand-border rounded-lg text-center transition">Hardware Architecture</a>
        </div>
      </div>

      <!-- Top Intro Visual Showcase (R1, R2, R3) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 glow-cyan">
        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden group">
          <div class="aspect-video relative overflow-hidden bg-black">
            <img src="solar1500w_R1.jpg" alt="Calidroid 1500 Solar Array Deck" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
          </div>
          <div class="p-3 font-mono text-xs border-t border-slate-800">
            <div class="font-bold text-cyan-400">400W Solar Deck Array</div>
            <div class="text-slate-400 text-[11px] mt-0.5">Continuous power replenishment for unlimited oceanic loitering[cite: 7].</div>
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden group">
          <div class="aspect-video relative overflow-hidden bg-black">
            <img src="solar1500w_R2.jpg" alt="Calidroid 1500 Submerged Thrusters" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
          </div>
          <div class="p-3 font-mono text-xs border-t border-slate-800">
            <div class="font-bold text-cyan-400">2x 800W Submerged Pod Motors</div>
            <div class="text-slate-400 text-[11px] mt-0.5">High-thrust differential propulsion engineered for heavy currents[cite: 7, 11].</div>
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden group">
          <div class="aspect-video relative overflow-hidden bg-black">
            <img src="solar1500w_R3.jpg" alt="Calidroid 1500 Cargo Deck" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
          </div>
          <div class="p-3 font-mono text-xs border-t border-slate-800">
            <div class="font-bold text-cyan-400">250 lb Reinforced Cargo Deck</div>
            <div class="text-slate-400 text-[11px] mt-0.5">Carries dual servo cargo release systems, life preservers, or defense gear[cite: 7, 11].</div>
          </div>
        </div>
      </div>

    </div>

    <!-- Core Competitive Advantages Grid (Cost, Range, Platform) -->
    <section class="mb-14">
      <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase mb-4">Strategic & Technical Advantages</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-brand-card border border-brand-border rounded-xl p-5">
          <div class="text-xs text-slate-400 font-mono">Platform Cost</div>
          <div class="text-3xl font-bold text-cyan-400 mt-1">&lt; $2,000</div>
          <div class="text-xs text-slate-300 mt-2 leading-relaxed">
            Low-cost modular architecture[cite: 7]. Delivers capability matching $50k+ ASVs at a fraction of the capital expense.
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-5">
          <div class="text-xs text-slate-400 font-mono">Operating Range</div>
          <div class="text-3xl font-bold text-white mt-1">Global / Unlimited</div>
          <div class="text-xs text-slate-300 mt-2 leading-relaxed">
            Continuous solar harvesting eliminates refueling[cite: 7]. Global roaming via SMS commands and satellite fallback[cite: 7, 8].
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-5">
          <div class="text-xs text-slate-400 font-mono">Payload Capacity</div>
          <div class="text-3xl font-bold text-white mt-1">250 lb (113 kg)</div>
          <div class="text-xs text-slate-300 mt-2 leading-relaxed">
            Outhauls competitors by nearly 4x[cite: 7]. Active cargo release for life rafts, sensor suites, or tactical gear[cite: 7].
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-5">
          <div class="text-xs text-slate-400 font-mono">Control Infrastructure</div>
          <div class="text-3xl font-bold text-cyan-400 mt-1">Standard Smartphone</div>
          <div class="text-xs text-slate-300 mt-2 leading-relaxed">
            Command via simple SMS text commands or Google Maps links[cite: 8, 10]. Zero costly satellite terminals required[cite: 8].
          </div>
        </div>
      </div>
    </section>

    <!-- Detailed Platform Specifications -->
    <section class="mb-14">
      <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase mb-4">Detailed Specifications</h2>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-brand-card border border-brand-border rounded-xl p-4">
          <div class="text-xs text-slate-400 font-mono">Payload Deck</div>
          <div class="text-2xl font-bold text-white mt-1">250 lb</div>
          <div class="text-[11px] text-cyan-400 mt-1">Heavy-duty load deck[cite: 7]</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl p-4">
          <div class="text-xs text-slate-400 font-mono">Propulsion</div>
          <div class="text-2xl font-bold text-white mt-1">2x 800W</div>
          <div class="text-[11px] text-cyan-400 mt-1">Submerged pod ESCs[cite: 11]</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl p-4">
          <div class="text-xs text-slate-400 font-mono">Solar Array</div>
          <div class="text-2xl font-bold text-white mt-1">400W</div>
          <div class="text-[11px] text-cyan-400 mt-1">Continuous charging[cite: 7]</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl p-4">
          <div class="text-xs text-slate-400 font-mono">Battery Bank</div>
          <div class="text-2xl font-bold text-white mt-1">24V 50Ah</div>
          <div class="text-[11px] text-cyan-400 mt-1">LiFePO4 cell array[cite: 7]</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl p-4">
          <div class="text-xs text-slate-400 font-mono">Telemetry Link</div>
          <div class="text-2xl font-bold text-white mt-1">SMS / USB</div>
          <div class="text-[11px] text-cyan-400 mt-1">Starlink / Cell fallback[cite: 8]</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl p-4">
          <div class="text-xs text-slate-400 font-mono">Mission Profile</div>
          <div class="text-2xl font-bold text-white mt-1">Multi-Role</div>
          <div class="text-[11px] text-cyan-400 mt-1">Civil / Monitor / Defense[cite: 7]</div>
        </div>
      </div>
    </section>

    <!-- Large NavMap Telemetry Header -->
    <section id="navmap-display" class="mb-14">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase">Autonomous Telemetry & NavMap Control Station</h2>
        <span class="text-xs font-mono text-slate-500">navmap_drone.jpg</span>
      </div>
      <div class="bg-brand-card border border-brand-border rounded-2xl p-4 md:p-6 glow-cyan">
        <div class="rounded-xl overflow-hidden border border-slate-800 bg-black flex justify-center">
          <img src="navmap_drone.jpg" alt="Calidroid NavMap Ground Station Interface" class="w-full h-auto max-h-[650px] object-contain">
        </div>
        <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs font-mono text-slate-400 border-t border-slate-800/80 pt-3">
          <div><span class="text-cyan-400 font-bold">CALIDROID-NAV LINK:</span> Parsing Google Maps GPS links & SMS command queue[cite: 8].</div>
          <div class="text-slate-500">Status: ONLINE (USB OTG Serial Linked)[cite: 8]</div>
        </div>
      </div>
    </section>

    <!-- Combined Hardware & Wiring Overview -->
    <section id="hardware-overview" class="mb-14">
      <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase mb-4">Hardware Architecture & Chassis Renders</h2>
      
      <!-- Combined Control Electronics Row -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-brand-card border border-brand-border rounded-2xl p-4 flex flex-col justify-between">
          <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-mono font-bold text-cyan-400">SUBMERGED POD ESC & MOTOR DRIVER</span>
            <span class="text-[11px] font-mono text-slate-500">thruster_controller.jpg</span>
          </div>
          <div class="aspect-video rounded-xl overflow-hidden bg-black border border-slate-800">
            <img src="thruster_controller.jpg" alt="Thruster Controller Module" class="w-full h-full object-cover">
          </div>
          <div class="mt-3 text-xs font-mono text-slate-400">
            Drives twin 800W ESC motors via GPIO 14 & GPIO 27 with differential thrust[cite: 11].
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-2xl p-4 flex flex-col justify-between">
          <div class="flex justify-between items-center mb-2">
            <span class="text-xs font-mono font-bold text-cyan-400">USB OTG SERIAL INTERFACE BRIDGE</span>
            <span class="text-[11px] font-mono text-slate-500">usb_cmd.jpg</span>
          </div>
          <div class="aspect-video rounded-xl overflow-hidden bg-black border border-slate-800">
            <img src="usb_cmd.jpg" alt="USB Command Interface" class="w-full h-full object-cover">
          </div>
          <div class="mt-3 text-xs font-mono text-slate-400">
            Connects Android device to MCU via USB OTG for offline serial command parsing[cite: 8].
          </div>
        </div>
      </div>

      <!-- Architecture Block Diagram -->
      <div class="bg-brand-card border border-brand-border rounded-2xl p-4 md:p-6 mb-6 glow-cyan">
        <div class="flex justify-between items-center mb-3">
          <span class="text-xs font-mono text-cyan-400 font-bold">SYSTEM ARCHITECTURE & WIRING DIAGRAM</span>
          <span class="text-xs font-mono text-slate-500">blk1.jpg</span>
        </div>
        <div class="rounded-xl overflow-hidden border border-slate-800 bg-black flex justify-center">
          <img src="blk1.jpg" alt="Calidroid 1500 Hardware Block Diagram" class="w-full max-w-5xl h-auto object-contain">
        </div>
      </div>

      <!-- Extended CAD & Revision Render Grid -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden">
          <img src="solar1500w_R4.jpg" alt="Solar R4" class="w-full aspect-video object-cover">
          <div class="p-2 font-mono text-[11px] text-cyan-400">solar1500w_R4.jpg</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden">
          <img src="rev0.jpg" alt="Rev 0" class="w-full aspect-video object-cover">
          <div class="p-2 font-mono text-[11px] text-cyan-400">rev0.jpg</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden">
          <img src="rev0a.jpg" alt="Rev 0A" class="w-full aspect-video object-cover">
          <div class="p-2 font-mono text-[11px] text-cyan-400">rev0a.jpg</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden">
          <img src="rev0b.jpg" alt="Rev 0B" class="w-full aspect-video object-cover">
          <div class="p-2 font-mono text-[11px] text-cyan-400">rev0b.jpg</div>
        </div>
        <div class="bg-brand-card border border-brand-border rounded-xl overflow-hidden">
          <img src="rev1.jpg" alt="Rev 1" class="w-full aspect-video object-cover">
          <div class="p-2 font-mono text-[11px] text-cyan-400">rev1.jpg</div>
        </div>
      </div>
    </section>

    <!-- Multi-Mission Capabilities Section -->
    <section class="mb-14">
      <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase mb-4">Multi-Mission Architecture</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-brand-card border border-brand-border rounded-2xl p-6">
          <div class="w-10 h-10 rounded-lg bg-cyan-950 border border-cyan-800 text-cyan-400 flex items-center justify-center font-mono font-bold text-lg mb-4">01</div>
          <h3 class="text-xl font-bold text-white mb-2">Civil: Lifeguard Response</h3>
          <p class="text-slate-400 text-sm leading-relaxed mb-4">
            Safety for swimmers by deploying life rings and flotation devices in dangerous surf[cite: 7].
          </p>
          <ul class="text-xs text-slate-300 space-y-2 font-mono">
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> SMS dispatch from lifeguard phone[cite: 7, 8]</li>
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> Dual servo cargo drop (GPIO 12/13)[cite: 7, 11]</li>
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> On-site GPS recovery beacon[cite: 8]</li>
          </ul>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-2xl p-6">
          <div class="w-10 h-10 rounded-lg bg-cyan-950 border border-cyan-800 text-cyan-400 flex items-center justify-center font-mono font-bold text-lg mb-4">02</div>
          <h3 class="text-xl font-bold text-white mb-2">Commercial: Sea Monitoring</h3>
          <p class="text-slate-400 text-sm leading-relaxed mb-4">
            Security over a designated water area, tracking fisheries and illegal entries 24/7[cite: 7].
          </p>
          <ul class="text-xs text-slate-300 space-y-2 font-mono">
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> Unlimited solar loitering presence[cite: 7]</li>
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> Automated status & map SMS links[cite: 8, 10]</li>
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> Persistent environmental sensing[cite: 7]</li>
          </ul>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-2xl p-6">
          <div class="w-10 h-10 rounded-lg bg-cyan-950 border border-cyan-800 text-cyan-400 flex items-center justify-center font-mono font-bold text-lg mb-4">03</div>
          <h3 class="text-xl font-bold text-white mb-2">Defense: Tactical Delivery</h3>
          <p class="text-slate-400 text-sm leading-relaxed mb-4">
            Delivering cargo through treacherous water areas, chaff dispersion, and threat engagement[cite: 7].
          </p>
          <ul class="text-xs text-slate-300 space-y-2 font-mono">
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> 250lb heavy payload capacity[cite: 7]</li>
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> Tactical payload & chaff dispersion[cite: 7]</li>
            <li class="flex items-center gap-2"><span class="text-cyan-400">•</span> Low-cost unmanned force multiplier[cite: 7]</li>
          </ul>
        </div>

      </div>
    </section>

    <!-- Field Testing Vertical Video Section -->
    <section id="video-footage" class="mb-14">
      <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase mb-4">Vertical Cell Camera Field Footage</h2>
      
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        <div class="bg-brand-card border border-brand-border rounded-xl p-2.5 flex flex-col items-center">
          <div class="font-mono text-xs text-cyan-400 mb-2 truncate w-full text-center">vid1.mp4</div>
          <div class="w-full aspect-[9/16] rounded-lg overflow-hidden bg-black border border-slate-800">
            <video controls class="w-full h-full object-cover">
              <source src="vid1.mp4" type="video/mp4">
            </video>
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-2.5 flex flex-col items-center">
          <div class="font-mono text-xs text-cyan-400 mb-2 truncate w-full text-center">vid2.mp4</div>
          <div class="w-full aspect-[9/16] rounded-lg overflow-hidden bg-black border border-slate-800">
            <video controls class="w-full h-full object-cover">
              <source src="vid2.mp4" type="video/mp4">
            </video>
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-2.5 flex flex-col items-center">
          <div class="font-mono text-xs text-cyan-400 mb-2 truncate w-full text-center">vid3.mp4</div>
          <div class="w-full aspect-[9/16] rounded-lg overflow-hidden bg-black border border-slate-800">
            <video controls class="w-full h-full object-cover">
              <source src="vid3.mp4" type="video/mp4">
            </video>
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-2.5 flex flex-col items-center">
          <div class="font-mono text-xs text-cyan-400 mb-2 truncate w-full text-center">vid4.mp4</div>
          <div class="w-full aspect-[9/16] rounded-lg overflow-hidden bg-black border border-slate-800">
            <video controls class="w-full h-full object-cover">
              <source src="vid4.mp4" type="video/mp4">
            </video>
          </div>
        </div>

        <div class="bg-brand-card border border-brand-border rounded-xl p-2.5 flex flex-col items-center">
          <div class="font-mono text-xs text-cyan-400 mb-2 truncate w-full text-center">vid5.mp4</div>
          <div class="w-full aspect-[9/16] rounded-lg overflow-hidden bg-black border border-slate-800">
            <video controls class="w-full h-full object-cover">
              <source src="vid5.mp4" type="video/mp4">
            </video>
          </div>
        </div>
      </div>
    </section>

    <!-- Command Set & Firmware Documentation Excerpts -->
    <section id="cmds" class="mb-14">
      <h2 class="text-xs font-mono text-cyan-400 tracking-widest uppercase mb-4">Command Set & Firmware Specifications</h2>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Command Set (by purpose) Excerpt -->
        <div class="bg-brand-card border border-brand-border rounded-2xl p-6">
          <div class="flex justify-between items-center border-b border-brand-border pb-3 mb-4">
            <h3 class="text-base font-mono font-bold text-white flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
              SMS Waypoint & Navigation API
            </h3>
            <span class="text-[11px] font-mono text-slate-500">Command set (by purpose).txt</span>
          </div>

          <div class="space-y-4 text-xs font-mono">
            <div>
              <div class="text-cyan-400 font-bold mb-1">1. Waypoints & Telemetry Commands</div>
              <div class="bg-slate-900/80 p-2.5 rounded border border-slate-800 space-y-1 text-slate-300">
                <div><span class="text-white font-bold">WP https://maps.google.com/?q=&lt;lat&gt;,&lt;lon&gt;</span> — Set target waypoint via Maps link[cite: 10]</div>
                <div><span class="text-white font-bold">WP &lt;lat&gt;,&lt;lon&gt;</span> — Shorthand GPS target[cite: 10]</div>
                <div><span class="text-white font-bold">WHERE</span> — Drone replies by SMS with current GPS fix link[cite: 10]</div>
                <div><span class="text-white font-bold">STATUS</span> — Returns USB link state, active waypoint, & nav mode[cite: 10]</div>
                <div><span class="text-white font-bold">CANCEL</span> — Clears active waypoint and halts nav loop[cite: 10]</div>
              </div>
            </div>

            <div>
              <div class="text-cyan-400 font-bold mb-1">2. Direct Drive Tokens (Relayed via USB Serial)</div>
              <div class="bg-slate-900/80 p-2.5 rounded border border-slate-800 text-slate-300">
                Tokens: <span class="text-yellow-400 font-bold">LEFT</span> | <span class="text-yellow-400 font-bold">RIGHT</span> | <span class="text-yellow-400 font-bold">STRAIGHT</span> | <span class="text-yellow-400 font-bold">BACK</span> | <span class="text-yellow-400 font-bold">HALT</span>[cite: 10]
              </div>
            </div>

            <div>
              <div class="text-cyan-400 font-bold mb-1">3. Authorization & Mode Settings</div>
              <div class="bg-slate-900/80 p-2.5 rounded border border-slate-800 text-slate-300 space-y-1">
                <div><span class="text-white font-bold">AUTH+ +1XXXXXXXXXX</span> — Whitelist phone number for commands[cite: 10]</div>
                <div><span class="text-white font-bold">SET MODE AUTO|MANUAL</span> — Toggle automatic nav loop vs manual SMS[cite: 10]</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Firmware Specifications (grok_2ppm_2servo) Excerpt -->
        <div class="bg-brand-card border border-brand-border rounded-2xl p-6">
          <div class="flex justify-between items-center border-b border-brand-border pb-3 mb-4">
            <h3 class="text-base font-mono font-bold text-white flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
              MCU Firmware & Pin Assignment Spec
            </h3>
            <span class="text-[11px] font-mono text-slate-500">grok_2ppm_2servo_2ain_rl.ino</span>
          </div>

          <div class="space-y-4 text-xs font-mono">
            <div>
              <div class="text-cyan-400 font-bold mb-1">1. Low-Level Motor Control Codes (2 Chars: XY)</div>
              <div class="bg-slate-900/80 p-2.5 rounded border border-slate-800 space-y-1 text-slate-300">
                <div><span class="text-yellow-400 font-bold">ff</span> — Both motors Forward (GPIO 14 & 27 max PPM)[cite: 11, 12]</div>
                <div><span class="text-yellow-400 font-bold">fr / rf</span> — Left Forward / Right Reverse (Differential turn)[cite: 12]</div>
                <div><span class="text-yellow-400 font-bold">fh / hf</span> — Single motor thrust pivot turn[cite: 12]</div>
                <div><span class="text-yellow-400 font-bold">hh / st</span> — Halt all thrusters & reset neutral (1500 µs)[cite: 12, 13]</div>
              </div>
            </div>

            <div>
              <div class="text-cyan-400 font-bold mb-1">2. Servo Cargo Release Commands</div>
              <div class="bg-slate-900/80 p-2.5 rounded border border-slate-800 space-y-1 text-slate-300">
                <div><span class="text-yellow-400 font-bold">x1</span> — Release Servo 1 (GPIO 12) for life preserver drop[cite: 11, 12]</div>
                <div><span class="text-yellow-400 font-bold">x2</span> — Release Servo 2 (GPIO 13) for tactical payload drop[cite: 11, 12]</div>
              </div>
            </div>

            <div>
              <div class="text-cyan-400 font-bold mb-1">3. Pin Mapping & Hardware Interface</div>
              <div class="bg-slate-900/80 p-2.5 rounded border border-slate-800 text-slate-300 space-y-1">
                <div>GPIO 14 / 27: Left / Right ESC Motor PWM Channels[cite: 11]</div>
                <div>GPIO 12 / 13: Payload Release Servos 1 & 2[cite: 11]</div>
                <div>GPIO 36 / 39: Analog Inputs (VP/VN Solar Telemetry)[cite: 11, 12]</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

  </main>

  <footer class="border-t border-brand-border mt-16 py-8 text-center text-xs text-slate-500 font-mono">
    iproto.com • Calidroid 1500 Platform • Low-Cost Sub-$2k Global Range ASV
  </footer>

</body>
</html>