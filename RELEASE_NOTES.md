# DynGUA-PD 0.8.1_9 — Beta RC3

Beta RC3 improves the Dynamic GUA-PD WebUI so routine operation is easier to
scan while detailed guidance remains available when needed. It also clarifies
the native Kea dependency and aligns package lifecycle scripts more closely
with FreeBSD Ports and pfSense conventions.

## WebUI Improvements

- Organize action buttons into three named rows:
  **Assignment Workflow**, **Assignment State**, and **Conflict Resolution**.
- Give all action buttons consistent responsive widths and horizontal spacing.
- Use native pfSense button colors with cog icons for functional actions, the
  save icon for **Save Settings**, and the trash icon for **Delete**.
- Shorten **Restore DGPD Assignment** to **Restore Assignment** so the caption
  fits the standard button width.
- Show concise action summaries directly below the buttons.
- Move the complete operational explanations, warnings, and staging details to
  the expandable **Usage** information.
- Clarify that **Apply Assignment** validates and stages an assignment while
  **Save Settings** commits and verifies it.

## Diagnostics and Packaging

- Name the required Kea interface explicitly as the **Kea DHCPv6 UNIX control
  socket**. The optional Kea HTTP Control Agent is not a DGPD prerequisite.
- Use the FreeBSD Ports `PREFIX` substitution in package install and deinstall
  scripts.
- Preserve the existing native pfSense feedback categories, package framework,
  configuration routines, and Kea DHCPv6 integration.

## Validation

- 66 isolated regression tests passed on pfSense CE 2.9.0.
- PHP syntax checks passed for every runtime and test file.
- Package XML and shell syntax checks passed.
- `check-plist` passed without missing or unexpected files.
- `portlint -AC` completed without fatal errors.
- Upgrade installation completed successfully on pfSense CE 2.9.0.
- Kea DHCPv4 and DHCPv6 remained active after installation.
- The installed package metadata and runtime help text were verified.

## Package

- Version: `0.8.1_9`
- Target: pfSense CE 2.9.0 / FreeBSD 16 amd64
- Build ID: `20261016-114825`
- Source commit: `6f3686f8b506`
- License: Apache-2.0
- SHA256: `3b6f1020c654a3014d9e75fc773cc047b303148ef19b2221629515ee0780f201`

Back up the pfSense configuration before installing this pre-release package.
Firewall aliases are maintained by DynGUA-PD; firewall and NAT rules remain
under administrator control.
