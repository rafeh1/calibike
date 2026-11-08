<?php
// ============================================================================
// 1. PHP AUTHENTICATION & SESSION CHECK
// ============================================================================
session_start();

// Preserves existing auth routing (adjust path or logic if needed)
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: /login.php');
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Authorized User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iproto.com | Multi-Platform Engineering Showcase</title>
    <!-- Tailwind CSS CDN -->
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
                            cyan: '#00f0ff',
                            amber: '#ffb700',
                            green: '#10b981',
                            red: '#ef4444'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0a0d14;
            color: #e2e8f0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .glow-cyan { box-shadow: 0 0 20px rgba(0, 240, 255, 0.15); }
        .glow-amber { box-shadow: 0 0 20px rgba(255, 183, 0, 0.15); }
        .tab-active {
            border-bottom: 2px solid #00f0ff;
            color: #00f0ff;
            background: rgba(0, 240, 255, 0.05);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header / Navigation -->
    <header class="border-b border-brand-border bg-brand-dark/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3 cursor-pointer" onclick="switchTab('intro')">
                <div class="w-8 h-8 rounded bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-black text-xl">
                    i
                </div>
                <span class="font-mono text-xl font-bold tracking-wider text-white">iproto<span class="text-cyan-400">.com</span></span>
            </div>
            <div class="flex items-center space-x-4">
                <nav class="hidden md:flex space-x-1 text-sm font-medium">
                    <button onclick="switchTab('intro')" id="nav-intro" class="px-3 py-2 rounded transition hover:text-cyan-400">Home</button>
                    <button onclick="switchTab('page1')" id="nav-page1" class="px-3 py-2 rounded transition hover:text-cyan-400">1. 40C3 E-Bike</button>
                    <button onclick="switchTab('page2')" id="nav-page2" class="px-3 py-2 rounded transition hover:text-cyan-400">2. Calidroid 1500</button>
                    <button onclick="switchTab('page3')" id="nav-page3" class="px-3 py-2 rounded transition hover:text-cyan-400">3. Audio Tracker</button>
                    <button onclick="switchTab('page4')" id="nav-page4" class="px-3 py-2 rounded transition hover:text-cyan-400">4. Auto Transmission</button>
                    <button onclick="switchTab('page5')" id="nav-page5" class="px-3 py-2 rounded transition hover:text-cyan-400 text-amber-400 font-semibold">5. Solar Transporter</button>
                </nav>
                <span class="text-xs font-mono text-emerald-400 bg-emerald-950/60 px-2.5 py-1 rounded border border-emerald-800/80">
                    User: <?php echo htmlspecialchars($user_name); ?>
                </span>
            </div>
        </div>
        <!-- Mobile Navigation Menu -->
        <div class="md:hidden flex overflow-x-auto border-t border-brand-border px-2 py-2 text-xs font-mono space-x-2">
            <button onclick="switchTab('intro')" id="mob-intro" class="px-3 py-1 rounded bg-brand-card whitespace-nowrap">Home</button>
            <button onclick="switchTab('page1')" id="mob-page1" class="px-3 py-1 rounded bg-brand-card whitespace-nowrap">1. 40C3 E-Bike</button>
            <button onclick="switchTab('page2')" id="mob-page2" class="px-3 py-1 rounded bg-brand-card whitespace-nowrap">2. Sea Drone</button>
            <button onclick="switchTab('page3')" id="mob-page3" class="px-3 py-1 rounded bg-brand-card whitespace-nowrap">3. Audio Tracker</button>
            <button onclick="switchTab('page4')" id="mob-page4" class="px-3 py-1 rounded bg-brand-card whitespace-nowrap">4. Transmission</button>
            <button onclick="switchTab('page5')" id="mob-page5" class="px-3 py-1 rounded bg-brand-card text-amber-400 whitespace-nowrap">5. Solar Transporter</button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 md:p-6">

        <!-- ================= INTRO PAGE ================= -->
        <section id="sec-intro" class="space-y-8">
            <div class="text-center max-w-3xl mx-auto pt-6 pb-4">
                <span class="text-cyan-400 font-mono text-sm tracking-widest uppercase bg-cyan-950/60 px-3 py-1 rounded border border-cyan-800">Advanced Hardware Engineering</span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mt-4 tracking-tight">iproto<span class="text-cyan-400">.com</span> Architecture Hub</h1>
                <p class="text-slate-400 mt-4 text-lg">Integrated prototype showcases across high-performance electric vehicles, marine autonomy, FPGA acoustic array tracking, and solar utility transport.</p>
            </div>

            <!-- 5 Subpage Showcase Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div onclick="switchTab('page1')" class="bg-brand-card border border-brand-border rounded-xl p-6 cursor-pointer hover:border-cyan-500/50 transition glow-cyan group flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono text-cyan-400 bg-cyan-950 px-2 py-1 rounded">SUBPAGE 1</span>
                            <span class="text-xs text-slate-500">Light Electric Vehicle</span>
                        </div>
                        <h2 class="text-xl font-bold text-white group-hover:text-cyan-400 transition">CaliBike 40C3 E-Bike</h2>
                        <p class="text-sm text-slate-400 mt-2">Ultra-lightweight hyper e-bike profile. 40 lbs total weight, 40 MPH top speed, 40 miles range powered by 52V FOC vector control.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-brand-border/60 flex justify-between items-center text-xs text-cyan-400 font-mono">
                        <span>Launch Telemetry Demo</span>
                        <span>?</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div onclick="switchTab('page2')" class="bg-brand-card border border-brand-border rounded-xl p-6 cursor-pointer hover:border-cyan-500/50 transition glow-cyan group flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono text-cyan-400 bg-cyan-950 px-2 py-1 rounded">SUBPAGE 2</span>
                            <span class="text-xs text-slate-500">Autonomous Marine</span>
                        </div>
                        <h2 class="text-xl font-bold text-white group-hover:text-cyan-400 transition">Calidroid 1500 Sea Drone</h2>
                        <p class="text-sm text-slate-400 mt-2">Autonomous surface vessel with continuous photovoltaic charging, satellite short-burst telemetry, and dual submerged thruster dynamics.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-brand-border/60 flex justify-between items-center text-xs text-cyan-400 font-mono">
                        <span>Open Marine Radar</span>
                        <span>?</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div onclick="switchTab('page3')" class="bg-brand-card border border-brand-border rounded-xl p-6 cursor-pointer hover:border-cyan-500/50 transition glow-cyan group flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono text-cyan-400 bg-cyan-950 px-2 py-1 rounded">SUBPAGE 3</span>
                            <span class="text-xs text-slate-500">Embedded Signal Processing</span>
                        </div>
                        <h2 class="text-xl font-bold text-white group-hover:text-cyan-400 transition">Acoustic Drone Audio Tracker</h2>
                        <p class="text-sm text-slate-400 mt-2">FPGA + ESP32-S3 acoustic localization using an 8-channel I2S MEMS microphone array for real-time TDOA and DoA beamforming.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-brand-border/60 flex justify-between items-center text-xs text-cyan-400 font-mono">
                        <span>View Beamformer</span>
                        <span>?</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div onclick="switchTab('page4')" class="bg-brand-card border border-brand-border rounded-xl p-6 cursor-pointer hover:border-cyan-500/50 transition glow-cyan group flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono text-cyan-400 bg-cyan-950 px-2 py-1 rounded">SUBPAGE 4</span>
                            <span class="text-xs text-slate-500">Mechanical Innovation</span>
                        </div>
                        <h2 class="text-xl font-bold text-white group-hover:text-cyan-400 transition">Auto Torque Transmission</h2>
                        <p class="text-sm text-slate-400 mt-2">Patented automatic torque-dependent bicycle transmission continuously adjusting ratio under hill climbs and variable motor strain.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-brand-border/60 flex justify-between items-center text-xs text-cyan-400 font-mono">
                        <span>Simulate Transmission</span>
                        <span>?</span>
                    </div>
                </div>

                <!-- Card 5 (Featured Solar Transporter) -->
                <div onclick="switchTab('page5')" class="bg-brand-card border border-amber-500/50 rounded-xl p-6 cursor-pointer hover:border-amber-400 transition glow-amber group flex flex-col justify-between md:col-span-2 lg:col-span-2">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono text-amber-400 bg-amber-950 px-2 py-1 rounded">SUBPAGE 5 • FEATURED PROJECT</span>
                            <span class="text-xs text-slate-500">Solar Utility Vehicle</span>
                        </div>
                        <h2 class="text-2xl font-bold text-amber-400 group-hover:text-amber-300 transition">5. Solar Mini Transporter</h2>
                        <p class="text-slate-300 mt-2">4-Wheel autonomous solar cart built on weldless interlocking square U-clamp aluminum framing, 4x100 PCD flange hub motors, dual 48V 20Ah batteries, and twin 450W solar panels with MPPT charge balancing.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-brand-border/60 flex justify-between items-center text-sm text-amber-400 font-mono font-bold">
                        <span>Explore 5th Subpage Utility Architecture</span>
                        <span>?</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= SUBPAGE 1: CALIBIKE 40C3 ================= -->
        <section id="sec-page1" class="hidden space-y-6">
            <div class="flex justify-between items-center border-b border-brand-border pb-4">
                <div>
                    <span class="text-xs font-mono text-cyan-400">SUBPAGE 1</span>
                    <h1 class="text-3xl font-extrabold text-white">CaliBike 40C3 E-Bike Platform</h1>
                </div>
                <button onclick="switchTab('intro')" class="text-xs font-mono bg-brand-card border border-brand-border px-3 py-1.5 rounded text-slate-300 hover:text-white">? Return Home</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-brand-border pb-2">"40-40-40" Core Specs</h3>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-brand-dark p-3 rounded border border-brand-border">
                            <div class="text-2xl font-bold text-cyan-400">40</div>
                            <div class="text-xs text-slate-400">lbs Total</div>
                        </div>
                        <div class="bg-brand-dark p-3 rounded border border-brand-border">
                            <div class="text-2xl font-bold text-cyan-400">40</div>
                            <div class="text-xs text-slate-400">MPH Max</div>
                        </div>
                        <div class="bg-brand-dark p-3 rounded border border-brand-border">
                            <div class="text-2xl font-bold text-cyan-400">40</div>
                            <div class="text-xs text-slate-400">Mi Range</div>
                        </div>
                    </div>
                    <ul class="text-xs space-y-2 text-slate-300 font-mono">
                        <li class="flex justify-between"><span>Motor Output:</span> <span class="text-white">2500W Ultra-Light Hub</span></li>
                        <li class="flex justify-between"><span>System Voltage:</span> <span class="text-white">52V Nominal</span></li>
                        <li class="flex justify-between"><span>Controller:</span> <span class="text-white">VESC 75100 FOC Algorithm</span></li>
                        <li class="flex justify-between"><span>Default Speed:</span> <span class="text-white">28 MPH (Class 3 Road Legal)</span></li>
                        <li class="flex justify-between"><span>Unlocked Speed:</span> <span class="text-white">37 - 43 MPH (Moped Mode)</span></li>
                    </ul>
                </div>

                <div class="lg:col-span-2 bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">FOC Vector Drive Telemetry</h3>
                        <div class="flex items-center space-x-3">
                            <label class="text-xs text-slate-400">Speed Unlock Mode:</label>
                            <button id="btn-ebike-unlock" onclick="toggleEbikeUnlock()" class="px-3 py-1 rounded text-xs font-mono bg-slate-800 text-slate-300 border border-slate-700">Class 3 (28mph)</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <canvas id="canvas-ebike" class="w-full h-44 bg-brand-dark rounded border border-brand-border"></canvas>
                        </div>
                        <div class="space-y-4 text-xs font-mono">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span>Throttle Applied:</span>
                                    <span id="txt-ebike-throttle" class="text-cyan-400">0%</span>
                                </div>
                                <input type="range" id="slider-ebike-throttle" min="0" max="100" value="0" oninput="updateEbikeSim()" class="w-full accent-cyan-400">
                            </div>
                            <div class="bg-brand-dark p-3 rounded space-y-2">
                                <div class="flex justify-between"><span>Calculated Speed:</span> <span id="val-ebike-speed" class="text-cyan-400 font-bold">0.0 MPH</span></div>
                                <div class="flex justify-between"><span>Power Consumption:</span> <span id="val-ebike-power" class="text-cyan-400">0 W</span></div>
                                <div class="flex justify-between"><span>Estimated Range:</span> <span id="val-ebike-range" class="text-cyan-400">40.0 Miles</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= SUBPAGE 2: CALIDROID 1500 ================= -->
        <section id="sec-page2" class="hidden space-y-6">
            <div class="flex justify-between items-center border-b border-brand-border pb-4">
                <div>
                    <span class="text-xs font-mono text-cyan-400">SUBPAGE 2</span>
                    <h1 class="text-3xl font-extrabold text-white">Calidroid 1500 Solar Sea Drone</h1>
                </div>
                <button onclick="switchTab('intro')" class="text-xs font-mono bg-brand-card border border-brand-border px-3 py-1.5 rounded text-slate-300 hover:text-white">? Return Home</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white">Autonomous Satellite & Navigation Radar</h3>
                    <canvas id="canvas-drone" class="w-full h-64 bg-brand-dark rounded border border-brand-border"></canvas>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs font-mono">
                        <div class="bg-brand-dark p-2 rounded">
                            <div class="text-slate-400">Heading</div>
                            <div class="text-cyan-400 font-bold">248° SW</div>
                        </div>
                        <div class="bg-brand-dark p-2 rounded">
                            <div class="text-slate-400">Link Status</div>
                            <div class="text-emerald-400 font-bold">RockBLOCK SBD Active</div>
                        </div>
                        <div class="bg-brand-dark p-2 rounded">
                            <div class="text-slate-400">Solar Power Input</div>
                            <div class="text-amber-400 font-bold">142 W</div>
                        </div>
                    </div>
                </div>

                <div class="bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-brand-border pb-2">Sub-surface Architecture</h3>
                    <ul class="text-xs space-y-3 text-slate-300 font-mono">
                        <li class="bg-brand-dark p-3 rounded">
                            <div class="font-bold text-cyan-400">Low-Power Satellite Telemetry</div>
                            <div class="text-slate-400 mt-1">Swarm M138 / RockBLOCK 9603 SBD transceiver transmitting long-range ocean coordinates.</div>
                        </li>
                        <li class="bg-brand-dark p-3 rounded">
                            <div class="font-bold text-cyan-400">Submerged Propulsion</div>
                            <div class="text-slate-400 mt-1">Twin brushless waterproof direct-drive thrusters running low-RPM vector steering.</div>
                        </li>
                        <li class="bg-brand-dark p-3 rounded">
                            <div class="font-bold text-cyan-400">Photovoltaic Charging</div>
                            <div class="text-slate-400 mt-1">Conformal deck solar panel array providing infinite endurance under daylight navigation.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ================= SUBPAGE 3: AUDIO TRACKER ================= -->
        <section id="sec-page3" class="hidden space-y-6">
            <div class="flex justify-between items-center border-b border-brand-border pb-4">
                <div>
                    <span class="text-xs font-mono text-cyan-400">SUBPAGE 3</span>
                    <h1 class="text-3xl font-extrabold text-white">Acoustic Drone Audio Tracker</h1>
                </div>
                <button onclick="switchTab('intro')" class="text-xs font-mono bg-brand-card border border-brand-border px-3 py-1.5 rounded text-slate-300 hover:text-white">? Return Home</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-brand-border pb-2">Hardware Signal Chain</h3>
                    <div class="text-xs font-mono space-y-2">
                        <div class="p-3 bg-brand-dark rounded border border-cyan-900/50">
                            <div class="text-cyan-400 font-bold">8x ICS-43434 Array</div>
                            <div class="text-slate-400">I2S Digital MEMS microphones configured in circular geometry.</div>
                        </div>
                        <div class="text-center text-cyan-400 font-bold">? Parallel I2S</div>
                        <div class="p-3 bg-brand-dark rounded border border-cyan-900/50">
                            <div class="text-cyan-400 font-bold">LilyGO T-FPGA Board</div>
                            <div class="text-slate-400">Gowin GW1NSR FPGA handling microsecond TDOA hardware timestamping.</div>
                        </div>
                        <div class="text-center text-cyan-400 font-bold">? High-Speed SPI</div>
                        <div class="p-3 bg-brand-dark rounded border border-cyan-900/50">
                            <div class="text-cyan-400 font-bold">ESP32-S3 Processor</div>
                            <div class="text-slate-400">Calculates Direction of Arrival (DoA) beamforming vector.</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white">Real-Time Acoustic TDOA Tracking Beamformer</h3>
                    <canvas id="canvas-audio" class="w-full h-64 bg-brand-dark rounded border border-brand-border"></canvas>
                    <div class="flex justify-between items-center text-xs font-mono text-slate-400 bg-brand-dark p-3 rounded">
                        <span>Target Bearing: <strong class="text-cyan-400">124°</strong></span>
                        <span>Estimated Distance: <strong class="text-cyan-400">38.4 meters</strong></span>
                        <span>Array Status: <strong class="text-emerald-400">8/8 Mics Synced</strong></span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= SUBPAGE 4: AUTO TRANSMISSION ================= -->
        <section id="sec-page4" class="hidden space-y-6">
            <div class="flex justify-between items-center border-b border-brand-border pb-4">
                <div>
                    <span class="text-xs font-mono text-cyan-400">SUBPAGE 4</span>
                    <h1 class="text-3xl font-extrabold text-white">Auto Torque-Dependent Bike Transmission</h1>
                </div>
                <button onclick="switchTab('intro')" class="text-xs font-mono bg-brand-card border border-brand-border px-3 py-1.5 rounded text-slate-300 hover:text-white">? Return Home</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white">Automatic Mechanical Gear Expansion Simulator</h3>
                    <canvas id="canvas-trans" class="w-full h-60 bg-brand-dark rounded border border-brand-border"></canvas>
                    
                    <div class="space-y-3 font-mono text-xs">
                        <div class="flex justify-between items-center">
                            <span>Simulated Uphill / Load Torque:</span>
                            <span id="txt-torque-val" class="text-amber-400 font-bold">15 Nm</span>
                        </div>
                        <input type="range" id="slider-torque" min="5" max="60" value="15" oninput="updateTransmissionSim()" class="w-full accent-amber-400">
                        <div class="grid grid-cols-2 gap-4 bg-brand-dark p-3 rounded">
                            <div>Effective Gear Ratio: <span id="val-gear-ratio" class="text-amber-400 font-bold">1 : 2.4</span></div>
                            <div>Transmission State: <span id="val-gear-state" class="text-emerald-400 font-bold">1:1 Cruising</span></div>
                        </div>
                    </div>
                </div>

                <div class="bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-brand-border pb-2">Patented Mechanical Principles</h3>
                    <ul class="text-xs space-y-3 text-slate-300">
                        <li class="bg-brand-dark p-3 rounded">
                            <strong class="text-amber-400 font-mono">Dynamic Torque Sensing:</strong>
                            <p class="text-slate-400 mt-1">Automatically shifts effective ratio down under increased pedaling or motor strain without electronics.</p>
                        </li>
                        <li class="bg-brand-dark p-3 rounded">
                            <strong class="text-amber-400 font-mono">Continuous Ratio Expansion:</strong>
                            <p class="text-slate-400 mt-1">Spring-loaded expanding drive arms alter the effective mechanical diameter dynamically.</p>
                        </li>
                        <li class="bg-brand-dark p-3 rounded">
                            <strong class="text-amber-400 font-mono">High-Efficiency Power Transfer:</strong>
                            <p class="text-slate-400 mt-1">Prevents motor overheating on steep inclines while maximizing battery efficiency.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ================= SUBPAGE 5: SOLAR MINI TRANSPORTER ================= -->
        <section id="sec-page5" class="hidden space-y-6">
            <div class="flex justify-between items-center border-b border-amber-500/50 pb-4">
                <div>
                    <span class="text-xs font-mono text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">SUBPAGE 5 • FEATURED UTILITY PROJECT</span>
                    <h1 class="text-3xl font-extrabold text-amber-400 mt-1">Solar Mini Transporter Platform</h1>
                </div>
                <button onclick="switchTab('intro')" class="text-xs font-mono bg-brand-card border border-brand-border px-3 py-1.5 rounded text-slate-300 hover:text-white">? Return Home</button>
            </div>

            <div class="bg-brand-card border border-amber-500/30 rounded-xl p-6 space-y-4 glow-amber">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-brand-border pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-white">4-Wheel Autonomous Heavy Utility Cart</h2>
                        <p class="text-xs font-mono text-slate-400 mt-1">Zero-weld modular frame constructed with interlocking rectangular U-clamps and stainless structural tubing.</p>
                    </div>
                    <div class="flex space-x-2">
                        <span class="px-3 py-1 bg-amber-950 border border-amber-800 text-amber-400 text-xs font-mono rounded">900W Dual MPPT</span>
                        <span class="px-3 py-1 bg-cyan-950 border border-cyan-800 text-cyan-400 text-xs font-mono rounded">4x Hub Drive</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                    <div class="bg-brand-dark p-4 rounded-lg border border-brand-border">
                        <div class="text-xs text-amber-400 font-mono font-bold">01. Modular Weldless Frame</div>
                        <div class="text-sm font-bold text-white mt-1">Square U-Clamp Joint System</div>
                        <p class="text-xs text-slate-400 mt-2">Built using interlocking M5 hardware and 1.25" structural tubing for field serviceability.</p>
                    </div>

                    <div class="bg-brand-dark p-4 rounded-lg border border-brand-border">
                        <div class="text-xs text-amber-400 font-mono font-bold">02. Photovoltaic Generation</div>
                        <div class="text-sm font-bold text-white mt-1">Dual 450W Solar Panels</div>
                        <p class="text-xs text-slate-400 mt-2">Overhead canopy arrangement supplying up to 900W continuous solar input during outdoor operation.</p>
                    </div>

                    <div class="bg-brand-dark p-4 rounded-lg border border-brand-border">
                        <div class="text-xs text-amber-400 font-mono font-bold">03. Drive System</div>
                        <div class="text-sm font-bold text-white mt-1">4x 100 PCD Flange Motors</div>
                        <p class="text-xs text-slate-400 mt-2">Casing-mounted hub motors offering 4-wheel independent torque control across terrain.</p>
                    </div>

                    <div class="bg-brand-dark p-4 rounded-lg border border-brand-border">
                        <div class="text-xs text-amber-400 font-mono font-bold">04. Battery Bank</div>
                        <div class="text-sm font-bold text-white mt-1">Dual 48V 20Ah LiFePO4</div>
                        <p class="text-xs text-slate-400 mt-2">Parallel high-capacity storage banks balanced via integrated smart BMS and MPPT controllers.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-brand-card border border-brand-border rounded-xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white">Transporter Solar & Battery Power Flow Simulator</h3>
                    <canvas id="canvas-solar" class="w-full h-64 bg-brand-dark rounded border border-brand-border"></canvas>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 font-mono text-xs">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Solar Irradiance (Sunlight):</span>
                                <span id="txt-sunlight" class="text-amber-400">75%</span>
                            </div>
                            <input type="range" id="slider-sunlight" min="0" max="100" value="75" oninput="updateSolarSim()" class="w-full accent-amber-400">
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Payload Transport Load:</span>
                                <span id="txt-payload" class="text-cyan-400">250 lbs</span>
                            </div>
                            <input type="range" id="slider-payload" min="0" max="600" value="250" oninput="updateSolarSim()" class="w-full accent-cyan-400">
                        </div>
                    </div>
                </div>

                <div class="bg-brand-card border border-brand-border rounded-xl p-6 space-y-4 font-mono text-xs">
                    <h3 class="text-lg font-bold text-white border-b border-brand-border pb-2">Live Power Readout</h3>
                    
                    <div class="bg-brand-dark p-3 rounded space-y-2">
                        <div class="flex justify-between"><span>Solar Generation:</span> <span id="val-solar-gen" class="text-amber-400 font-bold">675 W</span></div>
                        <div class="flex justify-between"><span>Motor Draw:</span> <span id="val-motor-draw" class="text-cyan-400 font-bold">420 W</span></div>
                        <div class="flex justify-between"><span>Net Battery Stream:</span> <span id="val-net-power" class="text-emerald-400 font-bold">+255 W (Charging)</span></div>
                    </div>

                    <div class="border-t border-brand-border/60 pt-3 space-y-2">
                        <div class="text-slate-400">Operating Mode:</div>
                        <div class="text-white bg-brand-dark p-2 rounded border border-brand-border font-sans text-xs">
                            <strong class="text-amber-400">Self-Sustaining Cruise:</strong> Solar generation exceeds motor drain under standard payload speeds.
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="border-t border-brand-border bg-brand-dark py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 font-mono gap-2">
            <div>© 2026 iproto.com • Embedded Engineering Showcase</div>
            <div class="flex space-x-4">
                <span>CaliBike</span>
                <span>•</span>
                <span>Calidroid</span>
                <span>•</span>
                <span>RD Labs</span>
            </div>
        </div>
    </footer>

    <!-- Interactive Navigation and Simulation Logic -->
    <script>
        function switchTab(tabId) {
            const sections = ['intro', 'page1', 'page2', 'page3', 'page4', 'page5'];
            sections.forEach(s => {
                document.getElementById(`sec-${s}`).classList.add('hidden');
                
                const navBtn = document.getElementById(`nav-${s}`);
                if (navBtn) {
                    navBtn.classList.remove('tab-active');
                    if (s === tabId) navBtn.classList.add('tab-active');
                }

                const mobBtn = document.getElementById(`mob-${s}`);
                if (mobBtn) {
                    mobBtn.classList.remove('bg-cyan-900', 'text-cyan-300');
                    if (s === tabId) mobBtn.classList.add('bg-cyan-900', 'text-cyan-300');
                }
            });

            document.getElementById(`sec-${tabId}`).classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });

            if (tabId === 'page1') initEbikeSim();
            if (tabId === 'page2') initDroneSim();
            if (tabId === 'page3') initAudioSim();
            if (tabId === 'page4') initTransSim();
            if (tabId === 'page5') initSolarSim();
        }

        let isEbikeUnlocked = false;
        let ebikeAnimId = null;

        function toggleEbikeUnlock() {
            isEbikeUnlocked = !isEbikeUnlocked;
            const btn = document.getElementById('btn-ebike-unlock');
            if (isEbikeUnlocked) {
                btn.innerText = 'Unlocked (40 MPH Mode)';
                btn.className = 'px-3 py-1 rounded text-xs font-mono bg-red-950 text-red-400 border border-red-800 font-bold';
            } else {
                btn.innerText = 'Class 3 (28mph)';
                btn.className = 'px-3 py-1 rounded text-xs font-mono bg-slate-800 text-slate-300 border border-slate-700';
            }
            updateEbikeSim();
        }

        function updateEbikeSim() {
            const throttle = parseInt(document.getElementById('slider-ebike-throttle').value);
            document.getElementById('txt-ebike-throttle').innerText = `${throttle}%`;

            const maxSpeed = isEbikeUnlocked ? 40 : 28;
            const speed = ((throttle / 100) * maxSpeed).toFixed(1);
            const power = Math.round((throttle / 100) * (isEbikeUnlocked ? 2500 : 750));
            const range = (40 * (1 - (throttle / 200))).toFixed(1);

            document.getElementById('val-ebike-speed').innerText = `${speed} MPH`;
            document.getElementById('val-ebike-power').innerText = `${power} W`;
            document.getElementById('val-ebike-range').innerText = `${range} Miles`;
        }

        function initEbikeSim() {
            const canvas = document.getElementById('canvas-ebike');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let t = 0;

            function draw() {
                canvas.width = canvas.clientWidth;
                canvas.height = canvas.clientHeight;
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                const throttle = parseInt(document.getElementById('slider-ebike-throttle').value);
                const freq = 0.05 + (throttle / 2000);

                ctx.strokeStyle = '#00f0ff';
                ctx.lineWidth = 2;
                ctx.beginPath();
                for (let x = 0; x < canvas.width; x++) {
                    const y = (canvas.height / 2) + Math.sin(x * freq + t) * (throttle * 0.3);
                    if (x === 0) ctx.moveTo(x, y);
                    else ctx.lineTo(x, y);
                }
                ctx.stroke();

                t += 0.05;
                ebikeAnimId = requestAnimationFrame(draw);
            }
            if (ebikeAnimId) cancelAnimationFrame(ebikeAnimId);
            draw();
        }

        function initDroneSim() {
            const canvas = document.getElementById('canvas-drone');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let angle = 0;

            function draw() {
                canvas.width = canvas.clientWidth;
                canvas.height = canvas.clientHeight;
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                const cx = canvas.width / 2;
                const cy = canvas.height / 2;
                const r = Math.min(cx, cy) - 20;

                ctx.strokeStyle = '#1e2638';
                ctx.lineWidth = 1;
                ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI * 2); ctx.stroke();
                ctx.beginPath(); ctx.arc(cx, cy, r * 0.6, 0, Math.PI * 2); ctx.stroke();

                ctx.strokeStyle = 'rgba(0, 240, 255, 0.5)';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(cx, cy);
                ctx.lineTo(cx + Math.cos(angle) * r, cy + Math.sin(angle) * r);
                ctx.stroke();

                ctx.fillStyle = '#10b981';
                ctx.beginPath(); ctx.arc(cx + 40, cy - 30, 5, 0, Math.PI * 2); ctx.fill();

                angle += 0.02;
                requestAnimationFrame(draw);
            }
            draw();
        }

        function initAudioSim() {
            const canvas = document.getElementById('canvas-audio');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            function draw() {
                canvas.width = canvas.clientWidth;
                canvas.height = canvas.clientHeight;
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                const cx = canvas.width / 2;
                const cy = canvas.height / 2;

                for (let i = 0; i < 8; i++) {
                    const a = (i / 8) * Math.PI * 2;
                    const mx = cx + Math.cos(a) * 60;
                    const my = cy + Math.sin(a) * 60;
                    ctx.fillStyle = '#00f0ff';
                    ctx.beginPath(); ctx.arc(mx, my, 4, 0, Math.PI * 2); ctx.fill();
                }

                const targetAngle = 2.16;
                ctx.strokeStyle = '#ffb700';
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.moveTo(cx, cy);
                ctx.lineTo(cx + Math.cos(targetAngle) * 120, cy + Math.sin(targetAngle) * 120);
                ctx.stroke();

                requestAnimationFrame(draw);
            }
            draw();
        }

        function updateTransmissionSim() {
            const torque = parseInt(document.getElementById('slider-torque').value);
            document.getElementById('txt-torque-val').innerText = `${torque} Nm`;

            const ratio = (1 + (torque / 35)).toFixed(1);
            document.getElementById('val-gear-ratio').innerText = `1 : ${ratio}`;
            
            const state = document.getElementById('val-gear-state');
            if (torque > 35) {
                state.innerText = 'High Torque Hill Climb';
                state.className = 'text-amber-400 font-bold';
            } else {
                state.innerText = '1:1 Cruising';
                state.className = 'text-emerald-400 font-bold';
            }
            initTransSim();
        }

        function initTransSim() {
            const canvas = document.getElementById('canvas-trans');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const torque = parseInt(document.getElementById('slider-torque').value);

            canvas.width = canvas.clientWidth;
            canvas.height = canvas.clientHeight;
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const cx = canvas.width / 2;
            const cy = canvas.height / 2;
            const baseRadius = 40;
            const expandedRadius = baseRadius + (torque * 0.7);

            ctx.fillStyle = '#1e2638';
            ctx.beginPath(); ctx.arc(cx, cy, 20, 0, Math.PI * 2); ctx.fill();

            ctx.strokeStyle = '#ffb700';
            ctx.lineWidth = 4;
            ctx.beginPath(); ctx.arc(cx, cy, expandedRadius, 0, Math.PI * 2); ctx.stroke();
        }

        function updateSolarSim() {
            const sunlight = parseInt(document.getElementById('slider-sunlight').value);
            const payload = parseInt(document.getElementById('slider-payload').value);

            document.getElementById('txt-sunlight').innerText = `${sunlight}%`;
            document.getElementById('txt-payload').innerText = `${payload} lbs`;

            const gen = Math.round((sunlight / 100) * 900);
            const draw = Math.round(150 + (payload * 1.1));
            const net = gen - draw;

            document.getElementById('val-solar-gen').innerText = `${gen} W`;
            document.getElementById('val-motor-draw').innerText = `${draw} W`;

            const netElem = document.getElementById('val-net-power');
            if (net >= 0) {
                netElem.innerText = `+${net} W (Charging)`;
                netElem.className = 'text-emerald-400 font-bold';
            } else {
                netElem.innerText = `${net} W (Discharging)`;
                netElem.className = 'text-red-400 font-bold';
            }
        }

        function initSolarSim() {
            const canvas = document.getElementById('canvas-solar');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            canvas.width = canvas.clientWidth;
            canvas.height = canvas.clientHeight;
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.strokeStyle = '#ffb700';
            ctx.lineWidth = 2;
            ctx.strokeRect(canvas.width / 2 - 80, canvas.height / 2 - 40, 160, 80);

            ctx.fillStyle = '#00f0ff';
            ctx.beginPath(); ctx.arc(canvas.width / 2 - 70, canvas.height / 2 - 40, 12, 0, Math.PI * 2); ctx.fill();
            ctx.beginPath(); ctx.arc(canvas.width / 2 + 70, canvas.height / 2 - 40, 12, 0, Math.PI * 2); ctx.fill();
            ctx.beginPath(); ctx.arc(canvas.width / 2 - 70, canvas.height / 2 + 40, 12, 0, Math.PI * 2); ctx.fill();
            ctx.beginPath(); ctx.arc(canvas.width / 2 + 70, canvas.height / 2 + 40, 12, 0, Math.PI * 2); ctx.fill();
        }

        window.addEventListener('load', () => {
            switchTab('intro');
        });
    </script>
</body>
</html>