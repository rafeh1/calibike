# Motor Command Documentation for LilyPad USB

This document provides a detailed guide to the motor control commands for the LilyPad USB-based system, utilizing a custom Arduino sketch to manage two motors via PPM (Pulse Position Modulation) signals. The system supports serial communication over USB and optional Bluetooth, with feedback logged via the onboard LED and terminal.

## Overview
- **Hardware**: LilyPad USB (ATmega32U4) connected to COM9.
- **Motors**: Left motor on digital pin D8, right motor on digital pin D9.
- **LED**: Onboard LED on digital pin D13, flashes on command execution.
- **Communication**: Serial at 9600 baud (USB and Bluetooth via SoftwareSerial).
- **PPM Range**: Based on ESC specifications:
  - 1000 µs: Reverse (maximum reverse speed).
  - 1500 µs: Neutral/Stop (halt position).
  - 2000 µs: Forward (maximum forward speed).
  - Frequency: 50 Hz (handled by the Servo library).

## Command Structure
Commands are two-character sequences sent via the Serial Monitor (Arduino IDE) or a Bluetooth terminal app (e.g., Serial USB Terminal on Android). The first character controls the left motor (D8), and the second controls the right motor (D9). Valid characters are:
- `F`: Forward (2000 µs).
- `R`: Reverse (1000 µs).
- `H`: Halt (1500 µs).

Commands are case-insensitive (e.g., `ff`, `FF`, `Ff` all work).

## Command List
| Command | Left Motor (D8) | Right Motor (D9) | Description                     | Terminal Output Example         |
|---------|-----------------|-----------------|---------------------------------|---------------------------------|
| `ff`    | Forward (2000 µs) | Forward (2000 µs) | Both motors move forward        | "Both Forward: Left=2000µs, Right=2000µs" |
| `fr`    | Forward (2000 µs) | Reverse (1000 µs) | Left forward, right reverse     | "Left Forward, Right Reverse: Left=2000µs, Right=1000µs" |
| `fh`    | Forward (2000 µs) | Halt (1500 µs)   | Left forward, right stopped     | "Left Forward, Right Halt: Left=2000µs, Right=1500µs" |
| `rf`    | Reverse (1000 µs) | Forward (2000 µs) | Left reverse, right forward     | "Left Reverse, Right Forward: Left=1000µs, Right=2000µs" |
| `rr`    | Reverse (1000 µs) | Reverse (1000 µs) | Both motors move reverse        | "Both Reverse: Left=1000µs, Right=1000µs" |
| `rh`    | Reverse (1000 µs) | Halt (1500 µs)   | Left reverse, right stopped     | "Left Reverse, Right Halt: Left=1000µs, Right=1500µs" |
| `hf`    | Halt (1500 µs)   | Forward (2000 µs) | Left stopped, right forward     | "Left Halt, Right Forward: Left=1500µs, Right=2000µs" |
| `hr`    | Halt (1500 µs)   | Reverse (1000 µs) | Left stopped, right reverse     | "Left Halt, Right Reverse: Left=1500µs, Right=1000µs" |
| `hh`    | Halt (1500 µs)   | Halt (1500 µs)   | Both motors stopped             | "Halt: Left=1500µs, Right=1500µs" |

## Usage Instructions
### Sending Commands
- **Via Serial Monitor (Arduino IDE)**:
  1. Open Arduino IDE.
  2. Select Tools > Port > COM9.
  3. Upload the sketch.
  4. Open Tools > Serial Monitor.
  5. Set baud rate to 9600 and line ending to "Newline."
  6. Type a two-character command (e.g., `ff`) and press Enter.
- **Via Bluetooth Terminal (Android)**:
  1. Connect an HC-05 module: VCC → 3.3–5V, GND → GND, TX → D0, RX → D1 (with 3.3V-to-5V level shifter).
  2. Pair the HC-05 with your Android device (default PIN: 1234 or 0000).
  3. Open a terminal app (e.g., Serial USB Terminal), set to 9600 baud, 8N1.
  4. Send the two-character command and press Enter.
- **Notes**:
  - Commands are case-insensitive (e.g., `FF`, `Ff` work).
  - Invalid commands (e.g., `ab`, single char) will be ignored or cleared with a partial input message.

## Feedback Mechanism
- **LED Feedback**: The onboard LED on D13 flashes for 100ms each time a valid command is received.
- **Command Log**: Every 10th LED flash (approximately 10 seconds with 1-second delays), the terminal displays:
  ```
  Command log: Last 10 commands processed
  ```
- **Response**: Each valid command triggers a response in the terminal (e.g., "Both Forward: Left=2000µs, Right=2000µs").

## Hardware Setup
- **Power**: Connect via USB (COM9) or a 3.3–5V battery (e.g., 3.7V LiPo) to VCC/GND pads.
- **Motors/ESCs**: Attach the left motor ESC signal to D8, right motor ESC signal to D9. Use an external battery for the ESCs, with a shared GND to the LilyPad.
- **LED**: The onboard LED on D13 provides visual feedback (no external wiring needed).
- **Bluetooth (Optional)**: For wireless control, connect an HC-05 Bluetooth module with a level shifter (3.3V to 5V) on D0 (RX) and D1 (TX).

## Software Setup
1. **Arduino IDE**:
   - Install the Arduino IDE (latest version recommended).
   - Add board support if needed: File > Preferences > Additional Boards Manager URLs: `https://downloads.arduino.cc/packages/packages_index.json`.
   - Tools > Board > Boards Manager > Install "Arduino AVR Boards."
   - Select Tools > Board > Arduino LilyPad USB.
   - Tools > Port > COM9.
   - Upload the sketch.
2. **Libraries**:
   - Ensure `Servo.h` (version 1.2.2) and `SoftwareSerial.h` (version 1.0) are installed via Sketch > Include Library > Manage Libraries.

## Limitations and Notes
- **PPM Range**: The code uses 1000 µs (reverse), 1500 µs (halt), and 2000 µs (forward) within the ESC’s 1ms–2ms range. Adjust these values in the code if your ESC requires finer control (e.g., 1100–1900 µs).
- **ESC Calibration**: If motors don’t respond as expected, calibrate the ESC per its manual (e.g., set 1000 µs and 2000 µs during power-on).
- **Power Supply**: Ensure the external battery for ESCs provides adequate current, as the LilyPad’s USB power (5V) may be insufficient.

## Example Session
```
Send 2-char command via USB or Bluetooth: ff, fr, fh, rf, rr, rh, hf, hr, hh
ff
Both Forward: Left=2000µs, Right=2000µs
fr
Left Forward, Right Reverse: Left=2000µs, Right=1000µs
hh
Halt: Left=1500µs, Right=1500µs
[9 more commands]
Command log: Last 10 commands processed
```

This documentation should guide you in using the motor control system effectively. Let me know if you need further adjustments or additional details!