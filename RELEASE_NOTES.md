# DynGUA-PD 0.8.1_7 — Beta RC2

Beta RC2 extends the first public beta with automatic firewall aliases, more
reliable native apply and recovery handling, package-safe diagnostics, and an
identifiable FreeBSD port build.

## Added

- Native `DGPD_<INTERFACE>_PD` aliases for each managed downstream prefix.
- Native `DGPD_ALL_PD` containing all verified active delegated prefixes.
- Fail-closed empty aliases when a referenced assignment is disabled, stale,
  or deleted.
- Native package logging in `/var/log/dgpd.log` with pfSense log rotation.
- Optional **Enable Debug Logging** diagnostics and a visible beta notice.
- Installed build identity containing the package version, build timestamp,
  and source commit.

## Corrected

- Wait for stable Kea state after native apply and retry convergence during
  boot and service reloads.
- Distinguish the Kea control socket, Lease Commands hook, and native
  `kea2fib6` routing diagnostics.
- Preserve managed alias order and reclaim exact DGPD aliases during package
  upgrades without adopting foreign aliases.
- Keep staged deletion in `PENDING` until **Save Settings** finalizes it and
  suppress duplicate external-change errors.
- Preserve and recover pending assignments when native settings are changed.
- Verify pending operations across disabled interfaces.
- Remove the obsolete unrotated reconciliation log.
- Align package metadata, source layout, messages, and form behavior with the
  current Netgate development conventions.

## Validation

- 65 regression tests passed.
- PHP syntax checks passed.
- Native package GUI rendering passed.
- `check-plist` passed.
- Upgrade alias recovery and build identity were verified on pfSense CE 2.9.0.

## Package

- Version: `0.8.1_7`
- Target: pfSense CE 2.9.0 / FreeBSD 16 amd64
- License: Apache-2.0
- SHA256: `28895b8812b29b0056dff6af411c6346a173ca0bbe63ea43c16e42159d3f6177`

Back up the pfSense configuration before installing this pre-release package.
Firewall aliases are maintained by DynGUA-PD; firewall and NAT rules remain
under administrator control.
