# DynGUA-PD 1.0.0

DynGUA-PD 1.0.0 is the first release qualified through the complete beta test
cycle on pfSense CE 2.9.0. It manages downstream IPv6 prefix delegation through
native pfSense Track Interface, Kea DHCPv6, package, firewall-alias, and lease
routing facilities.

## Highlights

- Discovers the current upstream IA_PD through the tested allocator and uses
  Router Advertisements as secondary comparison evidence.
- Proposes aligned, upstream-contained downstream pools through `/64` while
  excluding interface networks, Kea pools, leases, and existing reservations.
- Stages and commits assignments through the native pfSense DHCPv6 workflow.
- Detects safe native changes, ownership conflicts, stale assignments, and
  incomplete prerequisites without overwriting unrelated configuration.
- Realigns managed assignments after a compatible upstream prefix change and
  disables obsolete assignments when no safe replacement exists.
- Revokes only matching IA_PD leases when a confirmed Disable or Delete action
  requires downstream interruption.
- Maintains per-interface `DGPD_<INTERFACE>_PD` aliases and the aggregate
  `DGPD_ALL_PD` alias for administrator-defined firewall rules.
- Uses native `kea2fib6` lease routing instead of implementing a separate route
  manager.

## Web Interface

- Groups controls into **Assignment Workflow**, **Assignment State**,
  **Conflict Resolution**, and a dedicated **Save Settings** row.
- Shows persistent staged-operation guidance until an assignment is committed
  or otherwise resolved.
- Shows progress while staged settings are being saved and prevents repeated
  submissions during the transition.
- Limits transient success-message cleanup to DGPD-owned notices.
- Provides controlled Restore, Accept Native Change, and Release Ownership
  paths for external native configuration changes.
- Retains optional debug logging for diagnostics without displaying the former
  beta warning.
- Includes a complete German translation catalog; English remains the source
  language.

## Safety and Compatibility

- Protects foreign aliases and unrelated Kea DHCPv6 configuration.
- Requires explicit management consent when an interface is first adopted or
  an existing downstream prefix is changed.
- Warns before actions that can interrupt downstream connectivity.
- Preserves empty aliases referenced by firewall rules.
- Uses native pfSense package hooks and does not patch pfSense core files.

## Validation

- 68 isolated regression tests pass on pfSense CE 2.9.0.
- PHP syntax, package XML, translation catalogs, `portlint`, package integrity,
  and `check-plist` are validated as part of the release build.
- Fresh installation, upgrade, reboot, service reload, IA_PD renumbering,
  assignment recovery, lease revocation, and firewall-alias synchronization
  were exercised during beta testing.

## Package

- Version: `1.0.0`
- Target: pfSense CE 2.9.0 / FreeBSD 16 amd64
- License: Apache-2.0
- Tag: `v1.0.0`
- Artifact: `pfSense-pkg-dgpd-1.0.0.pkg`

Back up the pfSense configuration before installation. Verify the published
SHA256 checksum before transferring the package to the firewall.
